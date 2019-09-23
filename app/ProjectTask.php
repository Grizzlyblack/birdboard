<?php

namespace App;

use App\Activity;
use Illuminate\Database\Eloquent\Model;

class ProjectTask extends Model
{
    use RecordsActivity;

    protected $guarded=[];
    protected $touches = ['project'];
    protected $casts = ['completed' => 'boolean'];

    protected static $recordableEvents = ['created', 'deleted'];

    public function toggleComplete($toggler)
    {   if($toggler != $this->completed) {
            $this->update(['completed' => $toggler]);
            $this->recordActivity($toggler ? 'completed task' : 'uncompleted task');
        }

    }

    public function project() {
    	return $this->belongsTo(Project::class);
    }

    public function path() {
    	return "/tasks/{$this->id}";
    }

}
