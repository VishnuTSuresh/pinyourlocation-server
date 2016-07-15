<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(\App\Role::where("name","admin")->count()==0){
            $admin = new \App\Role();
            $admin->name         = 'admin';
            $admin->display_name = 'User Administrator'; // optional
            $admin->description  = 'User is allowed to manage and edit other users'; // optional
            $admin->save();
        }
        if(\App\Role::where("name","verified")->count()==0){
            $admin = new \App\Role();
            $admin->name         = 'verified';
            $admin->display_name = 'Verified User';
            $admin->description  = 'This user has verified his email and can now access much of the functionality of the app'; // optional
            $admin->save();
        }   
        // $this->call(UsersTableSeeder::class);
    }
}
