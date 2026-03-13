import axios from 'axios';

export const api = axios.create({
    baseURL: '/api/v1',
    headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json',
    },
    withCredentials: true,
});

api.interceptors.response.use(
    (response) => response,
    (error) => {
        // You can add global error handling here (e.g., logging out on 401)
        return Promise.reject(error);
    }
);

export default api;
