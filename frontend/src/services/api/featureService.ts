import api from '@/lib/api';

export const featureService = {
    getFeatures: () =>
        api.get('/api/v1/features').then(r => r.data.data),

    adminGetFeatures: () =>
        api.get('/api/v1/admin/features').then(r => r.data.data),

    adminUpdateFeature: (id: number, data: { is_premium: boolean }) =>
        api.put(`/api/v1/admin/features/${id}`, data).then(r => r.data.data),

    adminGetPlatformFeatures: () =>
        api.get('/api/v1/admin/platform-features').then(r => r.data.data),

    adminTogglePlatformFeature: (id: number, data: { is_active?: boolean; is_premium?: boolean }) =>
        api.put(`/api/v1/admin/platform-features/${id}`, data).then(r => r.data.data),
};
