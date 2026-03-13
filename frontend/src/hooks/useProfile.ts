import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import { profileService } from '@/services/api/profileService';

export function useProfile() {
    return useQuery({
        queryKey: ['profile'],
        queryFn: profileService.getProfile,
        retry: false,
    });
}

export function useUpdateProfile() {
    const queryClient = useQueryClient();
    return useMutation({
        mutationFn: profileService.updateProfile,
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['profile'] });
            queryClient.invalidateQueries({ queryKey: ['dashboard'] });
        },
    });
}
