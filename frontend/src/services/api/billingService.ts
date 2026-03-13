import api from '@/lib/api';

export const billingService = {
    getPlans: () => api.get('/api/v1/plans').then(r => r.data),
    getCurrentSubscription: () => api.get('/api/v1/billing/current').then(r => r.data),
    subscribe: (planId: number, currency?: string) => api.post('/api/v1/billing/subscribe', { plan_id: planId, currency }).then(r => r.data),
    verifyPayment: (reference: string) => api.get(`/api/v1/billing/verify?reference=${reference}`).then(r => r.data),
    getHistory: () => api.get('/api/v1/billing/history').then(r => r.data),
};
