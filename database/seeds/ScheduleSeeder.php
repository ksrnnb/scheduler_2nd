<?php

use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    
    public function run()
    {


        // factory(App\User::class, 5)->create()->schedule()->save(factory(App\Schedule::class)->make());
        $users = factory(App\Schedule::class)->create()->users()->saveMany(factory(App\User::class, 5)->make());
        
        // $users->each(function($user) {
        //     $user->candidate()
        // });
    }
}
