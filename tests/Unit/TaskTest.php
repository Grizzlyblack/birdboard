<?php

namespace Tests\Unit;

use App\Project;
use App\ProjectTask;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskTest extends TestCase
{ use RefreshDatabase;
    /** @test */
	function it_belongs_to_a_project() {
		$task = factory(ProjectTask::class)->create();

		$this->assertInstanceOf(Project::class, $task->project);
	}
    /** @test */
    function it_has_a_path() {
    	$task = factory(ProjectTask::class)->create();

    	$this->assertEquals('/tasks/'.$task->id, $task->path());
    }
    // /** @test */
    // public function it_can_be_completed() {
    //     $task = factory(ProjectTask::class)->create();

    //     $this->assertFalse($task->fresh()->completed);

    //     $task->toggleComplete(true);

    //     $this->assertTrue($task->fresh()->completed);
    // }
    /** @test */
    public function it_can_be_toggled_between_complete_and_incomplete() {
        $task = factory(ProjectTask::class)->create();

        $this->assertFalse($task->fresh()->completed);

        $task->toggleComplete(true);

        $this->assertTrue($task->fresh()->completed);

        $task->toggleComplete(false);

        $this->assertFalse($task->fresh()->completed);
    }
}
