import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import api from '@/lib/api';
import { Users, DollarSign, Edit2, CheckCircle2, Search, History, TrendingUp } from 'lucide-react';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { useToast } from '@/hooks/use-toast';
import { useState } from 'react';

export default function ReferralManagement() {
    const { toast } = useToast();
    const queryClient = useQueryClient();
    const [searchTerm, setSearchTerm] = useState('');
    const [editingUserId, setEditingUserId] = useState<number | null>(null);
    const [newRate, setNewRate] = useState<string>('');

    const { data: referrers, isLoading: loadingReferrers } = useQuery({
        queryKey: ['admin-referrers'],
        queryFn: () => api.get('/api/v1/admin/referrals').then(res => res.data),
    });

    const { data: commissions, isLoading: loadingCommissions } = useQuery({
        queryKey: ['admin-commissions'],
        queryFn: () => api.get('/api/v1/admin/referrals/commissions').then(res => res.data),
    });

    const updateRateMutation = useMutation({
        mutationFn: ({ userId, rate }: { userId: number, rate: number }) =>
            api.put(`/api/v1/admin/users/${userId}/commission-rate`, { commission_rate: rate }),
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['admin-referrers'] });
            toast({ title: "Success", description: "Commission rate updated." });
            setEditingUserId(null);
        },
    });

    const triggerPayoutMutation = useMutation({
        mutationFn: (commissionId: number) =>
            api.post(`/api/v1/admin/referrals/commissions/${commissionId}/trigger-payout`),
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['admin-commissions'] });
            toast({ title: "Initiated", description: "Automated payout sequence started." });
        },
        onError: (err: any) => {
            toast({ title: "Failed", description: err.response?.data?.message || "Could not initiate payout", variant: 'destructive' });
        }
    });

    const markAsPaidMutation = useMutation({
        mutationFn: (commissionId: number) =>
            api.post(`/api/v1/admin/referrals/commissions/${commissionId}/pay`),
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['admin-commissions'] });
            toast({ title: "Paid", description: "Commission marked as paid." });
        },
    });

    if (loadingReferrers || loadingCommissions) {
        return <div className="p-8 text-center">Loading management data...</div>;
    }

    return (
        <div className="space-y-8 pb-12">
            <div>
                <h1 className="text-3xl font-black text-[#1A1A1A] mb-2">Referral Management</h1>
                <p className="text-slate-500">Manage influencer commission rates and payout history.</p>
            </div>

            {/* Quick Stats */}
            <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div className="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
                    <div className="h-12 w-12 rounded-2xl bg-blue-50 flex items-center justify-center mb-4">
                        <Users className="h-6 w-6 text-blue-600" />
                    </div>
                    <h3 className="text-sm font-bold text-slate-500 uppercase tracking-wider">Total Referrers</h3>
                    <p className="text-3xl font-black text-[#1A1A1A] mt-1">{referrers?.total || 0}</p>
                </div>
                <div className="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
                    <div className="h-12 w-12 rounded-2xl bg-green-50 flex items-center justify-center mb-4">
                        <TrendingUp className="h-6 w-6 text-green-600" />
                    </div>
                    <h3 className="text-sm font-bold text-slate-500 uppercase tracking-wider">Total Commissions Paid</h3>
                    <p className="text-3xl font-black text-[#1A1A1A] mt-1">${(commissions?.data?.reduce((acc: number, c: any) => c.status === 'paid' ? acc + parseFloat(c.amount) : acc, 0) || 0).toLocaleString()}</p>
                </div>
                <div className="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
                    <div className="h-12 w-12 rounded-2xl bg-amber-50 flex items-center justify-center mb-4">
                        <DollarSign className="h-6 w-6 text-amber-600" />
                    </div>
                    <h3 className="text-sm font-bold text-slate-500 uppercase tracking-wider">Pending Payouts</h3>
                    <p className="text-3xl font-black text-[#1A1A1A] mt-1">${(commissions?.data?.reduce((acc: number, c: any) => c.status === 'pending' ? acc + parseFloat(c.amount) : acc, 0) || 0).toLocaleString()}</p>
                </div>
            </div>

            {/* Referrers Table */}
            <div className="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
                <div className="px-8 py-6 border-b border-slate-50 flex items-center justify-between">
                    <h3 className="text-lg font-bold flex items-center gap-2">Referrers & Influencers</h3>
                    <div className="relative w-64">
                        <Search className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-400" />
                        <Input
                            placeholder="Search referrers..."
                            className="pl-10 rounded-xl"
                            value={searchTerm}
                            onChange={(e) => setSearchTerm(e.target.value)}
                        />
                    </div>
                </div>
                <div className="overflow-x-auto">
                    <table className="w-full text-left">
                        <thead>
                            <tr className="bg-slate-50 text-[10px] font-black uppercase tracking-widest text-slate-400">
                                <th className="px-8 py-4">Name / Email</th>
                                <th className="px-8 py-4">Total Referrals</th>
                                <th className="px-8 py-4">Total Commission</th>
                                <th className="px-8 py-4">Current Rate</th>
                                <th className="px-8 py-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-slate-50">
                            {Array.isArray(referrers?.data) && referrers.data.map((user: any) => (
                                <tr key={user.id} className="text-sm hover:bg-slate-50/50 transition-colors">
                                    <td className="px-8 py-4">
                                        <div className="font-bold text-[#1A1A1A]">{user.name}</div>
                                        <div className="text-xs text-slate-400">{user.email}</div>
                                    </td>
                                    <td className="px-8 py-4 text-[#1A1A1A] font-bold">{user.referrals_count}</td>
                                    <td className="px-8 py-4 text-blue-600 font-bold">${user.commissions_sum_amount || 0}</td>
                                    <td className="px-8 py-4">
                                        {editingUserId === user.id ? (
                                            <div className="flex items-center gap-2">
                                                <Input
                                                    type="number"
                                                    className="w-20 h-8 rounded-lg"
                                                    value={newRate}
                                                    onChange={(e) => setNewRate(e.target.value)}
                                                />
                                                <Button size="sm" onClick={() => updateRateMutation.mutate({ userId: user.id, rate: parseFloat(newRate) })}>Save</Button>
                                                <Button size="sm" variant="ghost" onClick={() => setEditingUserId(null)}>Cancel</Button>
                                            </div>
                                        ) : (
                                            <div className="flex items-center gap-2">
                                                <span className="font-bold text-[#1A1A1A]">{user.commission_rate}%</span>
                                                <Button
                                                    variant="ghost"
                                                    size="icon"
                                                    className="h-8 w-8 text-slate-400"
                                                    onClick={() => {
                                                        setEditingUserId(user.id);
                                                        setNewRate(user.commission_rate.toString());
                                                    }}
                                                >
                                                    <Edit2 className="h-3 w-3" />
                                                </Button>
                                            </div>
                                        )}
                                    </td>
                                    <td className="px-8 py-4">
                                        <Button variant="outline" size="sm" className="rounded-xl font-bold">Details</Button>
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>
            </div>

            {/* Commissions History */}
            <div className="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
                <div className="px-8 py-6 border-b border-slate-50">
                    <h3 className="text-lg font-bold flex items-center gap-2">
                        <History className="h-5 w-5 text-slate-400" /> Recent Commissions
                    </h3>
                </div>
                <div className="overflow-x-auto">
                    <table className="w-full text-left">
                        <thead>
                            <tr className="bg-slate-50 text-[10px] font-black uppercase tracking-widest text-slate-400">
                                <th className="px-8 py-4">Referrer</th>
                                <th className="px-8 py-4">New User</th>
                                <th className="px-8 py-4">Amount</th>
                                <th className="px-8 py-4">Status</th>
                                <th className="px-8 py-4">Date</th>
                                <th className="px-8 py-4">Action</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-slate-50">
                            {Array.isArray(commissions?.data) && commissions.data.map((row: any) => (
                                <tr key={row.id} className="text-sm">
                                    <td className="px-8 py-4">
                                        <div className="font-bold text-[#1A1A1A]">{row.referrer?.name}</div>
                                        <div className="text-[10px] text-slate-400">{row.referrer?.email}</div>
                                        {row.referrer?.payout_method && row.referrer?.payout_details && (
                                            <div className="mt-2 space-y-0.5">
                                                <div className="inline-flex items-center gap-1.5 px-2 py-1 bg-slate-50 border border-slate-100 rounded text-[10px] font-bold uppercase text-slate-500">
                                                    {row.referrer.payout_method === 'paypal' ? '💳 PayPal' : '🏦 Bank Transfer'}
                                                </div>
                                                {row.referrer.payout_method === 'paypal' && (
                                                    <div className="text-xs text-slate-600">{row.referrer.payout_details.email}</div>
                                                )}
                                                {row.referrer.payout_method === 'bank' && (
                                                    <>
                                                        <div className="text-xs font-semibold text-slate-800">{row.referrer.payout_details.account_name}</div>
                                                        <div className="text-xs text-slate-600">{row.referrer.payout_details.bank_name} — {row.referrer.payout_details.account_number}</div>
                                                        {row.referrer.payout_details.swift_bic && <div className="text-[10px] text-slate-400">SWIFT: {row.referrer.payout_details.swift_bic}</div>}
                                                        {row.referrer.payout_details.iban && <div className="text-[10px] text-slate-400">IBAN: {row.referrer.payout_details.iban}</div>}
                                                        {row.referrer.payout_details.routing_number && <div className="text-[10px] text-slate-400">Routing: {row.referrer.payout_details.routing_number}</div>}
                                                        {row.referrer.payout_details.sort_code && <div className="text-[10px] text-slate-400">Sort: {row.referrer.payout_details.sort_code}</div>}
                                                        {row.referrer.payout_details.country && <div className="text-[10px] text-slate-400">Country: {row.referrer.payout_details.country}</div>}
                                                    </>
                                                )}
                                            </div>
                                        )}
                                    </td>
                                    <td className="px-8 py-4 font-bold text-[#1A1A1A]">{row.referred_user?.name}</td>
                                    <td className="px-8 py-4 text-blue-600 font-bold">${row.amount}</td>
                                    <td className="px-8 py-4">
                                        <span className={`inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider ${row.status === 'paid' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700'
                                            }`}>
                                            {row.status}
                                        </span>
                                    </td>
                                    <td className="px-8 py-4 text-slate-500">{new Date(row.created_at).toLocaleDateString()}</td>
                                    <td className="px-8 py-4 text-right">
                                        {row.status === 'pending' && (
                                            <div className="flex justify-end gap-2">
                                                {row.referrer?.payout_method === 'bank' && (
                                                    <Button
                                                        size="sm"
                                                        className="bg-blue-600 hover:bg-blue-700 rounded-xl font-bold"
                                                        onClick={() => triggerPayoutMutation.mutate(row.id)}
                                                        disabled={triggerPayoutMutation.isPending}
                                                    >
                                                        {triggerPayoutMutation.isPending ? 'Initiating...' : 'Pay via Flutterwave'}
                                                    </Button>
                                                )}
                                                <Button
                                                    size="sm"
                                                    variant="outline"
                                                    className="rounded-xl font-bold"
                                                    onClick={() => markAsPaidMutation.mutate(row.id)}
                                                >
                                                    Mark Paid
                                                </Button>
                                            </div>
                                        )}
                                        {row.status === 'processing' && (
                                            <span className="text-xs text-blue-600 font-bold animate-pulse">Processing...</span>
                                        )}
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    );
}
