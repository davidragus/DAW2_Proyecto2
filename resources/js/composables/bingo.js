import { ref } from 'vue'

export default function useBingo() {

	const bingoCards = ref([]);

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

	const startCountdown = () => {
	};

	const stopCountdown = () => {
	};

	return {
		bingoCards,
		generateBingoCard
	};

};