<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles
        $adminRole = Role::create(['name' => 'admin']);
        $bidderRole = Role::create(['name' => 'bidder']);

        // Create permissions
        $permissions = [
            'view auctions',
            'create auctions',
            'edit auctions',
            'delete auctions',
            'place bids',
            'view users',
            'view bid history',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Assign permissions to roles
        $adminRole->givePermissionTo([
            'view auctions',
            'create auctions',
            'edit auctions',
            'delete auctions',
            'view users',
            'view bid history',
        ]);

        $bidderRole->givePermissionTo([
            'view auctions',
            'place bids',
            'view bid history',
        ]);
    }
}
