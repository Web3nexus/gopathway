import api from '@/lib/api';

export const recommendationService = {
    getRecommendations: () => api.get('/api/v1/recommendations').then(r => r.data),
};
