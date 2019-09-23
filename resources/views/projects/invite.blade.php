@can('manage', $project)
	<div class="card mt-6">
		<h3 class="font-normal text-xl py-4 mb-3 -ml-5 border-l-4 border-accent-light pl-4">
			Invite a user
		</h3>

		<form method="POST" action="{{$project->path().'/invitations'}}">
			@csrf
			<div class="mb-3">
				<input type="text" name="email" 
					class="bg-card text-default w-full mb-2 py-2 px-3" 
					placeholder="Email address">
			</div>
			<button type="submit" class="button">Invite</button>
		</form>
		@include('layouts.errors', ['bag'=>'invitations'])
	</div>
@endcan