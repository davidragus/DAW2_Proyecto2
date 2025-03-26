<template>
    <div class="container mt-5">
        <h2 class="text-white text-center mb-4">Create Achievement</h2>
        <form @submit.prevent="submitForm">
            <div class="row">
                <!-- Left Column -->
                <div class="col-md-8">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body">
                            <!-- Name -->
                            <div class="mb-3">
                                <label for="achievement-name" class="form-label">Name</label>
                                <input
                                    v-model="achievement.name"
                                    id="achievement-name"
                                    type="text"
                                    class="form-control"
                                    placeholder="Enter achievement name"
                                />
                                <div class="text-danger mt-1">{{ errors.name }}</div>
                            </div>

                            <!-- Description -->
                            <div class="mb-3">
                                <label for="achievement-description" class="form-label">Description</label>
                                <textarea
                                    v-model="achievement.description"
                                    id="achievement-description"
                                    class="form-control"
                                    rows="3"
                                    placeholder="Enter achievement description"
                                ></textarea>
                                <div class="text-danger mt-1">{{ errors.description }}</div>
                            </div>

                            <!-- Achievement Type -->
                            <div class="mb-3">
                                <label for="achievement-type" class="form-label">Achievement Type</label>
                                <select
                                    v-model="achievement.achievement_type"
                                    id="achievement-type"
                                    class="form-select"
                                >
                                    <option value="games">Games</option>
                                    <option value="chips">Chips</option>
                                    <option value="wins">Wins</option>
                                </select>
                                <div class="text-danger mt-1">{{ errors.achievement_type }}</div>
                            </div>

                            <!-- Amount -->
                            <div class="mb-3">
                                <label for="achievement-amount" class="form-label">Amount</label>
                                <input
                                    v-model="achievement.amount"
                                    id="achievement-amount"
                                    type="number"
                                    class="form-control"
                                    placeholder="Enter amount"
                                />
                                <div class="text-danger mt-1">{{ errors.amount }}</div>
                            </div>

                            <!-- Reward -->
                            <div class="mb-3">
                                <label for="achievement-reward" class="form-label">Reward (Chips)</label>
                                <input
                                    v-model="achievement.reward_amount"
                                    id="achievement-reward"
                                    type="number"
                                    class="form-control"
                                    placeholder="Enter reward amount"
                                />
                                <div class="text-danger mt-1">{{ errors.reward_amount }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <h6 class="mb-3">Achievement Image</h6>
                            <!-- Componente DropZone para cargar una nueva imagen -->
                            <DropZone v-model="achievement.image" :style="{backgroundColor: '#313131', color: 'white' , borderColor: '#414141'}" @onDrop="onDrop"/>
                            <div class="text-danger mt-1">{{ errors.image }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Save Button -->
            <div class="text-center mt-4">
                <button :disabled="isLoading" class="btn btn-primary px-4">
                    <span v-if="isLoading">Processing...</span>
                    <span v-else>Create Achievement</span>
                </button>
            </div>
        </form>
    </div>
</template>

<script setup>
import { ref, reactive } from "vue";
import { useRouter } from "vue-router";
import useAchievements from "../../../composables/achievements";
import DropZone from "@/components/DropZone.vue";

const router = useRouter();
const { createAchievement, validationErrors, isLoading } = useAchievements();

const errors = ref({});
const achievement = reactive({
    name: "",
    description: "",
    achievement_type: "games",
    amount: 0,
    reward_amount: 0,
    image: null,
});

const submitForm = async () => {
    try {
        await createAchievement(achievement);
        router.push({ name: "achievements.index" });
    } catch (error) {
        errors.value = validationErrors.value;
    }
};

// Cuando se arrastra un archivo a la DropZone
const onDrop = (file) => {
    achievement.image = file;
};

</script>

<style scoped>
/* General Styles */
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
input::placeholder, textarea::placeholder {
    color: white;
}
</style>