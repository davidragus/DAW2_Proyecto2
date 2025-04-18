import { ref, inject } from "vue";
import { useRouter } from "vue-router";

export default function useGameRooms() {
    const gameRooms = ref([]);
    const gameRoom = ref({});
    const router = useRouter();
    const validationErrors = ref({});
    const isLoading = ref(false);
    const swal = inject("$swal");

    const getAllGameRooms = async () => {
        try {
            const response = await axios.get("/api/game-rooms").then(response => {
                gameRooms.value = response.data.data;
            })
        } catch (error) {
            console.error("Error fetching game rooms:", error);
        }
    };

    const getGameRoom = async (id) => {
        try {
            const response = await axios.get(`/api/game-rooms/${id}`);
            gameRoom.value = response.data.data;
            console.log(gameRoom.value);
        } catch (error) {
            console.error("Error fetching game room:", error);
        }
    };

    const createGameRoom = async () => {
        try {
            isLoading.value = true;

            const formData = new FormData();
            formData.append("game_id", gameRoom.value.game_id);
            formData.append("name", gameRoom.value.name);
            formData.append("max_users", gameRoom.value.max_users);
            formData.append("status", gameRoom.value.status);
            console.log(gameRoom.value);
            await axios.post(
                `/api/game-rooms`,
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
                "Game room created successfully.",
                "success"
            );
            router.push({ name: "game-rooms" });
        } catch (error) {
            isLoading.value = false;
            if (error.response && error.response.data.errors) {
                validationErrors.value = error.response.data.errors;
            }
            throw error;
        }
    }

    const updateGameRoom = async (id) => {
        try {
            isLoading.value = true;

            await axios.put(`/api/game-rooms/${id}`, {
                game_id: gameRoom.value.game_id,
                name: gameRoom.value.name,
                max_users: gameRoom.value.max_players,
                status: gameRoom.value.status,
            }, {
                headers: {
                    "Content-Type": "application/json",
                    Accept: "application/json"
                },
            });
            isLoading.value = false;
            swal.fire(
                "Success!",
                "Game room updated successfully.",
                "success"
            );
            router.push({ name: "game-rooms" });
        } catch (error) {
            isLoading.value = false;
            if (error.response && error.response.data.errors) {
                validationErrors.value = error.response.data.errors;
            }
            throw error;
        }
    }

    const deleteGameRoom = async (id, index) => {
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
                await axios.delete(`/api/game-rooms/${id}`);
                console.log(gameRooms.value);
                gameRooms.value.splice(index, 1);
                swal.fire(
                    "Deleted!",
                    "The game room has been deleted.",
                    "success"
                );
            }
        } catch (error) {
            console.error("Error deleting game room:", error);
            swal.fire("Error!", "Failed to delete the game room.", "error");
        }
    };
    

    return {
        gameRooms,
        gameRoom,
        validationErrors,
        isLoading,
        getAllGameRooms,
        getGameRoom,
        createGameRoom,
        updateGameRoom,
        deleteGameRoom,
    };
}
