import axios from 'axios';
import { ref, inject } from 'vue'

export default function useBingo() {

	const swal = inject("$swal");

	const player = ref(null);
	const bingoCards = ref([]);
	const cardsPositions = ref([]);
	const isReady = ref(false);
	const wrongLineCalls = ref(0);
	const wrongBingoCalls = ref(0);
	const drawnBalls = ref([]);
	const numberHistory = ref([]);
	const canChangeStatus = ref(false);
	const canBuyCards = ref(false);
	const canCallLine = ref(false);
	const canCallBingo = ref(false);

	const generateBingoCard = () => {
		const columnRanges = [
			[1, 9],
			[10, 19],
			[20, 29],
			[30, 39],
			[40, 49],
			[50, 59],
			[60, 69],
			[70, 79],
			[80, 90],
		];

		const getRandomNumbers = (start, end, count) => {
			const pool = Array.from(
				{ length: end - start + 1 },
				(_, i) => i + start
			);
			const nums = [];
			for (let i = 0; i < count; i++) {
				const idx = Math.floor(Math.random() * pool.length);
				nums.push(pool.splice(idx, 1)[0]);
			}
			return nums.sort((a, b) => a - b);
		};

		let pattern;
		while (true) {
			pattern = Array.from({ length: 3 }, () => Array(9).fill(false));

			for (let r = 0; r < 3; r++) {
				const availableCols = [...Array(9).keys()];
				for (let i = 0; i < 5; i++) {
					const idx = Math.floor(Math.random() * availableCols.length);
					pattern[r][availableCols[idx]] = true;
					availableCols.splice(idx, 1);
				}
			}

			let valid = true;
			for (let c = 0; c < 9; c++) {
				const colCount = pattern.reduce(
					(sum, row) => sum + (row[c] ? 1 : 0),
					0
				);
				if (colCount === 0 || colCount === 3) {
					valid = false;
					break;
				}
			}
			if (valid) break;
		}

		const card = Array.from({ length: 3 }, () => Array(9).fill(""));

		for (let c = 0; c < 9; c++) {
			const rowsWithNumber = [];
			for (let r = 0; r < 3; r++) {
				if (pattern[r][c]) rowsWithNumber.push(r);
			}
			const nums = getRandomNumbers(
				columnRanges[c][0],
				columnRanges[c][1],
				rowsWithNumber.length
			);
			rowsWithNumber.sort((a, b) => a - b);
			rowsWithNumber.forEach((r, i) => {
				card[r][c] = nums[i];
			});
		}

		return card;
	};

	const generateNumbersPosition = () => {
		cardsPositions.value = Array.from({ length: bingoCards.value.length }, () =>
			Array.from({ length: 3 }, () => Array(9).fill(null)));
		for (let i = 0; i < bingoCards.value.length; i++) {
			for (let j = 0; j < bingoCards.value[i].length; j++) {
				for (let k = 0; k < bingoCards.value[i][j].length; k++) {
					if (bingoCards.value[i][j][k]) {
						cardsPositions.value[i][j][k] = false;
					}
				}
			}
		}
	}

	const checkForNumber = (number) => {
		for (let i = 0; i < bingoCards.value.length; i++) {
			for (let j = 0; j < bingoCards.value[i].length; j++) {
				for (let k = 0; k < bingoCards.value[i][j].length; k++) {
					if (bingoCards.value[i][j][k] === number) {
						cardsPositions.value[i][j][k] = true;
					}
				}
			}
		}
	}

	const checkForNumbers = () => {
		for (let i = 0; i < bingoCards.value.length; i++) {
			for (let j = 0; j < bingoCards.value[i].length; j++) {
				for (let k = 0; k < bingoCards.value[i][j].length; k++) {
					if (drawnBalls.value.includes(bingoCards.value[i][j][k])) {
						cardsPositions.value[i][j][k] = true;
					}
				}
			}
		}
	}

	const startGame = async (gameRoomId) => {
		axios.post('/api/game/startGame/' + gameRoomId, {});
	}

	const isGameOngoing = async (gameRoomId) => {
		const response = await axios.get('/api/game/getGameRoom/' + gameRoomId)
			.catch((error) => {
				swal.fire({
					icon: "error",
					title: "Error",
					text: `${error.response.data.message}`,
					background: '#2A2A2A',
					color: '#ffffff'
				});
			});
		const gameRoom = response.data.data;
		if (gameRoom['status'] === 'WAITING') {
			return false;
		} else {
			return true;
		}
	}

	const getPlayer = async (gameRoomId, playerId) => {
		await axios.get(`/api/game/getPlayer/${gameRoomId}/${playerId}`)
			.then((response) => {
				player.value = response.data.data;
				if (player.value) {
					bingoCards.value = JSON.parse(player.value.game_data);
					isReady.value = player.value.is_ready;
					generateNumbersPosition();
				}
			});
	}

	const getPlayersStatus = async (gameRoomId) => {
		const response = await axios.get(`/api/game/getPlayersStatus/${gameRoomId}`);
		return response.data;
	}

	const joinGame = async (gameRoomId, chipsBetted) => {
		axios.post('/api/game/joinGame/' + gameRoomId, {
			game_data: bingoCards.value,
			chips_betted: chipsBetted
		})
	};

	const updatePlayerGameData = async (gameRoomId, playerId, chipsBetted) => {
		axios.post(`/api/game/updatePlayerGameData/${gameRoomId}/${playerId}`, {
			game_data: bingoCards.value,
			chips_betted: chipsBetted
		});
	};

	const updatePlayerStatus = async (gameRoomId, playerId, isReady) => {
		axios.post(`/api/game/updatePlayerStatus/${gameRoomId}/${playerId}`, {
			is_ready: isReady
		});
	};

	const checkForLine = () => {
		for (let i = 0; i < cardsPositions.value.length; i++) {
			for (let j = 0; j < cardsPositions.value[i].length; j++) {
				let numCounter = 0;
				for (let k = 0; k < cardsPositions.value[i][j].length; k++) {
					if (cardsPositions.value[i][j][k] == true) {
						numCounter++;
					}
				}
				if (numCounter == 5) {
					return true;
				}
			}
		}
		return false;
	}

	const checkForBingo = () => {
		for (let i = 0; i < cardsPositions.value.length; i++) {
			let numCounter = 0;
			for (let j = 0; j < cardsPositions.value[i].length; j++) {
				for (let k = 0; k < cardsPositions.value[i][j].length; k++) {
					if (cardsPositions.value[i][j][k] == true) {
						numCounter++;
					}
				}
			}
			if (numCounter == 15) {
				return true;
			}
		}
		return false;
	}

	const callLine = async (gameRoomId, playerId) => {
		if (checkForLine()) {
			axios.post(`/api/game/callLine/${gameRoomId}/${playerId}`, {});
			swal.fire({
				icon: "success",
				title: "Success",
				text: `You have a line!`,
				background: '#2A2A2A',
				color: '#ffffff'
			});
		} else {
			wrongLineCalls.value++;
			const errorMessage = wrongLineCalls.value >= 3 ? 'You can\'t call line again.' : `You have ${3 - wrongLineCalls.value} ${3 - wrongLineCalls.value > 1 ? 'attempts' : 'attempt'} left.`;
			swal.fire({
				icon: "error",
				title: "You don't have a line!",
				text: errorMessage,
				background: '#2A2A2A',
				color: '#ffffff'
			});
		}
	};

	const callBingo = async (gameRoomId, playerId) => {
		if (checkForBingo()) {
			axios.post(`/api/game/callBingo/${gameRoomId}/${playerId}`, {});
			swal.fire({
				icon: "success",
				title: "Success",
				text: `You won the bingo!`,
				background: '#2A2A2A',
				color: '#ffffff'
			});
		} else {
			wrongBingoCalls.value++;
			const errorMessage = wrongBingoCalls.value >= 3 ? 'You can\'t call bingo again.' : `You have ${3 - wrongBingoCalls.value} ${3 - wrongLineCalls.value > 1 ? 'attempts' : 'attempt'} left.`;
			swal.fire({
				icon: "error",
				title: "You don't have bingo!",
				text: errorMessage,
				background: '#2A2A2A',
				color: '#ffffff'
			});
		}
	};

	const loadGameData = async (gameRoomId, playerId) => {
		await getPlayer(gameRoomId, playerId);
		if (await isGameOngoing(gameRoomId)) {
			const response = await axios.get(`/api/games/getGameData/${gameRoomId}`);
			if (response.data) {
				drawnBalls.value = response.data.numbers;
				numberHistory.value = response.data.numbers.slice(Math.max(response.data.numbers.length - 6, 0));
				if (player.value) {
					checkForNumbers();
					if (response.data.winners.line) {
						canCallLine.value = false;
						canCallBingo.value = true;
					} else {
						canCallLine.value = true;
					}
					if (response.data.winners.bingo) {
						canCallBingo.value = false;
					}
				}
			}
		} else {
			canBuyCards.value = true;
			if (player.value) {
				canChangeStatus.value = true;
			}
		}
	}

	return {
		player,
		canChangeStatus,
		bingoCards,
		canBuyCards,
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
		callBingo,
		canCallLine,
		canCallBingo
	};

};