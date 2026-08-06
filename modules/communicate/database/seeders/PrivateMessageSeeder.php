<?php
namespace Modules\Communicate\Database\Seeders;

use Modules\Communicate\Models\PrivateMessage;
use Modules\Communicate\Models\PrivateMessageThread;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Generator as Faker;




class PrivateMessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get active users
        $users = User::where('account_status', true)->get();
        
        if ($users->count() < 2) {
            $this->command->error('Not enough active users. Need at least 2 users.');
            return;
        }

        // Number of threads to create
        $threadCount = 20;
        $totalMessages = 0;

        $this->command->info("Creating {$threadCount} private message threads...");

        for ($i = 0; $i < $threadCount; $i++) {
            // Get random users
            $userPair = $users->random(2);
            $user1 = $userPair[0];
            $user2 = $userPair[1];

            // Randomly decide who creates the thread
            $createdBy = rand(0, 1) == 0 ? $user1->id : $user2->id;
            $sendTo = $createdBy == $user1->id ? $user2->id : $user1->id;

            // Create thread
            $thread = $this->createThread($createdBy, $sendTo);
            
            // Create messages for the thread
            $messageCount = rand(3, 15);
            $totalMessages += $this->createMessagesForThread($thread, $messageCount);
        }

        $this->command->info("Created {$threadCount} threads with {$totalMessages} messages total.");
        $this->displayQuickStats();
    }

    private function createThread(int $createdBy, int $sendTo): PrivateMessageThread
    {
        $createdAt = $this->getRandomDate('-60 days', 'now');
        
        return PrivateMessageThread::create([
            'title' => rand(0, 7) == 0 ? null : $this->getRandomTitle(),
            'status' => rand(0, 9) > 1 ? 'enable' : 'disable',
            'created_by' => $createdBy,
            'send_to' => $sendTo,
            
            'created_at' => $createdAt,
            'updated_at' => $createdAt,
        ]);
    }

    private function createMessagesForThread(PrivateMessageThread $thread, int $count): int
    {
        $messages = [];
        $previousMessages = [];
        $lastDate = $thread->created_at;

        // First message
        // $firstDate = $this->getRandomDate($lastDate, $lastDate->copy()->addHours(2));
        $firstDate = $lastDate;

        $firstMessage = PrivateMessage::create([
            'message' => $this->getRandomFirstMessage(),
            'posted_date_time' => $firstDate,
            'private_message_thread_id' => $thread->id,
            'replied_to_private_message_id' => null,
            'posted_by' => $thread->created_by,
            //'created_at' => $firstDate,
            //'updated_at' => $firstDate,
        ]);

        $messages[] = $firstMessage;
        $previousMessages[] = $firstMessage;
        $lastDate = $firstDate;

        // Remaining messages
        for ($i = 1; $i < $count; $i++) {
            $postedBy = $i % 2 == 0 ? $thread->created_by : $thread->send_to;
            
            // Alternate to ensure both participate
            if ($postedBy == $messages[$i-1]->posted_by) {
                $postedBy = $postedBy == $thread->created_by ? $thread->send_to : $thread->created_by;
            }

            $isReply = rand(0, 10) > 5;
            $repliedToId = null;
            
            if ($isReply && count($previousMessages) > 0) {
                $repliedTo = $previousMessages[array_rand($previousMessages)];
                $repliedToId = $repliedTo->id;
            }

            $minutes = rand(5, 20); // 5 to 20 minutes
            $messageDate = $this->getRandomDate($lastDate, (clone $lastDate)->add(new \DateInterval('PT' . $minutes . 'M')));
            $lastDate = $messageDate;

            $message = PrivateMessage::create([
                'message' => $this->getRandomMessage($isReply),
                'posted_date_time' => $messageDate,
                'private_message_thread_id' => $thread->id,
                'replied_to_private_message_id' => $repliedToId,
                'posted_by' => $postedBy,
                //'created_at' => $messageDate,
                //'updated_at' => $messageDate,
            ]);

            $messages[] = $message;
            $previousMessages[] = $message;
        }

        // Update thread's updated_at
        if (count($messages) > 0) {
            $thread->updated_at = $messages[count($messages)-1]->posted_date_time;
            $thread->save();
        }

        return count($messages);
    }

    private function getRandomDate($start, $end)
    {
        $faker = \Faker\Factory::create();
        return $faker->dateTimeBetween($start, $end);
    }

    private function getRandomTitle(): string
    {
        $titles = [
            'Project Discussion',
            'Team Collaboration',
            'Task Assignment',
            'Meeting Follow-up',
            'Project Update',
            'Review Request',
            'Feedback Session',
            'Planning Meeting',
            'Status Report',
            'Design Discussion',
            'Development Update',
            'Client Meeting',
            'Sprint Planning',
            'Code Review',
            'Documentation Review',
        ];
        return $titles[array_rand($titles)];
    }

    private function getRandomFirstMessage(): string
    {
        $messages = [
            "Hi! I'd like to discuss the project timeline and deliverables.",
            "Hello, I hope you're doing well. Let's talk about the upcoming tasks.",
            "Hey there! I have some thoughts about our collaboration.",
            "Good morning! Let's catch up on the project status.",
            "Hi, I wanted to share some ideas I've been working on.",
            "Hello! I noticed some things we should discuss.",
            "Hey, can we coordinate on the next phase?",
            "Hi there! I'm reaching out about the project.",
            "Good day! I have some important updates to share.",
            "Hello! Let's plan our next steps together.",
        ];
        $message = $messages[array_rand($messages)];

        $faker = \Faker\Factory::create();
        return $message . "\n\n" . $faker->paragraph(2);
    }

    private function getRandomMessage(bool $isReply): string
    {
        if ($isReply) {
            $messages = [
                "Re: I understand your point. I think we should consider...",
                "To follow up on your message, I'd like to add...",
                "Regarding your question, here's what I think...",
                "Building on what you said, I suggest that...",
                "In response to your message, I've looked into it...",
                "Just to clarify my previous point...",
                "Following up on our discussion, I've prepared...",
                "To address your question about this...",
                "Thanks for your message. In response, I'd say...",
                "Related to your point, I think that...",
            ];
        } else {
            $messages = [
                "I appreciate your input. Let me share my perspective.",
                "That's a great point. I think we should move forward.",
                "I have some concerns about the approach. Can we discuss?",
                "Thanks for your feedback. I'll work on it.",
                "Let me clarify what I meant in my previous message.",
                "I agree with you. Let's collaborate on this.",
                "Could you provide more details about that?",
                "I've completed the tasks we discussed.",
                "Let's schedule a time to discuss this further.",
                "I appreciate your feedback. I'll make adjustments.",
                "That's interesting. Let me think about it.",
                "I've shared the documents for your review.",
                "Could you review my work and provide feedback?",
                "I think we should prioritize this task.",
                "Let me know your availability for a call.",
                "I've updated the documentation.",
                "Let's plan a follow-up meeting.",
                "I've shared the files. Please check them.",
                "Let's finalize the plan and start.",
                "I'll update the team and keep you informed.",
            ];
        }
        
        $message = $messages[array_rand($messages)];

        $faker = \Faker\Factory::create();
        return $message . "\n\n" . $faker->paragraph(1);
    }

    private function displayQuickStats(): void
    {
        $threadCount = PrivateMessageThread::count();
        $messageCount = PrivateMessage::count();
        $replyCount = PrivateMessage::whereNotNull('replied_to_private_message_id')->count();
        
        $this->command->newLine();
        $this->command->info('=== STATISTICS ===');
        $this->command->info("Threads: {$threadCount}");
        $this->command->info("Messages: {$messageCount}");
        $this->command->info("Replies: {$replyCount}");
        $this->command->info("First Messages: " . ($messageCount - $replyCount));
        $this->command->newLine();
    }
}





/*in private_message_threads there is created_by, send_to   users involve
in that thread all messages creator (private_messages,posted_by)  should be only created_by
or send_to*/



/*when create
private_message_threads.create_at <   private_messages.posted_date_time*/

/*private_message_threads belongs message are in private_messages table 
first message in private mesage thread has no reply 
(replied_to_private_message_id should be null)*/


// after first one private_messages can have replies (replied_to_private_message_id can be not null)


/*
when set reply message can reply to previous messages in thread
order of the messages is defined by private_messages.posted_date_time
*/


/*private_message_threads first message (private_message_threads.created_by)
should be also equal to private_message_threads.created_by*/

