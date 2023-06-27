<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router } from "@inertiajs/vue3";
import { useNewsStore } from "@/Stores/newsStore";
import { ref, watch, reactive, computed } from "vue";
// import { Inertia } from "@inertiajs/inertia";
import toast from "@/Stores/toast";
// import { getEvent, createEvent } from "@/Services/api.js";

export default {
    components: {
        AuthenticatedLayout,
        Head,
    },
    methods: {},
    setup() {
        const message = ref("");
        const showConfirmDiv = reactive({});
        const searchTitle = ref("");
        const isDeleteBtnActive = reactive({});
        const news = ref([]);
        const newsStore = useNewsStore();
        const currentPage = ref(1);
        const itemsPerPage = 5;
        const form = reactive({
            title: "",
            content: "",
            image: "",
            author: "",
            published: true,
        });
        const activateConfirmDiv = (id) => {
            showConfirmDiv[id] = true;
            isDeleteBtnActive[id] = true;
        };
        const deactivateConfirmDiv = (id) => {
            showConfirmDiv[id] = false;
            isDeleteBtnActive[id] = false;
        };
        const paginatedEvents = computed(() => {
            const filteredNews = news.value.filter((item) =>
                item.title
                    .toLowerCase()
                    .includes(searchTitle.value.toLowerCase())
            );

            const startIndex = (currentPage.value - 1) * itemsPerPage;
            const endIndex = startIndex + itemsPerPage;

            return filteredNews.slice(startIndex, endIndex);
        });
        const totalPages = computed(() =>
            Math.ceil(
                news.value.filter((item) =>
                    item.title
                        .toLowerCase()
                        .includes(searchTitle.value.toLowerCase())
                ).length / itemsPerPage
            )
        );
        const setCurrentPage = (page) => {
            currentPage.value = page;
        };
        const fetchNews = () => {
            newsStore.fetchNews().then((response) => {
                news.value = newsStore.news.events.sort((a, b) => {
                    // We convert the date strings to Date objects for comparison
                    const dateA = new Date(a.createdAt);
                    const dateB = new Date(b.createdAt);

                    // Return negative number if dateA is later than dateB
                    // Return positive number if dateA is earlier than dateB
                    // Return 0 if they're the same
                    return dateB - dateA;
                });
                message.value = newsStore.news.message;
            });
        };
        fetchNews();
        watch(
            () => newsStore.news,
            (value) => {
                news.value = value;
            }
        );
        const createNews = () => {
            newsStore.createNews(form).then(() => fetchNews());
        };

        const deleteNews = (id) => {
            newsStore.deleteNews(id).then(() => fetchNews());
        };

        const editNews = (id) => {
            router.visit(`/news/${id}`);
        };

        function addToast() {
            toast.add({
                message: this.message,
            });
        }

        return {
            fetchNews,
            news,
            form,
            createNews,
            deleteNews,
            editNews,
            addToast,
            paginatedEvents,
            currentPage,
            itemsPerPage,
            totalPages,
            setCurrentPage,
            showConfirmDiv,
            activateConfirmDiv,
            deactivateConfirmDiv,
            isDeleteBtnActive,
            searchTitle,
        };
    },
    methods: {
        formatTimeAgo(dateTime) {
            const now = new Date();
            const createdAt = new Date(dateTime);
            const timeDiff = now - createdAt;

            const days = Math.floor(timeDiff / (1000 * 60 * 60 * 24));
            const hours = Math.floor(
                (timeDiff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)
            );

            let timeAgo = "";
            if (days > 0) {
                timeAgo += `${days}d${days > 1 ? "" : ""}`;
            }
            if (hours > 0) {
                timeAgo += `${days > 0 ? " " : ""}${hours}h${
                    hours > 1 ? "" : ""
                }`;
            }
            if (timeAgo === "") {
                timeAgo = "just now";
            } else {
                timeAgo += " ago";
            }

            return timeAgo;
        },
        truncateDescription(description, maxLength) {
            if (description.length <= maxLength) {
                return description;
            } else {
                return description.slice(0, maxLength) + "...";
            }
        },
    },
};
</script>

<template>
    <Head title="News" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight"
            >
                News
            </h2>
        </template>

        <div class="py-8">
            <div class="max-w-4xl flex flex-col gap-20 mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg"
                >
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h2 class="text-2xl text-center">Create article</h2>
                        <form @submit.prevent="createNews" class="space-y-4">
                            <div>
                                <label for="title" class="block text-gray-300"
                                    >Title</label
                                >
                                <input
                                    id="title"
                                    v-model="form.title"
                                    type="text"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-400"
                                />
                            </div>

                            <div>
                                <label for="content" class="block text-gray-300"
                                    >Content</label
                                >
                                <textarea
                                    id="content"
                                    v-model="form.content"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-400"
                                    rows="5"
                                ></textarea>
                            </div>

                            <div>
                                <label for="image" class="block text-gray-300"
                                    >Image</label
                                >
                                <input
                                    id="image"
                                    v-model="form.image"
                                    type="text"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-400"
                                />
                            </div>

                            <div>
                                <label for="author" class="block text-gray-300"
                                    >Author</label
                                >
                                <input
                                    id="author"
                                    v-model="form.author"
                                    type="text"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-400"
                                />
                            </div>

                            <div class="flex items-center">
                                <input
                                    id="published"
                                    v-model="form.published"
                                    type="checkbox"
                                    class="mr-1"
                                />
                                <label for="published" class="text-gray-300"
                                    >Published</label
                                >
                            </div>
                            <div class="flex items-center">
                                <input
                                    id="tendances"
                                    v-model="form.tendances"
                                    type="checkbox"
                                    class="mr-1"
                                />
                                <label
                                    for="tendances"
                                    class="block text-gray-300"
                                    >Tendances</label
                                >
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
                        <h2 class="text-2xl text-center">News list</h2>
                        <div class="mt-4">
                            <label
                                for="search-title"
                                class="block text-gray-300"
                                >Search by Title</label
                            >
                            <input
                                id="search-title"
                                v-model="searchTitle"
                                type="text"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-400"
                            />
                        </div>

                        <div v-if="news.length > 0" class="mt-8">
                            <div
                                v-for="actualite in paginatedEvents"
                                :key="actualite.id"
                                class="mb-4"
                            >
                                <h3
                                    class="flex justify-between text-xl font-semibold"
                                >
                                    <span>
                                        {{
                                            truncateDescription(
                                                actualite.title,
                                                40
                                            )
                                        }}
                                    </span>
                                    <div>
                                        <span
                                            class="mr-2 text-base text-yellow-600"
                                            >{{
                                                actualite.tendances
                                                    ? "Tendances"
                                                    : ""
                                            }}</span
                                        >
                                        <span
                                            :style="
                                                actualite.published
                                                    ? 'color:green'
                                                    : 'color:red'
                                            "
                                            >{{
                                                actualite.published
                                                    ? "Published"
                                                    : "Not published"
                                            }}</span
                                        >
                                    </div>
                                </h3>
                                <p class="text-gray-600">
                                    {{
                                        truncateDescription(
                                            actualite.content,
                                            200
                                        )
                                    }}
                                </p>

                                <div class="flex justify-between">
                                    <div class="flex mt-2">
                                        <button
                                            @click="editNews(actualite.id)"
                                            class="text-[#7458ea] hover:text-black mr-4"
                                        >
                                            Edit
                                        </button>
                                        <button
                                            v-if="
                                                !isDeleteBtnActive[actualite.id]
                                            "
                                            @click="
                                                activateConfirmDiv(actualite.id)
                                            "
                                            class="text-red-500 hover:text-red-700"
                                        >
                                            Delete
                                        </button>
                                        <div
                                            class="gap-2 flex"
                                            v-if="showConfirmDiv[actualite.id]"
                                        >
                                            <button
                                                @click="
                                                    deleteNews(actualite.id)
                                                "
                                                class="text-red-500 hover:text-red-700"
                                            >
                                                Yes
                                            </button>
                                            <button
                                                @click="
                                                    deactivateConfirmDiv(
                                                        actualite.id
                                                    )
                                                "
                                            >
                                                No
                                            </button>
                                        </div>
                                    </div>
                                    <div>
                                        {{ formatTimeAgo(actualite.createdAt) }}
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
                            <p class="text-gray-600">No news available.</p>
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
