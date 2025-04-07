<template>
    <div id="mainContent">
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

// Ejemplo de prueba: generar 100 cartones y verificar su validez.
const testCards = [];
for (let i = 0; i < 100; i++) {
    const card = generateBingoCard();
    // Verificación de filas: cada fila debe tener 5 números.
    const rowsValid = card.every(
        (row) => row.filter((cell) => cell !== "").length === 5
    );
    // Verificación de columnas: cada columna debe tener 1 o 2 números.
    let colsValid = true;
    for (let c = 0; c < 9; c++) {
        const colCount = card.reduce(
            (sum, row) => sum + (row[c] !== "" ? 1 : 0),
            0
        );
        if (colCount < 1 || colCount > 2) {
            colsValid = false;
            break;
        }
    }
    if (rowsValid && colsValid) {
        testCards.push(card);
    } else {
        console.error("Cartón inválido encontrado:", card);
    }
}
console.log(
    "Se generaron",
    testCards.length,
    "cartones válidos de 100 pruebas."
);
console.log(generateBingoCard());
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
}
.bingo-cell .complete{
    background-color: #5a5a5a;
}
</style>
