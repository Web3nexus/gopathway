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
};
