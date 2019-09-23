<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Project;
use App\ProjectTask;

class ProjectTaskController extends Controller
{
    /**
     * Description
     * @param Project $project 
     * @return type
     */
	public function create(Project $project) {
		$this->authorize('update', $project);

		return view('projects.tasks.create', compact($project));
	}

    /**
     * Description
     * @param Project $project 
     * @return type
     */
    public function store(Project $project) {
    	$this->authorize('update', $project);

    	request()->validate(['body' => 'required']);
    	$project->addTask(request('body'));

    	return redirect($project->path());
    }

    /**
     * Description
     * @param Project $project 
     * @param ProjectTask $task 
     * @return type
     */
    public function update(ProjectTask $task) {
        $this->authorize('update', $task->project);

        $task->update(request()
            ->validate(['body'=>'required']));
        $task->toggleComplete(request('completed') ? true : false);
        
    	return redirect($task->project->path());
    }
}
