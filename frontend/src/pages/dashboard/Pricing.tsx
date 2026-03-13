import { useQuery, useMutation } from '@tanstack/react-query';
import { billingService } from '@/services/api/billingService';
import { Check, Loader2, Zap, Shield, Crown } from 'lucide-react';
import { Button } from '@/components/ui/button';
import { useToast } from '@/hooks/use-toast';

export default function Pricing() {
    const { toast } = useToast();

    const { data: planResponse, isLoading } = useQuery({
        queryKey: ['billing-plans'],
        queryFn: async () => {
            return await billingService.getPlans();
        }
    });

    const plans = planResponse?.data || [];
    const detectedCurrency = planResponse?.detected_currency || 'USD';

    const subscribeMutation = useMutation({
        mutationFn: (planId: number) => billingService.subscribe(planId, detectedCurrency),
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

    if (isLoading) {
        return <div className="flex justify-center p-20"><Loader2 className="h-8 w-8 animate-spin text-[#0B3C91]" /></div>;
    }

    // Select specifically Free, Monthly, and Yearly plans
    const freePlan = plans.find((p: any) => p.tier === 'free');
    const monthlyPlan = plans.find((p: any) => p.tier === 'starter' && p.interval === 'month') || plans.find((p: any) => p.interval === 'month' && p.tier !== 'free');
    const yearlyPlan = plans.find((p: any) => p.tier === 'premium' && p.interval === 'year') || plans.find((p: any) => p.interval === 'year');

    const displayPlans = [
        { ...freePlan, label: 'Free Forever', highlight: false },
        { ...monthlyPlan, label: 'Monthly Pro', highlight: true },
        { ...yearlyPlan, label: 'Yearly Premium', highlight: false, savings: 'Save 17%', subLabel: '2 Months Free' }
    ].filter(p => !!p.id);

    const tierIcons: Record<string, any> = {
        free: Shield,
        starter: Zap,
        premium: Crown,
    };

    const currencySymbols: Record<string, string> = {
        'USD': '$',
        'NGN': '₦',
        'GBP': '£',
        'EUR': '€',
        'CAD': 'C$',
        'AUD': 'A$',
    };

    return (
        <div className="max-w-5xl mx-auto py-10">
            <div className="text-center mb-12 space-y-4">
                <h1 className="text-4xl font-extrabold text-[#1A1A1A]">Simple, Transparent Pricing</h1>
                <p className="text-[#6B7280] text-lg max-w-2xl mx-auto">
                    Choose the plan that's right for your relocation journey. Unlock expert tools and personalized guidance.
                </p>
            </div>

            <div className="flex flex-wrap justify-center gap-8 items-start">
                {displayPlans.map((plan: any) => {
                    const isFree = plan.tier === 'free';
                    const features = Array.isArray(plan.features) ? plan.features : JSON.parse(plan.features || '[]');
                    const TierIcon = tierIcons[plan.tier] || Zap;
                    const symbol = currencySymbols[plan.display_currency] || plan.display_currency || detectedCurrency;

                    return (
                        <div
                            key={plan.id}
                            className={`relative bg-white rounded-[40px] border w-full max-w-[340px] min-h-[620px] ${plan.highlight ? 'border-[#00C2FF] shadow-2xl shadow-blue-200/50 scale-[1.06] z-10' : 'border-[#E5E7EB] shadow-lg'} p-10 flex flex-col transition-all duration-300 hover:translate-y-[-8px]`}
                        >
                            {plan.highlight && (
                                <div className="absolute top-0 right-8 transform -translate-y-1/2 bg-gradient-to-r from-[#0B3C91] to-[#00C2FF] text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-widest shadow-lg">
                                    Most Popular
                                </div>
                            )}

                            <div className="mb-8">
                                <div className={`h-14 w-14 rounded-2xl ${plan.highlight ? 'bg-blue-50 text-[#00C2FF]' : 'bg-slate-50 text-slate-400'} flex items-center justify-center mb-8`}>
                                    <TierIcon className="w-7 h-7" />
                                </div>
                                <div className="flex items-center justify-between mb-2">
                                    <h3 className="text-2xl font-black text-[#1A1A1A]">{plan.label}</h3>
                                    {plan.savings && (
                                        <span className="bg-green-100 text-green-700 text-[10px] font-black px-2 py-0.5 rounded-full uppercase tracking-widest border border-green-200">
                                            {plan.savings}
                                        </span>
                                    )}
                                </div>
                                {plan.description && <p className="text-sm font-medium text-slate-500 mb-6 leading-relaxed">{plan.description}</p>}
                                <div className="flex items-baseline gap-1">
                                    {isFree ? (
                                        <span className="text-4xl font-extrabold text-[#1A1A1A]">Free</span>
                                    ) : (
                                        <>
                                            <span className="text-5xl font-black text-[#1A1A1A]">
                                                {symbol}{parseFloat(plan.display_price).toLocaleString()}
                                            </span>
                                            <span className="text-[#6B7280] font-bold text-sm">/{plan.interval === 'month' ? 'mo' : 'yr'}</span>
                                            {plan.subLabel && (
                                                <div className="text-xs font-bold text-green-600 mt-2 flex items-center gap-1">
                                                    <Zap className="w-3 h-3" /> {plan.subLabel}
                                                </div>
                                            )}
                                        </>
                                    )}
                                </div>
                            </div>

                            <ul className="space-y-4 mb-10 flex-1">
                                {features.map((feature: string, i: number) => (
                                    <li key={i} className="flex items-start gap-3">
                                        <div className={`mt-1 h-5 w-5 rounded-full ${plan.highlight ? 'bg-blue-50 text-[#00C2FF]' : 'bg-slate-50 text-slate-400'} flex items-center justify-center shrink-0`}>
                                            <Check className="w-3 h-3" />
                                        </div>
                                        <span className="text-sm text-slate-600 font-medium">{feature}</span>
                                    </li>
                                ))}
                            </ul>

                            <Button
                                onClick={() => subscribeMutation.mutate(plan.id)}
                                disabled={subscribeMutation.isPending || isFree}
                                className={`w-full py-6 rounded-2xl font-bold text-lg transition-all duration-300 ${plan.highlight
                                    ? 'bg-[#0B3C91] hover:bg-[#0A2A66] text-white shadow-lg shadow-blue-200'
                                    : isFree
                                        ? 'bg-slate-100 text-slate-400 cursor-default border-0'
                                        : 'bg-white border-2 border-[#E5E7EB] hover:border-[#0B3C91] text-[#1A1A1A] hover:text-[#0B3C91]'
                                    }`}
                            >
                                {subscribeMutation.isPending ? <Loader2 className="w-5 h-5 animate-spin mx-auto" /> : isFree ? 'Current Plan' : 'Upgrade Now'}
                            </Button>
                        </div>
                    );
                })}
            </div>

            <div className="mt-20 border-t border-[#E5E7EB] pt-12 flex flex-col md:flex-row items-center justify-between gap-8 bg-slate-50/50 p-10 rounded-3xl">
                <div>
                    <h4 className="text-lg font-bold text-[#1A1A1A] mb-1">Secure Payment Processing</h4>
                    <p className="text-sm text-[#6B7280]">We process all payments securely via Paystack. Your payment data is never stored on our servers.</p>
                </div>
                <div className="flex items-center gap-3 text-sm font-bold text-slate-400">
                    <Shield className="w-5 h-5" /> Powered by Paystack
                </div>
            </div>
        </div >
    );
}
