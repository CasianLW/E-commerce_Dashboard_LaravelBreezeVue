import { defineStore } from "pinia";
import axios from "axios";

import api from "@/Services/api";
const API_URL = import.meta.env.VITE_APP_API_URL;

export const useUsersStore = defineStore({
    id: "users",
    state: () => ({
        users: [],
        user: {},
        message: "",
    }),
    actions: {
        async fetchUsers() {
            const response = await api.getUsers();
            console.log(response);
            // coawait api.getUsers();
            this.users = response;
            // console.log(this.users);
            // return response;
        },
        async createUsers(data) {
            await api.createUser(data);
            this.fetchUsers();
        },
        async getUsers(id) {
            this.user = {};
            const response = await api.userById(id);
            this.user = response.user;
        },
        async editUsers(id, data) {
            const response = await api.editUserById(id, data);
            this.message = response.message;

            this.getUsers(id);
        },
        async deleteUsers(id) {
            await api.deleteUserById(id);
            this.fetchUsers();
        },
    },
});
