import api from '@/lib/api';

export const financeService = {
    getRecommendations: () =>
        api.get('/api/v1/finance/recommendations').then(res => res.data),
};
