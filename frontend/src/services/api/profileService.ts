import api from '@/lib/api';

export const profileService = {
    getProfile: () => api.get('/api/v1/profile').then(r => r.data.data),
    updateProfile: (data: Record<string, any>) => api.put('/api/v1/profile', data).then(r => r.data.data),
    updateBudget: (data: { current_savings?: number; monthly_savings_target?: number }) =>
        api.patch('/api/v1/profile/budget', data).then(r => r.data),
};
