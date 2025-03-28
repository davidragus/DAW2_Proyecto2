<template>
    <div class="container mt-5">
        <h2 class="color-white">Edit Achievement</h2>
        <form @submit.prevent="submitForm">
            <div class="row my-5">
                <div class="col-md-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <!-- Name -->
                            <div class="mb-3">
                                <label for="achievement-name" class="form-label">Name</label>
                                <input v-model="achievement.name" id="achievement-name" type="text"
                                    class="form-control" />
                                <div class="text-danger mt-1">
                                    {{ errors.name }}
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="mb-3">
                                <label for="achievement-description" class="form-label">Description</label>
                                <textarea v-model="achievement.description" id="achievement-description"
                                    class="form-control" rows="4"></textarea>
                                <div class="text-danger mt-1">
                                    {{ errors.description }}
                                </div>
                            </div>

                            <!-- Achievement Type -->
                            <div class="mb-3">
                                <label for="achievement-type" class="form-label">Achievement Type</label>
                                <select v-model="achievement.achievement_type" id="achievement-type"
                                    class="form-select">
                                    <option value="games">Games</option>
                                    <option value="chips">Chips</option>
                                    <option value="wins">Wins</option>
                                </select>
                                <div class="text-danger mt-1">
                                    {{ errors.achievement_type }}
                                </div>
                            </div>

                            <!-- Amount -->
                            <div class="mb-3">
                                <label for="achievement-amount" class="form-label">Amount</label>
                                <input v-model="achievement.amount" id="achievement-amount" type="number"
                                    class="form-control" />
                                <div class="text-danger mt-1">
                                    {{ errors.amount }}
                                </div>
                            </div>

                            <!-- Reward -->
                            <div class="mb-3">
                                <label for="achievement-reward" class="form-label">Reward (Chips)</label>
                                <input v-model="achievement.reward_amount" id="achievement-reward" type="number"
                                    class="form-control" />
                                <div class="text-danger mt-1">
                                    {{ errors.reward_amount }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h6>Image</h6>
                            <!-- Mostrar la imagen actual -->
                            <div class="mb-3 text-center">
                                <img v-if="achievement.image && !(achievement.image)"
                                    :src="achievement.image" alt="Achievement Image" class="img-thumbnail"
                                    style="max-width: 200px; max-height: 200px;" />
                            </div>

                            <!-- Componente DropZone para cargar una nueva imagen -->
                            <DropZone v-model="achievement.image" :style="{backgroundColor: '#313131', color: 'white' , borderColor: '#414141'}" />
                            <div class="text-danger mt-1">
                                {{ errors.image }}
                            </div>

                            <div class="mt-3 text-center">
                                <button :disabled="isLoading" class="btn btn-primary">
                                    <span v-if="isLoading">Processing...</span>
                                    <span v-else>Save Achievement</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</template>

<script setup>
import { ref, reactive, onMounted, watchEffect } from "vue";
import { useRoute, useRouter } from "vue-router";
import useAchievements from "../../../composables/achievements";
import DropZone from "@/components/DropZone.vue";

const route = useRoute();
const router = useRouter();
const { getAchievement, updateAchievement, validationErrors, isLoading, achievement } = useAchievements();

const errors = ref({});

const submitForm = async () => {
    try {
        await updateAchievement(achievement.value);
        router.push({ name: "achievements.index" });
    } catch (error) {
        errors.value = validationErrors.value;
    }
};

onMounted(async () => {
    getAchievement(route.params.id);
});
</script>

<style scoped>
.text-danger {
    color: red;
    font-size: 0.875rem;
}

.card {
    background-color: #2a2a2a;
    color: white;
}

.form-control,
.form-select {
    background-color: #313131;
    color: white;
    border: 1px solid #414141;
}

.form-control:focus,
.form-select:focus {
    border-color: #007bff;
    box-shadow: none;
}

.btn-primary {
    background-color: #007bff;
    border-color: #007bff;
}

.btn-primary:disabled {
    background-color: #6c757d;
    border-color: #6c757d;
}
</style>