<?php

namespace App\Http\Controllers;

use App\Project;
use App\User;
use Illuminate\Http\Request;

class ProjectInvitationController extends Controller
{
    public function store(Project $project)
    {
    	$this->authorize('manage', $project);

        $this->validateWithBag('invitations', request(), [
            'email'=>['required', 'exists:users,email']
        ],[
            'email.exists' => 'The user you are inviting must have a birdboard account.'
        ]);

    	$user = User::whereEmail(request('email'))->first();

    	$project->invite($user);

    	return redirect($project->path());
    }
}
