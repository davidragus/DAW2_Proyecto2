import { ref, inject } from "vue";
import { useRouter } from "vue-router";

export default function useAchievements() {
    const achievements = ref([]);
    const achievement = ref({});
    const router = useRouter();
    const validationErrors = ref({});
    const isLoading = ref(false);
    const swal = inject("$swal");

    const getAllAchievements = async () => {
        try {
            const response = await axios.get("/api/achievements");
            achievements.value = response.data.data;
            console.log(achievements.value);
        } catch (error) {
            console.error("Error fetching achievements:", error);
        }
    };

    const getAchievement = async (id) => {
        try {
            const response = await axios.get(`/api/achievements/${id}`);
            achievement.value = response.data.data;
            console.log(achievement.value);
        } catch (error) {
            console.error("Error fetching achievement:", error);
        }
    };

    const updateAchievement = async (achievement) => {
        try {
            isLoading.value = true;

            const formData = new FormData();
            formData.append("name", achievement.name);
            formData.append("description", achievement.description);
            formData.append("achievement_type", achievement.achievement_type);
            formData.append("amount", achievement.amount);
            formData.append("reward_amount", achievement.reward_amount);

            if (achievement.image instanceof File) {
                formData.append("image", achievement.image); // Agregar la imagen si es un archivo
            }

            await axios.post(
                `/api/achievements/${achievement.id}`,
                formData,
                {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                }
            );

            isLoading.value = false;
        } catch (error) {
            isLoading.value = false;
            if (error.response && error.response.data.errors) {
                validationErrors.value = error.response.data.errors;
            }
            throw error;
        }
    };

    const deleteAchievement = async (id, index) => {
        try {
            const confirmed = await swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!",
            });

            if (confirmed.isConfirmed) {
                await axios.delete(`/api/achievements/${id}`);
                achievements.value.splice(index, 1);
                swal.fire(
                    "Deleted!",
                    "The achievement has been deleted.",
                    "success"
                );
            }
        } catch (error) {
            console.error("Error deleting achievement:", error);
            swal.fire("Error!", "Failed to delete the achievement.", "error");
        }
    };
    const createAchievement = async (achievement) => {
        try {
            isLoading.value = true;

            const formData = new FormData();
            formData.append("name", achievement.name);
            formData.append("description", achievement.description);
            formData.append("achievement_type", achievement.achievement_type);
            formData.append("amount", achievement.amount);
            formData.append("reward_amount", achievement.reward_amount);
            
            if (achievement.image instanceof File) {
                formData.append("image", achievement.image); // Agregar la imagen si es un archivo
            }

            console.log(formData.get("image"));
            await axios.post(
                `/api/achievements`,
                formData,
                {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                }
            );

            isLoading.value = false;
        } catch (error) {
            isLoading.value = false;
            if (error.response && error.response.data.errors) {
                validationErrors.value = error.response.data.errors;
            }
            throw error;
        }
    };

    return {
        achievements,
        achievement,
        getAllAchievements,
        deleteAchievement,
        getAchievement,
        updateAchievement,
        validationErrors,
        createAchievement,
    };
}
