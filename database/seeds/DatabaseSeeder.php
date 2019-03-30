<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //insert demo data
        DB::insert('INSERT INTO `users` (`name`, `status`, `email`, `updated_at`, `password`, `created_at`, `role`) VALUES (\'Benny\', \'1\', \'benny.sheerin@appscore.com.au\', NOW(), \'$2y$10$WSeHv0LAyypoosWBZQ77H.vKq2qJ66SJvw4/Ez3pMK4U1Zi5ShLGG\', NOW(), \'admin\')');
        //demo data for roles table
        DB::insert('INSERT INTO `roles` (`name`, `updated_at`, `created_at`) VALUES (\'iOS Developer\', NOW(), NOW())');
        DB::insert('INSERT INTO `roles` (`name`, `updated_at`, `created_at`) VALUES (\'Android Developer\', NOW(), NOW())');
        DB::insert('INSERT INTO `roles` (`name`, `updated_at`, `created_at`) VALUES (\'PHP Developer\', NOW(), NOW())');
        DB::insert('INSERT INTO `roles` (`name`, `updated_at`, `created_at`) VALUES (\'Tech Lead\', NOW(), NOW())');

        //demo data for recruiters table
        DB::insert('INSERT INTO `recruiters` (`name`, `updated_at`, `created_at`) VALUES (\'Clicks IT Recruitment\', NOW(), NOW())');
        DB::insert('INSERT INTO `recruiters` (`name`, `updated_at`, `created_at`) VALUES (\'Method Recruitment\', NOW(), NOW())');
        DB::insert('INSERT INTO `recruiters` (`name`, `updated_at`, `created_at`) VALUES (\'Yolk Agency\', NOW(), NOW())');
        DB::insert('INSERT INTO `recruiters` (`name`, `updated_at`, `created_at`) VALUES (\'Sirius Technology\', NOW(), NOW())');

        //demo data for clients table
        DB::insert('INSERT INTO `clients` (`name`, `updated_at`, `created_at`) VALUES (\'Medi Bank\', NOW(), NOW())');
        DB::insert('INSERT INTO `clients` (`name`, `updated_at`, `created_at`) VALUES (\'Samsung\', NOW(), NOW())');
        DB::insert('INSERT INTO `clients` (`name`, `updated_at`, `created_at`) VALUES (\'Telestra\', NOW(), NOW())');
        DB::insert('INSERT INTO `clients` (`name`, `updated_at`, `created_at`) VALUES (\'Boxer\', NOW(), NOW())');
    }
}
