import { ref, inject } from "vue";
import { useRouter } from "vue-router";

export default function useGames() {
	const games = ref([]);
	const game = ref({});
	const router = useRouter();
	const validationErrors = ref({});
	const isLoading = ref(false);
	const swal = inject("$swal");

    const getAllGames = async () => {
        try {
            const response = await axios.get("/api/games").then(response => {
                games.value = response.data.data;
            })
        } catch (error) {
            console.error("Error fetching games:", error);
        }
    };

    const getGame = async (id) => {
        try {
            const response = await axios.get(`/api/games/${id}`);
            game.value = response.data.data;
            console.log(game.value);
        } catch (error) {
            console.error("Error fetching game:", error);
        }
    };

    const createGame = async () => {
        try {
            isLoading.value = true;

            const formData = new FormData();
            formData.append("name", game.value.name);
            formData.append("route_path", game.value.route_path);
            formData.append("active", game.value.active ? 1 : 0);
            formData.append("image", game.value.image);
            formData.append("icon", game.value.icon);
            await axios.post(
                `/api/games`,
                formData,
                {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                }
            );
            isLoading.value = false;
            swal.fire(
                "Success!",
                "Game created successfully.",
                "success"
            );
            router.push({ name: "games" });
        } catch (error) {
            isLoading.value = false;
            if (error.response && error.response.data.errors) {
                validationErrors.value = error.response.data.errors;
            }
            throw error;
        }
    }

    const updateGame = async (id) => {
        try {
            isLoading.value = true;

            const formData = new FormData();
            formData.append("name", game.value.name);
            formData.append("route_path", game.value.route_path);
            formData.append("active", game.value.active ? 1 : 0);
            formData.append("image", game.value.image);
            formData.append("icon", game.value.icon);

            await axios.post(
                `/api/games/${id}`,formData
                ,{
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                }
            );
            isLoading.value = false;
            swal.fire(
                "Success!",
                "Game updated successfully.",
                "success"
            );
            router.push({ name: "games" });
        } catch (error) {
            isLoading.value = false;
            if (error.response && error.response.data.errors) {
                validationErrors.value = error.response.data.errors;
            }
            throw error;
        }
    }

    const deleteGame = async (id, index) => {
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
                await axios.delete(`/api/games/${id}`);
                games.value.splice(index, 1);
                swal.fire(
                    "Deleted!",
                    "The game has been deleted.",
                    "success"
                );
            }
        } catch (error) {
            console.error("Error deleting game:", error);
            swal.fire("Error!", "Failed to delete the game.", "error");
        }
    };

    return {
        game,
        games,
        getAllGames,
        getGame,
        createGame,
        updateGame,
        deleteGame,
        validationErrors,
        isLoading

    };
}
