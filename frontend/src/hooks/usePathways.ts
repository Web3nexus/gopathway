import { useQuery } from '@tanstack/react-query';
import api from '@/lib/api';

export function usePathways() {
    return useQuery({
        queryKey: ['pathways'],
        queryFn: () => api.get('/api/v1/pathways').then(r => r.data.data),
    });
}
