<div class="card flex flex-col" style="height: 200px">
	<h3 class="font-normal text-xl py-4 flex-1 -ml-5 border-l-4 border-accent-light pl-4">
		<a href="{{$project->path()}}" class="text-default">{{$project->title}}</a>
	</h3>

	<div class=" flex-1 break-words">{{ $project->description }}</div>

	@can('manage', $project)
		<footer>
			<form method="POST" action="{{$project->path()}}" class="text-right">
				@method('DELETE')
				@csrf
				<button type="submit" class="text-xs">Delete</button>
			</form>
		</footer>
	@endcan
</div>
