<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router } from "@inertiajs/vue3";
import { useUsersStore } from "@/Stores/usersStore";
import { ref, watch, reactive, computed } from "vue";
export default {
    components: {
        AuthenticatedLayout,
        Head,
    },
    setup() {
        const message = ref("");
        const showConfirmDiv = reactive({});
        const searchEmail = ref("");
        const isDeleteBtnActive = reactive({});
        const users = ref([]);
        const userStore = useUsersStore();
        const currentPage = ref(1);
        const itemsPerPage = 5;
        const form = reactive({
            name: "",
            email: "",
            password: "",
            phone: "",
            zip: "",
            city: "",
            location: "",
        });
        const activateConfirmDiv = (id) => {
            showConfirmDiv[id] = true;
            isDeleteBtnActive[id] = true;
        };
        const deactivateConfirmDiv = (id) => {
            showConfirmDiv[id] = false;
            isDeleteBtnActive[id] = false;
        };
        const paginatedUsers = computed(() => {
            const filteredUsers = users.value.filter((item) =>
                item.email
                    .toLowerCase()
                    .includes(searchEmail.value.toLowerCase())
            );
            const startIndex = (currentPage.value - 1) * itemsPerPage;
            const endIndex = startIndex + itemsPerPage;
            return filteredUsers.slice(startIndex, endIndex);
        });
        const totalPages = computed(() =>
            Math.ceil(
                users.value.filter((item) =>
                    item.email
                        .toLowerCase()
                        .includes(searchEmail.value.toLowerCase())
                ).length / itemsPerPage
            )
        );
        const setCurrentPage = (page) => {
            currentPage.value = page;
        };
        const fetchUsers = () => {
            userStore.fetchUsers().then((response) => {
                users.value = userStore.users.users.sort((a, b) => {
                    const dateA = new Date(a.createdAt);
                    const dateB = new Date(b.createdAt);
                    return dateB - dateA;
                });
                message.value = userStore.users.message;
            });
        };
        fetchUsers();
        watch(
            () => userStore.users,
            (value) => {
                users.value = value;
            }
        );
        const createUser = () => {
            userStore.createUsers(form).then(() => fetchUsers());
        };
        const deleteUser = (id) => {
            userStore.deleteUsers(id).then(() => fetchUsers());
        };
        const editUser = (id) => {
            router.visit(`/users/${id}`);
        };
        return {
            message,
            showConfirmDiv,
            searchEmail,
            isDeleteBtnActive,
            users,
            currentPage,
            itemsPerPage,
            form,
            activateConfirmDiv,
            deactivateConfirmDiv,
            paginatedUsers,
            totalPages,
            setCurrentPage,
            createUser,
            deleteUser,
            editUser,
        };
    },
    methods: {},
};
</script>

<template>
    <Head title="Users" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight"
            >
                Users
            </h2>
        </template>

        <div class="py-8">
            <div class="max-w-4xl flex flex-col gap-20 mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg"
                >
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h2 class="text-2xl text-center">Create User</h2>
                        <form @submit.prevent="createUser" class="space-y-4">
                            <div>
                                <label for="name" class="block text-gray-300"
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
                                <label for="email" class="block text-gray-300"
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
                                    for="password"
                                    class="block text-gray-300"
                                    >Password</label
                                >
                                <input
                                    id="password"
                                    v-model="form.password"
                                    type="password"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-400"
                                />
                            </div>

                            <div>
                                <label for="phone" class="block text-gray-300"
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
                                <label for="city" class="block text-gray-300"
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
                                Create
                            </button>
                        </form>
                    </div>
                </div>

                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg"
                >
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h2 class="text-2xl text-center">User list</h2>
                        <div class="mt-4 mb-4">
                            <label for="search" class="block text-gray-300"
                                >Search by Email</label
                            >
                            <input
                                id="search"
                                v-model="searchEmail"
                                type="text"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-400"
                            />
                        </div>
                        <div v-if="users.length > 0" class="mt-8">
                            <div
                                v-for="user in paginatedUsers"
                                :key="user.id"
                                class="mb-4"
                            >
                                <h3
                                    class="flex justify-between text-xl font-semibold"
                                >
                                    <span>
                                        {{ user.name }}
                                    </span>
                                    <div>
                                        {{ user.email }}
                                    </div>
                                </h3>

                                <div class="flex justify-between">
                                    <div class="flex mt-2">
                                        <button
                                            @click="editUser(user.id)"
                                            class="text-[#7458ea] hover:text-black mr-4"
                                        >
                                            Edit
                                        </button>
                                        <button
                                            v-if="!isDeleteBtnActive[user.id]"
                                            @click="activateConfirmDiv(user.id)"
                                            class="text-red-500 hover:text-red-700"
                                        >
                                            Delete
                                        </button>
                                        <div
                                            class="gap-2 flex"
                                            v-if="showConfirmDiv[user.id]"
                                        >
                                            <button
                                                @click="deleteUser(user.id)"
                                                class="text-red-500 hover:text-red-700"
                                            >
                                                Yes
                                            </button>
                                            <button
                                                @click="
                                                    deactivateConfirmDiv(
                                                        user.id
                                                    )
                                                "
                                            >
                                                No
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="pagination">
                                <button
                                    :class="{
                                        'page-btn': true,
                                        active: currentPage === pageNumber,
                                    }"
                                    v-for="pageNumber in totalPages"
                                    :key="pageNumber"
                                    @click="setCurrentPage(pageNumber)"
                                >
                                    {{ pageNumber }}
                                </button>
                            </div>
                        </div>

                        <div v-else>
                            <p class="text-gray-600">No users available.</p>
                        </div>
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
