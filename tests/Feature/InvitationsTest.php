<?php

namespace Tests\Feature;

use App\User;
use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class InvitationsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function non_owners_may_not_invite_users() {
        $user = $this->signIn();
        $project = ProjectFactory::create();

        $assertInvitationForbidden = function() use ($user, $project) {
            $this->post($project->path()."/invitations")->assertStatus(403);
        };

        $assertInvitationForbidden();
        $project->invite($user);
        $assertInvitationForbidden();
    }

    /** @test */
    public function a_project_owner_can_invite_a_user() {
        $this->withoutExceptionHandling();
        $project = ProjectFactory::ownedBy($this->signIn())->create();
        $newUser = factory(User::class)->create();
        $this->post($project->path()."/invitations", [
            'email' => $newUser->email,
        ])->assertRedirect($project->path());

        $this->assertTrue($project->members->contains($newUser));
    }

    /** @test */
    public function the_email_must_be_associated_with_a_birdboard_account() {
        $project = ProjectFactory::ownedBy($this->signIn())->create();
        
        $this->post($project->path()."/invitations", [
            'email' => 'notauser@mail.com'
        ])->assertSessionHasErrors([
            'email' => 'The user you are inviting must have a birdboard account.'
        ],null,'invitations');
    }

    /** @test */
    public function invited_users_may_update_project_details() {
        $project = ProjectFactory::create();

        $project->invite($newUser = $this->signIn());

        $this->post($project->path()."/tasks", $task = ['body'=> 'test task'])
            ->assertRedirect($project->path());
        $this->assertDatabaseHas('project_tasks', $task);
    }

}
