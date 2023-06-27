<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, usePage } from "@inertiajs/vue3";
import { ref, onMounted } from "vue";
import { useNewsStore } from "@/Stores/newsStore";

export default {
    components: {
        AuthenticatedLayout,
        Head,
    },
    setup() {
        const { props } = usePage();
        const message = ref("");

        const newsStore = useNewsStore();
        const news = ref(null);
        const form = ref({
            title: "",
            content: "",
            image: "",
            published: false,
            author: "",
            tendances: false,
        });

        onMounted(async () => {
            const response = await newsStore.getNews(props.id);
            console.log(response);
            news.value = newsStore.new;
            // message.value = newsStore.message;
            form.value = news.value; // Assign the fetched news data to the form
        });

        const editNews = async () => {
            const response = await newsStore.editNews(props.id, form.value);
            message.value = newsStore.message;

            setTimeout(() => {
                message.value = "";
            }, 3000);
        };

        return {
            news,
            form,
            editNews,
            message,
        };
    },
};
</script>

<template>
    <Head title="News Edit" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                News Edit
            </h2>
        </template>

        <div class="py-8">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg"
                >
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h2 class="text-2xl text-center">Edit News</h2>
                        <form @submit.prevent="editNews" class="space-y-4">
                            <div v-if="news">
                                <div>
                                    <label
                                        for="title"
                                        class="block text-gray-300"
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
                                    <label
                                        for="content"
                                        class="block text-gray-300"
                                        >Content</label
                                    >
                                    <textarea
                                        id="content"
                                        v-model="form.content"
                                        required
                                        class="w-full h-40 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-400"
                                    ></textarea>
                                </div>

                                <div>
                                    <label
                                        for="image"
                                        class="block text-gray-300"
                                        >Image</label
                                    >
                                    <input
                                        id="image"
                                        v-model="form.image"
                                        type="text"
                                        required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-400"
                                    />
                                </div>

                                <div class="mt-4 gap-2 flex items-center">
                                    <label
                                        for="published"
                                        class="block text-gray-300"
                                        >Published:</label
                                    >
                                    <input
                                        id="published"
                                        v-model="form.published"
                                        type="checkbox"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-400"
                                    />
                                </div>

                                <div>
                                    <label
                                        for="author"
                                        class="block text-gray-300"
                                        >Author</label
                                    >
                                    <input
                                        id="author"
                                        v-model="form.author"
                                        type="text"
                                        required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-400"
                                    />
                                </div>

                                <div class="mt-4 mb-4 gap-2 flex items-center">
                                    <label
                                        for="tendances"
                                        class="block text-gray-300"
                                        >Tendances:</label
                                    >
                                    <input
                                        id="tendances"
                                        v-model="form.tendances"
                                        type="checkbox"
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
