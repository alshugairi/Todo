<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'challenges' => ['view', 'create', 'edit', 'delete'],
            'categories' => ['view', 'create', 'edit', 'delete'],
            'posts' => ['view', 'create', 'edit', 'delete'],
            'journeys' => ['view', 'create', 'edit', 'delete'],
            'permissions' => ['view', 'create', 'edit', 'delete'],
            'roles' => ['view', 'create', 'edit', 'delete'],
            'admins' => ['view', 'create', 'edit', 'delete'],
            'clients' => ['view', 'create', 'edit', 'delete'],
            'languages' => ['view', 'create', 'edit', 'delete'],
            'countries' => ['view', 'create', 'edit', 'delete'],
            'cities' => ['view', 'create', 'edit', 'delete'],
            'pages' => ['view', 'create', 'edit', 'delete'],
            'reasons' => ['view', 'create', 'edit', 'delete'],
            'questions' => ['view', 'create', 'edit', 'delete'],
            'settings' => ['view', 'edit'],
        ];

        $flattenedPermissions = [];
        foreach ($permissions as $group => $actions) {
            foreach ($actions as $action) {
                $flattenedPermissions[] = "$group.$action";
            }
        }

        //Permission::query()->delete();
        Permission::whereNotIn('name', $flattenedPermissions)->delete();

        foreach ($flattenedPermissions as $permissionName) {
            Permission::firstOrCreate(
                ['name' => $permissionName],
                ['name' => $permissionName]
            );
        }

        // Assign all permissions to the admin role
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $allPermissions = Permission::pluck('name')->toArray();
        $adminRole->givePermissionTo($allPermissions);

        $adminUser = User::where('username', 'admin')->first();
        if ($adminRole) {
            $adminUser->assignRole($adminRole);
        }
    }
}
