import api from '@/lib/api';

export const employabilityService = {
    getScore: () => api.get('/api/v1/user/employability-score').then(r => r.data.data),
    getOccupations: () => api.get('/api/v1/occupations').then(r => r.data.data),
};
