import React, { useEffect } from 'react';
import { useLocation } from 'react-router-dom';
import { useQuery } from '@tanstack/react-query';
import { publicService } from '@/services/api/publicService';

// Add type for window.gtag
declare global {
    interface Window {
        gtag: (...args: any[]) => void;
        dataLayer: any[];
    }
}

export function GoogleAnalytics() {
    const location = useLocation();
    
    const { data } = useQuery({
        queryKey: ['public-settings'],
        queryFn: publicService.getSettings,
        staleTime: 1000 * 60 * 60,
    });

    const gaId = data?.data?.google_analytics_id;

    useEffect(() => {
        if (!gaId) return;

        // Initialize script if not already present
        const scriptId = 'google-analytics-script';
        if (!document.getElementById(scriptId)) {
            const script = document.createElement('script');
            script.id = scriptId;
            script.async = true;
            script.src = `https://www.googletagmanager.com/gtag/js?id=${gaId}`;
            document.head.appendChild(script);

            const inlineScript = document.createElement('script');
            inlineScript.innerHTML = `
                window.dataLayer = window.dataLayer || [];
                function gtag(){dataLayer.push(arguments);}
                gtag('js', new Date());
                gtag('config', '${gaId}');
            `;
            document.head.appendChild(inlineScript);
        }
    }, [gaId]);

    useEffect(() => {
        if (gaId && window.gtag) {
            // Track page view on route change
            window.gtag('event', 'page_view', {
                page_path: location.pathname + location.search,
            });
        }
    }, [location, gaId]);

    return null;
}
