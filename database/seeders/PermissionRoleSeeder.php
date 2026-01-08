<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define all permissions
        $permissions = [
            // Accounts
            ['name' => 'view_accounts', 'description' => 'View accounts'],
            ['name' => 'create_accounts', 'description' => 'Create accounts'],
            ['name' => 'edit_accounts', 'description' => 'Edit accounts'],
            ['name' => 'delete_accounts', 'description' => 'Delete accounts'],

            // Contacts
            ['name' => 'view_contacts', 'description' => 'View contacts'],
            ['name' => 'create_contacts', 'description' => 'Create contacts'],
            ['name' => 'edit_contacts', 'description' => 'Edit contacts'],
            ['name' => 'delete_contacts', 'description' => 'Delete contacts'],

            // Leads
            ['name' => 'view_leads', 'description' => 'View leads'],
            ['name' => 'create_leads', 'description' => 'Create leads'],
            ['name' => 'edit_leads', 'description' => 'Edit leads'],
            ['name' => 'delete_leads', 'description' => 'Delete leads'],

            // Opportunities
            ['name' => 'view_opportunities', 'description' => 'View opportunities'],
            ['name' => 'create_opportunities', 'description' => 'Create opportunities'],
            ['name' => 'edit_opportunities', 'description' => 'Edit opportunities'],
            ['name' => 'delete_opportunities', 'description' => 'Delete opportunities'],

            // Interactions
            ['name' => 'view_interactions', 'description' => 'View interactions'],
            ['name' => 'create_interactions', 'description' => 'Create interactions'],
            ['name' => 'edit_interactions', 'description' => 'Edit interactions'],
            ['name' => 'delete_interactions', 'description' => 'Delete interactions'],

            // Products
            ['name' => 'view_products', 'description' => 'View products'],
            ['name' => 'create_products', 'description' => 'Create products'],
            ['name' => 'edit_products', 'description' => 'Edit products'],
            ['name' => 'delete_products', 'description' => 'Delete products'],

            // Categories
            ['name' => 'view_categories', 'description' => 'View categories'],
            ['name' => 'create_categories', 'description' => 'Create categories'],
            ['name' => 'edit_categories', 'description' => 'Edit categories'],
            ['name' => 'delete_categories', 'description' => 'Delete categories'],

            // Employer Management
            ['name' => 'manage_employees', 'description' => 'Manage employees'],
            ['name' => 'manage_roles', 'description' => 'Manage roles and permissions'],
        ];

        // Create permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name']],
                ['description' => $permission['description']]
            );
        }

        // Note: System roles will be created per employer when needed
        // These are just the permission definitions
    }
}
