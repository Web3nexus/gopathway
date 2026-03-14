import { NavLink } from 'react-router-dom';
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
    Briefcase
} from 'lucide-react';
import { cn } from '@/lib/utils';

export function AdminSidebar() {
    const routes = [
        { name: 'Admin Overview', path: '/securegate', icon: Home },
        { name: 'Destinations', path: '/admin/countries', icon: Globe },
        { name: 'Visa & Checklists', path: '/admin/visas', icon: FileCheck },
        { name: 'Cost Templates', path: '/admin/costs', icon: DollarSign },
        { name: 'Subscription Plans', path: '/admin/plans', icon: CreditCard },
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
        { name: 'Support Messages', path: '/admin/support', icon: FileText },
        { name: 'SEO & Branding', path: '/admin/seo-settings', icon: Globe },
        { name: 'General Settings', path: '/admin/settings', icon: Settings },
    ];

    return (
        <div className="w-64 h-screen hidden lg:flex flex-col overflow-y-auto border-r bg-slate-900 text-slate-300 z-20">
            <div className="p-6">
                <h2 className="text-xl font-bold text-white flex items-center gap-2">
                    <div className="w-8 h-8 rounded-lg bg-blue-600 flex items-center justify-center text-sm">G</div>
                    GoPathway <span className="text-[10px] bg-blue-600/20 text-blue-400 px-1.5 py-0.5 rounded uppercase tracking-tighter">Admin</span>
                </h2>
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
                    >
                        <route.icon className="w-5 h-5" />
                        {route.name}
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
