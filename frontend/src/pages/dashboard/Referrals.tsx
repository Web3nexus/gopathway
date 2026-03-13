import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import api from '@/lib/api';
import { Users, DollarSign, Share2, Copy, CheckCircle2, Clock, History, Landmark } from 'lucide-react';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { useToast } from '@/hooks/use-toast';
import { useState, useEffect } from 'react';

export default function Referrals() {
    const { toast } = useToast();
    const [copying, setCopying] = useState(false);

    const queryClient = useQueryClient();
    const [payoutMethod, setPayoutMethod] = useState('paypal');
    const [payoutDetails, setPayoutDetails] = useState<Record<string, string>>({});
    const [bankCountry, setBankCountry] = useState('nigeria');

    const { data: stats, isLoading: loadingStats } = useQuery({
        queryKey: ['referral-stats'],
        queryFn: () => api.get('/api/v1/referral/stats').then(res => res.data),
    });

    useEffect(() => {
        if (stats) {
            setPayoutMethod(stats.payout_method || 'paypal');
            if (stats.payout_details) {
                setPayoutDetails(stats.payout_details);
                setBankCountry(stats.payout_details.country === 'nigeria' ? 'nigeria' : 'international');
            }
        }
    }, [stats]);

    const { data: history } = useQuery({
        queryKey: ['referral-history'],
        queryFn: () => api.get('/api/v1/referral/history').then(res => res.data),
    });

    const setDetail = (key: string, val: string) =>
        setPayoutDetails(prev => ({ ...prev, [key]: val }));

    const isPayoutValid = () => {
        if (payoutMethod === 'paypal') return !!payoutDetails.email;
        if (payoutMethod === 'bank') {
            const base = payoutDetails.account_name && payoutDetails.bank_name && payoutDetails.account_number && payoutDetails.country;
            if (bankCountry === 'international') return !!base && !!payoutDetails.swift_bic;
            return !!base;
        }
        return false;
    };

    const updatePayoutMutation = useMutation({
        mutationFn: () => api.put('/api/v1/referral/payout', {
            payout_method: payoutMethod,
            payout_details: { ...payoutDetails, country: bankCountry === 'nigeria' ? 'nigeria' : (payoutDetails.country || '') }
        }),
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['referral-stats'] });
            toast({ title: 'Saved!', description: 'Payout details updated successfully.' });
        },
        onError: () => {
            toast({ title: 'Error', description: 'Please fill in all required fields.', variant: 'destructive' });
        }
    });

    const copyToClipboard = () => {
        if (!stats?.referral_link) return;
        setCopying(true);
        navigator.clipboard.writeText(stats.referral_link);
        toast({
            title: "Copied!",
            description: "Referral link copied to clipboard.",
        });
        setTimeout(() => setCopying(false), 2000);
    };

    if (loadingStats) {
        return <div className="p-8 text-center">Loading referral data...</div>;
    }

    return (
        <div className="max-w-6xl mx-auto space-y-8 pb-12">
            <div>
                <h1 className="text-3xl font-black text-[#1A1A1A] mb-2">Referral Program</h1>
                <p className="text-slate-500">Invite your friends to GoPathway and earn {stats?.commission_rate}% commission on their payments.</p>
            </div>

            {/* Link Sharing Card */}
            <div className="bg-gradient-to-br from-[#0B3C91] to-[#0A2A66] rounded-3xl p-8 text-white relative overflow-hidden shadow-xl">
                <div className="absolute top-[-20%] right-[-10%] w-64 h-64 bg-blue-400/20 rounded-full blur-3xl" />
                <div className="relative z-10 grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
                    <div>
                        <div className="inline-flex items-center gap-2 px-3 py-1 bg-white/10 rounded-full text-xs font-bold uppercase tracking-widest mb-4">
                            <Share2 className="h-3 w-3" /> Share the love
                        </div>
                        <h2 className="text-2xl font-black mb-4">Your Unique Invite Link</h2>
                        <div className="flex items-center gap-2 bg-white/10 p-2 rounded-2xl backdrop-blur-md border border-white/10">
                            <code className="flex-1 px-3 text-sm font-mono truncate">{stats?.referral_link}</code>
                            <Button
                                onClick={copyToClipboard}
                                className="bg-white text-[#0B3C91] hover:bg-white/90 rounded-xl font-bold h-10 px-6 shrink-0"
                            >
                                {copying ? <CheckCircle2 className="h-4 w-4 mr-2" /> : <Copy className="h-4 w-4 mr-2" />}
                                {copying ? 'Copied' : 'Copy Link'}
                            </Button>
                        </div>
                    </div>
                    <div className="grid grid-cols-2 gap-4 mt-8 lg:mt-0">
                        <div className="bg-white/10 p-4 rounded-2xl backdrop-blur-sm border border-white/5">
                            <p className="text-blue-200 text-[10px] font-black uppercase tracking-widest mb-1">Total Clicks</p>
                            <p className="text-2xl font-black">{stats?.stats?.total_clicks || 0}</p>
                        </div>
                        <div className="bg-white/10 p-4 rounded-2xl backdrop-blur-sm border border-white/5">
                            <p className="text-blue-200 text-[10px] font-black uppercase tracking-widest mb-1">Conversion Rate</p>
                            <p className="text-2xl font-black">
                                {stats?.stats?.total_clicks > 0
                                    ? Math.round((stats.stats.total_referrals / stats.stats.total_clicks) * 100)
                                    : 0}%
                            </p>
                        </div>
                        <div className="bg-white/10 p-4 rounded-2xl backdrop-blur-sm border border-white/5">
                            <p className="text-blue-200 text-[10px] font-black uppercase tracking-widest mb-1">Total Referrals</p>
                            <p className="text-2xl font-black">{stats?.stats?.total_referrals || 0}</p>
                        </div>
                        <div className="bg-white/10 p-4 rounded-2xl backdrop-blur-sm border border-white/5">
                            <p className="text-blue-200 text-[10px] font-black uppercase tracking-widest mb-1">Total Earned</p>
                            <p className="text-2xl font-black">${stats?.stats?.total_commissions?.toLocaleString() || 0}</p>
                        </div>
                    </div>
                </div>
            </div>

            {/* Detailed Stats */}
            <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div className="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
                    <div className="h-12 w-12 rounded-2xl bg-blue-50 flex items-center justify-center mb-4">
                        <Users className="h-6 w-6 text-blue-600" />
                    </div>
                    <h3 className="text-lg font-bold text-[#1A1A1A]">Friends Invited</h3>
                    <p className="text-3xl font-black text-blue-600 mt-1">{stats?.stats?.total_referrals || 0}</p>
                </div>
                <div className="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
                    <div className="h-12 w-12 rounded-2xl bg-amber-50 flex items-center justify-center mb-4">
                        <Clock className="h-6 w-6 text-amber-600" />
                    </div>
                    <h3 className="text-lg font-bold text-[#1A1A1A]">Pending Payout</h3>
                    <p className="text-3xl font-black text-amber-600 mt-1">${stats?.stats?.pending_commissions?.toLocaleString() || 0}</p>
                </div>
                <div className="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
                    <div className="h-12 w-12 rounded-2xl bg-green-50 flex items-center justify-center mb-4">
                        <DollarSign className="h-6 w-6 text-green-600" />
                    </div>
                    <h3 className="text-lg font-bold text-[#1A1A1A]">Already Paid</h3>
                    <p className="text-3xl font-black text-green-600 mt-1">${stats?.stats?.paid_commissions?.toLocaleString() || 0}</p>
                </div>
            </div>

            {/* Payout Settings */}
            <div className="bg-white rounded-3xl border border-slate-100 shadow-sm p-8">
                <h3 className="text-xl font-bold flex items-center gap-2 mb-6">
                    <Landmark className="h-6 w-6 text-blue-600" /> Payout Settings
                </h3>
                <div className="space-y-2 max-w-xs">
                    <Label>Payout Method</Label>
                    <Select value={payoutMethod} onValueChange={m => { setPayoutMethod(m); setPayoutDetails({}); }}>
                        <SelectTrigger className="h-12 rounded-xl">
                            <SelectValue placeholder="Select method" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem value="paypal">PayPal</SelectItem>
                            <SelectItem value="bank">Bank Transfer</SelectItem>
                        </SelectContent>
                    </Select>
                </div>

                {payoutMethod === 'paypal' && (
                    <div className="space-y-2 mt-4 max-w-md">
                        <Label>PayPal Email Address <span className="text-red-500">*</span></Label>
                        <Input value={payoutDetails.email || ''} onChange={e => setDetail('email', e.target.value)} placeholder="hello@paypal.com" className="h-11 rounded-xl" />
                    </div>
                )}

                {payoutMethod === 'bank' && (
                    <div className="mt-4 space-y-4">
                        <div className="flex gap-2">
                            <button onClick={() => setBankCountry('nigeria')} className={`flex-1 py-2.5 rounded-xl text-sm font-bold border transition-all ${bankCountry === 'nigeria' ? 'bg-blue-600 text-white border-blue-600' : 'border-slate-200 text-slate-500 hover:border-slate-300'}`}>🇳🇬 Nigerian Bank</button>
                            <button onClick={() => setBankCountry('international')} className={`flex-1 py-2.5 rounded-xl text-sm font-bold border transition-all ${bankCountry === 'international' ? 'bg-blue-600 text-white border-blue-600' : 'border-slate-200 text-slate-500 hover:border-slate-300'}`}>🌍 International Bank</button>
                        </div>
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div className="space-y-2">
                                <Label>Account Name <span className="text-red-500">*</span></Label>
                                <Input value={payoutDetails.account_name || ''} onChange={e => setDetail('account_name', e.target.value)} placeholder="Full name as on account" className="h-11 rounded-xl" />
                            </div>
                            <div className="space-y-2">
                                <Label>Bank Name <span className="text-red-500">*</span></Label>
                                <Input value={payoutDetails.bank_name || ''} onChange={e => setDetail('bank_name', e.target.value)} placeholder={bankCountry === 'nigeria' ? 'e.g. First Bank, GTBank' : 'e.g. Barclays, Chase'} className="h-11 rounded-xl" />
                            </div>
                            <div className="space-y-2">
                                <Label>Account Number <span className="text-red-500">*</span></Label>
                                <Input value={payoutDetails.account_number || ''} onChange={e => setDetail('account_number', e.target.value)} placeholder={bankCountry === 'nigeria' ? '10-digit NUBAN number' : 'Your account number'} className="h-11 rounded-xl" />
                            </div>
                            {bankCountry === 'international' && (
                                <div className="space-y-2">
                                    <Label>Bank Country <span className="text-red-500">*</span></Label>
                                    <Input value={payoutDetails.country || ''} onChange={e => setDetail('country', e.target.value)} placeholder="e.g. United Kingdom" className="h-11 rounded-xl" />
                                </div>
                            )}
                        </div>
                        {bankCountry === 'international' && (
                            <div className="space-y-4 pt-4 border-t border-slate-100">
                                <p className="text-[10px] font-black text-slate-400 uppercase tracking-widest">International Transfer Details</p>
                                <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div className="space-y-2">
                                        <Label>SWIFT / BIC Code <span className="text-red-500">*</span></Label>
                                        <Input value={payoutDetails.swift_bic || ''} onChange={e => setDetail('swift_bic', e.target.value)} placeholder="e.g. BARCGB22XXX" className="h-11 rounded-xl" />
                                    </div>
                                    <div className="space-y-2">
                                        <Label>IBAN <span className="text-slate-400 text-xs font-normal">(Europe/Middle East)</span></Label>
                                        <Input value={payoutDetails.iban || ''} onChange={e => setDetail('iban', e.target.value)} placeholder="e.g. GB29NWBK60161331926819" className="h-11 rounded-xl" />
                                    </div>
                                    <div className="space-y-2">
                                        <Label>Routing / ABA No. <span className="text-slate-400 text-xs font-normal">(USA)</span></Label>
                                        <Input value={payoutDetails.routing_number || ''} onChange={e => setDetail('routing_number', e.target.value)} placeholder="e.g. 021000021" className="h-11 rounded-xl" />
                                    </div>
                                    <div className="space-y-2">
                                        <Label>Sort Code <span className="text-slate-400 text-xs font-normal">(UK)</span></Label>
                                        <Input value={payoutDetails.sort_code || ''} onChange={e => setDetail('sort_code', e.target.value)} placeholder="e.g. 20-00-00" className="h-11 rounded-xl" />
                                    </div>
                                    <div className="space-y-2 md:col-span-2">
                                        <Label>Bank Branch Address <span className="text-slate-400 text-xs font-normal">(optional)</span></Label>
                                        <Input value={payoutDetails.bank_address || ''} onChange={e => setDetail('bank_address', e.target.value)} placeholder="Full branch address" className="h-11 rounded-xl" />
                                    </div>
                                </div>
                            </div>
                        )}
                    </div>
                )}

                <div className="mt-6 flex justify-end">
                    <Button
                        onClick={() => updatePayoutMutation.mutate()}
                        disabled={updatePayoutMutation.isPending || !isPayoutValid()}
                        className="bg-blue-600 hover:bg-blue-700 text-white font-bold h-12 px-8 rounded-xl"
                    >
                        {updatePayoutMutation.isPending ? 'Saving...' : 'Save Payout Details'}
                    </Button>
                </div>
            </div>

            {/* History Table */}
            <div className="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
                <div className="px-8 py-6 border-b border-slate-50 flex items-center justify-between">
                    <h3 className="text-lg font-bold flex items-center gap-2">
                        <History className="h-5 w-5 text-slate-400" /> Earnings History
                    </h3>
                </div>
                <div className="overflow-x-auto">
                    <table className="w-full text-left">
                        <thead>
                            <tr className="bg-slate-50 text-[10px] font-black uppercase tracking-widest text-slate-400">
                                <th className="px-8 py-4">Friend</th>
                                <th className="px-8 py-4">Commission</th>
                                <th className="px-8 py-4">Status</th>
                                <th className="px-8 py-4">Date</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-slate-50">
                            {history?.data?.length > 0 ? (
                                history.data.map((row: any) => (
                                    <tr key={row.id} className="text-sm">
                                        <td className="px-8 py-4 font-bold text-[#1A1A1A]">{row.referred_user?.name || 'Anonymous'}</td>
                                        <td className="px-8 py-4 text-blue-600 font-bold">${row.amount}</td>
                                        <td className="px-8 py-4">
                                            <span className={`inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider ${row.status === 'paid' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700'
                                                }`}>
                                                {row.status}
                                            </span>
                                        </td>
                                        <td className="px-8 py-4 text-slate-500">{new Date(row.created_at).toLocaleDateString()}</td>
                                    </tr>
                                ))
                            ) : (
                                <tr>
                                    <td colSpan={4} className="px-8 py-12 text-center text-slate-400">No earnings history yet. Start sharing!</td>
                                </tr>
                            )}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    );
}
