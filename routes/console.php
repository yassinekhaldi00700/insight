<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Grant or revoke admin (dashboard) access: php artisan user:admin email@uemf.ma [--revoke]
Artisan::command('user:admin {email} {--revoke}', function (string $email) {
    $updated = \Illuminate\Support\Facades\DB::table('users')
        ->where('email', $email)
        ->update(['is_admin' => $this->option('revoke') ? 0 : 1]);

    $updated
        ? $this->info($email . ($this->option('revoke') ? ' is no longer an admin.' : ' is now an admin.'))
        : $this->error('No user found (or no change) for ' . $email);
})->purpose('Grant or revoke dashboard admin access');

// Create the first admin account (or promote an existing one): php artisan user:create-admin
Artisan::command('user:create-admin', function () {
    $DB = \Illuminate\Support\Facades\DB::class;

    $email = trim((string) $this->ask('Email'));
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $this->error('Invalid email.');
        return 1;
    }

    $existing = $DB::table('users')->where('email', $email)->first();
    if ($existing) {
        if (!$this->confirm("{$existing->name} <{$email}> already exists. Make this account an admin?", true)) {
            return 0;
        }
        $update = ['is_admin' => 1, 'updated_at' => now()];
        if ($this->confirm('Also set a new password?', false)) {
            $password = $this->secret('New password (min 8 characters)');
            if (strlen((string) $password) < 8 || $password !== $this->secret('Confirm password')) {
                $this->error('Passwords too short or do not match.');
                return 1;
            }
            $update['password'] = \Illuminate\Support\Facades\Hash::make($password);
        }
        $DB::table('users')->where('id', $existing->id)->update($update);
        $this->info("{$email} is now an admin.");
        return 0;
    }

    $name = trim((string) $this->ask('Full name'));
    $password = $this->secret('Password (min 8 characters)');
    if ($name === '' || strlen((string) $password) < 8 || $password !== $this->secret('Confirm password')) {
        $this->error('Name is required and passwords must be 8+ characters and match.');
        return 1;
    }

    $DB::table('users')->insert([
        'name' => $name,
        'email' => $email,
        'password' => \Illuminate\Support\Facades\Hash::make($password),
        'management_id' => 0,
        'is_admin' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $this->info("Admin account created for {$email}.");
    return 0;
})->purpose('Create an admin account, or make an existing user admin');
