<template>
	<div class="dropdown relative">
		<div class="dropdown-toggle"
			aria-haspopup="true"
			:aria-expanded="isOpen"
			@click.prevent="isOpen = !isOpen">

			<slot name="trigger"></slot>
		</div>

		<div v-show="isOpen"
			class="absolute bg-card dropdown-menu mt-2 py-2 rounded shadow"
			:class="align === 'left' ? 'pin-l' : 'pin-r'"
			:style="{width}">
			<slot></slot>
		</div>

	</div>
</template>

<script>
	export default {
		props: {
			width: {default: 'auto'},
			align: {default: 'left'}
		},

		data() {
			return { isOpen: false}
		},

		watch: {
			isOpen(isOpen) {
				if(isOpen) {
					document.addEventListener('click', this.closeIfClickedOutside);
				}
			}
		},

		methods: {
			closeIfClickedOutside(event) {
				if(! event.target.closest('.dropdown')) {
					this.isOpen = false;

					document.removeEventListener('click', this.closeIfClickedOutside);

				}
			}
		}
	}
</script>