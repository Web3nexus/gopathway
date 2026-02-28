<?php

namespace Database\Seeders; // ✅ Add this

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run()
    {
        Role::create(['name' => 'user']);
        Role::create(['name' => 'translator']);
        Role::create(['name' => 'lawyer']);
        Role::create(['name' => 'admin']);
    }
}