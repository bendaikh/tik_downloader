<?php

namespace App\Console\Commands;

use App\Models\User;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class MakeDemoUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:demo-user {--name=Demo Admin : The demo user name} {--email=demo@example.com : The demo user email} {--password=demo123 : The demo user password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a demo user with limited permissions for CodeCanyon listing';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $name = $this->option('name');
        $email = $this->option('email');
        $password = $this->option('password');

        $this->info("Creating demo user {$name}...");
        
        try {
            // Check if demo user already exists
            $existingUser = User::where('email', $email)->first();
            if ($existingUser) {
                $this->warn("A user with email {$email} already exists. Updating to demo role...");
                $existingUser->update([
                    'name' => $name,
                    'role' => 'demo',
                    'password' => Hash::make($password),
                ]);
                $this->info("Demo user updated successfully!");
            } else {
                User::query()->create([
                    'name' => $name,
                    'email' => $email,
                    'password' => Hash::make($password),
                    'role' => 'demo',
                ]);
                $this->info("Demo user {$name} created successfully!");
            }

            $this->info("\nDemo User Credentials:");
            $this->info("Email: {$email}");
            $this->info("Password: {$password}");
            $this->info("\nDemo user has limited permissions:");
            $this->info("- Cannot modify AI integration settings");
            $this->info("- Cannot modify general settings");
            $this->info("- Cannot modify payment settings");
            $this->info("- Cannot modify Google Analytics settings");
            $this->info("- Cannot modify Google Search Console settings");
            $this->info("- Cannot modify Safari Analytics settings");
            $this->info("- Cannot modify Microsoft Services settings");
            $this->info("- Cannot modify SEO settings");
            $this->info("\nDemo user can still access:");
            $this->info("- Analytics dashboard (view only)");
            $this->info("- Proxy configuration");
            $this->info("- Appearance settings");
            $this->info("- Products management");
            $this->info("- Blog posts management");
            $this->info("- Account settings");
            
        } catch (Exception $e) {
            $this->error($e->getMessage());
            return 1;
        }
        
        return 0;
    }
}
