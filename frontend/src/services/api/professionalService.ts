import api from '@/lib/api';

export const professionalService = {
    apply: (formData: FormData) =>
        api.post('/api/v1/professionals/apply', formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
        }).then(r => r.data),

    getStatus: () =>
        api.get('/api/v1/professionals/status').then(r => r.data),

    updateProfile: (data: any) =>
        api.put('/api/v1/professionals/profile', data).then(r => r.data),

    getEarnings: () =>
        api.get('/api/v1/expert-payments/stats').then(r => r.data),

    requestWithdrawal: (data: any) =>
        api.post('/api/v1/expert-payments/withdraw', data).then(r => r.data),

    initializePayment: (data: any) =>
        api.post('/api/v1/expert-payments/initialize', data).then(r => r.data),
};
