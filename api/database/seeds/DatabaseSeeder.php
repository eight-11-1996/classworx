<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         DB:: table('modules') -> insert(array(
          array('id' => 1, 'parent_id' => 0,  'description'=>'Dashboard', 'icon' => 'fa fa-tachometer', 'path' => 'dashboard', 'rank' => 1),
          array('id' => 2, 'parent_id' => 0,  'description'=>'Semester', 'icon' => 'fa fa-address-card-o', 'path' => 'semester', 'rank' => 2),
          array('id' => 3, 'parent_id' => 0,  'description'=>'Courses', 'icon' => 'fa fa-bell',  'path' => 'courses', 'rank' => 3),
          array('id' => 4, 'parent_id' => 0,  'description'=>'My Account', 'icon' => 'fa fa-users',  'path' => 'my_account', 'rank' => 4)
        ));
    }
}
