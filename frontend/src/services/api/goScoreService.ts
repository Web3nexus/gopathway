import api from '@/lib/api';

export const goScoreService = {
    getScore: () => api.get('/api/v1/go-score').then(r => r.data.data),
    calculate: () => api.post('/api/v1/go-score/calculate').then(r => r.data.data),
};
