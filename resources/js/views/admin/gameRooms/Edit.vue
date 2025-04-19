<template>
    <div class="container mt-5">
        <h2 class="text-white text-center mb-4">Edit Game Room</h2>
        <form @submit.prevent="submitForm">
            <div class="row">
                <!-- Left Column -->
                <div class="col-md-8">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body">
                            <!-- Room Name -->
                            <div class="mb-3">
                                <label for="room-name" class="form-label">Room Name</label>
                                <input
                                    v-model="gameRoom.name"
                                    id="room-name"
                                    type="text"
                                    class="form-control"
                                    placeholder="Enter room name"
                                />
                                <div class="text-danger mt-1">{{ errors.name }}</div>
                            </div>

                            <!-- Game Selector -->
                            <div class="mb-3">
                                <label for="game-id" class="form-label">Game</label>
                                <select
                                    v-model="gameRoom.game_id"
                                    id="game-id"
                                    class="form-select"
                                >
                                    <option value="">Select a game</option>
                                    <option v-for="game in games.data" :key="game.id" :value="game.id">
                                        {{ game.name }}
                                    </option>
                                </select>
                                <div class="text-danger mt-1">{{ errors.game_id }}</div>
                            </div>

                            <!-- Max Players -->
                            <div class="mb-3">
                                <label for="max-players" class="form-label">Max Players</label>
                                <input
                                    v-model="gameRoom.max_players"
                                    id="max-players"
                                    type="number"
                                    class="form-control"
                                    placeholder="Enter maximum players"
                                />
                                <div class="text-danger mt-1">{{ errors.max_players }}</div>
                            </div>

                            <!-- Status fixed -->
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select v-model="gameRoom.status" id="status" class="form-select" disabled>
                                    <option value="WAITING">WAITING</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <h6 class="mb-3">Room Image</h6>
                            <p>No image required for game rooms.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="text-center mt-4">
                <button :disabled="isLoading" class="btn btn-primary px-4">
                    <span v-if="isLoading">Processing...</span>
                    <span v-else>Edit Game Room</span>
                </button>
            </div>
        </form>
    </div>
</template>

<script setup>
import { ref, reactive, onMounted } from "vue";
import { useRouter, useRoute } from "vue-router";
import * as yup from "yup";
import useGameRooms from "@/composables/gameRooms";
import useGames from "@/composables/games";

const router = useRouter();
const route = useRoute();
const { gameRoom, updateGameRoom, validationErrors, isLoading, getGameRoom } = useGameRooms();
const { games, getAllGames } = useGames();

const errors = ref({});

// Yup schema: solo valida que los campos no estén vacíos
const schema = yup.object({
    name: yup.string().required("Room name is required"),
    game_id: yup.string().required("Game is required"),
    max_players: yup.number().required("Max players is required").min(0, "Max players must be at least 0"),
    status: yup.string().required("Status is required"),
});

onMounted(() => {
    getAllGames();
    getGameRoom(route.params.id); 
});

const submitForm = async () => {
    try {
        await schema.validate(gameRoom.value, { abortEarly: false });
        errors.value = {};

        await updateGameRoom(gameRoom.value.id);
        router.push({ name: "game-rooms.index" });
    } catch (error) {
        if (error.inner) {
            const formattedErrors = {};
            error.inner.forEach(err => {
                formattedErrors[err.path] = err.message;
            });
            errors.value = formattedErrors;
        }
    }
};
</script>

<style scoped>
.text-danger {
    color: red;
    font-size: 0.875rem;
}

.card {
    background-color: #2a2a2a;
    color: white;
    border-radius: 8px;
}

.form-control,
.form-select {
    background-color: #313131;
    color: white;
    border: 1px solid #414141;
    border-radius: 6px;
    padding: 8px 12px;
}

.form-control::placeholder,
textarea::placeholder {
    color: white;
}

.form-control:focus,
.form-select:focus {
    border-color: #007bff;
    box-shadow: none;
}

.btn-primary {
    background-color: #007bff;
    border-color: #007bff;
    font-size: 1rem;
    font-weight: bold;
    border-radius: 6px;
}

.btn-primary:disabled {
    background-color: #6c757d;
    border-color: #6c757d;
}

h2 {
    font-size: 1.8rem;
    font-weight: bold;
}

h6 {
    font-size: 1rem;
    font-weight: bold;
    color: #b0b0b0;
}
</style>
