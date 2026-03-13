import api from '@/lib/api';

export const bookingService = {
    getMarketplace: () => api.get('/api/v1/marketplace').then(r => r.data),
    getBookings: () => api.get('/api/v1/bookings').then(r => r.data),
    createBooking: (data: { professional_id: number; type: string; scheduled_at?: string; notes?: string }) =>
        api.post('/api/v1/bookings', data).then(r => r.data),
    updateStatus: (id: number, status: string) =>
        api.patch(`/api/v1/bookings/${id}/status`, { status }).then(r => r.data),
};
