@extends('layouts.app')

@section('content')
<header class="flex items-end mb-6 pb-4">
	<div class="flex justify-between items-end w-full">
		<p class="text-muted text-sm font-light">
			<a href="/projects" class="text-muted">My Projects</a> / {{$project->title}}
		</p>
		<div class="flex items-center">
			@foreach($project->members as $member)
				<img src="{{gravatar_url($member->email)}}" 
					alt="{{$member->name}}'s avatar" 
					class="rounded-full w-8 mr-2">
			@endforeach
			<a href="{{$project->path().'/edit'}}" 
				class="button align-bottom ml-6"
				>Edit Project</a>
		</div>
	</div>
</header>
<main>
	<div class="lg:flex -mx-3">
		<div class="lg:w-3/4 px-3 mb-6">
			<div class="mb-8">
				<h2 class="text-lg text-muted font-light mb-3">Tasks</h2>
				{{-- tasks --}}
				@foreach($project->tasks as $task)

					<div class="card mb-3">
						<form method="POST" action="{{$task->path()}}">
							@method('PATCH')
							@csrf
							<div class="flex">
								<input name="body" value="{{$task->body}}" 
									class="bg-card text-default w-full {{$task->completed ? 'line-through text-muted': ''}}">
								<input type="checkbox" name="completed" onChange="this.form.submit()" {{$task->completed ? 'checked' : '' }}>
							</div>
						</form>
					</div>
				@endforeach
					
				<div class="card mb-3">
					<form method="POST" action="{{$project->path().'/tasks'}}">
						@csrf
						<input name="body" placeholder="Add a new task..." class="bg-card text-default w-full">
					</form>
				</div>
			</div>

			<div>
				<h2 class="text-lg text-muted font-light mb-3">General Notes</h2>

				<form method="POST" action="{{$project->path()}}">
					@method('PATCH')
					@csrf
					<textarea
						name="notes"
						class="card text-default w-full mb-4" 
						style="min-height: 150px" 
						placeholder="Add notes..."
						>{{$project->notes}}
					</textarea>
					<button type="submit" class="button is-link">Save</button>
				</form>
				@include('layouts.errors')
			</div>
		</div>
		<div class="lg:w-1/4 px-3">
			@include('projects.card')
			@include('projects.activities.card')
			@include('projects.invite')
		</div>
	</div>
</main>
	
@endsection