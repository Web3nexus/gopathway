import api from '@/lib/api';

export const planService = {
    getPlans: () => api.get('/api/v1/plans').then(r => r.data.data),
};
