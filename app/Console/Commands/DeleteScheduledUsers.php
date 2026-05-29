<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class DeleteScheduledUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:delete-scheduled';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete users who were scheduled for deletion more than 7 days ago';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $usersToDelete = User::whereNotNull('scheduled_for_deletion_at')
            ->where('scheduled_for_deletion_at', '<=', now())
            ->get();

        $count = $usersToDelete->count();

        foreach ($usersToDelete as $user) {
            $user->delete();
        }

        $this->info("Successfully deleted {$count} scheduled users.");
    }
}
