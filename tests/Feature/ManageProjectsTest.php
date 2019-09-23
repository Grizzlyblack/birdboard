<?php

namespace Tests\Feature;

use App\Project;
use Tests\TestCase;
use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ManageProjectsTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test */
    public function guests_cannot_interact_with_projects() {
        $project = factory(Project::class)->create();

        $this->get('/projects')->assertRedirect('login');
        $this->get($project->path())->assertRedirect('login');
        $this->get($project->path().'/edit')->assertRedirect('login');
        $this->get('/projects/create')->assertRedirect('login');
        $this->post('/projects', $project->toArray())->assertRedirect('login');
        $this->delete($project->path())->assertRedirect('login');

    }

    /** @test */
    public function a_user_can_create_a_project_and_view_it() {
        $this->signIn();

        $this->get('/projects/create')->assertStatus(200);

        $this->followingRedirects()
            ->post('/projects',
                $attributes = factory(Project::class)->raw())
            ->assertSee($attributes['title'])
            ->assertSee($attributes['description'])
            ->assertSee($attributes['notes']);
    }

    /** @test */
    public function tasks_can_be_included_as_part_of_a_new_project() {
        $this->withoutExceptionHandling();
        $this->signIn();

        $attributes = factory(Project::class)->raw();
        $attributes['tasks'] = [
            ['body' => 'Task 1'],
            ['body'=> 'Task 2']
        ];
        $this->post('/projects',$attributes);

        $this->assertCount(2, Project::first()->tasks);
    }

    /** @test */
    public function a_user_can_see_projects_they_have_been_invited_to() {

        $project = tap(ProjectFactory::create())
            ->invite($newUser = $this->signIn());

        $this->get('/projects')->assertSee($project->title);
    }

    /** @test */
    public function a_user_can_delete_a_project() {
        $project = ProjectFactory::ownedBy($this->signIn())->create();
        $this->delete($project->path())->assertRedirect('/projects');

        $this->assertDatabaseMissing('projects', $project->only('id'));
    }
    /** @test */
    public function unauthorized_users_cannot_delete_projects() {
        $user = $this->signIn();

        $project = ProjectFactory::create();

        $this->delete($project->path())->assertStatus(403);

        $project->invite($user);

        $this->delete($project->path())->assertStatus(403);
    }

    /** @test*/
    public function a_user_can_update_a_project() {
        $this->withoutExceptionHandling();
        $project = ProjectFactory::ownedBy($this->signIn())->create();

        $this->get($project->path() . '/edit')->assertOk();

        $this->patch($project->path(), $attributes = [
            'title'=>'changed', 
            'description'=>'changed',
            'notes'=>'changed'])->assertRedirect($project->path());

        $this->assertDatabaseHas('projects',$attributes);
    }

    /** @test */
    public function a_user_can_update_a_projects_general_notes() {
        $project = ProjectFactory::ownedBy($this->signIn())->create();



        $this->patch($project->path(), 
            $attributes = ['notes'=>'changed']);

        $this->assertDatabaseHas('projects',$attributes);
    }

    /** @test */
    public function an_authenticated_user_cannot_view_others_projects() {
        $this->signIn();

        $project = factory(Project::class)->create();

        $this->get($project->path())->assertStatus(403);
    }

    /** @test */
    public function an_authenticated_user_cannot_update_others_projects() {
        $this->signIn();

        $project = factory(Project::class)->create();
        $this->patch($project->path())->assertStatus(403);
    }


    /** @test */
    public function a_project_requires_a_title() {
        $this->signIn();
        $attributes = factory(Project::class)->raw(['title'=>""]);

        $this->post('/projects', $attributes)
            ->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_project_requires_a_description() {
        $this->signIn();

        $attributes = factory(Project::class)->raw(['description'=>""]);
        $this->post('/projects', $attributes)->assertSessionHasErrors('description');
    }

}