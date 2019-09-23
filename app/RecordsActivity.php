<?php

namespace App;

trait RecordsActivity
{
    public $oldAttributes = [];

    /**
     * Description
     * @return type
     */
    public static function bootRecordsActivity()
    {
    	foreach(self::recordableEvents() as $event) {
    		static::$event(function($model) use ($event) {
    			$model->recordActivity($model->activityDescription($event));
    		});

    		if($event == 'updated')
    			static::updating(function($model) {
		    		$model->oldAttributes = $model->getOriginal();
		    	});
    	}
    }
    /**
     * Description
     * @return type
     */
    protected static function recordableEvents() {
    	if(isset(static::$recordableEvents))
    		return static::$recordableEvents;
    	return ['created', 'updated'];
    }

    /**
     * Description
     * @param type $description 
     * @return type
     */
    protected function activityDescription($description)
    {
		if(($modelName = strtolower(class_basename($this))) 
			== 'projecttask')
			$modelName = 'task';

		return $description .= " {$modelName}";
    }

    /**
     * Description
     * @return type
     */
    public function activities() {
        return $this->morphMany(Activity::class, 'subject')->latest();   
    }

    /**
     * Description
     * @param type $description 
     * @return type
     */
	public function recordActivity($description)
    {
        $this->activities()->create([
        	'description'=>$description,
            'project_id'=>class_basename($this) === 'Project' 
            	? $this->id : $this->project_id,
            'user_id'=>($this->project ?? $this)->owner->id,
        	'changes'=> $this->activityChanges()
        ]);
    }

    /**
     * Description
     * @return type
     */
    protected function activityChanges()
    {
    	if(! $this->wasChanged()) return;
    	return [
    		'before'=>array_except(
    			array_diff(
    				$this->oldAttributes, $this->getAttributes()
    			), 'updated_at'),
    		'after'=>array_except($this->getChanges(), 'updated_at')
    	];
    }
}