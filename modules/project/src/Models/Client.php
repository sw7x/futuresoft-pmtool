<?php
namespace Modules\Project\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


//use Database\Factories\ClientFactory;
use Database\Factories\ClientFactory; // Add this import
use Modules\Project\Models\Project;










class Client extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'clients';


    /**
     * Create a new factory instance for the model.
    */
    protected static function newFactory()
    {
        return ClientFactory::new();
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'company_name',
        'client_type',
        'email',
        'phone',
        'address',
        'country',
        'description',
        'comments',
        'status',
        'profile_image',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'deleted_at',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [       
        'full_name',
        'status_badge',
        'profile_image_url'

    ];

    // ==================== RELATIONSHIPS ====================

    /**
     * Get the projects for the client.
     */
    public function projects()
    {
        return $this->hasMany(\Modules\Project\Models\Project::class);
    }

    // ==================== SCOPES ====================

    /**
     * Scope a query to only include active clients.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'enable');
    }

    /**
     * Scope a query to only include inactive clients.
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'disable');
    }

    /**
     * Scope a query to only include initial clients.
     */
    public function scopeInitial($query)
    {
        return $query->where('client_type', 'initial');
    }

    /**
     * Scope a query to only include company clients.
     */
    public function scopeCompany($query)
    {
        return $query->where('client_type', 'company');
    }

    /**
     * Scope a query to search clients by name or company.
     */
    public function scopeSearch($query, $searchTerm)
    {
        return $query->where('name', 'LIKE', "%{$searchTerm}%")
                     ->orWhere('company_name', 'LIKE', "%{$searchTerm}%")
                     ->orWhere('email', 'LIKE', "%{$searchTerm}%");
    }

    /**
     * Scope a query to filter by country.
     */
    public function scopeByCountry($query, $country)
    {
        return $query->where('country', $country);
    }

    // ==================== ACCESSORS & MUTATORS ====================    

    /**
     * Get the full name (with company).
     */
    public function getFullNameAttribute()
    {
        if ($this->company_name) {
            return $this->name . ' (' . $this->company_name . ')';
        }
        return $this->name;
    }

    /**
     * Get the status badge color.
     */
    public function getStatusBadgeAttribute()
    {
        $colors = [
            'enable' => 'success',
            'disable' => 'danger',
        ];

        return $colors[$this->status] ?? 'secondary';
    }

    

    // ==================== CUSTOM METHODS ====================

    /**
     * Check if the client is active.
     */
    public function isActive()
    {
        return $this->status === 'enable';
    }

    /**
     * Check if the client is a company.
     */
    public function isCompany()
    {
        return $this->client_type === 'company';
    }

    /**
     * Check if the client is an individual.
     */
    public function isInitial()
    {
        return $this->client_type === 'initial';
    }

    /**
     * Get total project count.
     */
    public function getTotalProjects()
    {
        return $this->projects()->count();
    }

    /**
     * Get active project count.
     */
    public function getActiveProjects()
    {
        return $this->projects()->active()->count();
    }

    

    /**
     * Get client summary.
     */
    public function getSummary()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'company' => $this->company_name,
            'type' => $this->client_type,
            'email' => $this->primary_email,
            'phone' => $this->primary_phone,
            'country' => $this->country,
            'status' => $this->status,
            'total_projects' => $this->getTotalProjects(),
            'active_projects' => $this->getActiveProjects(),            
            'profile_image' => $this->profile_image,
            'profile_image_url' => $this->profile_image_url,
        ];
    }

    /**
     * Get client profile image URL.
     */
    public function getProfileImageUrlAttribute()
    {
        if ($this->profile_image) {
            return asset('storage/' . $this->profile_image);
        }
        return asset('images/default-images/client.png');
    }


}