import api from '@/lib/api';

export const dashboardService = {
    getSummary: () => api.get('/api/v1/dashboard/summary').then(r => r.data),
};
