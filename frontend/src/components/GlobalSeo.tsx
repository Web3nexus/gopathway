import React, { useEffect } from 'react';
import { Helmet } from 'react-helmet-async';
import { useQuery } from '@tanstack/react-query';
import { publicService } from '@/services/api/publicService';

export function GlobalSeo() {
    const { data } = useQuery({
        queryKey: ['public-settings'],
        queryFn: publicService.getSettings,
        staleTime: 1000 * 60 * 60, // 1 hour stale time for settings
    });

    const settings = data?.data || {};

    const title = settings.site_meta_title || 'GoPathway - Relocation & Immigration Made Easy';
    const description = settings.site_meta_description || 'Discover your pathway to a new country with GoPathway.';
    const ogImage = settings.site_og_image || '';
    const favicon = settings.site_favicon || '/favicon.ico';

    useEffect(() => {
        // Dynamically update favicon
        let link: HTMLLinkElement | null = document.querySelector("link[rel~='icon']");
        if (!link) {
            link = document.createElement('link');
            link.rel = 'icon';
            document.head.appendChild(link);
        }
        if (favicon) {
            link.href = favicon.startsWith('http') || favicon.startsWith('/') ? favicon : `/${favicon}`;
        }
    }, [favicon]);

    return (
        <Helmet>
            <title>{title}</title>
            <meta name="description" content={description} />
            <meta property="og:title" content={title} />
            <meta property="og:description" content={description} />
            {ogImage && <meta property="og:image" content={ogImage} />}
        </Helmet>
    );
}
