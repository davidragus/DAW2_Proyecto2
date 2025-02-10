<template>
	<button @click="rotateIcon">
		<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 11.95 7" id="buttonIcon" class="icon"
			:class="{ rotated: isRotated }">
			<g>
				<g>
					<path
						d="M.15,3.15c.2-.2.51-.2.71,0l2.89,2.89c.2.2.2.51,0,.71s-.51.2-.71,0L.15,3.85c-.2-.2-.2-.51,0-.71" />
					<path
						d="M.15,3.85c-.2-.2-.2-.51,0-.71L3.04.25c.2-.2.51-.2.71,0s.2.51,0,.71L.85,3.85c-.2.2-.51.2-.71,0" />
					<path
						d="M4.85,6.5c0-.28.22-.5.5-.5h6.11c.28,0,.5.22.5.5s-.22.5-.5.5h-6.11c-.28,0-.5-.22-.5-.5M0,3.5C0,3.22.22,3,.5,3h10.95c.28,0,.5.22.5.5s-.22.5-.5.5H.5C.22,4,0,3.78,0,3.5M4.85.5c0-.28.22-.5.5-.5h6.11c.28,0,.5.22.5.5s-.22.5-.5.5h-6.11c-.28,0-.5-.22-.5-.5" />
				</g>
			</g>
		</svg>
	</button>
</template>

<script>
export default {
	name: 'SideBarButton',
	data() {
		return {
			isRotated: false,
		};
	},
	methods: {
		rotateIcon() {
			this.isRotated = !this.isRotated; // Cambia el estado de rotación
		},
	}
}
</script>

<style scoped>
button {
	background: none;
	border: none;
	height: 70px;
	width: 90px;
}

.icon {
	fill: white;
	width: 24px;
	height: 24px;
}

.icon:hover {
	fill: white;
}

.rotated {
	transform: rotate(180deg);
}
</style>