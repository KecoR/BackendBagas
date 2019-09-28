<?php

use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = new \App\Role;
        $role->role_desc = "Admin";

        $role->save();

        $this->command->info("Role created");
    }
}
