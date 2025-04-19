<template>
	<div id="mainContent">
		<div class="mt-5 container d-flex justify-content-center">
			<div
				class="d-flex justify-content-around align-items-center col-12 md:col-8 lg:col-8 xl:col-6 xxl:col-4 mt-3 bingo-history">
				<div v-for="(num, index) in numberHistory" :key="index" class="history-ball">
					{{ num }}
				</div>
			</div>
		</div>
		<div class="mt-3">
			<h4 class="text-center">Players ready: {{ countReadyPlayers }}/1 </h4>
			<!-- Modificar el contador al umbral asignado en el backend -->
			<!-- <h5>Números salidos:</h5>
			<div class="d-flex flex-wrap">
				<div v-for="ball in drawnBalls" :key="ball" class="bingo-cell" style="width: 30px; height: 30px">
					{{ ball }}
				</div>
			</div> -->
		</div>
		<div class="timer-number-container my-5 d-flex justify-content-center align-items-center">
			<Timer v-if="timerSeconds" :seconds="timerSeconds" />
			<div class="current-number" v-if="currentBall !== null" :class="{ 'fade-out': isFading }">
				<div class="white-circle"></div>
				<div class="number">
					{{ currentBall }}
				</div>
			</div>
		</div>
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
					<button :disabled="wrongLineCalls >= 3" @click="callLine(route.params.id, authStore().user.id)"
						class="btn btn-secondary ms-2">
						Call Line
					</button>
					<button :disabled="wrongBingoCalls >= 3" @click="callBingo(route.params.id, authStore().user.id)"
						class="btn btn-secondary ms-2">
						Call Bingo
					</button>
				</div>
			</div>
		</div>
		<div class="row lg:justify-content-center mx-0 mb-5 cards-container">
			<div v-for="(card, cardIndex) in bingoCards" :key="cardIndex" class="md:col-6 xl:col-4 xxl:col-3 mb-3">
				<div class="card p-3">
					<h4 class="text-center">BINGO CARD #{{ cardIndex + 1 }}</h4>
					<div v-for="(row, rowIndex) in card" :key="rowIndex" class="d-flex justify-content-center">
						<div v-for="(cell, colIndex) in row" :key="colIndex" class="bingo-cell"
							:class="{ 'empty-cell': cardsPositions[cardIndex][rowIndex][colIndex] == null, 'cell-marker': cardsPositions[cardIndex][rowIndex][colIndex] == true }">
							{{ cell }}
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
import { useRoute } from 'vue-router';

const route = useRoute();

const swal = inject("$swal");
const { getChips, updateChips } = useUsers();
const {
	player,
	bingoCards,
	cardsPositions,
	isReady,
	wrongLineCalls,
	wrongBingoCalls,
	drawnBalls,
	numberHistory,
	getPlayer,
	loadGameData,
	getPlayersStatus,
	startGame,
	isGameOngoing,
	generateBingoCard,
	generateNumbersPosition,
	joinGame,
	updatePlayerGameData,
	updatePlayerStatus,
	checkForNumber,
	callLine,
	callBingo
} = useBingo();

const imLeader = ref(false);
const currentBall = ref(null);
const isFading = ref(false);
const timerSeconds = ref(null);

const drawNumber = (number) => {

	currentBall.value = number;
	drawnBalls.value.push(number);
	isFading.value = false;

	speakBall(number);
	checkForNumber(number);

	setTimeout(() => {
		isFading.value = true;
		setTimeout(() => {
			if (numberHistory.value.length >= 6) {
				numberHistory.value.shift();
			}
			numberHistory.value.push(currentBall.value);
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
	window.Echo.join(`bingo-${route.params.id}`)
		.here((channelUsers) => {
			loadGameData(route.params.id, authStore().user.id);
			users.value = channelUsers;
			updatePlayersStatus();
			if (channelUsers.length === 1) {
				imLeader.value = true;
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
		.listen('.BroadcastWinners', (data) => {
			let lineWinners = [];
			let bingoWinners = [];

			if (data.lineWinners.length > 0) {
				lineWinners = data.lineWinners.map(winner => users.value.find(user => user.id == winner).username);
			}
			if (data.bingoWinners.length > 0) {
				bingoWinners = data.bingoWinners.map(winner => users.value.find(user => user.id == winner).username);
			}

			swal.fire({
				icon: "success",
				title: `Bingo!`,
				text: `Line winners: ${lineWinners.join(', ')}\nBingo winners: ${bingoWinners.join(', ')}`,
				background: '#2A2A2A',
				color: '#ffffff'
			});

			restoreDataAfterGame();
			updateUserChips();
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

const restoreDataAfterGame = () => {
	bingoCards.value = [];
	cardsPositions.value = [];
	player.value = null;
	isReady.value = false;
	wrongLineCalls.value = 0;
	wrongBingoCalls.value = 0;
	drawnBalls.value = [];
	currentBall.value = null;
	numberHistory.value = [];

	console.log(users.value);
	for (const user of users.value) {
		user.isReady = false;
	}
}

const updateUserChips = async () => {
	authStore().user.chips = await getChips(authStore().user.id);
}

const startCountdown = () => {
	let secondsPassed = 0;
	const countdown = setInterval(() => {
		if (secondsPassed >= timerSeconds.value) {
			clearInterval(countdown);
			timerSeconds.value = null;
			if (imLeader.value) {
				startGame(route.params.id);
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
	if (!await isGameOngoing(route.params.id)) {
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
					joinGame(route.params.id, bingoCardsAmount.value * 10);
					getPlayer(route.params.id, authStore().user.id);
				} else {
					updatePlayerGameData(route.params.id, authStore().user.id, bingoCardsAmount.value * 10);
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
	} else {
		swal.fire({
			icon: "error",
			title: "Error",
			text: `You cannot join the game because it has already started.`,
			background: '#2A2A2A',
			color: '#ffffff'
		});
	}
};

const bingoCardsAmount = ref(1);

const updateStatus = () => {
	isReady.value = !isReady.value;
	updatePlayerStatus(route.params.id, authStore().user.id, isReady.value);
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

.empty-cell {
	background-color: #2a2a2a;
}

.cell-marker {
	background-color: rgba(255, 0, 0, 0.5);
}

.bingo-history {
	min-height: 74px;
	background-color: #222;
	padding: 10px 20px;
	gap: 5%;
	border-radius: 10px;
	border: 2px solid white;
}

.history-ball {
	width: 50px;
	height: 50px;
	background: radial-gradient(circle at 25% 25%, white 1px, rgb(226, 226, 226) 20%, #7e7e7e 80%);
	color: black;
	font-weight: bold;
	border-radius: 50%;
	display: flex;
	justify-content: center;
	align-items: center;
	font-size: 18px;
}

.timer-number-container {
	min-width: 150px;
	min-height: 150px;
}

.current-number {
	width: 150px;
	height: 150px;
	border-radius: 50%;
	/* background: radial-gradient(circle at 25% 25%, rgb(255, 0, 0) 1px, rgb(218, 0, 0) 20%, #850000 80%); */
	background: radial-gradient(circle at 25% 25%, rgb(255, 255, 255) 1px, rgb(226, 226, 226) 20%, #7e7e7e 80%);
	/* color: white; */
	color: black;
	font-weight: bold;
	font-size: 3rem;
	display: flex;
	justify-content: center;
	align-items: center;
	animation: bounce-in 0.4s ease;
	position: relative;
}

.number {
	position: absolute;
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

@media (max-width: 768px) {
	.bingo-cell {
		width: 30px;
		height: 30px;
	}

	.history-ball {
		width: 35px;
		height: 35px;
	}

	.cards-container {
		flex-wrap: nowrap;
		overflow-x: auto;
		-webkit-overflow-scrolling: touch;
	}
}
</style>