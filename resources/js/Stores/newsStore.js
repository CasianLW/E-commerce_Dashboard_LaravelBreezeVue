import { defineStore } from "pinia";
import axios from "axios";

import api from "@/Services/api";
const API_URL = import.meta.env.VITE_APP_API_URL;

export const useNewsStore = defineStore({
    id: "news",
    state: () => ({
        news: [],
        new: {},
        message: "",
    }),
    actions: {
        async fetchNews() {
            const response = await api.getEvents();
            // console.log(response);
            // coawait api.getEvents();
            this.news = response;
            // console.log(this.news);
            // return response;
        },
        async createNews(data) {
            await api.createEvent(data);
            this.fetchNews();
        },
        async getNews(id) {
            this.new = {};
            const response = await api.eventById(id);
            this.new = response.event;
        },
        async editNews(id, data) {
            const response = await api.editEventById(id, data);
            this.message = response.message;
            this.getNews(id);
        },
        async deleteNews(id) {
            await api.deleteEventById(id);
            this.fetchNews();
        },
    },
});
