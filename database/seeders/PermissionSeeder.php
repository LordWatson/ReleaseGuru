<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modules = ['releases', 'users', 'roles', 'permissions', 'features', 'reports', 'bug reports', 'change requests', 'projects'];

        $permissionsData = [];

        foreach($modules as $module){
            foreach(['view', 'edit', 'delete'] as $action){
                $permissionsData[] = [
                    'name' => ucfirst($action) . ' ' . $module,
                    'description' => 'Permission to ' . $action . ' ' . $module,
                    'action' => $action,
                    'module' => $module,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        Permission::insert($permissionsData);

        // give all permissions to the admin user and the QA user
        $adminPermissions = Permission::whereIn('name', array_column($permissionsData, 'name'))->pluck('id');
        Role::find(1)->permissions()->sync($adminPermissions);
        Role::find(3)->permissions()->sync($adminPermissions);

        // give developer permission to view and edit features, create releases
        $developerPermissions = Permission::where(function ($query) {
            $query->where('module', 'releases')
                ->orWhere(function ($query) {
                    $query->where('module', 'features')
                        ->whereIn('action', ['view', 'edit']);
                })
                ->orWhere(function ($query) {
                    $query->where('module', 'reports')
                        ->where('action', 'view');
                })
                ->orWhere(function ($query) {
                    $query->where('module', 'projects')
                        ->where('action', 'view');
                });
        })->pluck('id');

        Role::find(2)->permissions()->sync($developerPermissions);

        // give standard user permission to view all modules
        $userPermissions = Permission::where('action', 'view')->pluck('id');
        Role::find(4)->permissions()->sync($userPermissions);
    }
}
