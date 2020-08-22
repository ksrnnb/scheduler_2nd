<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithoutMiddleware; 
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Support\Str;

use App\Schedule;


class ScheduleTest extends DuskTestCase
{
        use WithoutMiddleware; 

    /**
     * A Dusk test example.
     *
     * @return void
     */
//     public function testSchedule()
//     {
//         $this->browse(function (Browser $browser) {

//             // check input error
//             $browser->visit('/')
//                     ->clickAtXPath('//td[contains(@data-date, "14")]')
//                     ->clickAtXPath('//td[contains(@data-date, "15")]')
//                     ->clickAtXPath('//td[contains(@data-date, "16")]')
//                     ->press('Create schedule')
//                     ->assertPathIs('/');

//             // create schedule
//             $browser->type('scheduleName', 'test')
//                     ->press('Create schedule')
//                     ->assertPathIs('/add');

//             // add user
//             $browser->type('userName', 'test_user')
//                     ->press('Add user')
//                     ->assertSee('test_user')
//                     ->type('userName', 'test_user2')
//                     ->press('Add user')
//                     ->assertSee('test_user2');

//             // delete user
//             $browser->clickLink('test_user2')
//                     ->press('Delete user')
//                     ->assertDontSee('test_user2');

//             // update schedule name
//             $browser->clickLink('test')
//                     ->type('scheduleName', 'test2')
//                     ->press('Update')
//                     ->assertSee('test2');

//             // delete schedule
//             $browser->clickLink('test2')
//                     ->press('Delete')
//                     ->assertSee('Schedule has deleted.')
//                     ->clickLink('Scheduler');
//         });
//     }

    public function testMakeSchedule()
    {

        $params = [
                'scheduleName' => 'Hoge',
                'candidates' => `8/4 (Tue)\r\n8/6 (Thr)\r\n8/10(Mon)`,
        ];

        $response = $this->withHeaders([
                'X-Header' => 'Value',
                ])->json('POST', '/', $params);

        $response->assertStatus(302);
        
        $schedule = Schedule::orderBy('scheduleId', 'desc')->take(1)->get();

        Schedule::deleteSchedule($schedule[0]);

        // the case scheduleName is not defined
        $params = [
                'scheduleName' => '',
                'candidates' => `8/4 (Tue)\r\n8/6 (Thr)\r\n8/10(Mon)`,
        ];
        
        $response = $this->withHeaders([
                'X-Header' => 'Value',
                ])->json('POST', '/', $params);

        $response->assertRedirect('/error');


        // the case candidates are not date
        $params = [
                'scheduleName' => 'hoge',
                'candidates' => 'not data format',
        ];
        
        $response = $this->withHeaders([
                'X-Header' => 'Value',
                ])->json('POST', '/', $params);

        $response->assertRedirect('/error');


    }
    
}
