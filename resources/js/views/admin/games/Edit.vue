<template>
    <div class="container mt-5">
        <h2 class="text-white text-center mb-4">Edit Game</h2>
        <form @submit.prevent="submitForm">
            <div class="row">
                <!-- Left Column -->
                <div class="col-md-8">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body">
                            <!-- Game Name -->
                            <div class="mb-3">
                                <label for="game-name" class="form-label">Name</label>
                                <input
                                    v-model="game.name"
                                    id="game-name"
                                    type="text"
                                    class="form-control"
                                    placeholder="Enter game name"
                                />
                                <div class="text-danger mt-1">{{ errors.name }}</div>
                            </div>

                            <!-- Route Path -->
                            <div class="mb-3">
                                <label for="game-route" class="form-label">Route Path</label>
                                <input
                                    v-model="game.route_path"
                                    id="game-route"
                                    type="text"
                                    class="form-control"
                                    placeholder="Enter route path"
                                />
                                <div class="text-danger mt-1">{{ errors.route_path }}</div>
                            </div>

                            <!-- Game Active -->
                            <div class="mb-3 d-flex align-items-start flex-column">
                                <label for="game-active" class="form-label">Game Active</label>
                                <ToggleSwitch v-model="game.active" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <h6 class="mb-3">Game Image</h6>
                            <DropZone
                                v-model="game.image"
                                :style="{ backgroundColor: '#313131', color: 'white', borderColor: '#414141' }"
                                @onDrop="onDrop"
                            />
                            <div class="text-danger mt-1">{{ errors.image }}</div>
                        </div>
                    </div>
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <h6 class="mb-3">Game Icon</h6>
                            <DropZone
                                v-model="game.icon"
                                :style="{ backgroundColor: '#313131', color: 'white', borderColor: '#414141' }"
                                @onDrop="onDropIcon"
                            />
                            <div class="text-danger mt-1">{{ errors.icon }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="text-center mt-4">
                <button :disabled="isLoading" class="btn btn-primary px-4" :style="{ backgroundColor: 'red', color: 'white', borderColor: 'red' }">
                    <span v-if="isLoading">Processing...</span>
                    <span v-else>Edit Game</span>
                </button>
            </div>
        </form>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRoute, useRouter } from "vue-router";
import * as yup from "yup";
import useGames from "@/composables/games";
import DropZone from "@/components/DropZone.vue";

const route = useRoute();
const router = useRouter();
const { game, updateGame, getGame, isLoading } = useGames();

const errors = ref({});

// Yup validation schema
const schema = yup.object({
    name: yup.string().required("Name is required"),
    route_path: yup.string().required("Route path is required"),
    image: yup
        .mixed()
        .required("Image is required")
});

// Carga inicial
onMounted(() => {
    getGame(route.params.id);
});

const submitForm = async () => {
    try {
        await schema.validate(game.value, { abortEarly: false });
        errors.value = {};
        await updateGame(game.value.id);
        router.push({ name: "games.index" });
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

// Imagen con DropZone
const onDrop = (file) => {
    game.value.image = file;
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

input::placeholder {
    color: white;
}
</style>
