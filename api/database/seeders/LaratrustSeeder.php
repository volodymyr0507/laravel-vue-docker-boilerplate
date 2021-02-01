<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

class LaratrustSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return  void
     */
    public function run()
    {
        $this->truncateLaratrustTables();

        $config = config('laratrust_seeder.roles_structure');

        foreach ($config as $key) {
            \App\Models\Role::firstOrCreate([
                'name' => $key,
                'display_name' => ucwords(str_replace('_', ' ', $key)),
                'description' => ucwords(str_replace('_', ' ', $key))
            ]);

            $this->command->info('Creating Role ' . strtoupper($key));
        }
    }

    /**
     * Truncates all the laratrust tables and the users table
     *
     * @return    void
     */
    public function truncateLaratrustTables()
    {
        $this->command->info('Truncating User and Role tables');
        Schema::disableForeignKeyConstraints();
        DB::table('role_user')->truncate();
        if (Config::get('laratrust_seeder.truncate_tables')) {
            \App\Models\Role::truncate();
        }
        if (Config::get('laratrust_seeder.truncate_tables') && Config::get('laratrust_seeder.create_users')) {
            \App\Models\User::truncate();
        }
        Schema::enableForeignKeyConstraints();
    }
}
