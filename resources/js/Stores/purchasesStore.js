import { defineStore } from "pinia";
import axios from "axios";

import api from "@/Services/api";
const API_URL = import.meta.env.VITE_APP_API_URL;

export const usePurchasesStore = defineStore({
    id: "purchases",
    state: () => ({
        purchases: [],
        purchase: {},
        message: "",
    }),
    actions: {
        async fetchPurchases() {
            const response = await api.getPurchases();
            this.purchases = response.purchases;
        },
        async createPurchase(data) {
            await api.createPurchase(data);
            this.fetchPurchases();
        },
        async getPurchase(id) {
            this.purchase = {};
            const response = await api.purchaseById(id);
            this.purchase = response.purchase;
        },
        async editPurchase(id, data) {
            const response = await api.editPurchaseById(id, data);
            this.message = response.message;
            this.getPurchase(id);
        },
        async deletePurchase(id) {
            await api.deletePurchaseById(id);
            this.fetchPurchases();
        },
    },
});
