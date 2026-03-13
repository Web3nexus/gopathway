import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import { authService } from '@/lib/auth';

export function useAuth() {
    const queryClient = useQueryClient();

    const { data: user, isLoading } = useQuery({
        queryKey: ['user'],
        queryFn: async () => {
            try {
                const response = await authService.getUser();
                return {
                    user: response.data.user,
                    isImpersonating: response.data.is_impersonating
                };
            } catch (error) {
                return null;
            }
        },
        retry: false,
    });

    const logoutMutation = useMutation({
        mutationFn: authService.logout,
        onSuccess: () => {
            queryClient.setQueryData(['user'], null);
        },
    });

    return {
        user: user?.user,
        isImpersonating: user?.isImpersonating || false,
        isLoading,
        logout: logoutMutation.mutate,
        isLoggingOut: logoutMutation.isPending,
    };
}
