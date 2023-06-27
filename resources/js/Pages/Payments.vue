<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router } from "@inertiajs/vue3";
import { usePurchasesStore } from "@/Stores/purchasesStore";
import { ref, watch, reactive, computed } from "vue";
export default {
    components: {
        AuthenticatedLayout,
        Head,
    },
    setup() {
        const message = ref("");
        const showConfirmDiv = reactive({});
        const searchUserId = ref("");
        const isDeleteBtnActive = reactive({});
        const purchases = ref([]);
        const purchasesStore = usePurchasesStore();
        const currentPage = ref(1);
        const itemsPerPage = 5;
        const form = reactive({
            stripeCustomerId: "",
            price: "",
            stripeUserId: "",
            status: "",
            invoiceUrl: "",
        });
        // ...
        const fetchPurchases = () => {
            console.log("purchases : ", purchasesStore.purchases);
            purchasesStore.fetchPurchases().then(() => {
                purchases.value = Array.isArray(purchasesStore.purchases)
                    ? purchasesStore.purchases.sort((a, b) => {
                          const dateA = new Date(a.createdAt);
                          const dateB = new Date(b.createdAt);
                          return dateB - dateA;
                      })
                    : [];
                message.value = purchasesStore.message;
            });
        };

        fetchPurchases();

        watch(
            () => purchasesStore.purchases,
            (newPurchases) => {
                purchases.value = Array.isArray(newPurchases)
                    ? [...newPurchases]
                    : [];
            },
            { immediate: true }
        );

        const createPurchase = () => {
            purchasesStore.createPurchase(form).then(() => fetchPurchases());
        };
        const deletePayment = (id) => {
            purchasesStore.deletePurchase(id).then(() => fetchPayments());
        };
        const editPurchase = (id) => {
            router.visit(`/purchases/${id}`);
        };
        const activateConfirmDiv = (id) => {
            showConfirmDiv[id] = true;
        };
        const deactivateConfirmDiv = (id) => {
            showConfirmDiv[id] = false;
            isDeleteBtnActive[id] = false;
        };

        const paginatedPayments = computed(() => {
            if (Array.isArray(purchases.value)) {
                const filteredPayments = purchases.value.filter((item) => {
                    const userId = item.userId ? item.userId.toString() : "";
                    return userId.includes(searchUserId.value.toString());
                });
                const startIndex = (currentPage.value - 1) * itemsPerPage;
                const endIndex = startIndex + itemsPerPage;
                return filteredPayments.slice(startIndex, endIndex);
            } else {
                return [];
            }
        });

        const totalPages = computed(() => {
            if (Array.isArray(purchases.value)) {
                return Math.ceil(
                    purchases.value.filter((item) => {
                        const userId = item.userId
                            ? item.userId.toString()
                            : "";
                        return userId.includes(searchUserId.value.toString());
                    }).length / itemsPerPage
                );
            } else {
                return 0;
            }
        });

        const setCurrentPage = (page) => {
            currentPage.value = page;
        };
        return {
            message,
            showConfirmDiv,
            searchUserId,
            isDeleteBtnActive,
            purchases,
            currentPage,
            itemsPerPage,
            form,
            activateConfirmDiv,
            deactivateConfirmDiv,
            paginatedPayments,
            totalPages,
            setCurrentPage,
            createPurchase,
            deletePayment,
            editPurchase,
        };
    },
    methods: {
        formatCreatedAt(createdAt) {
            const dateOptions = {
                year: "numeric",
                month: "long",
                day: "numeric",
            };
            const timeOptions = { hour: "numeric", minute: "numeric" };
            const date = new Date(createdAt);
            const formattedDate = date.toLocaleDateString(
                undefined,
                dateOptions
            );
            const formattedTime = date.toLocaleTimeString(
                undefined,
                timeOptions
            );
            return `${formattedDate}, ${formattedTime}`;
        },
    },
};
</script>

<template>
    <Head title="Manage Payments" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight"
            >
                Manage Payments
            </h2>
        </template>

        <div class="py-8">
            <div class="max-w-4xl flex flex-col gap-20 mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg"
                >
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h2 class="text-2xl text-center">Manage Payments</h2>

                        <div class="mt-4 mb-4">
                            <label for="search" class="block text-gray-300"
                                >Search by User ID:</label
                            >
                            <input
                                id="search"
                                v-model.number="searchUserId"
                                type="number"
                                placeholder="Search by User ID"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-400"
                            />
                        </div>

                        <div
                            v-for="payment in paginatedPayments"
                            :key="payment.id"
                            class="mb-4"
                        >
                            <div>
                                <div
                                    class="flex justify-between border-b border-gray-200 py-2"
                                >
                                    <div class="flex flex-col">
                                        <h3
                                            class="text-xl font-semibold text-[#7458EA]"
                                        >
                                            Payment ID:
                                            <span class="font-normal">{{
                                                payment.id
                                            }}</span>
                                        </h3>
                                        <p>
                                            User ID:
                                            <span>{{ payment.userId }}</span>
                                        </p>
                                    </div>
                                    <div class="flex items-center">
                                        <a
                                            :href="payment.invoiceUrl"
                                            target="_blank"
                                            class="inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded text-xs"
                                        >
                                            Invoice Link
                                        </a>
                                    </div>
                                </div>
                                <div class="flex justify-between py-2">
                                    <div>
                                        <p>
                                            Price:
                                            <span
                                                class="text-[#D3FE57] text-xl"
                                                >{{ payment.price }}</span
                                            >
                                        </p>
                                        <p>
                                            Status:
                                            <span
                                                :style="
                                                    payment.status == 'paid'
                                                        ? 'color:green;'
                                                        : 'color:yellow;'
                                                "
                                                >{{ payment.status }}</span
                                            >
                                        </p>
                                        <p class="text-xs">
                                            Date:
                                            <span>{{
                                                formatCreatedAt(
                                                    payment.createdAt
                                                )
                                            }}</span>
                                        </p>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <!-- <button
                                            @click="editPayment(payment.id)"
                                            class="text-[#7458ea] hover:text-black"
                                        >
                                            Edit
                                        </button> -->
                                        <button
                                            v-if="!showConfirmDiv[payment.id]"
                                            @click="
                                                activateConfirmDiv(payment.id)
                                            "
                                            class="text-red-500 hover:text-red-700"
                                        >
                                            Delete
                                        </button>
                                        <div
                                            class="gap-2 flex"
                                            v-if="showConfirmDiv[payment.id]"
                                        >
                                            <button
                                                @click="
                                                    deletePayment(payment.id)
                                                "
                                                class="text-red-500 hover:text-red-700"
                                            >
                                                Yes
                                            </button>
                                            <button
                                                @click="
                                                    deactivateConfirmDiv(
                                                        payment.id
                                                    )
                                                "
                                            >
                                                No
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div v-if="totalPages > 1" class="pagination">
                            <button
                                :class="{
                                    'page-btn': true,
                                    active: currentPage === page,
                                }"
                                v-for="page in totalPages"
                                :key="page"
                                @click="setCurrentPage(page)"
                            >
                                {{ page }}
                            </button>
                        </div>
                    </div>
                    <div class="relative p-6 text-gray-900 dark:text-gray-100">
                        <form
                            @submit.prevent="createPurchase"
                            class="space-y-4"
                        >
                            <h2 class="text-2xl text-center">Create Payment</h2>

                            <div>
                                <label
                                    for="stripeCustomerId"
                                    class="block text-gray-300"
                                    >Stripe Customer ID:</label
                                >
                                <input
                                    v-model="form.stripeCustomerId"
                                    id="stripeCustomerId"
                                    type="text"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-400"
                                />
                            </div>
                            <div>
                                <label for="price" class="block text-gray-300"
                                    >Price:</label
                                >
                                <input
                                    v-model="form.price"
                                    id="price"
                                    type="number"
                                    step="0.01"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-400"
                                />
                            </div>
                            <div>
                                <label
                                    for="stripeUserId"
                                    class="block text-gray-300"
                                    >Stripe User ID:</label
                                >
                                <input
                                    v-model="form.stripeUserId"
                                    id="stripeUserId"
                                    type="text"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-400"
                                />
                            </div>
                            <div>
                                <label for="status" class="block text-gray-300"
                                    >Status:</label
                                >
                                <input
                                    v-model="form.status"
                                    id="status"
                                    type="text"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-400"
                                />
                            </div>
                            <div>
                                <label
                                    for="invoiceUrl"
                                    class="block text-gray-300"
                                    >Invoice URL:</label
                                >
                                <input
                                    v-model="form.invoiceUrl"
                                    id="invoiceUrl"
                                    type="text"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-400"
                                />
                            </div>
                            <button
                                type="submit"
                                class="py-2 px-4 bg-[#7458ea] text-white rounded-md hover:bg-black"
                            >
                                Create Payment
                            </button>
                        </form>

                        <p>{{ message }}</p>
                        <div class="under-dev-overlay">
                            <p
                                style="transform: rotate(-30deg)"
                                class="under-dev-text"
                            >
                                Under Dev
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.under-dev-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.3);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
}

.under-dev-text {
    color: white;
    font-size: 52px;
    text-align: center;
}

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
