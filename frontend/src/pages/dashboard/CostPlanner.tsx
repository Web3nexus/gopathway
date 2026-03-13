import { useState, useMemo } from 'react';
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import { Link } from 'react-router-dom';
import { costService } from '@/services/api/costService';
import { profileService } from '@/services/api/profileService';
import { pathwayService } from '@/services/api/pathwayService';
import { BarChart, Bar, XAxis, YAxis, Tooltip, ResponsiveContainer, Cell } from 'recharts';
import { Wallet, TrendingUp, AlertCircle, Users, Home, Clock, MapPin, Loader2, Calculator, Lock, Sparkles, Calendar, ArrowRight } from 'lucide-react';
import { useFeatures } from '@/hooks/useFeatures';
import { useCurrency } from '@/contexts/CurrencyContext';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import { SavingsGoalCard } from '@/components/dashboard/SavingsGoalCard';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from "@/components/ui/dialog";
import { toast } from '@/hooks/use-toast';

const CITY_MULTIPLIERS: Record<string, number> = {
    london: 1.4,
    manchester: 1.1,
    edinburgh: 1.15,
    toronto: 1.3,
    berlin: 1.0,
    lisbon: 0.85,
    madrid: 0.9,
};

const HOUSING_MULTIPLIERS: Record<string, number> = {
    shared: 0.7,
    private: 1.0,
    serviced: 1.5,
};

const CITY_TO_COUNTRY_MAP: Record<string, number> = {
    london: 1,
    manchester: 1,
    edinburgh: 1,
    toronto: 2,
    berlin: 3,
    lisbon: 4,
    madrid: 5,
};

export default function CostPlanner() {
    const { canAccessFeature, isLoading: featuresLoading } = useFeatures();
    const { formatCurrency, currency, supported, setCurrency } = useCurrency();
    const queryClient = useQueryClient();

    // UI State for inputs
    const [applicants, setApplicants] = useState('1');
    const [city, setCity] = useState('london');
    const [housing, setHousing] = useState('private');
    const [monthsTimeline, setMonthsTimeline] = useState('12');
    const [isEditModalOpen, setIsEditModalOpen] = useState(false);

    // Active state (what the calculator actually uses)
    const [activeParams, setActiveParams] = useState({
        applicants: '1',
        city: 'london',
        housing: 'private',
        monthsTimeline: '12'
    });

    const { data: pathwayRes, isLoading: pathwayLoading } = useQuery({
        queryKey: ['active-pathway'],
        queryFn: pathwayService.getPathway,
    });

    const pathway = pathwayRes?.data;
    const projection = pathwayRes?.projection;

    const { data: templates = [], isLoading: templatesLoading, error: templatesError } = useQuery({
        queryKey: ['cost-templates', activeParams.city],
        queryFn: () => costService.getTemplates({
            country_id: CITY_TO_COUNTRY_MAP[activeParams.city]
        }),
        retry: false,
        enabled: canAccessFeature('cost-planner')
    });

    const updateSavingsMutation = useMutation({
        mutationFn: (data: { current_savings: number; monthly_target: number; target_date?: string | null }) =>
            pathwayService.updateSavings(data),
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['active-pathway'] });
            setIsEditModalOpen(false);
            toast({
                title: "Savings Plan Updated",
                description: "Your financial roadmap has been recalibrated.",
            });
        },
    });

    const handleCalculate = () => {
        setActiveParams({
            applicants,
            city,
            housing,
            monthsTimeline
        });
    };

    const calculatedCosts = useMemo(() => {
        const cityMult = CITY_MULTIPLIERS[activeParams.city] || 1;
        const housingMult = HOUSING_MULTIPLIERS[activeParams.housing] || 1;
        const pax = Number(activeParams.applicants) || 1;
        const months = Number(activeParams.monthsTimeline) || 12;

        const results: Record<string, number> = {};

        if (!Array.isArray(templates) || templates.length === 0) return results;

        templates.forEach((template: any) => {
            let amount = parseFloat(template.amount) || 0;
            const name = (template.name || '').toLowerCase();

            if (name.includes('visa')) {
                amount = amount * pax;
            } else if (name.includes('flight')) {
                amount = amount * pax;
            } else if (name.includes('accommodation') || name.includes('housing') || name.includes('rent')) {
                amount = amount * cityMult * housingMult;
            } else if (name.includes('funds')) {
                amount = amount * cityMult;
            } else if (name.includes('insurance')) {
                amount = amount * pax * (months / 12);
            } else if (name.includes('health') || name.includes('ihs')) {
                amount = amount * pax * (months / 12);
            } else if (name.includes('translation')) {
                amount = amount * pax;
            }

            results[template.name] = Math.round(amount);
        });

        return results;
    }, [templates, activeParams]);

    const total = Object.values(calculatedCosts).reduce((a, b) => a + b, 0);
    const savedAmount = pathway?.current_savings || 0;
    const gap = Math.max(0, total - savedAmount);

    const simulatedProjection = useMemo(() => {
        const monthlyTarget = pathway?.monthly_target || 0;
        const targetDate = pathway?.target_date ? new Date(pathway.target_date) : null;

        const monthsToReady = monthlyTarget > 0 ? Math.ceil(gap / monthlyTarget) : null;
        const projectedDate = monthsToReady !== null ? new Date() : null;
        if (projectedDate && monthsToReady !== null) {
            projectedDate.setMonth(projectedDate.getMonth() + monthsToReady);
        }

        let status: 'on-track' | 'ahead' | 'behind' = 'on-track';
        let message = '';

        if (targetDate && projectedDate) {
            const targetTotalMonths = targetDate.getFullYear() * 12 + targetDate.getMonth();
            const projectedTotalMonths = projectedDate.getFullYear() * 12 + projectedDate.getMonth();
            const diff = projectedTotalMonths - targetTotalMonths;

            if (diff > 0) {
                status = 'behind';
                message = `You are currently projecting to be ready ${diff} month(s) after your target date.`;
            } else if (diff < 0) {
                status = 'ahead';
                message = `You are on track to be ready ${Math.abs(diff)} month(s) before your target date.`;
            } else {
                status = 'on-track';
                message = "You are right on track to meet your target date.";
            }
        }

        return {
            total_cost: total,
            current_savings: savedAmount,
            gap: gap,
            months_to_ready: monthsToReady,
            projected_ready_date: projectedDate?.toISOString() || null,
            status,
            message,
            percentage: total > 0 ? Math.round((savedAmount / total) * 100) : 0
        };
    }, [total, savedAmount, gap, pathway]);

    const chartData = Object.entries(calculatedCosts).map(([name, value]) => ({ name, value }));

    const isLoading = templatesLoading || pathwayLoading || featuresLoading;

    // Detect subscription requirement
    const isSubscriptionRequired = !canAccessFeature('cost-planner') ||
        (templatesError as any)?.response?.status === 403 ||
        (templatesError as any)?.response?.data?.subscription_required;

    const hasError = !!(templatesError) && !isSubscriptionRequired;

    if (isLoading) {
        return (
            <div className="flex flex-col items-center justify-center p-20 space-y-4">
                <Loader2 className="h-10 w-10 animate-spin text-[#0B3C91]" />
                <p className="text-slate-500 font-medium">Calibrating budget templates...</p>
            </div>
        );
    }

    if (isSubscriptionRequired) {
        return (
            <div className="max-w-4xl mx-auto my-12 relative overflow-hidden bg-white border border-blue-100 rounded-[32px] shadow-2xl shadow-blue-50/50">
                <div className="absolute top-0 right-0 p-8 opacity-10 pointer-events-none">
                    <Sparkles className="h-64 w-64 text-[#0B3C91]" />
                </div>

                <div className="relative z-10 p-12 text-center">
                    <div className="h-20 w-20 bg-blue-50 rounded-3xl flex items-center justify-center mx-auto mb-8 rotate-3">
                        <Lock className="h-10 w-10 text-[#0B3C91]" />
                    </div>

                    <h2 className="text-4xl font-extrabold text-[#1A1A1A] mb-4 tracking-tight">Unlock Strategic Financial Planning</h2>
                    <p className="text-lg text-[#6B7280] max-w-xl mx-auto mb-10 leading-relaxed">
                        The fully interactive Cost Planner uses real-time visa fees, housing data, and flight averages to build your perfect relocation budget.
                    </p>

                    <div className="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12 max-w-3xl mx-auto">
                        {[
                            { title: 'Visa & Health Fees', desc: 'Auto-calculated for your family' },
                            { title: 'City-Specific Rent', desc: 'Real market averages for 10+ cities' },
                            { title: 'Proof of Funds', desc: 'Maintenance tracking & gap analysis' }
                        ].map((feat, i) => (
                            <div key={i} className="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                                <p className="font-bold text-[#1A1A1A] text-sm mb-1">{feat.title}</p>
                                <p className="text-xs text-[#6B7280]">{feat.desc}</p>
                            </div>
                        ))}
                    </div>

                    <div className="flex flex-col sm:flex-row items-center justify-center gap-4">
                        <Link to="/settings/billing">
                            <Button className="bg-[#0B3C91] hover:bg-[#0A2A66] text-white px-10 py-7 rounded-2xl text-lg font-bold shadow-xl shadow-blue-100 group transition-all">
                                Upgrade to Premium
                                <Sparkles className="ml-2 h-5 w-5 group-hover:scale-110 transition-transform" />
                            </Button>
                        </Link>
                        <Link to="/dashboard">
                            <Button variant="ghost" className="text-[#6B7280] hover:text-[#1A1A1A] font-semibold text-lg px-8">
                                Return to Dashboard
                            </Button>
                        </Link>
                    </div>
                </div>

                <div className="bg-slate-50 border-t border-slate-100 py-6 px-12 flex items-center justify-between text-xs text-[#6B7280]">
                    <div className="flex items-center gap-2">
                        <div className="h-2 w-2 rounded-full bg-green-500" />
                        Live costing server active
                    </div>
                    <div className="flex items-center gap-4">
                        <span>Transparent Fees</span>
                        <span>Expert Verification</span>
                        <span>Real-time Updates</span>
                    </div>
                </div>
            </div>
        );
    }

    if (hasError) {
        return (
            <div className="bg-red-50 border border-red-100 rounded-3xl p-12 text-center max-w-2xl mx-auto my-12">
                <div className="h-16 w-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <AlertCircle className="h-8 w-8 text-red-600" />
                </div>
                <h2 className="text-xl font-bold text-red-900 mb-2">Sync Failed</h2>
                <p className="text-red-700 mb-6">We couldn't reach the core costing server. Please check your connection or try again later.</p>
                <Button onClick={() => window.location.reload()} variant="outline" className="border-red-200 text-red-700 hover:bg-red-100">
                    Retry Sync
                </Button>
            </div>
        );
    }

    return (
        <div className="max-w-5xl mx-auto space-y-6 pb-20">
            {/* Header */}
            <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div className="flex items-center gap-4">
                    <div className="h-12 w-12 rounded-xl bg-blue-50 flex items-center justify-center shrink-0">
                        <Wallet className="h-6 w-6 text-[#0B3C91]" />
                    </div>
                    <div>
                        <h1 className="text-2xl font-bold text-[#1A1A1A]">Relocation Cost Planner</h1>
                        <p className="text-[#6B7280] mt-1 text-sm">Simulate your move and track your savings goal in your local currency.</p>
                    </div>
                </div>

                <div className="flex items-center gap-2 bg-white px-3 py-2 rounded-xl border border-slate-200 shadow-sm self-start">
                    <span className="text-xs font-bold text-slate-400 uppercase tracking-widest hidden sm:inline-block">Currency:</span>
                    <select
                        value={currency}
                        onChange={(e) => setCurrency(e.target.value)}
                        className="bg-transparent text-sm font-bold text-slate-700 outline-none cursor-pointer"
                    >
                        {Object.entries(supported).map(([code, data]) => (
                            <option key={code} value={code}>{code} - {data.label}</option>
                        ))}
                    </select>
                </div>
            </div>

            <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {/* Controls Sidebar */}
                <div className="lg:col-span-1 space-y-6">
                    <div className="bg-white rounded-2xl border border-[#E5E7EB] shadow-sm p-6 space-y-5 h-fit">
                        <div className="flex items-center gap-2 mb-2">
                            <div className="p-2 bg-blue-50 rounded-lg">
                                <Calculator className="h-4 w-4 text-[#0B3C91]" />
                            </div>
                            <h3 className="font-bold text-[#1A1A1A]">Simulation Controls</h3>
                        </div>

                        <div className="space-y-4">
                            <div className="space-y-2">
                                <Label className="flex items-center gap-2 text-sm font-medium"><Users className="h-4 w-4 text-[#6B7280]" /> Applicants</Label>
                                <Select value={applicants} onValueChange={setApplicants}>
                                    <SelectTrigger><SelectValue /></SelectTrigger>
                                    <SelectContent>
                                        {['1', '2', '3', '4'].map(n => <SelectItem key={n} value={n}>{n} {parseInt(n) > 1 ? 'People' : 'Person'}</SelectItem>)}
                                    </SelectContent>
                                </Select>
                            </div>

                            <div className="space-y-2">
                                <Label className="flex items-center gap-2 text-sm font-medium"><MapPin className="h-4 w-4 text-[#6B7280]" /> Destination City</Label>
                                <Select value={city} onValueChange={setCity}>
                                    <SelectTrigger><SelectValue /></SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="london">London, UK</SelectItem>
                                        <SelectItem value="manchester">Manchester, UK</SelectItem>
                                        <SelectItem value="edinburgh">Edinburgh, UK</SelectItem>
                                        <SelectItem value="toronto">Toronto, Canada</SelectItem>
                                        <SelectItem value="berlin">Berlin, Germany</SelectItem>
                                        <SelectItem value="lisbon">Lisbon, Portugal</SelectItem>
                                        <SelectItem value="madrid">Madrid, Spain</SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>

                            <div className="space-y-2">
                                <Label className="flex items-center gap-2 text-sm font-medium"><Home className="h-4 w-4 text-[#6B7280]" /> Housing Type</Label>
                                <Select value={housing} onValueChange={setHousing}>
                                    <SelectTrigger><SelectValue /></SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="shared">Shared / HMO</SelectItem>
                                        <SelectItem value="private">Private Rental</SelectItem>
                                        <SelectItem value="serviced">Serviced Apartment</SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>

                            <div className="space-y-2">
                                <Label className="flex items-center gap-2 text-sm font-medium"><Clock className="h-4 w-4 text-[#6B7280]" /> Planning Timeline</Label>
                                <Select value={monthsTimeline} onValueChange={setMonthsTimeline}>
                                    <SelectTrigger><SelectValue /></SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="6">6 Months</SelectItem>
                                        <SelectItem value="12">12 Months</SelectItem>
                                        <SelectItem value="18">18 Months</SelectItem>
                                        <SelectItem value="24">24 Months</SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>

                            <Button
                                onClick={handleCalculate}
                                className="w-full bg-[#0B3C91] hover:bg-[#0A2A66] text-white font-bold py-6 rounded-xl shadow-lg shadow-blue-100 mt-2"
                            >
                                Sync & Calculate
                            </Button>
                        </div>
                    </div>
                </div>

                {/* Main Content: Projections & Summary */}
                <div className="lg:col-span-2 space-y-6">
                    {/* Savings Goal Card & Summary Stats */}
                    <div className="grid grid-cols-1 md:grid-cols-1 gap-6">
                        {simulatedProjection && (
                            <SavingsGoalCard
                                projection={simulatedProjection as any}
                                onEdit={() => setIsEditModalOpen(true)}
                                currentMonthlyTarget={pathway?.monthly_target || 0}
                            />
                        )}
                    </div>

                    <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div className="bg-[#0B3C91] rounded-2xl p-5 text-white">
                            <p className="text-xs font-semibold text-blue-200 tracking-wider uppercase mb-1">Total Estimate</p>
                            <p className="text-2xl font-extrabold">{formatCurrency(total, true)}</p>
                        </div>
                        <div className="bg-white rounded-2xl p-5 border border-[#E5E7EB] shadow-sm flex flex-col justify-between">
                            <p className="text-xs font-semibold text-[#6B7280] tracking-wider uppercase mb-1">Current Savings</p>
                            <p className="text-2xl font-extrabold text-[#1A1A1A]">{formatCurrency(savedAmount, true)}</p>
                        </div>
                        <div className={`rounded-2xl p-5 border shadow-sm ${gap > 0 ? 'bg-red-50 border-red-100' : 'bg-green-50 border-green-100'}`}>
                            <p className="text-xs font-semibold text-[#6B7280] tracking-wider uppercase mb-1">Funding Gap</p>
                            <p className={`text-2xl font-extrabold ${gap > 0 ? 'text-red-600' : 'text-green-600'}`}>
                                {gap > 0 ? formatCurrency(gap, true) : 'Covered ✓'}
                            </p>
                        </div>
                    </div>

                    {/* Bar Chart */}
                    <div className="bg-white rounded-2xl p-6 border border-[#E5E7EB] shadow-sm">
                        <div className="flex items-center justify-between mb-4">
                            <h4 className="font-bold text-[#1A1A1A]">Expense Breakdown</h4>
                            <span className="text-xs text-slate-400 font-medium">{templates.length} items from server</span>
                        </div>
                        <ResponsiveContainer width="100%" height={240}>
                            <BarChart data={chartData} margin={{ top: 0, right: 0, bottom: 60, left: 0 }}>
                                <XAxis dataKey="name" tick={{ fontSize: 10, fill: '#6B7280' }} angle={-35} textAnchor="end" interval={0} />
                                <YAxis tick={{ fontSize: 10, fill: '#6B7280' }} tickFormatter={v => formatCurrency(v, true)} width={80} />
                                <Tooltip formatter={(v: number | undefined) => [formatCurrency(v ?? 0, true), 'Cost']} />
                                <Bar dataKey="value" radius={[6, 6, 0, 0]}>
                                    {chartData.map((_, i) => (
                                        <Cell key={i} fill={i % 2 === 0 ? '#0B3C91' : '#00C2FF'} />
                                    ))}
                                </Bar>
                            </BarChart>
                        </ResponsiveContainer>
                    </div>

                    {/* Detailed List */}
                    <div className="bg-white rounded-2xl border border-[#E5E7EB] shadow-sm overflow-hidden">
                        <div className="px-6 py-4 border-b bg-slate-50">
                            <h4 className="font-bold text-slate-800 text-sm">Line Item Details</h4>
                        </div>
                        <div className="divide-y divide-slate-100">
                            {templates.map((template: any) => (
                                <div key={template.id} className="px-6 py-3 flex items-center justify-between hover:bg-slate-50 transition-colors">
                                    <div>
                                        <p className="text-sm font-semibold text-slate-900">{template.name}</p>
                                        <p className="text-xs text-slate-500">{template.description || 'Base relocation expense'}</p>
                                    </div>
                                    <div className="text-right">
                                        <p className="text-sm font-bold text-slate-900">{formatCurrency(calculatedCosts[template.name] || 0)}</p>
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>
                </div>
            </div>

            {/* Edit Savings Modal */}
            <Dialog open={isEditModalOpen} onOpenChange={setIsEditModalOpen}>
                <DialogContent className="sm:rounded-3xl max-w-md">
                    <DialogHeader>
                        <DialogTitle className="text-xl font-bold">Adjust Savings Plan</DialogTitle>
                        <DialogDescription>
                            Configure your financial targets to recalibrate your roadmap.
                        </DialogDescription>
                    </DialogHeader>

                    <form onSubmit={(e) => {
                        e.preventDefault();
                        const formData = new FormData(e.currentTarget);
                        updateSavingsMutation.mutate({
                            current_savings: parseFloat(formData.get('current_savings') as string),
                            monthly_target: parseFloat(formData.get('monthly_target') as string),
                            target_date: formData.get('target_date') as string || null,
                        });
                    }} className="space-y-5 pt-4">
                        <div className="space-y-2">
                            <Label htmlFor="current_savings" className="text-xs font-bold uppercase text-slate-500">Available Savings (USD Base)</Label>
                            <Input
                                id="current_savings"
                                name="current_savings"
                                type="number"
                                defaultValue={pathway?.current_savings}
                                className="h-12 rounded-xl"
                                required
                            />
                        </div>

                        <div className="space-y-2">
                            <Label htmlFor="monthly_target" className="text-xs font-bold uppercase text-slate-500">Monthly Target (USD Base)</Label>
                            <Input
                                id="monthly_target"
                                name="monthly_target"
                                type="number"
                                defaultValue={pathway?.monthly_target}
                                className="h-12 rounded-xl"
                                required
                            />
                        </div>

                        <div className="space-y-2">
                            <Label htmlFor="target_date" className="text-xs font-bold uppercase text-slate-500">Goal Readiness Date</Label>
                            <Input
                                id="target_date"
                                name="target_date"
                                type="date"
                                defaultValue={pathway?.target_date}
                                className="h-12 rounded-xl"
                            />
                        </div>

                        <DialogFooter className="pt-2">
                            <Button type="submit" disabled={updateSavingsMutation.isPending} className="w-full bg-[#0B3C91] hover:bg-[#0A2A66] h-12 rounded-xl font-bold">
                                {updateSavingsMutation.isPending ? <Loader2 className="animate-spin" /> : 'Save & Recalibrate'}
                            </Button>
                        </DialogFooter>
                    </form>
                </DialogContent>
            </Dialog>
        </div>
    );
}
