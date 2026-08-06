<?php

namespace App\Traits;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

/**
 * Generates simple placeholder images locally using GD — no network required.
 *
 * Use this in any Seeder (or Factory/other class) that previously relied on
 * $faker->image() / $faker->imageUrl(), which depend on third-party services
 * (lorempixel.com, via.placeholder.com, etc.) that have been discontinued or
 * are unreliable in local/offline dev environments.
 *
 * Usage in a Seeder:
 *
 *     class ProductSeeder extends Seeder
 *     {
 *         use \App\Traits\GeneratesPlaceholderImages;
 *
 *         public function run()
 *         {
 *             $relativePath = $this->makePlaceholderImage(
 *                 'products',        // relative to storage/app/public
 *                 800, 600,          // width, height
 *                 'Product'          // label drawn on the image
 *             );
 *             // $relativePath === 'products/product-64f1a2b3c9e21.png'
 *         }
 *     }
 *
 * Three image "types" are supported via $options['type']:
 *
 *   'color' (default) — solid color box with a text label. Cheapest, always works.
 *
 *   'face'  — a procedurally drawn cartoon avatar (head, hair, eyes, mouth),
 *             randomized per call. Fully offline, no assets needed, but not
 *             photorealistic — it's a simple avatar, not a real photo.
 *
 *   'pool'  — randomly picks and copies a real image file from a local
 *             folder you provide (e.g. a folder of stock/dummy face photos
 *             you download once). Gives genuine photos, fully offline once
 *             the pool exists. Requires $options['poolDir'] (absolute path).
 */
trait GeneratesPlaceholderImages
{
    /**
     * Generate a placeholder image and return its path RELATIVE to
     * storage/app/public — ready to store directly in a DB column.
     *
     * This is the method most seeders should call. It handles directory
     * creation, generation (color/face/pool), and correct relative-path
     * building in one step, avoiding the "folder mismatch" / "empty path"
     * bugs that come from manually concatenating strings.
     *
     * @param string $relativeDir  e.g. 'products', 'users/managers', 'projects/covers'
     * @param int    $width
     * @param int    $height
     * @param string $label        Text label (used by 'color' type) or a hint (unused by others)
     * @param array  $options      'type' => 'color'|'face'|'pool' (default 'color'), plus
     *                             type-specific keys — see generatePlaceholderImage(),
     *                             generateFaceAvatar(), and generateFromPool() below.
     * @return string              Relative path, e.g. 'products/product-abc123.png'
     */
    protected function makePlaceholderImage(
        string $relativeDir,
        int $width = 640,
        int $height = 480,
        string $label = '',
        array $options = []
    ): string {
        $absoluteDir = storage_path('app/public/' . trim($relativeDir, '/'));
        $type = $options['type'] ?? 'color';

        /*$filename = match ($type) {
            'face'      => $this->generateFaceAvatar($absoluteDir, $width, $height, $options),
            'identicon' => $this->generateIdenticon($absoluteDir, $width, $height, $label, $options),
            'pool'      => $this->generateFromPool($absoluteDir, $options),
            default     => $this->generatePlaceholderImage($absoluteDir, $width, $height, $label, $options),
        };*/


        switch ($type) {
            case 'face':
                $filename = $this->generateFaceAvatar($absoluteDir, $width, $height, $options);
                break;

            case 'identicon':
                $filename = $this->generateIdenticon($absoluteDir, $width, $height, $label, $options);
                break;

            case 'pool':
                $filename = $this->generateFromPool($absoluteDir, $options);
                break;

            default:
                $filename = $this->generatePlaceholderImage($absoluteDir, $width, $height, $label, $options);
                break;
        }

        return trim($relativeDir, '/') . '/' . $filename;
    }

    /**
     * Generate a placeholder image in an absolute directory and return
     * just the filename (not the full path). Use makePlaceholderImage()
     * instead unless you specifically need the lower-level behavior.
     *
     * @param string $dir      Absolute directory to save into
     * @param int    $width
     * @param int    $height
     * @param string $label    Text drawn on the image
     * @param array  $options  Supported keys:
     *                         - 'prefix' (string)  filename prefix, defaults to slug($label) or 'img'
     *                         - 'extension' (string) 'png' or 'jpg', default 'png'
     *                         - 'bg' (array [r,g,b]) fixed background color, default random pastel
     *                         - 'textColor' (array [r,g,b]) default white
     *                         - 'font' (int 1-5) built-in GD font size, default 5
     * @return string          Just the filename
     */
    protected function generatePlaceholderImage(
        string $dir,
        int $width = 640,
        int $height = 480,
        string $label = '',
        array $options = []
    ): string {
        if (!File::exists($dir)) {
            File::makeDirectory($dir, 0777, true);
        }

        if (!is_writable($dir)) {
            throw new \RuntimeException("Directory not writable: {$dir}");
        }

        $extension = $options['extension'] ?? 'png';
        $prefix    = $options['prefix'] ?? (Str::slug($label) ?: 'img');
        $filename  = $prefix . '-' . uniqid() . '.' . $extension;
        $fullPath  = rtrim($dir, '/\\') . DIRECTORY_SEPARATOR . $filename;

        $image = imagecreatetruecolor($width, $height);

        $bg = $options['bg'] ?? [rand(120, 220), rand(120, 220), rand(120, 220)];
        $bgColor = imagecolorallocate($image, $bg[0], $bg[1], $bg[2]);
        imagefilledrectangle($image, 0, 0, $width, $height, $bgColor);

        if ($label !== '') {
            $textRgb   = $options['textColor'] ?? [255, 255, 255];
            $textColor = imagecolorallocate($image, $textRgb[0], $textRgb[1], $textRgb[2]);

            $font = $options['font'] ?? 5; // built-in GD font, no external .ttf needed
            $textWidth = imagefontwidth($font) * strlen($label);
            $x = (int) (($width - $textWidth) / 2);
            $y = (int) ($height / 2);
            imagestring($image, $font, max($x, 10), $y, $label, $textColor);
        }

        if ($extension === 'jpg' || $extension === 'jpeg') {
            imagejpeg($image, $fullPath, 90);
        } else {
            imagepng($image, $fullPath);
        }

        imagedestroy($image);

        return $filename;
    }

    /**
     * Generate a procedural cartoon "face" avatar using only GD primitives —
     * randomized skin tone, hair color/style, eyes, eyebrows, nose, and mouth.
     * Not photorealistic, but a noticeably cleaner cartoon avatar than a
     * naive version, thanks to supersampling (drawn 4x size, then downscaled,
     * which smooths out the jagged edges GD's primitives normally produce).
     * Fully offline, no external assets required.
     *
     * @param string $dir      Absolute directory to save into
     * @param int    $width
     * @param int    $height
     * @param array  $options  Supported keys:
     *                         - 'prefix' (string) filename prefix, default 'avatar'
     *                         - 'extension' (string) 'png' or 'jpg', default 'png'
     *                         - 'skinTone' (array [r,g,b]) fixed skin color, default random
     *                         - 'hairColor' (array [r,g,b]) fixed hair color, default random
     *                         - 'hairStyle' (string) 'short'|'long'|'bald', default random
     *                         - 'mood' (string) 'smile'|'neutral', default random
     *                         - 'bg' (array [r,g,b]) background color, default light gray
     * @return string          Just the filename
     */
    protected function generateFaceAvatar(string $dir, int $width, int $height, array $options = []): string
    {
        if (!File::exists($dir)) {
            File::makeDirectory($dir, 0777, true);
        }
        if (!is_writable($dir)) {
            throw new \RuntimeException("Directory not writable: {$dir}");
        }

        $extension = $options['extension'] ?? 'png';
        $prefix    = $options['prefix'] ?? 'avatar';
        $filename  = $prefix . '-' . uniqid() . '.' . $extension;
        $fullPath  = rtrim($dir, '/\\') . DIRECTORY_SEPARATOR . $filename;

        // Supersampling: draw everything at 4x resolution, then downscale.
        // This is what smooths out GD's otherwise jagged circles/arcs.
        $scale = 4;
        $w = $width * $scale;
        $h = $height * $scale;

        $image = imagecreatetruecolor($w, $h);
        imagesavealpha($image, true);

        $bg = $options['bg'] ?? [235, 235, 240];
        $bgColor = imagecolorallocate($image, $bg[0], $bg[1], $bg[2]);
        imagefilledrectangle($image, 0, 0, $w, $h, $bgColor);

        $skinTones = [
            [255, 224, 189], [240, 200, 160], [198, 150, 110],
            [141, 85, 55],   [90, 55, 35],
        ];
        $hairColors = [
            [20, 20, 20], [80, 50, 20], [160, 120, 60],
            [230, 210, 150], [120, 30, 30], [60, 60, 60], [230, 230, 235],
        ];

        $skin      = $options['skinTone']  ?? $skinTones[array_rand($skinTones)];
        $hair      = $options['hairColor'] ?? $hairColors[array_rand($hairColors)];
        $hairStyle = $options['hairStyle'] ?? ['short', 'long', 'bald'][array_rand(['short', 'long', 'bald'])];
        $mood      = $options['mood']      ?? (rand(0, 1) ? 'smile' : 'neutral');

        $skinColor  = imagecolorallocate($image, $skin[0], $skin[1], $skin[2]);
        $skinShadow = imagecolorallocate($image, max($skin[0] - 25, 0), max($skin[1] - 25, 0), max($skin[2] - 25, 0));
        $hairColor  = imagecolorallocate($image, $hair[0], $hair[1], $hair[2]);
        $black      = imagecolorallocate($image, 30, 30, 30);
        $white      = imagecolorallocate($image, 255, 255, 255);
        $blush      = imagecolorallocate($image, min($skin[0] + 40, 255), max($skin[1] - 40, 0), max($skin[2] - 40, 0));

        $cx = (int) ($w / 2);
        $cy = (int) ($h / 2);
        $headRx = (int) ($w * 0.26);
        $headRy = (int) ($h * 0.30);

        // Neck/shoulders (drawn first, behind head)
        imagefilledrectangle(
            $image,
            $cx - (int) ($headRx * 0.9),
            $cy + (int) ($headRy * 1.3),
            $cx + (int) ($headRx * 0.9),
            $h,
            $skinShadow
        );
        imagefilledellipse(
            $image,
            $cx,
            $cy + (int) ($headRy * 1.5),
            (int) ($headRx * 2.6),
            (int) ($headRy * 1.6),
            $skinShadow
        );

        // Ears
        $earRy = (int) ($headRy * 0.35);
        foreach ([-1, 1] as $side) {
            imagefilledellipse($image, $cx + ($side * $headRx), $cy, (int) ($headRx * 0.3), $earRy * 2, $skinColor);
        }

        // Head
        imagefilledellipse($image, $cx, $cy, $headRx * 2, $headRy * 2, $skinColor);

        // Eyebrows
        $browOffsetX = (int) ($headRx * 0.42);
        $browY = $cy - (int) ($headRy * 0.32);
        foreach ([-1, 1] as $side) {
            $bx = $cx + ($side * $browOffsetX);
            imagesetthickness($image, (int) (4 * $scale / 2));
            imageline($image, $bx - 20 * $scale / 2, $browY, $bx + 20 * $scale / 2, $browY - 6 * $scale / 2, $hairColor);
        }
        imagesetthickness($image, 1);

        // Eyes
        $eyeOffsetX = (int) ($headRx * 0.42);
        $eyeOffsetY = (int) ($headRy * 0.05);
        $eyeRadius  = (int) ($headRx * 0.13);

        foreach ([-1, 1] as $side) {
            $ex = $cx + ($side * $eyeOffsetX);
            $ey = $cy - $eyeOffsetY;
            imagefilledellipse($image, $ex, $ey, $eyeRadius * 2, $eyeRadius * 2, $white);
            imagefilledellipse($image, $ex, $ey, (int) ($eyeRadius * 0.85), (int) ($eyeRadius * 0.85), $black);
            // small highlight for a bit of life
            imagefilledellipse(
                $image,
                $ex - (int) ($eyeRadius * 0.3),
                $ey - (int) ($eyeRadius * 0.3),
                (int) ($eyeRadius * 0.3),
                (int) ($eyeRadius * 0.3),
                $white
            );
        }

        // Nose (simple triangle/line)
        $noseTopY = $cy - (int) ($headRy * 0.05);
        $noseBottomY = $cy + (int) ($headRy * 0.28);
        imagesetthickness($image, (int) (3 * $scale / 2));
        imageline($image, $cx, $noseTopY, $cx - (int) ($headRx * 0.08), $noseBottomY, $skinShadow);
        imageline($image, $cx - (int) ($headRx * 0.08), $noseBottomY, $cx + (int) ($headRx * 0.08), $noseBottomY, $skinShadow);
        imagesetthickness($image, 1);

        // Blush
        foreach ([-1, 1] as $side) {
            imagefilledellipse(
                $image,
                $cx + ($side * (int) ($headRx * 0.65)),
                $cy + (int) ($headRy * 0.35),
                (int) ($headRx * 0.28),
                (int) ($headRy * 0.16),
                $blush
            );
        }

        // Mouth
        $mouthY = $cy + (int) ($headRy * 0.55);
        $mouthWidth = (int) ($headRx * 0.85);
        imagesetthickness($image, (int) (5 * $scale / 2));
        if ($mood === 'smile') {
            imagearc($image, $cx, $mouthY - (int) (10 * $scale / 2), $mouthWidth, (int) (40 * $scale / 2), 20, 160, $black);
        } else {
            imageline($image, $cx - (int) ($mouthWidth / 2), $mouthY, $cx + (int) ($mouthWidth / 2), $mouthY, $black);
        }
        imagesetthickness($image, 1);

        // Hair — drawn last, over the top of the head only
        if ($hairStyle !== 'bald') {
            $hairTopY = $cy - (int) ($headRy * 0.95);
            $hairBottomY = $cy - (int) ($headRy * ($hairStyle === 'long' ? 0.05 : 0.35));

            imagefilledellipse(
                $image,
                $cx,
                $cy - (int) ($headRy * 0.55),
                (int) ($headRx * 2.15),
                (int) ($headRy * 1.5),
                $hairColor
            );
            // clip the bottom half back off by redrawing skin/background over it
            imagefilledrectangle($image, 0, $hairBottomY, $w, $cy - $headRy - 1, $bgColor);
            imagefilledellipse($image, $cx, $cy, $headRx * 2, $headRy * 2, $skinColor);
            // redraw the hair cap on top, now properly clipped to the head's upper arc
            imagefilledarc(
                $image,
                $cx,
                $cy,
                $headRx * 2,
                $headRy * 2,
                180,
                360,
                $hairColor,
                IMG_ARC_PIE
            );

            if ($hairStyle === 'long') {
                foreach ([-1, 1] as $side) {
                    imagefilledellipse(
                        $image,
                        $cx + ($side * (int) ($headRx * 1.05)),
                        $cy + (int) ($headRy * 0.6),
                        (int) ($headRx * 0.5),
                        (int) ($headRy * 1.3),
                        $hairColor
                    );
                }
            }
        }

        // Downscale with resampling — this is where the supersampling pays off
        $final = imagecreatetruecolor($width, $height);
        imagecopyresampled($final, $image, 0, 0, 0, 0, $width, $height, $w, $h);
        imagedestroy($image);

        if ($extension === 'jpg' || $extension === 'jpeg') {
            imagejpeg($final, $fullPath, 90);
        } else {
            imagepng($final, $fullPath);
        }

        imagedestroy($final);

        return $filename;
    }

    /**
     * Generate an "identicon"-style abstract geometric avatar — a
     * symmetric grid of colored blocks, similar in spirit to GitHub's or
     * Gravatar's default avatars. These never look "off" the way a drawn
     * face can, because there's no realism bar to clear — they read as
     * clean, deliberate icons. Good default choice if face avatars aren't
     * landing well for your use case.
     *
     * The same $label produces the same pattern/color every time (like
     * real identicons), so passing something stable (e.g. a username or
     * email) gives each user a consistent, unique-looking icon.
     *
     * @param string $dir
     * @param int    $width
     * @param int    $height
     * @param string $label    Seed string — same label = same pattern. Pass
     *                         something unique per record (email, username).
     * @param array  $options  Supported keys:
     *                         - 'prefix' (string) filename prefix, default 'identicon'
     *                         - 'extension' (string) 'png' or 'jpg', default 'png'
     *                         - 'gridSize' (int) NxN block grid, default 5
     *                         - 'bg' (array [r,g,b]) background color, default white
     * @return string          Just the filename
     */
    protected function generateIdenticon(string $dir, int $width, int $height, string $label, array $options = []): string
    {
        if (!File::exists($dir)) {
            File::makeDirectory($dir, 0777, true);
        }
        if (!is_writable($dir)) {
            throw new \RuntimeException("Directory not writable: {$dir}");
        }

        $extension = $options['extension'] ?? 'png';
        $prefix    = $options['prefix'] ?? 'identicon';
        $filename  = $prefix . '-' . uniqid() . '.' . $extension;
        $fullPath  = rtrim($dir, '/\\') . DIRECTORY_SEPARATOR . $filename;

        $gridSize = $options['gridSize'] ?? 5;
        $seed = $label !== '' ? $label : (string) mt_rand();
        $hash = md5($seed);

        // Derive a foreground color from the hash so it's stable per label
        $r = hexdec(substr($hash, 0, 2));
        $g = hexdec(substr($hash, 2, 2));
        $b = hexdec(substr($hash, 4, 2));

        $image = imagecreatetruecolor($width, $height);
        $bg = $options['bg'] ?? [255, 255, 255];
        $bgColor = imagecolorallocate($image, $bg[0], $bg[1], $bg[2]);
        $fgColor = imagecolorallocate($image, $r, $g, $b);
        imagefilledrectangle($image, 0, 0, $width, $height, $bgColor);

        $cellW = $width / $gridSize;
        $cellH = $height / $gridSize;
        $halfCols = (int) ceil($gridSize / 2);

        // Build a symmetric bit pattern from the hash — only need the left
        // half of columns, then mirror them for the right half.
        $bits = [];
        for ($i = 0; $i < strlen($hash); $i++) {
            $bits[] = hexdec($hash[$i]) % 2; // 0 or 1 per hex char
        }

        $bitIndex = 0;
        for ($col = 0; $col < $halfCols; $col++) {
            for ($row = 0; $row < $gridSize; $row++) {
                $on = $bits[$bitIndex % count($bits)] === 1;
                $bitIndex++;

                if (!$on) {
                    continue;
                }

                $mirrorCol = $gridSize - 1 - $col;

                imagefilledrectangle(
                    $image,
                    (int) ($col * $cellW),
                    (int) ($row * $cellH),
                    (int) (($col + 1) * $cellW),
                    (int) (($row + 1) * $cellH),
                    $fgColor
                );
                imagefilledrectangle(
                    $image,
                    (int) ($mirrorCol * $cellW),
                    (int) ($row * $cellH),
                    (int) (($mirrorCol + 1) * $cellW),
                    (int) (($row + 1) * $cellH),
                    $fgColor
                );
            }
        }

        if ($extension === 'jpg' || $extension === 'jpeg') {
            imagejpeg($image, $fullPath, 90);
        } else {
            imagepng($image, $fullPath);
        }

        imagedestroy($image);

        return $filename;
    }

    /**
     * Pick a random real image file from a local folder ("pool") and copy
     * it into the target directory under a fresh unique filename. This is
     * the option to use if you want genuine human face photos rather than
     * drawn avatars — populate the pool folder once (e.g. a downloaded
     * pack of dummy/stock avatar images) and every seeded record gets a
     * random one, fully offline from then on.
     *
     * @param string $dir      Absolute directory to copy the picked image into
     * @param array  $options  Required: 'poolDir' (absolute path to folder of source images).
     *                         Optional: 'prefix' (string) filename prefix, default 'photo'
     * @return string          Just the filename (in $dir)
     */
    protected function generateFromPool(string $dir, array $options = []): string
    {
        if (empty($options['poolDir'])) {
            throw new \InvalidArgumentException(
                "The 'pool' image type requires an options['poolDir'] absolute path " .
                "pointing to a folder of source images (e.g. storage_path('app/seed-assets/faces'))."
            );
        }

        $poolDir = rtrim($options['poolDir'], '/\\');

        if (!File::exists($poolDir)) {
            throw new \RuntimeException("Image pool directory does not exist: {$poolDir}");
        }

        $candidates = File::files($poolDir);
        $imageFiles = array_filter($candidates, function ($file) {
            return in_array(strtolower($file->getExtension()), ['jpg', 'jpeg', 'png', 'webp']);
        });

        if (empty($imageFiles)) {
            throw new \RuntimeException(
                "No image files found in pool directory: {$poolDir}. " .
                "Add some .jpg/.png files there first."
            );
        }

        $imageFiles = array_values($imageFiles);
        $source = $imageFiles[array_rand($imageFiles)];

        if (!File::exists($dir)) {
            File::makeDirectory($dir, 0777, true);
        }

        $prefix   = $options['prefix'] ?? 'photo';
        $filename = $prefix . '-' . uniqid() . '.' . $source->getExtension();
        $fullPath = rtrim($dir, '/\\') . DIRECTORY_SEPARATOR . $filename;

        File::copy($source->getPathname(), $fullPath);

        return $filename;
    }

    /**
     * Ensure a directory (or list of directories) exists, creating it if needed.
     * Handy for the folder-setup boilerplate seeders often need up front.
     *
     * @param string|array $dirs  Absolute path or array of absolute paths
     */
    protected function ensureDirectoriesExist($dirs): void
    {
        foreach ((array) $dirs as $dir) {
            if (!File::exists($dir)) {
                File::makeDirectory($dir, 0777, true);
                if (isset($this->command)) {
                    $this->command->alert($dir . ' - Folder created successfully.');
                }
            }
        }
    }
}