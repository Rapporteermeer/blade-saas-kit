<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\TeamType;
use Illuminate\Database\Seeder;

class RolesAndTeamTypesSeeder extends Seeder
{
    public function run()
    {
        // Create roles
        $roles = [
            ['name' => 'SuperAdmin', 'description' => 'Has access to everything'],
            ['name' => 'Owner', 'description' => 'Owner of a team'],
            ['name' => 'Employer', 'description' => 'Can manage employees'],
            ['name' => 'Employee', 'description' => 'Regular team member'],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }

        // Create team types
        $teamTypes = [
            ['name' => 'Housing Assistance', 'description' => 'Provides housing assistance services'],
            ['name' => 'Home Care', 'description' => 'Provides home care services'],
            ['name' => 'Outpatient Guidance', 'description' => 'Provides outpatient guidance services'],
        ];

        foreach ($teamTypes as $type) {
            TeamType::create($type);
        }
    }
}
