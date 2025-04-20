<template>
	<button @click="toggleSideBar" class="clickable">
		<svg id="Capa_2" data-name="Capa 2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 19.87 13.57" class="icon">
			<defs></defs>
			<g id="Capa_1-2" data-name="Capa 1">
				<g>
					<line class="cls-1" x1="1" y1="12.57" x2="14" y2="12.57" />
					<line class="cls-1" x1="1" y1="7.44" x2="8.55" y2="7.44" />
					<line class="cls-1" x1="1" y1="2.32" x2="8.55" y2="2.32" />
					<circle class="cls-3" cx="14" cy="3.7" r="3.2" />
					<line class="cls-2" x1="19.37" y1="8.98" x2="16.31" y2="5.92" />
				</g>
			</g>
		</svg>
	</button>
</template>
<script setup>

const emit = defineEmits(['toggle-sidebar-mobile']);
function toggleSideBar() {
	emit('toggle-sidebar-mobile');
}


</script>
<style scoped>
button {
	background: none;
	border: none;
	height: 70px;
	width: 70px;
}

.cls-1 {
	stroke-width: 2px;
}

.cls-1,
.cls-2 {
	stroke-linecap: round;
}

.cls-1,
.cls-2,
.cls-3 {
	fill: none;
	stroke: white;
	stroke-miterlimit: 10;
}

.icon {
	fill: white;
	width: 24px;
	height: 24px;
}

.icon:hover {
	fill: rgb(255, 0, 0);
}
</style>