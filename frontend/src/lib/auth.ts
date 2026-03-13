import { api } from './api';

export const authService = {
    csrfCookie: () => api.get('/sanctum/csrf-cookie'),
    login: async (credentials: any) => {
        await authService.csrfCookie();
        return api.post('/api/v1/auth/login', credentials);
    },
    register: async (userData: any) => {
        await authService.csrfCookie();
        return api.post('/api/v1/auth/register', userData);
    },
    logout: () => api.post('/api/v1/auth/logout'),
    getUser: () => api.get('/api/v1/auth/me'),
};
