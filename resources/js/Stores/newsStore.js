import { defineStore } from "pinia";
import axios from "axios";

import api from "@/Services/api";
const API_URL = import.meta.env.VITE_APP_API_URL;

export const useNewsStore = defineStore({
    id: "news",
    state: () => ({
        news: [],
    }),
    actions: {
        async fetchNews() {
            const response = await api.getEvents();
            console.log(response);
            // coawait api.getEvents();
            this.news = response;
            console.log(this.news);
            // return response;
        },
        async createNews(data) {
            await api.createEvent(data);
            this.fetchNews();
        },
        async getNews(id) {
            await api.eventById(id);
        },
        async editNews(id, data) {
            await api.editEventById(id, data);
            this.fetchNews();
        },
        async deleteNews(id) {
            await api.deleteEventById(id);
            this.fetchNews();
        },
    },
});
