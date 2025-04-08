import { ref, inject } from 'vue'

export default function useBingo() {

	const swal = inject("$swal");

	const player = ref(null);
	const bingoCards = ref([]);
	const isReady = ref(false);

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

	const isGameOngoing = async (gameRoomId) => {
		await axios.get('/api/game/getGameRoom/' + gameRoomId)
			.then((response) => {
				const gameRoom = response.data.data;
				if (gameRoom['status'] === 'WAITING') {
					return false;
				} else {
					swal.fire({
						icon: "error",
						title: "Error",
						text: `You cannot join the game because it has already started.`,
						background: '#2A2A2A',
						color: '#ffffff'
					});
					return true;
				}
			})
			.catch((error) => {
				swal.fire({
					icon: "error",
					title: "Error",
					text: `${error.response.data.message}`,
					background: '#2A2A2A',
					color: '#ffffff'
				});
			});
	}

	const getPlayer = async (gameRoomId, playerId) => {
		await axios.get(`/api/game/getPlayer/${gameRoomId}/${playerId}`)
			.then((response) => {
				player.value = response.data.data;
				bingoCards.value = JSON.parse(player.value.game_data);
				isReady.value = player.value.is_ready;
			});
	}

	const joinGame = async (gameRoomId) => {
		axios.post('/api/game/joinGame/' + gameRoomId, {
			game_data: bingoCards.value
		});
	};

	const updatePlayerGameData = async (gameRoomId, playerId) => {
		axios.post(`/api/game/updatePlayerGameData/${gameRoomId}/${playerId}`, {
			game_data: bingoCards.value
		});
	};

	const updatePlayerStatus = async (gameRoomId, playerId, isReady) => {
		axios.post(`/api/game/updatePlayerStatus/${gameRoomId}/${playerId}`, {
			is_ready: isReady
		});
	};

	const startCountdown = () => {
	};

	const stopCountdown = () => {
	};

	return {
		player,
		bingoCards,
		isReady,
		getPlayer,
		isGameOngoing,
		generateBingoCard,
		joinGame,
		updatePlayerGameData,
		updatePlayerStatus
	};

};