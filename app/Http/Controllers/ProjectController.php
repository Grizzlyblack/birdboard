<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Http\Requests\UpdateProjectRequest;
use App\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * @return \Illuminate\Http\Response
     */
    public function index() {
    	$projects = auth()->user()->allProjects();
		return view('projects.index', compact('projects'));
    }

    /**
     * @param  Project
     * @return [type]
     */
    public function show(Project $project) {
        $this->authorize('update', $project);
        return view('projects.show', compact('project'));
    }

    /**
     * @return [type]
     */
    public function create() {
        return view('projects.create');
    }

    /**
     * @return [type]
     */
    public function store() {
        $project = auth()->user()->projects()
            ->create($this->validateRequest());

        ($tasks = request('tasks')) ? $project->addTasks($tasks) : null;

        return request()->wantsJson() ? 
            ['message' => $project->path()] : 
            redirect($project->path());
    }

    public function edit(Project $project)
    {
        $this->authorize('update', $project);
        return view('projects.edit', compact('project'));
    }

    /**
     * Description
     * @param UpdateProjectRequest $request 
     * @param Project $project 
     * @return type
     */
    public function update(Project $project) {
        $this->authorize('update', $project);
        $project->update($this->validateRequest());

        return redirect($project->path());
    }

    public function destroy(Project $project)
    {
        $this->authorize('manage', $project);
        $project->delete();

        return redirect('/projects');
    }

    protected function validateRequest() {
        return request()->validate([
            'title'=>'sometimes|required', 
            'description'=>'sometimes|required:max:100',
            'notes'=>'nullable',
        ]);
    }

}
