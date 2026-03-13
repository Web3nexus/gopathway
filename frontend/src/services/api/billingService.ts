import api from '@/lib/api';

export const billingService = {
    getPlans: () => api.get('/api/v1/plans').then(r => r.data),
    getCurrentSubscription: () => api.get('/api/v1/billing/current').then(r => r.data),
    subscribe: (planId: number, currency?: string, gateway?: string) => api.post('/api/v1/billing/subscribe', { plan_id: planId, currency, gateway }).then(r => r.data),
    verifyPayment: (params: { reference?: string, transaction_id?: string, gateway?: string }) => {
        const queryParams = new URLSearchParams(params as Record<string, string>).toString();
        return api.get(`/api/v1/billing/verify?${queryParams}`).then(r => r.data);
    },
    getHistory: () => api.get('/api/v1/billing/history').then(r => r.data),
};
