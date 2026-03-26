import { useQuery } from '@tanstack/react-query';
import { billingService } from '@/services/api/billingService';
import { Link } from 'react-router-dom';
import { Check, Zap, Loader2 } from 'lucide-react';
import { Button } from '@/components/ui/button';
import { useAuth } from '@/hooks/useAuth';

export default function Pricing() {
    const { user } = useAuth();
    const { data: planResponse, isLoading } = useQuery({
        queryKey: ['public-plans'],
        queryFn: async () => {
            return await billingService.getPlans();
        }
    });

    const plans = planResponse?.data || [];
    const detectedCurrency = planResponse?.detected_currency || 'USD';

    if (isLoading) {
        return <div className="flex justify-center p-20"><Loader2 className="h-8 w-8 animate-spin text-[#0B3C91]" /></div>;
    }

    // Select exactly 3 plans for display: Free, Monthly, Yearly
    const freePlan = plans.find((p: any) => p.tier === 'free');
    const monthlyPlan = plans.find((p: any) => p.tier === 'starter' && p.interval === 'month') || plans.find((p: any) => p.interval === 'month' && p.tier !== 'free');
    const yearlyPlan = plans.find((p: any) => p.tier === 'premium' && p.interval === 'year') || plans.find((p: any) => p.interval === 'year');

    const displayPlans = [
        { ...freePlan, label: 'Free Forever', highlight: false },
        { ...monthlyPlan, label: 'Monthly Pro', highlight: true },
        { ...yearlyPlan, label: 'Yearly Premium', highlight: false, savings: 'Save 17%', subLabel: '2 Months Free' }
    ].filter(p => p.id);

    const currencySymbols: Record<string, string> = {
        'USD': '$',
        'NGN': '₦',
        'GBP': '£',
        'EUR': '€',
        'CAD': 'C$',
        'AUD': 'A$',
    };

    return (
        <div className="py-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div className="text-center mb-12">
                <h1 className="text-4xl font-bold text-[#1A1A1A] mb-3">Simple, Transparent Pricing</h1>
                <p className="text-[#6B7280] max-w-lg mx-auto">Start free, upgrade when you're ready. No hidden fees.</p>
            </div>

            <div className="flex flex-wrap justify-center gap-6 items-start">
                {displayPlans.map((plan: any) => {
                    const isFree = plan.tier === 'free';
                    const symbol = currencySymbols[plan.display_currency] || plan.display_currency || detectedCurrency;
                    const features = Array.isArray(plan.features) ? plan.features : JSON.parse(plan.features || '[]');

                    return (
                        <div
                            key={plan.id}
                            className={`w-full max-w-[340px] min-h-[580px] rounded-3xl border p-10 flex flex-col gap-8 transition-all duration-300 hover:translate-y-[-8px] ${plan.highlight
                                ? 'bg-[#0B3C91] text-white border-[#0B3C91] shadow-2xl shadow-blue-900/40 relative scale-[1.05] z-10'
                                : 'bg-white text-[#1A1A1A] border-[#E5E7EB] shadow-xl'
                                }`}
                        >
                            {plan.highlight && (
                                <div className="absolute -top-3 left-1/2 -translate-x-1/2">
                                    <span className="bg-[#00C2FF] text-[#0B3C91] text-xs font-bold px-3 py-1 rounded-full flex items-center gap-1">
                                        <Zap className="h-3 w-3" /> Most Popular
                                    </span>
                                </div>
                            )}
                            <div>
                                <div className="flex items-center justify-between mb-2">
                                    <h3 className={`font-bold text-lg ${plan.highlight ? 'text-blue-200' : 'text-[#6B7280]'}`}>{plan.label}</h3>
                                    {plan.savings && (
                                        <span className="bg-green-100 text-green-700 text-[10px] font-black px-2 py-0.5 rounded-full uppercase tracking-wider">
                                            {plan.savings}
                                        </span>
                                    )}
                                </div>
                                <div className="flex items-baseline gap-1">
                                    {isFree ? (
                                        <span className="text-4xl font-bold">Free</span>
                                    ) : (
                                        <>
                                            <span className="text-5xl font-black">{symbol}{parseFloat(plan.display_price).toLocaleString()}</span>
                                            <span className={`text-sm font-medium ${plan.highlight ? 'text-blue-200' : 'text-[#6B7280]'}`}>/{plan.interval === 'month' ? 'mo' : 'yr'}</span>
                                            {plan.subLabel && (
                                                <div className="block text-[10px] font-bold text-green-500 mt-1 uppercase tracking-tight italic">
                                                    ✨ {plan.subLabel}
                                                </div>
                                            )}
                                        </>
                                    )}
                                </div>
                                <p className={`text-sm mt-4 font-medium leading-relaxed ${plan.highlight ? 'text-blue-100/80' : 'text-[#6B7280]'}`}>{plan.description}</p>
                            </div>

                            <ul className="space-y-4 flex-1">
                                {features.map((f: string) => (
                                    <li key={f} className="flex items-start gap-3 text-sm font-medium">
                                        <Check className={`h-5 w-5 flex-shrink-0 mt-0.5 ${plan.highlight ? 'text-[#00C2FF]' : 'text-[#0B3C91]'}`} />
                                        <span className={plan.highlight ? 'text-blue-50' : 'text-slate-600'}>{f}</span>
                                    </li>
                                ))}
                            </ul>

                            <Link to={user ? '/dashboard/pricing' : '/register'}>
                                <Button
                                    className={`w-full py-7 rounded-2xl text-lg font-bold shadow-lg transition-transform hover:scale-[1.02] active:scale-[0.98] ${plan.highlight
                                        ? 'bg-[#00C2FF] hover:bg-[#00C2FF]/90 text-[#0B3C91]'
                                        : 'bg-[#0B3C91] hover:bg-[#0B3C91]/90 text-white shadow-blue-100'
                                        }`}
                                >
                                    {isFree ? 'Get Started' : (user ? 'Upgrade Now' : 'Subscribe Now')}
                                </Button>
                            </Link>
                        </div>
                    );
                })}
            </div>
        </div>
    );
}
