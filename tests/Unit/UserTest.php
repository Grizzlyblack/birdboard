<?php

namespace Tests\Unit;

use App\User;
use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
	use RefreshDatabase;
    /** @test */
    public function a_user_has_projects() {
    	$user = $this->signIn();

    	$this->assertInstanceOf(Collection::class,  $user->projects);
    }
    /** @test */
    public function a_user_can_access_shared_projects() {
    	$ethan = $this->signIn();

    	ProjectFactory::ownedBy($ethan)->create();

    	$this->assertCount(1, $ethan->allProjects());

    	$alex = factory(User::class)->create();
    	$jack = factory(User::class)->create();

    	$project = tap(ProjectFactory::ownedBy($alex)->create())
    		->invite($jack);

    	$this->assertCount(1, $ethan->allProjects());

    	$project->invite($ethan);

    	$this->assertCount(2, $ethan->allProjects());

    }
}
