<template>
	<div class="base-timer">
		<svg class="base-timer-svg" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
			<g class="base-timer-circle">
				<circle class="base-timer-path-elapsed" cx="50" cy="50" r="46.5" />
				<path :stroke-dasharray="circleRemainingTime" class="base-timer-path-remaining" d="
				M 50, 50
				m -46.5, 0
				a 46.5,46.5 0 1,0 93,0
				a 46.5,46.5 0 1,0 -93,0
				" />
			</g>
		</svg>
		<span class="base-timer-label">
			{{ timeLeft }}
		</span>
	</div>
</template>

<script setup>

import { ref, onMounted, computed } from "vue";

const secondsPassed = ref(0);
const FULL_DASH_ARRAY = 292;
const props = defineProps({ seconds: { type: Number, default: 0 } });

const timeLeft = computed(() => {
	return props.seconds - secondsPassed.value;
});

const circleRemainingTime = computed(() => {
	return `${(timeFraction.value * FULL_DASH_ARRAY).toFixed(0)} 292`;
})

const timeFraction = computed(() => {
	return timeLeft.value / props.seconds;
});

const startTimer = () => {
	const interval = setInterval(() => {
		console.log(circleRemainingTime.value);
		if (secondsPassed.value >= props.seconds) {
			clearInterval(interval);
			return;
		}
		secondsPassed.value++;
	}, 1000);
};

onMounted(() => {
	startTimer();
});

</script>

<style scoped>
.base-timer {
	position: relative;
	width: 300px;
	height: 300px;
}

.base-timer-circle {
	fill: none;
	stroke: none;
}

.base-timer-path-elapsed {
	stroke-width: 7px;
	stroke: grey;
}

.base-timer-label {
	position: absolute;
	width: 300px;
	height: 300px;
	top: 0;
	display: flex;
	align-items: center;
	justify-content: center;
	font-size: 48px;
}

.base-timer-path-remaining {
	stroke-width: 7px;
	stroke-linecap: round;
	transform: rotate(90deg);
	transform-origin: center;
	transition: 1s linear all;
	stroke: rgb(65, 184, 131);
}

.base-timer-svg {
	transform: scaleX(-1);
}
</style>