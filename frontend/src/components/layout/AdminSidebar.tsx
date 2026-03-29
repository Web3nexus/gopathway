import { NavLink } from 'react-router-dom';
import { useQuery } from '@tanstack/react-query';
import { adminService } from '@/services/api/adminService';
import {
    Home,
    Globe,
    DollarSign,
    FileCheck,
    Users,
    Settings,
    Calendar,
    Share2,
    CreditCard,
    FileText,
    BookOpen,
    CheckSquare,
    GraduationCap,
    Briefcase,
    MessageSquare,
    Mail,
    User,
    X,
    Award,
    Database,
    Activity
} from 'lucide-react';
import { cn } from '@/lib/utils';
import { Button } from '@/components/ui/button';

export function AdminSidebar({ mobile, onClose }: { mobile?: boolean, onClose?: () => void }) {
    const { data: stats } = useQuery({
        queryKey: ['admin-stats'],
        queryFn: adminService.getDashboardStats,
        refetchInterval: 30000,
    });

    const routes = [
        { name: 'Admin Overview', path: '/securegate', icon: Home },
        { name: 'System Health', path: '/admin/system-health', icon: Activity },
        { name: 'Destinations', path: '/admin/countries', icon: Globe },
        { name: 'Visa & Checklists', path: '/admin/visas', icon: FileCheck },
        { name: 'Cost Templates', path: '/admin/costs', icon: DollarSign },
        { name: 'Subscription Plans', path: '/admin/plans', icon: CreditCard },
        { name: 'Subscription History', path: '/admin/subscriptions', icon: FileText },
        { name: 'Feature Management', path: '/admin/features', icon: Settings },
        { name: 'Bookings', path: '/admin/bookings', icon: Calendar },
        { name: 'Referral Management', path: '/admin/referrals', icon: Share2 },
        { name: 'Blog Management', path: '/admin/blog', icon: FileText },
        { name: 'Relocation Hub', path: '/admin/relocation', icon: BookOpen },
        { name: 'Settlement Checklist', path: '/admin/settlement', icon: CheckSquare },
        { name: 'School Management', path: '/admin/schools', icon: GraduationCap },
        { name: 'Residency & Career', path: '/admin/career', icon: Briefcase },
        { name: 'User Management', path: '/admin/users', icon: Users },
        { name: 'Expert Payouts', path: '/admin/expert-withdrawals', icon: DollarSign },
        { name: 'Finance Management', path: '/admin/finance', icon: DollarSign },
        { 
            name: 'Support Messages', 
            path: '/admin/support', 
            icon: MessageSquare, 
            badge: stats?.unattended_support > 0 ? stats.unattended_support : null 
        },
        { name: 'SEO & Branding', path: '/admin/seo-settings', icon: Globe },
        { name: 'Email Management', path: '/admin/email-management', icon: Mail },
        { name: 'Scholarships', path: '/admin/scholarships', icon: Award },
        { name: 'Scholarship Sources', path: '/admin/scholarship-sources', icon: Database },
        { name: 'General Settings', path: '/admin/settings', icon: Settings },
        { name: 'My Profile', path: '/admin/profile', icon: User },
    ];

    return (
        <div className={cn(
            "h-full flex flex-col overflow-y-auto border-r bg-slate-900 text-slate-300 z-20",
            mobile ? "w-72" : "w-64 hidden lg:flex"
        )}>
            <div className="p-6 flex items-center justify-between">
                <h2 className="text-xl font-bold text-white flex items-center gap-2">
                    <div className="w-8 h-8 rounded-lg bg-blue-600 flex items-center justify-center text-sm">G</div>
                    GoPathway <span className="text-[10px] bg-blue-600/20 text-blue-400 px-1.5 py-0.5 rounded uppercase tracking-tighter">Admin</span>
                </h2>
                {mobile && (
                    <Button
                        variant="ghost"
                        size="icon"
                        onClick={onClose}
                        className="text-slate-400 hover:text-white"
                    >
                        <X className="w-5 h-5" />
                    </Button>
                )}
            </div>

            <nav className="flex-1 px-4 space-y-1 mt-4">
                {routes.map((route) => (
                    <NavLink
                        key={route.path}
                        to={route.path}
                        end={route.path === '/securegate'}
                        className={({ isActive }) =>
                            cn(
                                'flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors',
                                isActive
                                    ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/20'
                                    : 'hover:bg-slate-800 hover:text-white'
                            )
                        }
                        onClick={() => {
                            if (mobile && onClose) onClose();
                        }}
                    >
                        <route.icon className="w-5 h-5 shrink-0" />
                        <span className="flex-1">{route.name}</span>
                        {(route as any).badge && (
                            <span className="inline-flex items-center justify-center w-5 h-5 text-[10px] font-bold bg-rose-500 text-white rounded-full shrink-0">
                                {(route as any).badge > 9 ? '9+' : (route as any).badge}
                            </span>
                        )}
                    </NavLink>
                ))}
            </nav>

            <div className="p-4 border-t border-slate-800">
                <div className="bg-slate-800/50 rounded-xl p-4">
                    <p className="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1">System Status</p>
                    <div className="flex items-center gap-2 text-xs text-green-400">
                        <div className="w-2 h-2 rounded-full bg-green-400 animate-pulse" />
                        API Operational
                    </div>
                </div>
            </div>
        </div>
    );
}
