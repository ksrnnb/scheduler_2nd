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

        $schedule = factory(App\Schedule::class)->create();
        
        $users = $schedule->users()->saveMany(factory(App\User::class, 5)->make());

        $candidates = $schedule->candidates()->saveMany(factory(App\Candidate::class, 3)->make());

        foreach($users as $user) {
            foreach($candidates as $candidate) {
                factory(App\Availability::class)->create(
                    array(
                        'scheduleId' => $schedule->scheduleId,
                        'userId' => $user['userId'],
                        'candidateId' => $candidate['candidateId'],
                    )
                );
            }
        }

    }
}
