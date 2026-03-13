import { useState } from 'react';
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import { aiChatService } from '@/services/api/aiChatService';

export function useAiChat(chatId?: number) {
    const queryClient = useQueryClient();
    const [activeChatId, setActiveChatId] = useState<number | undefined>(chatId);

    const { data: messagesRes, isLoading: loadingMessages } = useQuery({
        queryKey: ['ai-messages', activeChatId],
        queryFn: () => aiChatService.getMessages(activeChatId!),
        enabled: !!activeChatId,
    });

    const { data: chatsRes, isLoading: loadingChats } = useQuery({
        queryKey: ['ai-chats'],
        queryFn: aiChatService.getChats,
    });

    const createChatMutation = useMutation({
        mutationFn: aiChatService.createChat,
        onSuccess: (res) => {
            setActiveChatId(res.data.id);
            queryClient.invalidateQueries({ queryKey: ['ai-chats'] });
        }
    });

    const sendMessageMutation = useMutation({
        mutationFn: ({ message }: { message: string }) =>
            aiChatService.sendMessage(activeChatId!, message),
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['ai-messages', activeChatId] });
        }
    });

    return {
        chats: chatsRes?.data || [],
        messages: messagesRes?.data?.messages || [],
        activeChatId,
        setActiveChatId,
        loadingMessages,
        loadingChats,
        createChat: createChatMutation.mutate,
        isCreating: createChatMutation.isPending,
        sendMessage: sendMessageMutation.mutate,
        isSending: sendMessageMutation.isPending,
    };
}
