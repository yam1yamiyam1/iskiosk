<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Hash;
use Spatie\Permission\Traits\HasRoles;
use DB;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user= User::create([
            'fname' => 'Admin',
            'lname' => 'Admin',
            'mi' => 'A',
            'status' => 1,
            'image' => 'p1.png',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('12341234'),
        ]);
        $role = Role::create([
            'role' => '1',
            'name' => 'Admin',
        ]);
        $user->roles()->sync($role->id);
        $user= User::create([
            'fname' => 'User',
            'lname' => 'User',
            'mi' => 'U',
            'status' => 1,
            'image' => 'p1.png',
            'email' => 'user@gmail.com',
            'password' => Hash::make('12341234'),
        ]);
        $role = Role::create([
            'role' => '0',
            'name' => 'User',
        ]);
        $user->roles()->sync($role->id);
    }
}
