import { useQuery } from '@tanstack/react-query';
import { Link } from 'react-router-dom';
import { adminService } from '@/services/api/adminService';
import { StatCard } from '@/components/dashboard/StatCard';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { useCurrency } from '@/contexts/CurrencyContext';
import { 
    Globe, 
    Users, 
    FileCheck, 
    TrendingUp, 
    ShieldCheck, 
    GraduationCap, 
    Award, 
    CreditCard, 
    DollarSign, 
    MessageSquare,
    AlertCircle,
    Activity
} from 'lucide-react';

interface AdminStats {
    total_users: number;
    active_countries: number;
    total_pathways: number;
    pending_documents: number;
    schools_count: number;
    scholarships_count: number;
    total_subscriptions: number;
    total_revenue: number;
    experts_count: number;
    unattended_support: number;
}

export default function AdminDashboard() {
    const { formatCurrency } = useCurrency();
    const { data: stats } = useQuery<AdminStats>({
        queryKey: ['admin-stats'],
        queryFn: adminService.getDashboardStats,
        refetchInterval: 30000,
    });

    const { data: health } = useQuery({
        queryKey: ['system-health'],
        queryFn: adminService.getSystemHealth,
        refetchInterval: 60000,
    });

    return (
        <div className="space-y-8">
            <div className="flex items-center justify-between">
                <div>
                    <h1 className="text-3xl font-bold text-slate-900">Admin Control Center</h1>
                    <p className="text-slate-500 mt-1">Platform-wide overview and management</p>
                </div>
                <div className="flex items-center gap-3">
                    <Link to="/admin/system-health">
                        <Button variant="outline" className="gap-2 border-slate-200 text-slate-600 hover:bg-slate-50">
                            <Activity className="w-4 h-4" />
                            System Health
                        </Button>
                    </Link>
                    <div className="px-4 py-2 bg-white border rounded-xl flex items-center gap-2 text-sm font-medium text-slate-600 shadow-sm">
                        <ShieldCheck className="w-4 h-4 text-green-500" />
                        Admin Access Verified
                    </div>
                </div>
            </div>

            {/* Primary Stats */}
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <StatCard
                    title="Total Revenue"
                    value={formatCurrency(stats?.total_revenue || 0)}
                    icon={DollarSign}
                    trend={{ value: 24, isPositive: true }}
                />
                <StatCard
                    title="Active Subscriptions"
                    value={stats?.total_subscriptions || 0}
                    icon={CreditCard}
                />
                <StatCard
                    title="Unattended Support"
                    value={stats?.unattended_support || 0}
                    icon={MessageSquare}
                    trend={{ value: stats?.unattended_support || 0, isPositive: false }}
                />
                <StatCard
                    title="Verified Experts"
                    value={stats?.experts_count || 0}
                    icon={Award}
                />
            </div>

            {/* Secondary Stats */}
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <StatCard
                    title="Total Relocators"
                    value={stats?.total_users || 0}
                    icon={Users}
                />
                <StatCard
                    title="Schools Listed"
                    value={stats?.schools_count || 0}
                    icon={GraduationCap}
                />
                <StatCard
                    title="Scholarships"
                    value={stats?.scholarships_count || 0}
                    icon={Award}
                />
                <StatCard
                    title="Pending Documents"
                    value={stats?.pending_documents || 0}
                    icon={FileCheck}
                />
            </div>

            <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div className="lg:col-span-2 space-y-6">
                    <div className="bg-white rounded-2xl border p-8 shadow-sm">
                        <h3 className="text-lg font-bold text-slate-900 mb-6">Quick Actions</h3>
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <Link to="/admin/countries" className="group p-6 rounded-2xl border border-slate-100 bg-slate-50 hover:bg-blue-600 transition-all duration-300">
                                <Globe className="w-8 h-8 text-blue-600 group-hover:text-white mb-4 transition-colors" />
                                <div className="font-bold text-slate-900 group-hover:text-white transition-colors">Manage Destinations</div>
                                <p className="text-sm text-slate-500 group-hover:text-blue-100 transition-colors mt-1">Update countries and visa information</p>
                            </Link>
                            <Link to="/admin/users" className="group p-6 rounded-2xl border border-slate-100 bg-slate-50 hover:bg-blue-600 transition-all duration-300">
                                <Users className="w-8 h-8 text-blue-600 group-hover:text-white mb-4 transition-colors" />
                                <div className="font-bold text-slate-900 group-hover:text-white transition-colors">User Management</div>
                                <p className="text-sm text-slate-500 group-hover:text-blue-100 transition-colors mt-1">Oversee users and manage subscriptions</p>
                            </Link>
                            <Link to="/admin/scholarships" className="group p-6 rounded-2xl border border-slate-100 bg-slate-50 hover:bg-blue-600 transition-all duration-300">
                                <Award className="w-8 h-8 text-blue-600 group-hover:text-white mb-4 transition-colors" />
                                <div className="font-bold text-slate-900 group-hover:text-white transition-colors">Scholarship Hub</div>
                                <p className="text-sm text-slate-500 group-hover:text-blue-100 transition-colors mt-1">Manage global funding opportunities</p>
                            </Link>
                            <Link to="/admin/support" className="group p-6 rounded-2xl border border-slate-100 bg-slate-50 hover:bg-blue-600 transition-all duration-300">
                                <MessageSquare className="w-8 h-8 text-blue-600 group-hover:text-white mb-4 transition-colors" />
                                <div className="font-bold text-slate-900 group-hover:text-white transition-colors">Support Requests</div>
                                <p className="text-sm text-slate-500 group-hover:text-blue-100 transition-colors mt-1">Respond to user inquiries ({stats?.unattended_support || 0} unread)</p>
                            </Link>
                        </div>
                    </div>
                </div>

                <div className="space-y-6">
                    <div className="bg-slate-900 rounded-2xl p-8 text-white shadow-xl shadow-slate-200">
                        <div className="flex items-center justify-between mb-6">
                            <h3 className="text-lg font-bold">System Alerts</h3>
                            <Badge className="bg-amber-500/20 text-amber-500 border-none">Live Feed</Badge>
                        </div>
                        <div className="space-y-6">
                            {health?.alerts && health.alerts.length > 0 ? (
                                health.alerts.map((alert: any) => (
                                    <div key={alert.id} className="flex gap-3">
                                        <AlertCircle className={`w-5 h-5 mt-0.5 shrink-0 ${alert.severity === 'high' ? 'text-rose-500' : 'text-amber-500'}`} />
                                        <div>
                                            <p className="text-sm font-medium">{alert.type}</p>
                                            <p className="text-xs text-slate-400 mt-1 line-clamp-2">{alert.message}</p>
                                            <p className="text-[10px] text-slate-500 mt-1.5">{alert.timestamp}</p>
                                        </div>
                                    </div>
                                ))
                            ) : (
                                <div className="text-center py-8">
                                    <ShieldCheck className="w-12 h-12 text-slate-700 mx-auto mb-3 opacity-20" />
                                    <p className="text-sm text-slate-500">No critical alerts detected.</p>
                                </div>
                            )}
                        </div>
                        <Link to="/admin/system-health">
                            <Button variant="outline" className="w-full mt-8 bg-transparent border-slate-700 text-white hover:bg-slate-800">
                                Full System Diagnotics
                            </Button>
                        </Link>
                    </div>

                    <div className="bg-white rounded-2xl border p-6 shadow-sm">
                        <h4 className="text-sm font-bold text-slate-900 mb-4 flex items-center gap-2">
                            <Activity className="w-4 h-4 text-blue-600" />
                            Live Metrics
                        </h4>
                        <div className="space-y-4">
                            <div>
                                <div className="flex justify-between text-xs mb-1.5">
                                    <span className="text-slate-500 font-medium">Memory Usage</span>
                                    <span className="text-slate-900 font-bold">{health?.metrics?.memory?.percentage || 0}%</span>
                                </div>
                                <div className="h-1.5 w-full bg-slate-100 rounded-full overflow-hidden">
                                    <div 
                                        className="h-full bg-blue-600 rounded-full transition-all duration-1000" 
                                        style={{ width: `${health?.metrics?.memory?.percentage || 0}%` }}
                                    />
                                </div>
                            </div>
                            <div className="flex justify-between items-center py-2 border-t border-slate-50">
                                <span className="text-xs text-slate-500">API Health</span>
                                <Badge variant="secondary" className="bg-green-50 text-green-600 border-none text-[10px] uppercase font-bold">Operational</Badge>
                            </div>
                            <div className="flex justify-between items-center py-2 border-t border-slate-50">
                                <span className="text-xs text-slate-500">DB Latency</span>
                                <span className="text-xs font-bold text-slate-900">{health?.metrics?.database?.latency || '0ms'}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}
