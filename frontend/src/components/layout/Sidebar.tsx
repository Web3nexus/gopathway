import { NavLink, Link } from 'react-router-dom';
import { Home, Compass, Folder, Calculator, Settings, HelpCircle, FileText, CreditCard, MessageSquare, Lock, Sparkles, Scale, Share2, GraduationCap, Map, Briefcase, DollarSign } from 'lucide-react';
import { cn } from '@/lib/utils';
import { useAuth } from '@/hooks/useAuth';
import { useFeatures } from '@/hooks/useFeatures';

export function Sidebar() {
    const { user } = useAuth();
    const { canAccessFeature } = useFeatures();
    const isAdmin = user?.roles?.some((role: any) => role.name === 'admin');
    const routes = [
        { name: 'Dashboard', path: '/dashboard', icon: Home },
        { name: 'Pathway Planner', path: '/pathway', icon: Compass },
        { name: 'Cost Planner', path: '/cost', icon: Calculator, slug: 'cost-planner' },
        { name: 'Document Vault', path: '/documents', icon: Folder, slug: 'document-vault' },
        { name: 'SOP Builder', path: '/sop-builder', icon: Sparkles, slug: 'sop-builder' },
        { name: 'Checklists', path: '/relocation-hub', icon: FileText, slug: 'settlement-checklist' },
        { name: 'School Explorer', path: '/school-explorer', icon: GraduationCap, slug: 'school-explorer' },
        { name: 'Job Search', path: '/job-search', icon: Briefcase },
        { name: 'CV Builder', path: '/cv-builder', icon: FileText },
        { name: 'Residency Roadmap', path: '/residency', icon: Map },
        { name: 'Compare Countries', path: '/compare', icon: Scale },
        { name: 'Experts', path: '/experts', icon: FileText },
        { name: 'Messaging', path: '/dashboard/messages', icon: MessageSquare, slug: 'messaging' },
        ...(user?.roles?.some((role: any) => role.name === 'Professional') ? [{ name: 'Earnings', path: '/professional/earnings', icon: DollarSign }] : []),
        { name: 'Referral Program', path: '/dashboard/referrals', icon: Share2 },
        { name: 'Billing', path: '/billing', icon: CreditCard },
        { name: 'Settings', path: '/dashboard/settings', icon: Settings },
        { name: 'Help & Support', path: '/support', icon: HelpCircle },
    ];

    return (
        <div className="w-64 h-full hidden lg:flex flex-col overflow-y-auto border-r bg-white/60 backdrop-blur-xl dark:bg-black/40 z-20">
            <div className="p-6">
                <h2 className="text-2xl font-bold bg-gradient-to-r from-primary to-accent bg-clip-text text-transparent">
                    GoPathway
                </h2>
            </div>

            <nav className="flex-1 px-4 space-y-2 mt-4">
                {routes.map((route) => {
                    const restricted = route.slug && !canAccessFeature(route.slug);

                    return (
                        <NavLink
                            key={route.path}
                            to={restricted ? '#' : route.path}
                            onClick={(e) => {
                                if (restricted) {
                                    e.preventDefault();
                                    // Optionally toast
                                }
                            }}
                            className={({ isActive }) =>
                                cn(
                                    'flex items-center justify-between px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200',
                                    isActive && !restricted
                                        ? 'bg-primary/10 text-primary'
                                        : 'text-muted-foreground hover:bg-black/5 dark:hover:bg-white/5 hover:text-foreground',
                                    restricted && 'opacity-60 cursor-not-allowed'
                                )
                            }
                        >
                            <div className="flex items-center gap-3">
                                <route.icon className="w-5 h-5" />
                                {route.name}
                            </div>
                            {restricted && <Lock className="w-3.5 h-3.5 text-slate-400" />}
                        </NavLink>
                    );
                })}
            </nav>

            {!isAdmin && !user?.is_premium && (
                <div className="p-4 border-t border-border/50">
                    <div className="glass-card rounded-xl p-4 text-center">
                        <p className="text-sm font-medium text-foreground mb-1">Upgrade to Premium</p>
                        <p className="text-xs text-muted-foreground mb-3">Unlock advanced tools</p>
                        <Link to="/pricing">
                            <button className="w-full bg-primary hover:bg-primary/90 text-primary-foreground text-xs py-2 rounded-lg font-semibold transition-colors">
                                Upgrade Now
                            </button>
                        </Link>
                    </div>
                </div>
            )}
        </div>
    );
}
