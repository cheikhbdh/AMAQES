<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Invitation;
use Illuminate\Support\Facades\Log;

class CheckInvitations extends Command
{
    protected $signature = 'invitations:check-expired';
    protected $description = 'Check and disable expired invitations';

    public function handle()
    {
        $currentDate = Carbon::now()->toDateString();
        $expiredInvitations = Invitation::where('statue', true)
            ->whereDate('date_fin', '<', $currentDate)
            ->get();

        if ($expiredInvitations->isNotEmpty()) {
            $expiredInvitations->each(function ($invitation) {
                $invitation->update(['statue' => false]);
            });

            User::where('invitation', 1)->update(['invitation' => 0]);
        }

        $this->info('Expired invitations checked and updated successfully.');
    }
}
