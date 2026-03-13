import { useQuery } from '@tanstack/react-query';
import { employabilityService } from '@/services/api/employabilityService';

export function useEmployabilityScore() {
    return useQuery({
        queryKey: ['employability-score'],
        queryFn: employabilityService.getScore,
        retry: false,
    });
}

export function useOccupations() {
    return useQuery({
        queryKey: ['occupations'],
        queryFn: employabilityService.getOccupations,
        staleTime: 1000 * 60 * 60 * 24, // 24 hours
    });
}
