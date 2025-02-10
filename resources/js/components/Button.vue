<template>
	<button class="" :class="`${buttonColor}-${buttonStyle}`, `${buttonSize}-button`">
		<slot></slot>
	</button>
</template>

<script setup>
/**
 * Valores de los atributos:
 * buttonColor: red - yellow
 * buttonStyle: filled - outlined
 * buttonSize: normal - compact
 */
defineProps({
	buttonColor: String,
	buttonStyle: String,
	buttonSize: String
});
</script>

<style scoped>
button {
	font-family: Inter, sans-serif;
	font-weight: 800;
	text-rendering: optimizeLegibility;
	border: none;
	border-radius: 20px;
}

.red-filled {
	background-color: red;
	color: black;
	border: 2px solid red;
}

.red-filled:hover {
	background-color: transparent;
	color: red;
}

.red-outlined {
	background-color: transparent;
	color: red;
	border: 2px solid red;
}

.red-outlined:hover {
	background-color: red;
	color: black;
}

.yellow-filled {
	background-color: yellow;
	color: black;
	border: 2px solid yellow;
}

.yellow-filled:hover {
	background-color: transparent;
	color: yellow;
}

.yellow-outlined {
	background-color: transparent;
	color: yellow;
	border: 2px solid yellow;
}

.yellow-outlined:hover {
	background-color: yellow;
	color: black;
}

.normal-button {
	font-size: 16px;
	padding: 10px 20px;
}

.compact-button {
	font-size: 12px;
	padding: 5px 10px;
}
</style>