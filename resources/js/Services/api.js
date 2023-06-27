import axios from "axios";
// import { useNotificationStore } from "@/Stores/NotifStore";

const API_BASE_URL = `${import.meta.env.VITE_APP_API_URL}`;

axios.defaults.headers.common["Content-Type"] = "multipart/form-data";
axios.defaults.headers.common["Accept"] = "application/json";

const authApi = axios.create({
    baseURL: `${API_BASE_URL}/auth`,
});

authApi.interceptors.request.use(
    (config) => {
        let token =
            localStorage.getItem("token") ||
            document.cookie.replace(
                /(?:(?:^|.*;\s*)token\s*\=\s*([^;]*).*$)|^.*$/,
                "$1"
            );
        if (token) {
            config.headers["Authorization"] = `Bearer ${token}`;
        }
        return config;
    },
    (error) => {
        Promise.reject(error).then((r) => console.log(r));
    }
);

const adminApi = axios.create({
    baseURL: `${API_BASE_URL}/admin`,
});

adminApi.interceptors.request.use(
    (config) => {
        let token =
            localStorage.getItem("token") ||
            document.cookie.replace(
                /(?:(?:^|.*;\s*)token\s*\=\s*([^;]*).*$)|^.*$/,
                "$1"
            );
        console.log("test adminApi");

        if (token) {
            config.headers["Authorization"] = `Bearer ${token}`;
        }
        return config;
    },
    (error) => {
        Promise.reject(error).then((r) => console.log(r));
    }
);

const profileApi = axios.create({
    baseURL: `${API_BASE_URL}/profile`,
});

profileApi.interceptors.request.use(
    (config) => {
        let token =
            localStorage.getItem("token") ||
            document.cookie.replace(
                /(?:(?:^|.*;\s*)token\s*\=\s*([^;]*).*$)|^.*$/,
                "$1"
            );
        if (token) {
            config.headers["Authorization"] = `Bearer ${token}`;
        }
        return config;
    },
    (error) => {
        Promise.reject(error).then((r) => console.log(r));
    }
);

const storeApi = axios.create({
    baseURL: `${API_BASE_URL}/store`,
});

storeApi.interceptors.request.use(
    (config) => {
        let token =
            localStorage.getItem("token") ||
            document.cookie.replace(
                /(?:(?:^|.*;\s*)token\s*\=\s*([^;]*).*$)|^.*$/,
                "$1"
            );
        if (token) {
            config.headers["Authorization"] = `Bearer ${token}`;
        }
        return config;
    },
    (error) => {
        Promise.reject(error).then((r) => console.log(r));
    }
);

//
//
//
//
export default {
    // user : async () => adminApi.get('/').then((r) => r.data).catch((e) => useNotificationStore().displayError(e.response.data.message)),
    user: async () =>
        adminApi
            .get("/")
            .then((r) => r.data)
            .catch((e) => console.log("error get admin")),
    getUsers: async () =>
        adminApi
            .get(`/users`)
            .then((r) => r.data)
            .catch((e) => console.log("error get users")),
    userById: async (id, data) =>
        adminApi
            .get(`/users/${id}`, data)
            .then((r) => r.data)
            .catch((e) => console.log("error get user")),
    createUser: async (data) =>
        adminApi
            .post(`/users`, data)
            .then((r) => r.data)
            .catch((e) => console.log("error creating user")),
    editUserById: async (id, data) =>
        adminApi
            .put(`/users/${id}`, data)
            .then((r) => r.data)
            .catch((e) => console.log("error edit user")),
    deleteUserById: async (id) =>
        adminApi
            .delete(`/users/${id}`)
            .then((r) => r.data)
            .catch((e) => console.log("error delete user")),
    //
    // Methodes pour les actualités
    //
    getEvents: async () =>
        adminApi
            .get(`/events`)
            .then((r) => r.data)
            .catch((e) => console.log("error get events")),
    eventById: async (id, data) =>
        adminApi
            .get(`/events/${id}`, data)
            .then((r) => r.data)
            .catch((e) => console.log("error get event")),
    createEvent: async (data) =>
        adminApi
            .post(`/events`, data)
            .then((r) => r.data)
            .catch((e) => console.log("error creating event")),
    editEventById: async (id, data) =>
        adminApi
            .put(`/events/${id}`, data)
            .then((r) => r.data)
            .catch((e) => console.log("error edit event")),
    deleteEventById: async (id) =>
        adminApi
            .delete(`/events/${id}`)
            .then((r) => r.data)
            .catch((e) => console.log("error delete event")),
};

// async fetchNews() {
//     const token = localStorage.getItem("token");
//     const response = await axios.get(`${API_URL}/admin/events`, {
//         headers: {
//             Authorization: `Bearer ${token}`,
//         },
//     });
//     this.news = response.data;
// },
// async createNews(data) {
//     const token = localStorage.getItem("token");
//     await axios.post(`${API_URL}/admin/events`, data, {
//         headers: {
//             Authorization: `Bearer ${token}`,
//         },
//     });
//     this.fetchNews();
// },
// async getNews(id) {
//     const token = localStorage.getItem("token");
//     const response = await axios.get(`${API_URL}/admin/events/${id}`, {
//         headers: {
//             Authorization: `Bearer ${token}`,
//         },
//     });
//     return response.data;
// },
// async editNews(id, data) {
//     const token = localStorage.getItem("token");
//     await axios.put(`${API_URL}/admin/events/${id}`, data, {
//         headers: {
//             Authorization: `Bearer ${token}`,
//         },
//     });
//     this.fetchNews();
// },
// async deleteNews(id) {
//     const token = localStorage.getItem("token");
//     await axios.delete(`${API_URL}/admin/events/${id}`, {
//         headers: {
//             Authorization: `Bearer ${token}`,
//         },
//     });
//     this.fetchNews();
// },
