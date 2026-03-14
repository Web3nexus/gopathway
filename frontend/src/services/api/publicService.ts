import api from '@/lib/api';

export const publicService = {
    getSettings: () => api.get('/api/v1/settings').then((r: any) => r.data),
};
