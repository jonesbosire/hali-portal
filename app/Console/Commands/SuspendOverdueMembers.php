<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\AccountStatusChanged;
use Illuminate\Console\Command;

class SuspendOverdueMembers extends Command
{
    protected $signature   = 'members:suspend-overdue';
    protected $description = 'Suspend members whose dues are more than 7 days past due';

    public function handle(): int
    {
        // Suspend members whose dues_due_date was more than 7 days ago
        // and who are still active (skip already-suspended or non-member roles)
        $cutoff = now()->subDays(7)->toDateString();

        $overdue = User::whereIn('role', ['member', 'friend'])
            ->where('status', 'active')
            ->whereNotNull('dues_due_date')
            ->whereDate('dues_due_date', '<', $cutoff)
            ->get();

        if ($overdue->isEmpty()) {
            $this->info('No members to suspend.');
            return self::SUCCESS;
        }

        foreach ($overdue as $user) {
            $user->update(['status' => 'suspended']);

            try {
                $user->notify(new AccountStatusChanged('suspended', 'Your account has been suspended due to unpaid membership dues. Please contact the Secretariat to reinstate your account.'));
            } catch (\Exception $e) {
                report($e);
            }

            activity()
                ->causedBy(null)
                ->performedOn($user)
                ->withProperties(['reason' => 'dues_overdue', 'dues_due_date' => $user->dues_due_date?->toDateString()])
                ->log('account_auto_suspended');

            $this->line("Suspended: {$user->email} (dues due {$user->dues_due_date?->toDateString()})");
        }

        $this->info("Suspended {$overdue->count()} member(s).");
        return self::SUCCESS;
    }
}
