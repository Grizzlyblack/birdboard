<?php

namespace Tests\Feature;

use App\Project;
use Tests\TestCase;
use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectTasksTest extends TestCase
{
    use RefreshDatabase, withFaker;

    /** @test*/
    public function guests_cannot_add_tasks_to_projects() {
        $project = factory(Project::class)->create();
        $this->post($project->path().'/tasks')->assertRedirect('login');
    }

    /** @test*/
    public function only_the_owner_of_a_project_may_add_tasks() {
        $this->signIn();
        $project = factory(Project::class)->create();
        $this->post($project->path().'/tasks', ['body'=>'Test task'])->assertStatus(403);
        $this->assertDatabaseMissing('project_tasks', ['body'=>'Test task']);

    }

    /** @test*/
    public function only_the_owner_of_a_project_may_update_a_task() {
        $this->signIn();

        $project = ProjectFactory::withTasks(1)->create();

        $this->patch($project->tasks[0]->path(), ['body'=>'changed'])->assertStatus(403);
        $this->assertDatabaseMissing('project_tasks', ['body'=>'changed']);

    }

    /** @test */
    public function a_project_can_have_tasks() {
        $project = ProjectFactory::ownedBy($this->signIn())->create();

        $this->post($project->path().'/tasks', ['body'=>'Test task']);

        $this->get($project->path())->assertSee('Test task');
    }

    /** @test */
    public function a_task_can_be_updated() {
        $project =ProjectFactory::ownedBy($this->signIn())
            ->withTasks(1)
            ->create();

        $this->patch($project->tasks[0]->path(), [
            'body'=>'changed'
        ]);
        $this->assertDatabaseHas('project_tasks',[
            'body'=>'changed'
        ]);
    }

    /** @test */
    public function a_tasks_completed_status_can_be_toggled() {
        $this->withoutExceptionHandling();
        $project = ProjectFactory::ownedBy($this->signIn())
            ->withTasks(1)
            ->create();

        $this->patch($project->tasks[0]->path(), $attributes = [
            'body'=>'changed',
            'completed'=>true
        ]);

        $this->assertDatabaseHas('project_tasks',$attributes);

        $this->patch($project->tasks[0]->path(), $attributes = [
            'body'=>'changed',
            'completed'=>false
        ]);
        $this->assertDatabaseHas('project_tasks',$attributes);
    }

    /** @test */
    public function a_task_requires_a_body() {
        $project = ProjectFactory::ownedBy($this->signIn())->create();

    	$attributes = factory('App\ProjectTask')->raw(['body' => '']);
    	$this->post($project->path().'/tasks', $attributes)->assertSessionHasErrors('body');
    }

}
