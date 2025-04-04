<template>
    <div class="bingo-container">
        <h1 class="text-center">Bingo</h1>
        <div class="bingo-board">
            <div
                v-for="(number, index) in board"
                :key="index"
                :class="['bingo-cell', { selected: selectedNumbers.includes(number) }]"
            >
                {{ number }}
            </div>
        </div>
        <div class="controls">
            <button class="btn btn-primary" @click="drawNumber" :disabled="selectedNumbers.length >= board.length">
                Draw Number
            </button>
            <p v-if="selectedNumbers.length >= board.length" class="text-center mt-3">All numbers have been drawn!</p>
        </div>
        <div class="selected-numbers mt-4">
            <h3>Selected Numbers:</h3>
            <div class="selected-list">
                <span v-for="(number, index) in selectedNumbers" :key="index" class="selected-number">
                    {{ number }}
                </span>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";

const numCartones = ref(1);
const bingoCards = ref([]);

const generateBingoCards = () => {
    bingoCards.value = Array.from(
        { length: numCartones.value },
        generateBingoCard
    );
};

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
</script>

<style scoped>
.bingo-container {
    max-width: 600px;
    margin: 0 auto;
    text-align: center;
    color: white;
    background-color: #2a2a2a;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
}

.bingo-board {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 10px;
    margin: 20px 0;
}

.bingo-cell {
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #313131;
    color: white;
    font-size: 1.2rem;
    font-weight: bold;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    transition: background-color 0.3s, transform 0.2s;
}

.bingo-cell.selected {
    background-color: #007bff;
    color: white;
    transform: scale(1.1);
}

.controls {
    margin-top: 20px;
}

.btn {
    padding: 10px 20px;
    font-size: 1rem;
    font-weight: bold;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.btn-primary {
    background-color: #007bff;
    color: white;
}

.btn-primary:disabled {
    background-color: #6c757d;
    cursor: not-allowed;
}

.selected-numbers {
    margin-top: 20px;
    text-align: left;
}

.selected-list {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 10px;
}

.selected-number {
    background-color: #007bff;
    color: white;
    padding: 5px 10px;
    border-radius: 5px;
    font-size: 1rem;
}
</style>