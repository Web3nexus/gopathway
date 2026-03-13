import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import { chatService } from '@/services/api/chatService';

export function useConversations(enabled = true) {
    return useQuery({
        queryKey: ['conversations'],
        queryFn: chatService.getConversations,
        refetchInterval: 15000, // Poll every 15s for new messages
        enabled
    });
}

export function useMessages(conversationId: number | null, enabled = true) {
    return useQuery({
        queryKey: ['messages', conversationId],
        queryFn: () => conversationId ? chatService.getMessages(conversationId) : Promise.resolve([]),
        enabled: !!conversationId && enabled,
        refetchInterval: 5000, // Poll every 5s when active in chat
    });
}

export function useSendMessage() {
    const queryClient = useQueryClient();
    return useMutation({
        mutationFn: chatService.sendMessage,
        onSuccess: (data: any) => {
            queryClient.invalidateQueries({ queryKey: ['conversations'] });
            queryClient.invalidateQueries({ queryKey: ['messages', data.conversation_id] });
        },
    });
}
