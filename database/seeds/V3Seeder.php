<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class V3Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'item_access',
            'item_create',
            'item_edit',
            'item_view',
            'item_delete'
        ];

        foreach ($permissions as $item) {
            Permission::findOrCreate($item);
        }
        Artisan::call('cache:clear');

        $admin = Role::findByName('administrator');
        $admin->givePermissionTo($permissions);
    }
}
