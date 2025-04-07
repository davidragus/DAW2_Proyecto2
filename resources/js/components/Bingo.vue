<template>
    <div id="mainContent">
        <div class="mt-3">
    <h5>Números salidos:</h5>
    <div class="d-flex flex-wrap">
        <div
            v-for="ball in drawnBalls"
            :key="ball"
            class="bingo-cell"
            style="width: 30px; height: 30px"
        >
            {{ ball }}
        </div>
    </div>
</div>
<div class="bola-actual" v-if="currentBall !== null" :class="{ 'fade-out': isFading }">
    {{ currentBall }}
</div>


<button @click="drawBall" class="btn btn-success">
    Sacar bola
</button>
        <div class="container mt-3">
            <div class="row justify-content-center mb-3">
                <div class="col-auto">
                    <input
                        type="number"
                        v-model.number="numCartones"
                        min="1"
                        max="10"
                        class="form-control"
                        placeholder="Número de cartones"
                    />
                </div>
                <div class="col-auto">
                    <button @click="generateBingoCards" class="btn btn-primary">
                        Generar Cartones
                    </button>
                </div>
            </div>
            <div class="row">
                <div
                    v-for="(card, cardIndex) in bingoCards"
                    :key="cardIndex"
                    class="col-md-4 col-lg-3 mb-3"
                >
                    <div class="card p-2">
                        <div
                            v-for="(row, rowIndex) in card"
                            :key="rowIndex"
                            class="d-flex justify-content-center"
                        >
                            <div
                                v-for="(cell, colIndex) in row"
                                :key="colIndex"
                                class="bingo-cell"
                            >
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
import { ref, computed } from "vue";
import { useSpeechSynthesis } from '@vueuse/core'
import { langStore } from "@/store/lang";

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

    // Función auxiliar para obtener 'count' números aleatorios únicos en el rango, ordenados ascendentemente.
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

    // Generar un patrón válido (matriz de booleans de 3x9)
    // Cada fila tendrá 5 "true" (rellenos) y se verificará que cada columna tenga 1 o 2 "true".
    let pattern;
    while (true) {
        pattern = Array.from({ length: 3 }, () => Array(9).fill(false));

        // Para cada fila, elegimos 5 columnas al azar
        for (let r = 0; r < 3; r++) {
            const availableCols = [...Array(9).keys()];
            for (let i = 0; i < 5; i++) {
                const idx = Math.floor(Math.random() * availableCols.length);
                pattern[r][availableCols[idx]] = true;
                availableCols.splice(idx, 1);
            }
        }

        // Validar que cada columna tenga 1 o 2 celdas "true" (nunca 0 ni 3)
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

    // Creamos la matriz del cartón (3x9) inicializada en ''.
    const card = Array.from({ length: 3 }, () => Array(9).fill(""));

    // Para cada columna, asignamos los números correspondientes a las celdas marcadas.
    for (let c = 0; c < 9; c++) {
        // Obtenemos las filas en las que se deben colocar números en esta columna.
        const rowsWithNumber = [];
        for (let r = 0; r < 3; r++) {
            if (pattern[r][c]) rowsWithNumber.push(r);
        }
        // Se obtienen 'count' números aleatorios dentro del rango de la columna, ordenados.
        const nums = getRandomNumbers(
            columnRanges[c][0],
            columnRanges[c][1],
            rowsWithNumber.length
        );
        // Para respetar la regla de orden ascendente en la columna, asignamos los números a las filas ordenadas.
        rowsWithNumber.sort((a, b) => a - b);
        rowsWithNumber.forEach((r, i) => {
            card[r][c] = nums[i];
        });
    }

    return card;
};

const allBalls = ref(Array.from({ length: 90 }, (_, i) => i + 1));
const drawnBalls = ref([]);
const currentBall = ref(null);
const isFading = ref(false);

const drawBall = () => {
    if (allBalls.value.length === 0) return;

    const randomNumber = Math.floor(Math.random() * allBalls.value.length);
    const ball = allBalls.value.splice(randomNumber, 1)[0];
    currentBall.value = ball;
    drawnBalls.value.push(ball);
    isFading.value = false;

    speakBall(ball);

    setTimeout(() => {
        isFading.value = true;
        setTimeout(() => {
            currentBall.value = null;
        }, 500);
    }, 2000);
};

const textToSpeak = ref("");
const locale = ref(langStore().locale);
const language = computed(() => `${locale.value.toLowerCase()}-${locale.value.toUpperCase()}`);
const { speak, isSupported } = useSpeechSynthesis(textToSpeak, { lang: language.value, rate: 0.7 });

const speakBall = (number) => {
    console.log(language.value);
    if (!isSupported.value) {
        console.warn("Speech synthesis not supported");
        return;
    }

    textToSpeak.value = `${number}`;
    speak();
};

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
    0% { transform: scale(0.5); }
    50% { transform: scale(1.2); }
    100% { transform: scale(1); }
}

.fade-out {
    animation: shrink-out 0.5s ease forwards;
}

@keyframes shrink-out {
    0% { transform: scale(1); }
    50% { transform: scale(1.2); }
    100% { transform: scale(0); }
}
</style>
