import { useQuery } from '@tanstack/react-query';
import { Globe, Users, FileCheck, TrendingUp, ShieldCheck } from 'lucide-react';
import { Link } from 'react-router-dom';
import { adminService } from '@/services/api/adminService';
import { StatCard } from '@/components/dashboard/StatCard';
import { Button } from '@/components/ui/button';

interface AdminStats {
    total_users: number;
    active_countries: number;
    total_pathways: number;
    pending_documents: number;
}

export default function AdminDashboard() {
    const { data: stats } = useQuery<AdminStats>({
        queryKey: ['admin-stats'],
        queryFn: adminService.getDashboardStats,
    });

    return (
        <div className="space-y-8">
            <div className="flex items-center justify-between">
                <div>
                    <h1 className="text-3xl font-bold text-slate-900">Admin Control Center</h1>
                    <p className="text-slate-500 mt-1">Platform-wide overview and management</p>
                </div>
                <div className="flex items-center gap-3">
                    <div className="px-4 py-2 bg-white border rounded-xl flex items-center gap-2 text-sm font-medium text-slate-600 shadow-sm">
                        <ShieldCheck className="w-4 h-4 text-green-500" />
                        Admin Access Verified
                    </div>
                </div>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <StatCard
                    title="Total Relocators"
                    value={stats?.total_users || 0}
                    icon={Users}
                    trend={{ value: 12, isPositive: true }}
                />
                <StatCard
                    title="Active Destinations"
                    value={stats?.active_countries || 0}
                    icon={Globe}
                />
                <StatCard
                    title="Pathways Defined"
                    value={stats?.total_pathways || 0}
                    icon={TrendingUp}
                />
                <StatCard
                    title="Pending Documents"
                    value={stats?.pending_documents || 0}
                    icon={FileCheck}
                    trend={{ value: 5, isPositive: false }}
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
                            <Link to="/admin/costs" className="group p-6 rounded-2xl border border-slate-100 bg-slate-50 hover:bg-blue-600 transition-all duration-300">
                                <TrendingUp className="w-8 h-8 text-blue-600 group-hover:text-white mb-4 transition-colors" />
                                <div className="font-bold text-slate-900 group-hover:text-white transition-colors">Configure Costs</div>
                                <p className="text-sm text-slate-500 group-hover:text-blue-100 transition-colors mt-1">Set fee templates for pathways</p>
                            </Link>
                            <Link to="/admin/documents" className="group p-6 rounded-2xl border border-slate-100 bg-slate-50 hover:bg-blue-600 transition-all duration-300">
                                <FileCheck className="w-8 h-8 text-blue-600 group-hover:text-white mb-4 transition-colors" />
                                <div className="font-bold text-slate-900 group-hover:text-white transition-colors">Manage Documents</div>
                                <p className="text-sm text-slate-500 group-hover:text-blue-100 transition-colors mt-1">Define required files and evidence</p>
                            </Link>
                            <Link to="/admin/verifications" className="group p-6 rounded-2xl border border-slate-100 bg-slate-50 hover:bg-blue-600 transition-all duration-300">
                                <ShieldCheck className="w-8 h-8 text-blue-600 group-hover:text-white mb-4 transition-colors" />
                                <div className="font-bold text-slate-900 group-hover:text-white transition-colors">Professional Verifications</div>
                                <p className="text-sm text-slate-500 group-hover:text-blue-100 transition-colors mt-1">Review and approve specialist credentials</p>
                            </Link>
                        </div>
                    </div>
                </div>

                <div className="space-y-6">
                    <div className="bg-slate-900 rounded-2xl p-8 text-white shadow-xl shadow-slate-200">
                        <h3 className="text-lg font-bold mb-4">System Alerts</h3>
                        <div className="space-y-4">
                            <div className="flex gap-3">
                                <div className="w-2 h-2 rounded-full bg-amber-400 mt-2 shrink-0" />
                                <div>
                                    <p className="text-sm font-medium">Canada Visa Fees Updated</p>
                                    <p className="text-xs text-slate-400 mt-0.5">Automated update from IRCC source</p>
                                </div>
                            </div>
                            <div className="flex gap-3">
                                <div className="w-2 h-2 rounded-full bg-blue-400 mt-2 shrink-0" />
                                <div>
                                    <p className="text-sm font-medium">New User Registration Spark</p>
                                    <p className="text-xs text-slate-400 mt-0.5">+48% increase this week</p>
                                </div>
                            </div>
                        </div>
                        <Button variant="outline" className="w-full mt-8 bg-transparent border-slate-700 text-white hover:bg-slate-800">
                            View All Logs
                        </Button>
                    </div>
                </div>
            </div>
        </div>
    );
}
