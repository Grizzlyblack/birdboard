<?php

namespace App;

use App\Activity;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
	use RecordsActivity;

	protected $guarded = [];

	public function path() {
		return "/projects/{$this->id}";
	}

	public function owner() {
		return $this->belongsTo(User::class);
	}
	public function tasks() {
		return $this->hasMany(ProjectTask::class);
	}
	public function activities() {
		return $this->hasMany(Activity::class)->latest();	
	}

	public function members()
	{
		return $this->belongsToMany(User::class, 'project_member')->withTimestamps();
	}

	public function addTask($body) {
		return $this->tasks()->create(compact('body'));
	}

	public function addTasks($tasks) {
		return $this->tasks()->createMany($tasks);
	}

	public function invite(User $user)
	{
		return $this->members()->attach($user);
	}

}