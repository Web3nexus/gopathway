import api from '@/lib/api';

export const notificationService = {
    getNotifications: () => api.get('/api/v1/notifications').then(r => r.data),
    markRead: (id: number) => api.post(`/api/v1/notifications/${id}/read`),
    markAllRead: () => api.post('/api/v1/notifications/read-all'),
};
