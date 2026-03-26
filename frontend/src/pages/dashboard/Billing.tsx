import { useQuery } from '@tanstack/react-query';
import { billingService } from '@/services/api/billingService';
import { CreditCard, Calendar, Shield, ArrowUpRight, Loader2, AlertCircle } from 'lucide-react';
import { Button } from '@/components/ui/button';
import { useSearchParams, useNavigate } from 'react-router-dom';
import { useState, useEffect } from 'react';
import { useToast } from '@/hooks/use-toast';
import { useMutation } from '@tanstack/react-query';
import { Check, Zap, Crown } from 'lucide-react';

export default function Billing() {
    const { toast } = useToast();
    const navigate = useNavigate();
    const [searchParams] = useSearchParams();
    const reference = searchParams.get('reference');
    const transactionId = searchParams.get('transaction_id');
    const gateway = searchParams.get('gateway');

    useEffect(() => {
        if (reference || transactionId) {
            const params: any = {};
            if (reference) params.reference = reference;
            if (transactionId) params.transaction_id = transactionId;
            if (gateway) params.gateway = gateway;

            billingService.verifyPayment(params)
                .then(() => {
                    toast({ title: 'Payment Verified!', description: 'Your subscription is now active.' });
                    navigate('/billing', { replace: true });
                })
                .catch(() => {
                    toast({
                        title: 'Verification Failed',
                        description: 'We could not verify your payment. Please contact support.',
                        variant: 'destructive'
                    });
                });
        }
    }, [reference, transactionId, gateway]);

    const { data: billingInfo, isLoading: subLoading } = useQuery({
        queryKey: ['current-subscription'],
        queryFn: async () => {
            return await billingService.getCurrentSubscription();
        }
    });

    const subscription = billingInfo?.data;
    const activeGateway = billingInfo?.active_gateway || 'paystack';
    const gatewayName = activeGateway.charAt(0).toUpperCase() + activeGateway.slice(1);

    const { data: history, isLoading: historyLoading } = useQuery({
        queryKey: ['billing-history'],
        queryFn: billingService.getHistory
    });

    const { data: planResponse, isLoading: plansLoading } = useQuery({
        queryKey: ['billing-plans'],
        queryFn: billingService.getPlans,
        enabled: subscription?.status !== 'active'
    });

    const [selectedGateway, setSelectedGateway] = useState<string>('paystack');
    const [selectedPlanId, setSelectedPlanId] = useState<number | null>(null);

    useEffect(() => {
        if (billingInfo?.active_gateway && billingInfo.active_gateway !== 'both') {
            setSelectedGateway(billingInfo.active_gateway);
        }
    }, [billingInfo?.active_gateway]);

    const subscribeMutation = useMutation({
        mutationFn: ({ planId, gateway }: { planId: number, gateway: string }) => 
            billingService.subscribe(planId, planResponse?.detected_currency || 'USD', gateway),
        onSuccess: (res: any) => {
            if (res.data?.authorization_url) {
                window.location.href = res.data.authorization_url;
            } else if (res.message) {
                toast({ title: 'Success', description: res.message });
                window.location.href = '/dashboard';
            }
        },
        onError: (err: any) => {
            toast({
                title: 'Subscription failed',
                description: err.response?.data?.message || 'Transaction could not be initialized.',
                variant: 'destructive'
            });
        }
    });

    const isLoading = subLoading || historyLoading;

    if (isLoading) {
        return <div className="flex justify-center p-20"><Loader2 className="h-8 w-8 animate-spin text-[#0B3C91]" /></div>;
    }

    const plans = planResponse?.data || [];
    const activeGatewayChoice = planResponse?.gateways?.active || 'paystack';

    // Show plans if on free tier
    const showPlanSelector = subscription?.status !== 'active';

    return (
        <div className="max-w-4xl mx-auto space-y-8">
            <div className="flex items-center justify-between">
                <div>
                    <h1 className="text-3xl font-bold text-[#1A1A1A]">Billing & Subscription</h1>
                    <p className="text-[#6B7280] mt-1">Manage your plan, payment methods, and billing history.</p>
                </div>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                {/* Current Plan Card */}
                <div className="md:col-span-2 bg-white rounded-2xl border border-[#E5E7EB] shadow-sm overflow-hidden">
                    <div className="px-8 py-6 border-b border-[#E5E7EB] flex items-center justify-between bg-slate-50/50">
                        <h3 className="font-bold text-[#1A1A1A] flex items-center gap-2">
                            <Shield className="w-4 h-4 text-blue-500" /> Current Plan
                        </h3>
                        <span className={`px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider ${subscription?.status === 'active' ? 'bg-green-100 text-green-600' : 'bg-slate-100 text-slate-500'
                            }`}>
                            {subscription?.status || 'No Active Plan'}
                        </span>
                    </div>
                    <div className="p-8 space-y-6">
                        <div className="flex items-start justify-between">
                            <div>
                                <h4 className="text-2xl font-bold text-[#1A1A1A]">{subscription?.plan?.name || 'Free Tier'}</h4>
                                <p className="text-slate-500 text-sm mt-1">
                                    Next billing date: <span className="font-semibold text-slate-700">
                                        {subscription?.ends_at ? new Date(subscription.ends_at).toLocaleDateString() : 'N/A'}
                                    </span>
                                </p>
                            </div>
                            <Button variant="outline" onClick={() => navigate('/dashboard/pricing')} className="rounded-xl font-bold border-2">
                                Change Plan <ArrowUpRight className="ml-2 w-4 h-4" />
                            </Button>
                        </div>

                        <div className="grid grid-cols-2 gap-4 py-4">
                            <div className="p-4 bg-slate-50 rounded-xl space-y-1">
                                <p className="text-[10px] font-bold text-slate-400 uppercase">Billing Interval</p>
                                <p className="text-sm font-bold text-slate-700 capitalize">{subscription?.plan?.interval || 'Month'}</p>
                            </div>
                            <div className="p-4 bg-slate-50 rounded-xl space-y-1">
                                <p className="text-[10px] font-bold text-slate-400 uppercase">Currency</p>
                                <p className="text-sm font-bold text-slate-700">{subscription?.plan?.currency || 'USD'}</p>
                            </div>
                        </div>

                        {subscription?.status !== 'active' && !showPlanSelector && (
                            <div className="flex gap-3 p-4 bg-amber-50 rounded-xl text-amber-800 text-sm">
                                <AlertCircle className="w-5 h-5 shrink-0" />
                                <p>You are currently on the free tier. Upgrade below to unlock the full potential of GoPathway.</p>
                            </div>
                        )}
                    </div>
                </div>

                {/* Plan Selector Grid (Visible when on Free Tier) */}
                {showPlanSelector && (
                    <div className="md:col-span-2 space-y-6">
                        <div className="bg-white rounded-2xl border border-[#E5E7EB] shadow-sm p-8">
                            <h3 className="text-xl font-bold mb-6">Select an Upgrade Plan</h3>
                            
                            {plansLoading ? (
                                <div className="flex justify-center py-10"><Loader2 className="w-6 h-6 animate-spin text-primary" /></div>
                            ) : (
                                <div className="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
                                    {plans.filter((p: any) => p.tier !== 'free').map((plan: any) => (
                                        <div 
                                            key={plan.id}
                                            onClick={() => setSelectedPlanId(plan.id)}
                                            className={`p-6 rounded-2xl border-2 transition-all cursor-pointer relative ${
                                                selectedPlanId === plan.id 
                                                ? 'border-primary bg-primary/5 shadow-md' 
                                                : 'border-slate-100 hover:border-slate-200 bg-white'
                                            }`}
                                        >
                                            {selectedPlanId === plan.id && (
                                                <div className="absolute top-4 right-4 bg-primary text-white rounded-full p-1">
                                                    <Check className="w-3 h-3" />
                                                </div>
                                            )}
                                            <div className="flex items-center gap-2 mb-2">
                                                {plan.tier === 'premium' ? <Crown className="w-4 h-4 text-amber-500" /> : <Zap className="w-4 h-4 text-blue-500" />}
                                                <span className="font-bold text-slate-800">{plan.name}</span>
                                            </div>
                                            <div className="flex items-baseline gap-1 mb-2">
                                                <span className="text-2xl font-black">{plan.display_currency} {parseFloat(plan.display_price).toLocaleString()}</span>
                                                <span className="text-xs text-slate-400 font-medium">/{plan.interval === 'year' ? 'yr' : 'mo'}</span>
                                            </div>
                                            <p className="text-xs text-slate-500 line-clamp-2">{plan.description}</p>
                                        </div>
                                    ))}
                                </div>
                            )}

                            {selectedPlanId && (
                                <div className="space-y-6 animate-in fade-in slide-in-from-top-2 duration-300">
                                    {activeGatewayChoice === 'both' && (
                                        <div className="space-y-3">
                                            <p className="text-sm font-bold text-slate-700">Choose Payment Method:</p>
                                            <div className="flex flex-wrap gap-3">
                                                <Button 
                                                    variant={selectedGateway === 'paystack' ? 'default' : 'outline'}
                                                    className="rounded-xl"
                                                    onClick={() => setSelectedGateway('paystack')}
                                                >
                                                    Paystack (Cards/Bank)
                                                </Button>
                                                <Button 
                                                    variant={selectedGateway === 'flutterwave' ? 'default' : 'outline'}
                                                    className="rounded-xl"
                                                    onClick={() => setSelectedGateway('flutterwave')}
                                                >
                                                    Flutterwave (Mobile/Cards)
                                                </Button>
                                            </div>
                                        </div>
                                    )}

                                    <Button 
                                        className="w-full py-6 rounded-xl font-bold text-lg shadow-lg hover:shadow-xl transition-all"
                                        disabled={subscribeMutation.isPending}
                                        onClick={() => subscribeMutation.mutate({ planId: selectedPlanId, gateway: selectedGateway })}
                                    >
                                        {subscribeMutation.isPending ? <Loader2 className="w-5 h-5 animate-spin" /> : 'Complete Upgrade'}
                                    </Button>
                                </div>
                            )}
                        </div>
                    </div>
                )}

                {/* Quick Stats Sidebar */}
                <div className="space-y-6">
                    <div className="bg-[#1A1A1A] rounded-2xl p-6 text-white shadow-xl">
                        <div className="flex items-center gap-3 mb-4">
                            <CreditCard className="w-5 h-5 text-blue-400" />
                            <h4 className="font-bold">Payment Method</h4>
                        </div>
                        <p className="text-slate-400 text-xs mb-4">Payments are processed securely via {gatewayName}.</p>
                        <div className="p-4 bg-white/5 rounded-xl border border-white/10 text-sm text-center">
                            <p className="text-slate-300 font-medium">Managed by {gatewayName}</p>
                            <p className="text-[10px] text-slate-500 mt-1">Card details are stored securely on {gatewayName}'s servers</p>
                        </div>
                        <Button variant="ghost" onClick={() => navigate('/dashboard/pricing')} className="w-full mt-4 text-xs hover:bg-white/5 hover:text-white">
                            View All Pricing Plans
                        </Button>
                    </div>

                    <div className="bg-white rounded-2xl border border-[#E5E7EB] p-6 shadow-sm">
                        <div className="flex items-center gap-3 mb-4">
                            <Calendar className="w-5 h-5 text-indigo-500" />
                            <h4 className="font-bold">Billing History</h4>
                        </div>

                        {historyLoading ? (
                            <div className="flex justify-center py-4"><Loader2 className="h-5 w-5 animate-spin text-slate-300" /></div>
                        ) : history?.data?.length > 0 ? (
                            <div className="space-y-4">
                                {history.data.map((log: any) => (
                                    <div key={log.id} className="flex items-center justify-between text-sm">
                                        <div className="flex flex-col">
                                            <span className="text-slate-900 font-medium">{log.plan_name}</span>
                                            <span className="text-[10px] text-slate-400 uppercase tracking-tighter">
                                                {new Date(log.created_at).toLocaleDateString('en-GB', { month: 'short', year: 'numeric' })}
                                            </span>
                                        </div>
                                        <span className="font-bold">{log.currency} {parseFloat(log.amount).toFixed(0)}</span>
                                    </div>
                                ))}
                            </div>
                        ) : (
                            <div className="text-center py-6">
                                <p className="text-xs text-slate-400">No payment history found.</p>
                            </div>
                        )}

                        <Button 
                            variant="link" 
                            className="w-full mt-2 text-xs text-blue-600"
                            onClick={() => billingService.downloadHistory()}
                        >
                            Download All Invoices
                        </Button>
                    </div>
                </div>
            </div>
        </div>
    );
}
