<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DeleteUnverifiedCustomers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-unverified-customers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes customers who have not verified their email within 48 hours.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = \App\Models\Customer::whereNull('email_verified_at')
            ->where('created_at', '<', now()->subHours(48))
            ->delete();

        $this->info("Deleted {$count} unverified customer(s) older than 48 hours.");
    }
}
