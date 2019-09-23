<?php

namespace Tests\Feature;

use App\Project;
use App\ProjectTask;
use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RecordActivityTest extends TestCase
{
    use WithFaker, RefreshDatabase;
    /** @test */
    public function creating_a_project() {
        $project = ProjectFactory::create();

        $this->assertCount(1, $project->activities);

        tap($project->activities->last(), function($activity) {
            $this->assertEquals('created project', $activity->description);

            $this->assertNull($activity->changes);
        });
    }
    /** @test */
    public function updating_a_project() {
        $project = ProjectFactory::ownedBy($this->signIn())->create();
        $originalTitle = $project->title;

        $project->update(['title'=>'changed']);
        $this->assertCount(2, $project->activities);

        tap($project->activities->last(), function($activity) use ($originalTitle) {
            $this->assertEquals('updated project', $activity->description);
            $expected = [
                'before'=>['title'=>$originalTitle],
                'after'=>['title'=>'changed']
            ];
            $this->assertEquals($expected, $activity->changes);
        });
    }

    /** @test */
    public function creating_a_new_task() {
        $project = ProjectFactory::ownedBy($this->signIn())->create();
        
        $task = $project->addTask('test task');

        $this->assertCount(2, $project->activities);

        tap($project->activities->last(), function($activity) {
            $this->assertEquals('created task', $activity->description);
            $this->assertInstanceOf(ProjectTask::class,$activity->subject);
            $this->assertEquals('test task', $activity->subject->body);
        });



    }

    /** @test */
    public function completing_a_task() {
        $project = ProjectFactory::ownedBy($this->signIn())->withTasks(1)->create();
        $this->patch($project->tasks[0]->path(), ['body'=>'changed', 'completed' => true]);

        $this->assertCount(3, $project->activities);

        tap($project->activities->last(), function($activity) {
            $this->assertEquals('completed task', $activity->description);
            $this->assertInstanceOf(ProjectTask::class,$activity->subject);
            $this->assertEquals('changed', $activity->subject->body);
        });


    }

    /** @test */
    public function incompleting_a_task() {
        $project = ProjectFactory::ownedBy($this->signIn())->withTasks(1)->create();
        $this->patch($project->tasks[0]->path(), ['body'=>'changed', 'completed' => true]);
        $this->patch($project->tasks[0]->path(), ['body'=>'changed', 'completed' => false]);


        $this->assertCount(4, $project->activities);
        tap($project->activities->last(), function($activity) {
            $this->assertEquals('uncompleted task', $activity->description);
            $this->assertInstanceOf(ProjectTask::class,$activity->subject);
            $this->assertEquals('changed', $activity->subject->body);
        });


    }

    /** @test*/
    public function deleting_a_task() {
        $this->withoutExceptionHandling();
        $project = ProjectFactory::ownedBy($this->signIn())->withTasks(1)->create();
        $project->tasks[0]->delete();
        $this->assertCount(3, $project->activities);
        $this->assertEquals('deleted task', $project->activities->last()->description);
    }
}
