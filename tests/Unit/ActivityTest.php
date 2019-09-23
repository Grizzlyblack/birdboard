<?php

namespace Tests\Unit;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Facades\Tests\Setup\ProjectFactory;
use Tests\TestCase;

class ActivityTest extends TestCase
{
	use RefreshDatabase;

    /** @test */
    public function it_has_a_user() {
    	$user = $this->signIn();
    	$project = ProjectFactory::ownedBy($user)->create();

    	$this->assertEquals($user->id, $project->activities->first()->user_id);
    }
}
