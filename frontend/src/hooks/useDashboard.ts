import { useQuery } from '@tanstack/react-query';
import { dashboardService } from '@/services/api/dashboardService';

export function useDashboard() {
    return useQuery({
        queryKey: ['dashboard'],
        queryFn: dashboardService.getSummary,
        refetchInterval: 1000 * 60 * 2, // refresh every 2 min
    });
}
