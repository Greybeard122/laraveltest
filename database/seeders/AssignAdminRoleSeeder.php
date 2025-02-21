<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class AssignAdminRoleSeeder extends Seeder
{
    public function run()
    {
        // Assign admin role to the test user with ID 1
        $user = User::find(1);
        if ($user) {
            $user->assignRole('admin');
        }
    }
}

