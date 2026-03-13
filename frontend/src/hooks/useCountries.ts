import { useQuery } from '@tanstack/react-query';
import { countryService } from '@/services/api/countryService';

export function useCountries() {
    return useQuery({
        queryKey: ['countries'],
        queryFn: countryService.getCountries,
        staleTime: 1000 * 60 * 10, // 10 minutes
    });
}

export function useCountry(id: number) {
    return useQuery({
        queryKey: ['country', id],
        queryFn: () => countryService.getCountry(id),
        enabled: !!id,
    });
}

export function useVisaTypes(countryId: number) {
    return useQuery({
        queryKey: ['visa-types', countryId],
        queryFn: () => countryService.getVisaTypes(countryId),
        enabled: !!countryId,
    });
}
