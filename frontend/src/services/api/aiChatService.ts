import api from '@/lib/api';

export const aiChatService = {
    getChats: () =>
        api.get('/api/v1/ai-chats').then(res => res.data),

    createChat: (title?: string) =>
        api.post('/api/v1/ai-chats', { title }).then(res => res.data),

    getMessages: (chatId: number) =>
        api.get(`/api/v1/ai-chats/${chatId}`).then(res => res.data),

    sendMessage: (chatId: number, message: string) =>
        api.post(`/api/v1/ai-chats/${chatId}/send`, { message }).then(res => res.data),

    deleteChat: (chatId: number) =>
        api.delete(`/api/v1/ai-chats/${chatId}`).then(res => res.data),
};
