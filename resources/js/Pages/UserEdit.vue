<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
// import { Head, useRoute } from "@inertiajs/vue3";
import { Head, usePage } from "@inertiajs/vue3";
import { ref, onMounted } from "vue";
import { useUsersStore } from "@/Stores/usersStore";

export default {
    components: {
        AuthenticatedLayout,
        Head,
    },
    setup() {
        // const route = useRoute();
        const { props } = usePage();
        const message = ref("");

        const userStore = useUsersStore();
        const user = ref(null);
        const form = ref({
            name: "",
            email: "",
            phone: "",
            zip: "",
            city: "",
            location: "",
        });

        onMounted(async () => {
            const response = await userStore.getUsers(props.id);
            user.value = userStore.user;
            form.value = user.value; // Assign the fetched user data to the form
        });

        const editUser = async () => {
            const response = await userStore.editUsers(props.id, form.value);
            message.value = userStore.message;

            setTimeout(() => {
                message.value = "";
            }, 3000);
        };

        return {
            user,
            form,
            editUser,
            message,
        };
    },
};
</script>

<template>
    <Head title="User Edit" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                User Edit
            </h2>
        </template>

        <div class="py-8">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg"
                >
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h2 class="text-2xl text-center">Edit User</h2>
                        <form @submit.prevent="editUser" class="space-y-4">
                            <div v-if="user">
                                <div>
                                    <label
                                        for="name"
                                        class="block text-gray-300"
                                        >Name</label
                                    >
                                    <input
                                        id="name"
                                        v-model="form.name"
                                        type="text"
                                        required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-400"
                                    />
                                </div>

                                <div>
                                    <label
                                        for="email"
                                        class="block text-gray-300"
                                        >Email</label
                                    >
                                    <input
                                        id="email"
                                        v-model="form.email"
                                        type="email"
                                        required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-400"
                                    />
                                </div>

                                <div>
                                    <label
                                        for="phone"
                                        class="block text-gray-300"
                                        >Phone</label
                                    >
                                    <input
                                        id="phone"
                                        v-model="form.phone"
                                        type="text"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-400"
                                    />
                                </div>

                                <div>
                                    <label for="zip" class="block text-gray-300"
                                        >ZIP Code</label
                                    >
                                    <input
                                        id="zip"
                                        v-model="form.zip"
                                        type="text"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-400"
                                    />
                                </div>

                                <div>
                                    <label
                                        for="city"
                                        class="block text-gray-300"
                                        >City</label
                                    >
                                    <input
                                        id="city"
                                        v-model="form.city"
                                        type="text"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-400"
                                    />
                                </div>

                                <div>
                                    <label
                                        for="location"
                                        class="block text-gray-300"
                                        >Location</label
                                    >
                                    <input
                                        id="location"
                                        v-model="form.location"
                                        type="text"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-400"
                                    />
                                </div>

                                <button
                                    type="submit"
                                    class="py-2 px-4 bg-[#7458ea] text-white rounded-md hover:bg-black"
                                >
                                    Update
                                </button>
                                <div>{{ message }}</div>
                            </div>
                        </form>
                        <h2 class="text-2xl text-center mt-8">
                            List Purchases:
                        </h2>
                        <!-- Empty purchases list for now -->
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
<style scoped>
input:focus,
textarea:focus {
    outline: none;
    border-color: #93c5fd;
    box-shadow: 0 0 0 2px rgba(66, 153, 225, 0.5);
}
input,
textarea {
    color: black;
}

input[type="checkbox"] {
    /* Set custom size and style */
    width: 20px;
    height: 20px;
    border: 2px solid #7458ea;
    border-radius: 4px;
    /* Add custom checkmark */
    position: relative;
    background-color: #fff;
}

/* Style the checked state */
input[type="checkbox"]:checked {
    background-color: #7458ea;
}
</style>
