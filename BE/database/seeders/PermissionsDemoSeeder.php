<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionsDemoSeeder extends Seeder
{
    /**
     * Create the initial roles and permissions.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'edit clinics']);
        Permission::create(['name' => 'delete clinics']);

        // create roles and assign existing permissions
        $role1 = Role::create(['name' => 'Clinic-owner']);
        $role1->givePermissionTo('edit clinics');
        $role1->givePermissionTo('delete clinics');

//        $role2 = Staff::create(['name' => 'admin']);
//        $role2->givePermissionTo('publish articles');
//        $role2->givePermissionTo('unpublish articles');

        $role3 = Role::create(['name' => 'Super-Admin']);
        // gets all permissions via Gate::before rule; see AuthServiceProvider

        // create demo users
//        $user = \App\Models\User::where('id', '=', '1')->first();
//        $user->assignRole($role3);

//        $user = \App\Models\User::factory()->create([
//            'name' => 'Example Admin User',
//            'email' => 'admin@example.com',
//        ]);
//        $user->assignRole($role2);
//
        $user = \App\Models\User::factory()->create([
            'name' => 'Example Super-Admin User',
            'email' => 'superadmin@example.com',
        ]);
        $user->assignRole($role3);
    }
}
