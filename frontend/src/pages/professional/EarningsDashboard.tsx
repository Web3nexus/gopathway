import { useState } from 'react';
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import { professionalService } from '@/services/api/professionalService';
import { useToast } from '@/hooks/use-toast';
import {
    DollarSign,
    Wallet,
    ArrowUpRight,
    ArrowDownRight,
    Clock,
    CheckCircle2,
    History,
    Loader2
} from 'lucide-react';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';
import {
    Dialog,
    DialogContent,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
    DialogDescription
} from '@/components/ui/dialog';

export default function EarningsDashboard() {
    const { toast } = useToast();
    const queryClient = useQueryClient();
    const [isWithdrawOpen, setIsWithdrawOpen] = useState(false);
    const [withdrawAmount, setWithdrawAmount] = useState('');
    const [payoutDetails, setPayoutDetails] = useState('');

    const { data: earningsData, isLoading } = useQuery({
        queryKey: ['expert-earnings'],
        queryFn: professionalService.getEarnings,
    });

    const withdrawMutation = useMutation({
        mutationFn: professionalService.requestWithdrawal,
        onSuccess: () => {
            toast({ title: 'Withdrawal requested successfully' });
            setIsWithdrawOpen(false);
            setWithdrawAmount('');
            setPayoutDetails('');
            queryClient.invalidateQueries({ queryKey: ['expert-earnings'] });
        },
        onError: (error: any) => {
            toast({
                title: 'Withdrawal request failed',
                description: error.response?.data?.message || 'Check your balance and amount',
                variant: 'destructive',
            });
        }
    });

    const handleWithdraw = () => {
        withdrawMutation.mutate({
            amount: parseFloat(withdrawAmount),
            currency: 'USD',
            payout_details: payoutDetails,
        });
    };

    if (isLoading) {
        return (
            <div className="max-w-6xl mx-auto p-6 space-y-6 animate-pulse">
                <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div className="h-32 bg-white rounded-2xl border border-[#E5E7EB]" />
                    <div className="h-32 bg-white rounded-2xl border border-[#E5E7EB]" />
                    <div className="h-32 bg-white rounded-2xl border border-[#E5E7EB]" />
                </div>
                <div className="h-96 bg-white rounded-2xl border border-[#E5E7EB]" />
            </div>
        );
    }

    const { stats, transactions, withdrawals } = earningsData || { stats: {}, transactions: [], withdrawals: [] };

    return (
        <div className="max-w-6xl mx-auto p-6 space-y-8">
            <div className="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h1 className="text-3xl font-black text-[#1A1A1A] tracking-tight">Earnings Dashboard</h1>
                    <p className="text-[#6B7280]">Manage your income, view transaction history, and request withdrawals.</p>
                </div>
                <Dialog open={isWithdrawOpen} onOpenChange={setIsWithdrawOpen}>
                    <DialogTrigger asChild>
                        <Button className="bg-[#0B3C91] hover:bg-[#0B3C91]/90 font-bold h-12 px-6 rounded-xl">
                            <Wallet className="mr-2 h-5 w-5" /> Withdraw Funds
                        </Button>
                    </DialogTrigger>
                    <DialogContent className="sm:max-w-[425px] rounded-3xl">
                        <DialogHeader>
                            <DialogTitle className="text-2xl font-black text-[#1A1A1A]">Request Withdrawal</DialogTitle>
                            <DialogDescription>
                                Available Balance: <span className="font-bold text-green-600">${stats?.available_balance?.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</span>
                            </DialogDescription>
                        </DialogHeader>
                        <div className="grid gap-4 py-4">
                            <div className="space-y-2">
                                <label className="text-sm font-bold text-[#1A1A1A]">Amount to Withdraw (USD)</label>
                                <Input
                                    type="number"
                                    min="10"
                                    max={stats?.available_balance}
                                    value={withdrawAmount}
                                    onChange={(e) => setWithdrawAmount(e.target.value)}
                                    placeholder="e.g. 100.00"
                                    className="rounded-xl border-[#E5E7EB] h-12"
                                />
                                <p className="text-xs text-muted-foreground">Minimum withdrawal is $10.00.</p>
                            </div>
                            <div className="space-y-2">
                                <label className="text-sm font-bold text-[#1A1A1A]">Payout Details</label>
                                <Textarea
                                    placeholder="Enter your bank account details, PayPal email, or Crypto address..."
                                    value={payoutDetails}
                                    onChange={(e) => setPayoutDetails(e.target.value)}
                                    className="rounded-xl border-[#E5E7EB] min-h-[100px]"
                                />
                            </div>
                        </div>
                        <Button
                            onClick={handleWithdraw}
                            disabled={withdrawMutation.isPending || !withdrawAmount || !payoutDetails || parseFloat(withdrawAmount) > stats?.available_balance}
                            className="bg-[#0B3C91] hover:bg-[#0B3C91]/90 w-full font-bold h-12 rounded-xl"
                        >
                            {withdrawMutation.isPending ? <Loader2 className="h-5 w-5 animate-spin mx-auto" /> : 'Submit Request'}
                        </Button>
                    </DialogContent>
                </Dialog>
            </div>

            {/* Stats Row */}
            <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                <Card className="rounded-3xl border-transparent shadow-lg bg-gradient-to-br from-[#0B3C91] to-[#0A2A66] text-white overflow-hidden relative">
                    <div className="absolute -right-10 -bottom-10 w-40 h-40 bg-white/10 rounded-full blur-2xl pointer-events-none" />
                    <CardContent className="p-8 relative z-10">
                        <div className="flex justify-between items-start mb-4">
                            <div className="p-3 bg-white/10 rounded-xl backdrop-blur-md">
                                <DollarSign className="h-6 w-6 text-white" />
                            </div>
                        </div>
                        <p className="text-blue-200 font-medium mb-1 tracking-wide">Available Balance</p>
                        <h3 className="text-4xl font-black">${stats?.available_balance?.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</h3>
                    </CardContent>
                </Card>

                <Card className="rounded-3xl border border-[#E5E7EB] shadow-sm bg-white">
                    <CardContent className="p-8">
                        <div className="flex justify-between items-start mb-4">
                            <div className="p-3 bg-blue-50 rounded-xl">
                                <ArrowUpRight className="h-6 w-6 text-[#0B3C91]" />
                            </div>
                        </div>
                        <p className="text-[#6B7280] font-medium mb-1">Total Earnings (Net)</p>
                        <h3 className="text-3xl font-black text-[#1A1A1A]">${stats?.total_earnings?.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</h3>
                    </CardContent>
                </Card>

                <Card className="rounded-3xl border border-[#E5E7EB] shadow-sm bg-white">
                    <CardContent className="p-8">
                        <div className="flex justify-between items-start mb-4">
                            <div className="p-3 bg-amber-50 rounded-xl">
                                <Clock className="h-6 w-6 text-amber-500" />
                            </div>
                        </div>
                        <p className="text-[#6B7280] font-medium mb-1">Pending Withdrawals</p>
                        <h3 className="text-3xl font-black text-[#1A1A1A]">${stats?.pending_withdrawals?.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</h3>
                    </CardContent>
                </Card>
            </div>

            {/* Transactions & History */}
            <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {/* Earnings History */}
                <div className="lg:col-span-2 space-y-4">
                    <h2 className="text-xl font-bold flex items-center gap-2 text-[#1A1A1A] mb-4">
                        <History className="h-5 w-5 text-[#0B3C91]" /> Transaction History
                    </h2>
                    <div className="bg-white rounded-3xl border border-[#E5E7EB] shadow-sm overflow-hidden">
                        {transactions?.length > 0 ? (
                            <div className="divide-y divide-[#E5E7EB]">
                                {transactions.map((tx: any) => (
                                    <div key={tx.id} className="p-6 flex items-center justify-between hover:bg-slate-50 transition-colors">
                                        <div className="flex items-center gap-4">
                                            <div className="h-12 w-12 rounded-full bg-green-50 flex items-center justify-center shrink-0">
                                                <ArrowDownRight className="h-5 w-5 text-green-600" />
                                            </div>
                                            <div>
                                                <p className="font-bold text-[#1A1A1A]">{tx.description}</p>
                                                <p className="text-sm text-[#6B7280]">From: {tx.user_name} • {new Date(tx.date).toLocaleDateString()}</p>
                                            </div>
                                        </div>
                                        <div className="text-right">
                                            <p className="font-black text-green-600 text-lg">+ ${tx.net_amount}</p>
                                            <p className="text-xs text-[#6B7280] mt-0.5">${tx.commission} fee deducted</p>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        ) : (
                            <div className="p-12 text-center text-[#6B7280]">
                                <History className="h-12 w-12 mx-auto mb-4 opacity-20" />
                                <p>No transactions found.</p>
                            </div>
                        )}
                    </div>
                </div>

                {/* Withdrawal History */}
                <div className="space-y-4">
                    <h2 className="text-xl font-bold flex items-center gap-2 text-[#1A1A1A] mb-4">
                        <Wallet className="h-5 w-5 text-[#0B3C91]" /> Withdrawals
                    </h2>
                    <div className="bg-white rounded-3xl border border-[#E5E7EB] shadow-sm overflow-hidden p-6">
                        {withdrawals?.length > 0 ? (
                            <div className="space-y-6">
                                {withdrawals.map((wd: any) => (
                                    <div key={wd.id} className="flex flex-col gap-2 pb-6 border-b border-slate-100 last:border-0 last:pb-0">
                                        <div className="flex justify-between items-start">
                                            <span className="font-bold text-[#1A1A1A]">${wd.amount}</span>
                                            <Badge variant={wd.status === 'pending' ? 'outline' : wd.status === 'approved' || wd.status === 'processed' ? 'default' : 'destructive'}>
                                                {wd.status}
                                            </Badge>
                                        </div>
                                        <p className="text-xs text-[#6B7280]">
                                            Requested on {new Date(wd.created_at).toLocaleDateString()}
                                        </p>
                                    </div>
                                ))}
                            </div>
                        ) : (
                            <div className="text-center text-[#6B7280] py-6">
                                <p className="text-sm">No withdrawals requested yet.</p>
                            </div>
                        )}
                    </div>
                </div>
            </div>
        </div>
    );
}
