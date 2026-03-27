import { useQuery } from '@tanstack/react-query';
import { featureService } from '@/services/api/featureService';
import { useAuth } from './useAuth';
import api from '@/lib/api';

export function useFeatures() {
    const { user } = useAuth();

    const { data: features = [], isLoading: featuresLoading } = useQuery({
        queryKey: ['features'],
        queryFn: async () => {
            const res = await featureService.getFeatures();
            return Array.isArray(res) ? res : [];
        },
        staleTime: 1000 * 60 * 5, // 5 minutes
    });

    const { data: platformData = { flags: {}, isPremium: false }, isLoading: flagsLoading } = useQuery({
        queryKey: ['platform-flags'],
        queryFn: async () => {
            const res = await api.get('/api/v1/dashboard/summary');
            return {
                flags: res.data?.platform_features || {},
                isPremium: res.data?.is_premium || false
            };
        },
        staleTime: 1000 * 60,
    });

    const platformFlags = platformData.flags;
    const userIsPremium = user?.is_premium || platformData.isPremium;

    const isFeaturePremium = (slug: string) => {
        const feature = Array.isArray(features) ? features.find((f: any) => f.slug === slug) : null;
        if (feature) return feature.is_premium;

        // Also check if it's a platform feature with a matching key
        const flagKey = slug.toUpperCase();
        return platformFlags[flagKey]?.is_premium ?? false;
    };

    const isFeatureActive = (key: string) => {
        return platformFlags[key]?.is_active === true;
    };

    const getFeatureAccess = (slug: string): 'active' | 'locked' | 'hidden' => {
        // 1. Admins should always see everything
        const roles = user?.roles;
        const isAdmin = Array.isArray(roles) && roles.some((role: any) => role.name === 'admin');
        if (isAdmin) return 'active';

        const flagKey = slug.toUpperCase();
        const platformFeature = platformFlags[flagKey];

        // 2. While loading, be conservative
        if (featuresLoading || flagsLoading) return 'hidden';

        // 3. Global Release Check
        if (platformFeature && platformFeature.is_active === false) return 'hidden';

        // 4. Premium Check
        const isPremiumRequired = platformFeature?.is_premium ||
            (Array.isArray(features) && features.find((f: any) => f.slug.toLowerCase() === slug.toLowerCase())?.is_premium);

        if (isPremiumRequired && !userIsPremium) return 'locked';

        return 'active';
    };

    const canAccessFeature = (slug: string) => {
        return getFeatureAccess(slug) === 'active';
    };

    return {
        features,
        platformFlags,
        isLoading: featuresLoading || flagsLoading,
        isFeaturePremium,
        isFeatureActive,
        isFeatureLocked: (slug: string) => getFeatureAccess(slug) === 'locked',
        canAccessFeature,
        getFeatureAccess,
    };
}
