<?php

use Illuminate\Database\Seeder;

class AdministratorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = new \App\User;
        $admin->full_name = "Administrator";
        $admin->email = "admin@admin.com";
        $admin->password = \Hash::make("123456");
        $admin->no_hp = "02112345678";
        $admin->alamat = "Jalan Administrator";
        $admin->date_birth = "1997-01-01";
        $admin->image = "no_image.png";
        $admin->role_id = 1;

        $admin->save();

        $this->command->info("User created");
    }
}
