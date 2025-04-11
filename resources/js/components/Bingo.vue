<template>
	<div id="mainContent">
		<div class="mt-3">
			<h4 class="text-center">Players ready: {{ countReadyPlayers }}/1 </h4>
			<!-- Modificar el contador al umbral asignado en el backend -->
			<h5>Números salidos:</h5>
			<div class="d-flex flex-wrap">
				<div v-for="ball in drawnBalls" :key="ball" class="bingo-cell" style="width: 30px; height: 30px">
					{{ ball }}
				</div>
			</div>
		</div>
		<div class="bola-actual" v-if="currentBall !== null" :class="{ 'fade-out': isFading }">
			{{ currentBall }}
		</div>

		<Timer v-if="timerSeconds" :seconds="timerSeconds" />
		<div class="container mt-3">
			<div class="row justify-content-center mb-3">
				<div class="col-auto">
					<input type="number" v-model.number="bingoCardsAmount" min="1" max="10" class="form-control"
						placeholder="Número de cartones" />
				</div>
				<div class="col-auto">
					<button @click="generateBingoCards" class="btn btn-primary">
						Generar Cartones
					</button>
					<button :disabled="bingoCards.length < 1" @click="updateStatus" class="btn btn-secondary ms-2">
						{{ isReady ? 'Not ready' : 'Ready' }}
					</button>
				</div>
			</div>
			<div class="row">
				<div v-for="(card, cardIndex) in bingoCards" :key="cardIndex" class="col-md-4 col-lg-3 mb-3">
					<div class="card p-2">
						<div v-for="(row, rowIndex) in card" :key="rowIndex" class="d-flex justify-content-center">
							<div v-for="(cell, colIndex) in row" :key="colIndex" class="bingo-cell">
								<div v-if="cardsPositions[cardIndex][rowIndex][colIndex] == true" class="cell-marker">
								</div>
								{{ cell }}
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script setup>
import { ref, computed, inject, onMounted, onBeforeUnmount } from "vue";
import { useSpeechSynthesis } from '@vueuse/core'
import { langStore } from "@/store/lang";
import useBingo from '@/composables/bingo';
import Echo from "laravel-echo";
import { authStore } from "../store/auth";
import useUsers from '@/composables/users';
import Timer from './Timer.vue';

const swal = inject("$swal");
const { getChips, updateChips } = useUsers();
const {
	player,
	bingoCards,
	cardsPositions,
	isReady,
	getPlayer,
	getPlayersStatus,
	startGame,
	isGameOngoing,
	generateBingoCard,
	generateNumbersPosition,
	joinGame,
	updatePlayerGameData,
	updatePlayerStatus,
	checkForNumber
} = useBingo();

const imLeader = ref(false);
const allBalls = ref(Array.from({ length: 90 }, (_, i) => i + 1));
const drawnBalls = ref([]);
const currentBall = ref(null);
const isFading = ref(false);
const timerSeconds = ref(null);

const drawNumber = (number) => {

	allBalls.value.splice(number, 1)[0];
	currentBall.value = number;
	drawnBalls.value.push(number);
	isFading.value = false;

	speakBall(number);
	checkForNumber(number);

	setTimeout(() => {
		isFading.value = true;
		setTimeout(() => {
			currentBall.value = null;
		}, 500);
	}, 3000);
};

const textToSpeak = ref("");
const locale = ref(langStore().locale);
const language = computed(() => `${locale.value.toLowerCase()}-${locale.value.toUpperCase()}`);
const { speak, isSupported } = useSpeechSynthesis(textToSpeak, { lang: language.value, rate: 0.7 });

const speakBall = (number) => {
	// console.log(language.value);
	if (!isSupported.value) {
		console.warn("Speech synthesis not supported");
		return;
	}

	textToSpeak.value = `${number}`;
	speak();
};

window.Echo = new Echo({
	broadcaster: 'pusher',
	key: import.meta.env.VITE_PUSHER_APP_KEY,
	cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
	forceTLS: true,
	authEndpoint: "/broadcasting/auth",
});

const users = ref([]);

onMounted(() => {
	window.Echo.join('bingo')
		.here((channelUsers) => {
			getPlayer(1, authStore().user.id);
			users.value = channelUsers;
			updatePlayersStatus();
			if (channelUsers.length === 1) {
				imLeader.value = true;
				console.log('Yo soy el lider');
			}
		})
		.joining((user) => {
			users.value.push(user);
		})
		.leaving((user) => {
			const userIndex = users.value.findIndex((u) => u.id === user.id);
			users.value.splice(userIndex, 1);
		})
		.listen('.ChangePlayerStatus', (data) => {
			const userIndex = users.value.findIndex((user) => user.id == data.id);
			users.value[userIndex].isReady = data.isReady;
		})
		.listen('.client-ChangeLeader', (data) => {
			const userIndex = users.value.findIndex((user) => user.id == data.id);
			if (users.value[userIndex].id == authStore().user.id) {
				imLeader.value = true;
			}
		})
		.listen('.SendCountdown', (data) => {
			timerSeconds.value = data.seconds;
			startCountdown();
		})
		.listen('.DrawNumber', (data) => {
			drawNumber(data.number);
		})
});

onBeforeUnmount(() => {
	changeLeader();
	window.Echo.leave('bingo');
});

window.onbeforeunload = function () {
	changeLeader();
};

const changeLeader = () => {
	if (imLeader.value) {
		const newLeader = users.value.find(user => user.id != authStore().user.id);
		if (newLeader) {
			window.Echo.join('bingo')
				.whisper('ChangeLeader', { id: newLeader.id });
		}
	}
}

const startCountdown = () => {
	let secondsPassed = 0;
	const countdown = setInterval(() => {
		if (secondsPassed >= timerSeconds.value) {
			clearInterval(countdown);
			timerSeconds.value = null;
			if (imLeader.value) {
				startGame();
			}
		}
		secondsPassed++;
	}, 1000);
}

const updatePlayersStatus = async () => {
	const players = await getPlayersStatus(1);
	users.value.forEach(user => {
		user.isReady = players[user.id] == 1 ?? false;
	});
}

const countReadyPlayers = computed(() => {
	return users.value.reduce((accumulator, user) => accumulator + user.isReady ? 1 : 0, 0);
})

const generateBingoCards = async () => {
	if (!await isGameOngoing(1)) {
		const chips = await getChips(authStore().user.id);
		if (chips >= (bingoCardsAmount.value * 10)) {
			if (bingoCards.value.length + bingoCardsAmount.value > 10) {
				swal.fire({
					icon: "error",
					title: "Error",
					text: `You can only have 10 bingo cards at a time.`,
					background: '#2A2A2A',
					color: '#ffffff'
				});
			} else {
				const newBingoCards = Array.from(
					{ length: bingoCardsAmount.value },
					generateBingoCard
				);
				bingoCards.value.push(...newBingoCards);
				generateNumbersPosition(); //TODO: Modificar y añadir una función que añada un array nuevo en base a las tarjetas anteriores en vez de generarlas todas desde 0
				authStore().user.chips = await updateChips(authStore().user.id, chips - (bingoCardsAmount.value * 10));
				if (!player.value) {
					// console.log(player.value);
					joinGame(1);
				} else {
					updatePlayerGameData(1, authStore().user.id);
				}
			}
		} else {
			swal.fire({
				icon: "error",
				title: "Error",
				text: `You don't have enough chips to buy ${bingoCardsAmount.value} bingo cards.`,
				background: '#2A2A2A',
				color: '#ffffff'
			});
		}
	}
};

const bingoCardsAmount = ref(1);

const updateStatus = () => {
	isReady.value = !isReady.value;
	updatePlayerStatus(1, authStore().user.id, isReady.value);
}
</script>

<style scoped>
#mainContent {
	width: 100%;
}

.bingo-cell {
	width: 40px;
	height: 40px;
	border: 1px solid black;
	display: flex;
	align-items: center;
	justify-content: center;
	background-color: #4a4a4a;
	margin: 2px;
	color: white;
	position: relative;
}

.cell-marker {
	position: absolute;
	height: 80%;
	width: 100%;
	background-color: rgba(255, 0, 0, 0.5);
	border-radius: 100%;
	/* opacity: 0;
	transition: opacity 0.3s ease; */
}

.bola-actual {
	width: 100px;
	height: 100px;
	border-radius: 50%;
	background-color: red;
	color: white;
	font-size: 2rem;
	display: flex;
	justify-content: center;
	align-items: center;
	margin: 1rem auto;
	animation: bounce-in 0.4s ease;
}

@keyframes bounce-in {
	0% {
		transform: scale(0.5);
	}

	50% {
		transform: scale(1.2);
	}

	100% {
		transform: scale(1);
	}
}

.fade-out {
	animation: shrink-out 0.5s ease forwards;
}

@keyframes shrink-out {
	0% {
		transform: scale(1);
	}

	50% {
		transform: scale(1.2);
	}

	100% {
		transform: scale(0);
	}
}
</style>