import { Link, Outlet, useLocation } from 'react-router-dom';
import { Button } from '@/components/ui/button';

import { useQuery } from '@tanstack/react-query';
import { publicService } from '@/services/api/publicService';

const navLinks = [
    { to: '/', label: 'Home' },
    { to: '/countries', label: 'Countries' },
    { to: '/blog', label: 'Blog' },
    { to: '/pricing', label: 'Pricing' },
];

export function PublicLayout() {
    const { pathname } = useLocation();

    const { data: settingsData } = useQuery({
        queryKey: ['public-settings'],
        queryFn: publicService.getSettings,
        staleTime: 1000 * 60 * 60,
    });
    
    const settings = settingsData?.data || {};
    const logoUrl = settings.site_logo;
    const siteTitle = settings.site_meta_title?.split('-')[0]?.trim() || 'GoPathway';

    return (
        <div className="min-h-screen flex flex-col bg-[#F5F7FA]">
            {/* Top Nav */}
            <header className="sticky top-0 z-50 bg-white border-b border-[#E5E7EB] shadow-sm">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                    <Link to="/" className="flex items-center gap-2 font-bold text-lg text-[#0B3C91]">
                        {logoUrl ? (
                            <img src={logoUrl} alt={siteTitle} className="h-8 object-contain" />
                        ) : (
                            <div className="h-8 w-8 rounded-lg bg-[#0B3C91] flex items-center justify-center text-white text-sm font-bold">GP</div>
                        )}
                        {siteTitle}
                    </Link>
                    <nav className="hidden md:flex items-center gap-6">
                        {navLinks.map(({ to, label }) => (
                            <Link
                                key={to}
                                to={to}
                                className={`text-sm font-medium transition-colors hover:text-[#0B3C91] ${pathname === to ? 'text-[#0B3C91]' : 'text-[#6B7280]'}`}
                            >
                                {label}
                            </Link>
                        ))}
                    </nav>
                    <div className="flex items-center gap-3">
                        <Link to="/login">
                            <Button variant="ghost" size="sm">Log in</Button>
                        </Link>
                        <Link to="/register">
                            <Button size="sm" className="bg-[#0B3C91] hover:bg-[#0B3C91]/90">Get Started</Button>
                        </Link>
                    </div>
                </div>
            </header>

            {/* Page Content */}
            <main className="flex-1">
                <Outlet />
            </main>

            {/* Footer */}
            <footer className="bg-[#1A1A1A] text-white py-12 mt-auto">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div>
                            <div className="flex items-center gap-2 font-bold text-lg mb-3">
                                {logoUrl ? (
                                    <img src={logoUrl} alt={siteTitle} className="h-7 object-contain" />
                                ) : (
                                    <div className="h-7 w-7 rounded-md bg-[#00C2FF] flex items-center justify-center text-[#0B3C91] text-xs font-bold">GP</div>
                                )}
                                {siteTitle}
                            </div>
                            <p className="text-sm text-gray-400">Your premium relocation intelligence platform. Plan, track, and execute your global move.</p>
                        </div>
                        <div>
                            <h4 className="font-semibold mb-3 text-sm tracking-wider uppercase text-gray-400">Platform</h4>
                            <ul className="space-y-2 text-sm text-gray-300">
                                <li><Link to="/countries" className="hover:text-white transition-colors">Countries</Link></li>
                                <li><Link to="/blog" className="hover:text-white transition-colors">Blog</Link></li>
                                <li><Link to="/pricing" className="hover:text-white transition-colors">Pricing</Link></li>
                                <li><Link to="/register" className="hover:text-white transition-colors">Get Started</Link></li>
                            </ul>
                        </div>
                        <div>
                            <h4 className="font-semibold mb-3 text-sm tracking-wider uppercase text-gray-400">Legal & Help</h4>
                            <ul className="space-y-2 text-sm text-gray-300">
                                <li><Link to="/privacy" className="hover:text-white transition-colors">Privacy Policy</Link></li>
                                <li><Link to="/terms" className="hover:text-white transition-colors">Terms of Service</Link></li>
                                <li><Link to="/documentation" className="hover:text-white transition-colors">Documentation</Link></li>
                            </ul>
                        </div>
                    </div>
                    <div className="border-t border-gray-700 mt-8 pt-6 text-center text-xs text-gray-500">
                        © {new Date().getFullYear()} GoPathway.net — All rights reserved.
                    </div>
                </div>
            </footer>
        </div>
    );
}
