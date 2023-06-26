<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { useNewsStore } from "@/Stores/newsStore";
import { ref, watch, reactive } from "vue";
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
        const news = ref([]);
        const newsStore = useNewsStore();
        const form = reactive({
            title: "",
            content: "",
            image: "",
            author: "",
            published: false,
        });
        const fetchNews = () => {
            newsStore.fetchNews().then((response) => {
                news.value = newsStore.news.events;
                message.value = newsStore.news.message;
                // console.log(this.news);
                console.log(newsStore.news);
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
            // Inertia.visit(`/news/${id}`);
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
        };
    },
    methods: {},
};
</script>

<template>
    <Head title="News" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"
            >
                News
            </h2>
            {{ news }}
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg"
                >
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <form @submit.prevent="createNews" class="space-y-4">
                            <div>
                                <label for="title" class="block text-gray-700"
                                    >Title</label
                                >
                                <input
                                    id="title"
                                    v-model="form.title"
                                    type="text"
                                    required
                                    class="w-full px-2 py-1 border rounded"
                                />
                            </div>

                            <div>
                                <label for="content" class="block text-gray-700"
                                    >Content</label
                                >
                                <textarea
                                    id="content"
                                    v-model="form.content"
                                    required
                                    class="w-full px-2 py-1 border rounded"
                                ></textarea>
                            </div>

                            <div>
                                <label for="image" class="block text-gray-700"
                                    >Image</label
                                >
                                <input
                                    id="image"
                                    v-model="form.image"
                                    type="text"
                                    class="w-full px-2 py-1 border rounded"
                                />
                            </div>

                            <div>
                                <label for="author" class="block text-gray-700"
                                    >Author</label
                                >
                                <input
                                    id="author"
                                    v-model="form.author"
                                    type="text"
                                    class="w-full px-2 py-1 border rounded"
                                />
                            </div>

                            <div>
                                <label
                                    for="published"
                                    class="block text-gray-700"
                                    >Published</label
                                >
                                <input
                                    id="published"
                                    v-model="form.published"
                                    type="checkbox"
                                    class="mr-1"
                                />
                            </div>

                            <button
                                type="submit"
                                class="py-2 px-4 bg-blue-500 text-white rounded hover:bg-blue-600"
                            >
                                Create
                            </button>
                        </form>
                        <div v-if="news.length > 0">
                            <div v-for="actualite in news" :key="actualite.id">
                                <h3>{{ actualite.title }}</h3>
                                <p>{{ actualite.content }}</p>
                                <!-- You can add more information about the actualite here -->
                                <button @click="editNews(actualite.id)">
                                    Edit
                                </button>
                                <button @click="deleteNews(actualite.id)">
                                    Delete
                                </button>
                            </div>
                            <!-- Render the rest of the news content -->
                        </div>
                        <div v-else>
                            <p>No news available.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
<style scoped>
textarea {
    min-height: 200px;
}
</style>
