<template>
    <div>
        <h2
            class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight"
        >
            Payment Edit
        </h2>

        <div class="p-6 text-gray-900 dark:text-gray-100">
            <h2 class="text-2xl text-center">Payment Edit</h2>

            <div class="mt-4">
                <p class="text-center">Under Construction</p>
            </div>

            <div class="under-dev-overlay">
                <p class="under-dev-text">Under Dev</p>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "PaymentEdit",
};
</script>

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

/* Add any additional styles as needed */
</style>
