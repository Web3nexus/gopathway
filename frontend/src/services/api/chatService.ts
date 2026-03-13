import api from '@/lib/api';

export const chatService = {
    getConversations: () => api.get('/api/v1/messages').then(res => res.data.data),
    getMessages: (conversationId: number) => api.get(`/api/v1/messages/${conversationId}`).then(res => res.data.data),
    sendMessage: (data: { recipient_id: number; body: string }) => api.post('/api/v1/messages', data).then(res => res.data.data),
};
