<template>
	<modal name="new-project-modal" classes="p-10 bg-card rounded-lg" height="auto">

		<form @submit.prevent="submit">
			<h1 class="font-normal mb-16 text-center text-2xl">Let's Start Something New</h1>

			<div class="flex">
				<div class="flex-1 mr-4">
					<div class="mb-4">
						<label for="title" class="text-sm block mb-2">Title</label>

						<input type="text" 
							id="title" 
							class="border p-2 text-xs block w-full rounded"
							:class="form.errors.title ? 'border-error' : 'border-muted-light'"
							v-model="form.title">

						<span class="text-xs italic text-error" v-if="form.errors.title" v-text="form.errors.title[0]"></span>
					</div>

					<div class="mb-4">
						<label for="description" class="text-sm block mb-2">Description</label>

						<textarea 
							type="text" id="description" 
							class="border border-muted-light p-2 text-xs block w-full rounded"
							:class="form.errors.description ? 'border-error' : 'border-muted-light'"
							rows="7" v-model="form.description"></textarea>

						<span class="text-xs italic text-error" v-if="form.errors.description" v-text="form.errors.description[0]"></span>

					</div>
				</div>
				<div class="flex-1 mr-4">
					<div class="mb-4">
						<label class="text-sm block mb-2">Need Some Tasks?</label>
						<input 
							type="text" 
							class="border border-muted-light mb-2 p-2 text-xs block w-full rounded" 
							placeholder="Task 1" 
							v-for="task in form.tasks"
							v-model="task.body">
					</div>

					<button type="button" class="inline-flex items-center text-xs" @click="addTask">
						<svg class="mr-2" viewbox="0 0 18 18" height="18" width="18">
							<g fill="#000" fill-rule="evenodd" opacity=".307">
								<path fill="#000" d="M14.613,10c0,0.23-0.188,0.419-0.419,0.419H10.42v3.774c0,0.23-0.189,0.42-0.42,0.42s-0.419-0.189-0.419-0.42v-3.774H5.806c-0.23,0-0.419-0.189-0.419-0.419s0.189-0.419,0.419-0.419h3.775V5.806c0-0.23,0.189-0.419,0.419-0.419s0.42,0.189,0.42,0.419v3.775h3.774C14.425,9.581,14.613,9.77,14.613,10 M17.969,10c0,4.401-3.567,7.969-7.969,7.969c-4.402,0-7.969-3.567-7.969-7.969c0-4.402,3.567-7.969,7.969-7.969C14.401,2.031,17.969,5.598,17.969,10 M17.13,10c0-3.932-3.198-7.13-7.13-7.13S2.87,6.068,2.87,10c0,3.933,3.198,7.13,7.13,7.13S17.13,13.933,17.13,10"></path>
							</g>
						</svg>
						<span>Add New Task Field</span>
					</button>

				</div>
			</div>
			<footer class="flex justify-end">
				<button type="button" class="button is-outlined mr-4"  @click="$modal.hide('new-project-modal')">Cancel</button>
				<button class="button">Create Project</button>

			</footer>
		</form>
	</modal>
</template>

<script>                                                     
	import BirdboardForm from './BirdboardForm';
	export default {
		data() {
			return {
				form: new BirdboardForm ({
					title: '',
					description: '',
					tasks: [
						{ body: ''},

					],
				}),

			}
		},
		methods: {
			addTask() {
				this.form.tasks.push({ body: '' });
			},

			async submit() {
				if(! this.form.tasks[0].body) {
					delete this.form.originalData.tasks;
				}
				this.form.submit('/projects')
					.then(response => location = response.data.message);
			}
		}
	}
</script>