import { useQuery } from '@tanstack/react-query';
import api from '@/lib/api';

export function usePathwayComparison(pathwayIds: number[]) {
    return useQuery({
        queryKey: ['pathways-comparison', pathwayIds],
        queryFn: async () => {
            if (!pathwayIds.length) return [];
            
            // Format array as query params: ids[]=1&ids[]=2
            const params = new URLSearchParams();
            pathwayIds.forEach(id => params.append('ids[]', id.toString()));
            
            const response = await api.get(`/api/v1/pathways/compare?${params.toString()}`);
            return response.data.data;
        },
        enabled: pathwayIds.length >= 2,
    });
}
