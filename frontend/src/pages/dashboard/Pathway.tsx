import { useState } from 'react';
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import { pathwayService } from '@/services/api/pathwayService';
import { riskAnalysisService } from '@/services/api/riskAnalysisService';
import { useCountries } from '@/hooks/useCountries';
import { countryService } from '@/services/api/countryService';
import { Button } from '@/components/ui/button';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { MapPin, CheckCircle2, Loader2, ArrowRight, AlertTriangle, TrendingDown, Landmark, ShieldCheck, Wallet, Receipt, Info, Lock, Sparkles } from 'lucide-react';
import { Link } from 'react-router-dom';
import { useToast } from '@/hooks/use-toast';
import { useFeatures } from '@/hooks/useFeatures';
import { financeService } from '@/services/api/financeService';
import { FinancingCard } from '@/components/dashboard/FinancingCard';

// Standalone Risk Components removed as they are now integrated into the header

export default function Pathway() {
    const { toast } = useToast();
    const queryClient = useQueryClient();
    const [selectedCountryId, setSelectedCountryId] = useState<string>('');
    const [selectedVisaId, setSelectedVisaId] = useState<string>('');

    const { data: pathwayRes, isLoading: loadingPathway } = useQuery({
        queryKey: ['pathway'],
        queryFn: pathwayService.getPathway,
    });

    const { getFeatureAccess } = useFeatures();

    const { data: financeRes, isLoading: loadingFinance } = useQuery({
        queryKey: ['finance-recommendations'],
        queryFn: financeService.getRecommendations,
        enabled: getFeatureAccess('FINANCE_RECOMMENDATION') !== 'hidden' && !!pathwayRes?.data,
    });

    const pathway = pathwayRes?.data;

    const { data: timelineSteps, isLoading: loadingTimeline } = useQuery({
        queryKey: ['timeline'],
        queryFn: pathwayService.getTimeline,
    });

    // Risk Analysis query — only when pathway exists
    const { data: riskReport, isLoading: loadingRisk } = useQuery({
        queryKey: ['risk-analysis', pathway?.id],
        queryFn: () => riskAnalysisService.getReport(pathway!.id),
        enabled: !!pathway?.id,
    });

    const riskRecalcMutation = useMutation({
        mutationFn: () => {
            if (!pathway?.id) throw new Error('No pathway selected');
            return riskAnalysisService.calculate(pathway.id);
        },
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['risk-analysis', pathway?.id] });
            toast({ title: '🔄 Risk analysis recalculated.' });
        },
        onError: () => toast({ title: 'Could not recalculate risk.', variant: 'destructive' }),
    });

    const { data: countries } = useCountries();

    const { data: visaTypes } = useQuery({
        queryKey: ['visa-types', selectedCountryId],
        queryFn: () => countryService.getVisaTypes(Number(selectedCountryId)),
        enabled: !!selectedCountryId,
    });

    const selectMutation = useMutation({
        mutationFn: pathwayService.selectPathway,
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['pathway'] });
            queryClient.invalidateQueries({ queryKey: ['timeline'] });
            queryClient.invalidateQueries({ queryKey: ['dashboard'] });
            toast({ title: '✅ Pathway selected! Your roadmap is ready.' });
            setSelectedCountryId('');
            setSelectedVisaId('');
        },
        onError: () => toast({ title: 'Could not select pathway.', variant: 'destructive' }),
    });

    const completeMutation = useMutation({
        mutationFn: pathwayService.completeStep,
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['timeline'] });
            queryClient.invalidateQueries({ queryKey: ['dashboard'] });
            toast({ title: 'Step marked as complete!' });
        },
    });

    const deactivateMutation = useMutation({
        mutationFn: pathwayService.deactivatePathway,
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['pathway'] });
            queryClient.invalidateQueries({ queryKey: ['timeline'] });
            queryClient.invalidateQueries({ queryKey: ['dashboard'] });
            toast({ title: 'Pathway reset. You can now choose a new one.' });
        },
        onError: () => toast({ title: 'Could not reset pathway.', variant: 'destructive' }),
    });

    const completedSteps = timelineSteps?.filter((s: any) => s.status === 'completed').length || 0;
    const totalSteps = timelineSteps?.length || 0;
    const progressPct = totalSteps > 0 ? Math.round((completedSteps / totalSteps) * 100) : 0;

    if (loadingPathway) {
        return (
            <div className="max-w-4xl mx-auto space-y-4 animate-pulse">
                <div className="h-40 bg-white rounded-2xl border border-[#E5E7EB]" />
                <div className="h-96 bg-white rounded-2xl border border-[#E5E7EB]" />
            </div>
        );
    }

    return (
        <div className="max-w-4xl mx-auto space-y-6">
            {/* ─── Compact Pathway & Risk Header ─── */}
            {pathway ? (
                <div className="bg-white rounded-2xl border border-[#E5E7EB] shadow-sm overflow-hidden">
                    <div className="bg-gradient-to-br from-[#0B3C91] to-[#0A2A66] p-4 text-white relative flex flex-wrap items-center justify-between gap-4">
                        <div className="absolute top-[-20%] right-[-5%] w-[150px] h-[150px] bg-[#00C2FF]/10 rounded-full blur-[40px] pointer-events-none" />

                        {/* 1. Selection Info */}
                        <div className="relative z-10 flex-1 min-w-[200px]">
                            <div className="inline-flex items-center gap-1 px-1.5 py-0.5 bg-white/10 rounded-full text-[9px] font-black uppercase tracking-widest text-blue-100 mb-1">
                                <MapPin className="h-2.5 w-2.5" /> Active Pathway
                            </div>
                            <h1 className="text-xl font-black leading-tight truncate">{pathway.country?.name}</h1>
                            <div className="flex items-center gap-2">
                                <p className="text-blue-100/70 text-xs truncate max-w-[250px]">{pathway.visa_type?.name}</p>
                                <button
                                    onClick={() => deactivateMutation.mutate()}
                                    disabled={deactivateMutation.isPending}
                                    className="text-[10px] font-bold text-white/50 hover:text-white underline decoration-white/20 hover:decoration-white transition-colors"
                                >
                                    {deactivateMutation.isPending ? 'Resetting...' : 'Switch'}
                                </button>
                            </div>
                        </div>

                        {/* 2. Risk Mini-Indicator */}
                        <div className="relative z-10 flex items-center gap-3 px-4 border-l border-white/10">
                            {loadingRisk ? (
                                <Loader2 className="h-5 w-5 animate-spin text-blue-300" />
                            ) : riskReport ? (
                                <div className="flex items-center gap-3">
                                    <div className="relative h-10 w-10 shrink-0">
                                        <svg width="40" height="40" viewBox="0 0 40 40" className="-rotate-90">
                                            <circle cx="20" cy="20" r="18" fill="none" stroke="rgba(255,255,255,0.1)" strokeWidth="4" />
                                            <circle cx="20" cy="20" r="18" fill="none"
                                                stroke={riskReport.risk_level === 'low' ? '#22c55e' : riskReport.risk_level === 'medium' ? '#f59e0b' : '#ef4444'}
                                                strokeWidth="4" strokeLinecap="round"
                                                strokeDasharray={2 * Math.PI * 18}
                                                strokeDashoffset={2 * Math.PI * 18 * (1 - riskReport.risk_score / 100)}
                                            />
                                        </svg>
                                        <div className="absolute inset-0 flex items-center justify-center text-[10px] font-black">
                                            {riskReport.risk_score}
                                        </div>
                                    </div>
                                    <div className="hidden sm:block">
                                        <p className="text-[10px] font-bold uppercase tracking-widest text-blue-200 leading-none mb-0.5">{pathway?.labels?.risk_level || 'Risk Level'}</p>
                                        <p className={`text-xs font-black uppercase tracking-tight ${riskReport.risk_level === 'low' ? 'text-green-400' : riskReport.risk_level === 'medium' ? 'text-amber-400' : 'text-red-400'
                                            }`}>
                                            {riskReport.risk_level}
                                        </p>
                                    </div>
                                </div>
                            ) : (
                                <Button variant="ghost" size="sm" className="h-8 text-[10px] text-blue-200 border-white/10" onClick={() => riskRecalcMutation.mutate()}>
                                    Recalc Risk
                                </Button>
                            )}
                        </div>

                        {/* 3. Progress Info */}
                        <div className="relative z-10 flex items-center gap-3 pl-4 border-l border-white/10 min-w-[100px]">
                            <div className="text-right">
                                <p className="text-[10px] font-bold uppercase tracking-widest text-blue-200 leading-none mb-0.5">{pathway?.labels?.progress || 'Progress'}</p>
                                <p className="text-xl font-black">{progressPct}%</p>
                            </div>
                            <div className="h-10 w-10 bg-white/10 rounded-lg flex items-center justify-center shrink-0">
                                <TrendingDown className="h-5 w-5 text-[#00C2FF]" />
                            </div>
                        </div>
                    </div>

                    {/* Bottom Progress Bar */}
                    <div className="h-1 bg-slate-100 relative">
                        <div
                            className="h-full bg-[#00C2FF] transition-all duration-700"
                            style={{ width: `${progressPct}%` }}
                        />
                    </div>

                    {/* Expandable Risk Details (Only if clicked/hovered, or just keep it simple) */}
                    {riskReport && (
                        <div className="px-4 py-2 bg-slate-50 flex items-center justify-between">
                            <div className="flex items-center gap-4">
                                {riskReport.weak_areas?.slice(0, 1).map((area: any, i: number) => (
                                    <p key={i} className="text-[10px] font-medium text-slate-500 flex items-center gap-1">
                                        <AlertTriangle className="h-3 w-3 text-amber-500" />
                                        Focus: <span className="font-bold text-slate-700">{area.name}</span>
                                    </p>
                                ))}
                            </div>
                            <button className="text-[10px] font-bold text-[#0B3C91] hover:underline" onClick={() => queryClient.invalidateQueries({ queryKey: ['risk-analysis'] })}>
                                Refresh Analytics
                            </button>
                        </div>
                    )}
                </div>
            ) : (
                /* Pathway Selector (keep same as before) */
                <div className="bg-white rounded-2xl p-6 border border-[#E5E7EB] shadow-sm">
                    <h2 className="text-xl font-black text-[#1A1A1A] mb-1">Choose Your Pathway</h2>
                    <p className="text-sm text-[#6B7280] mb-4">Select a destination and visa type to generate yourpersonalised roadmap.</p>
                    <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div className="space-y-1.5">
                            <label className="text-xs font-bold uppercase tracking-widest text-[#1A1A1A]">Destination</label>
                            <Select value={selectedCountryId} onValueChange={v => { setSelectedCountryId(v); setSelectedVisaId(''); }}>
                                <SelectTrigger className="rounded-xl"><SelectValue placeholder="Select country" /></SelectTrigger>
                                <SelectContent className="rounded-xl">
                                    {countries?.map((c: any) => (
                                        <SelectItem key={c.id} value={c.id.toString()}>{c.name}</SelectItem>
                                    ))}
                                </SelectContent>
                            </Select>
                        </div>
                        <div className="space-y-1.5">
                            <label className="text-xs font-bold uppercase tracking-widest text-[#1A1A1A]">Visa Type</label>
                            <Select value={selectedVisaId} onValueChange={setSelectedVisaId} disabled={!selectedCountryId}>
                                <SelectTrigger className="rounded-xl"><SelectValue placeholder={selectedCountryId ? 'Select visa type' : 'Choose a country first'} /></SelectTrigger>
                                <SelectContent className="rounded-xl">
                                    {visaTypes?.map((v: any) => (
                                        <SelectItem key={v.id} value={v.id.toString()}>{v.name}</SelectItem>
                                    ))}
                                </SelectContent>
                            </Select>
                        </div>
                    </div>
                    <Button
                        className="mt-4 bg-[#0B3C91] hover:bg-[#0B3C91]/90 text-white rounded-xl font-bold w-full sm:w-auto"
                        disabled={!selectedCountryId || !selectedVisaId || selectMutation.isPending}
                        onClick={() => selectMutation.mutate({
                            country_id: Number(selectedCountryId),
                            visa_type_id: Number(selectedVisaId),
                        })}
                    >
                        {selectMutation.isPending ? <Loader2 className="mr-2 h-4 w-4 animate-spin" /> : <ArrowRight className="mr-2 h-4 w-4" />}
                        Generate My Roadmap
                    </Button>
                </div>
            )}

            {/* Remove the standalone ─── Risk Analysis Card ─── as it's now merged into the header */}

            {/* Cost Breakdown Section */}
            {pathway && pathway.visa_type?.cost_templates?.length > 0 && (
                <div className="bg-white rounded-2xl border border-[#E5E7EB] shadow-sm overflow-hidden">
                    <div className="px-6 py-4 bg-gradient-to-r from-slate-50 to-white border-b border-[#E5E7EB] flex items-center justify-between">
                        <div>
                            <h3 className="font-bold text-[#1A1A1A] text-lg flex items-center gap-2">
                                <Wallet className="h-5 w-5 text-[#0B3C91]" />
                                Relocation Cost Breakdown
                            </h3>
                            <p className="text-sm text-[#6B7280]">Estimated budget for your {pathway.country?.name} relocation.</p>
                        </div>
                        <div className="text-right">
                            <p className="text-[10px] font-bold uppercase tracking-widest text-[#6B7280] leading-none mb-1">Total Estimated Range</p>
                            <p className="text-xl font-black text-[#0B3C91]">
                                {pathway.visa_type.cost_templates[0].currency} {pathway.visa_type.cost_templates.reduce((acc: number, curr: any) => acc + Number(curr.min_cost), 0).toLocaleString()} – {pathway.visa_type.cost_templates.reduce((acc: number, curr: any) => acc + Number(curr.max_cost), 0).toLocaleString()}
                            </p>
                        </div>
                    </div>

                    <div className="p-6">
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {/* Group by category for a cleaner view */}
                            {Array.from(new Set(pathway.visa_type.cost_templates.map((ct: any) => ct.category))).map((category: any) => (
                                <div key={category} className="space-y-3">
                                    <h4 className="text-xs font-black uppercase tracking-wider text-slate-400 border-b border-slate-100 pb-1">{category}</h4>
                                    <div className="space-y-2">
                                        {pathway.visa_type.cost_templates.filter((ct: any) => ct.category === category).map((item: any) => (
                                            <div key={item.id} className="flex items-center justify-between group">
                                                <div className="flex items-center gap-2">
                                                    <div className="h-1.5 w-1.5 rounded-full bg-blue-400 group-hover:scale-125 transition-transform" />
                                                    <p className="text-sm font-medium text-slate-700">{item.item}</p>
                                                </div>
                                                <p className="text-sm font-bold text-slate-900">
                                                    {item.currency} {Number(item.min_cost).toLocaleString()} – {Number(item.max_cost).toLocaleString()}
                                                </p>
                                            </div>
                                        ))}
                                    </div>
                                </div>
                            ))}
                        </div>

                        <div className="mt-8 p-4 bg-blue-50 border border-blue-100 rounded-xl flex items-start gap-3">
                            <Info className="h-5 w-5 text-[#0B3C91] shrink-0 mt-0.5" />
                            <p className="text-xs text-blue-900/70 leading-relaxed italic">
                                <strong>Cost Disclaimer:</strong> Cost estimates are approximate and may vary depending on personal circumstances, exchange rates, and immigration policy updates. These figures are for planning purposes only.
                            </p>
                        </div>
                    </div>
                </div>
            )}

            {/* Recommended Financing Section */}
            {pathway && getFeatureAccess('FINANCE_RECOMMENDATION') !== 'hidden' && (
                <div className="space-y-4 relative">
                    <div className="flex items-center justify-between">
                        <div>
                            <h3 className="text-xl font-bold text-[#1A1A1A] flex items-center gap-2">
                                <Landmark className="h-5 w-5 text-[#0B3C91]" />
                                Recommended Financing
                            </h3>
                            <p className="text-sm text-[#6B7280]">Trusted providers matched for your {pathway.country?.name} pathway.</p>
                        </div>
                    </div>

                    <div className="relative">
                        {getFeatureAccess('FINANCE_RECOMMENDATION') === 'locked' && (
                            <div className="absolute inset-0 z-20 backdrop-blur-[4px] bg-white/40 flex items-center justify-center rounded-[32px] border border-blue-50 shadow-inner">
                                <div className="bg-white/90 p-8 rounded-[32px] shadow-2xl border border-blue-100 text-center max-w-sm mx-4 transform transition-all hover:scale-[1.02]">
                                    <div className="h-16 w-16 bg-blue-50 rounded-3xl flex items-center justify-center mx-auto mb-4 rotate-3">
                                        <Lock className="h-8 w-8 text-[#0B3C91]" />
                                    </div>
                                    <h4 className="text-xl font-black text-[#1A1A1A] mb-2 tracking-tight">Premium Financing Access</h4>
                                    <p className="text-sm text-[#6B7280] mb-6 leading-relaxed">
                                        Unlock verified local and international financing providers tailored to your relocation path.
                                    </p>
                                    <Link to="/pricing">
                                        <Button className="bg-[#0B3C91] hover:bg-[#0A2A66] text-white w-full rounded-2xl py-6 font-bold shadow-lg shadow-blue-100 group">
                                            Upgrade to Premium
                                            <Sparkles className="ml-2 h-4 w-4 group-hover:scale-110 transition-transform" />
                                        </Button>
                                    </Link>
                                </div>
                            </div>
                        )}

                        <div className={`grid grid-cols-1 md:grid-cols-2 gap-4 ${getFeatureAccess('FINANCE_RECOMMENDATION') === 'locked' ? 'opacity-40 grayscale-[0.5]' : ''}`}>
                            {financeRes?.data?.length > 0 ? (
                                financeRes.data.map((provider: any) => (
                                    <FinancingCard key={provider.id} provider={provider} />
                                ))
                            ) : (
                                <div className="col-span-2 p-12 bg-slate-50 rounded-[32px] border border-dashed border-slate-200 text-center">
                                    <p className="text-slate-400 font-medium">No financing providers currently listed for this pathway.</p>
                                </div>
                            )}
                        </div>
                    </div>

                    <div className="bg-slate-50 border border-slate-200 rounded-2xl p-4 flex items-start gap-3">
                        <ShieldCheck className="h-5 w-5 text-[#0B3C91] shrink-0 mt-0.5" />
                        <div>
                            <p className="text-[11px] font-bold text-[#0B3C91] uppercase tracking-wider mb-1">GoPathway Verification Disclaimer</p>
                            <p className="text-[10px] text-[#6B7280] leading-relaxed">
                                {financeRes?.meta?.disclaimer || "GoPathway does not provide loans or financial services. We only recommend verified providers based on their support for international relocation. Users are strongly advised to perform their own due diligence before entering any financial agreements."}
                            </p>
                        </div>
                    </div>
                </div>
            )}

            {/* Timeline Steps */}
            {pathway && (
                <div className="bg-white rounded-2xl border border-[#E5E7EB] shadow-sm overflow-hidden">
                    <div className="px-6 py-4 bg-[#F5F7FA] border-b border-[#E5E7EB]">
                        <h3 className="font-bold text-[#1A1A1A] text-lg">{pathway?.labels?.roadmap || 'Your Roadmap'}</h3>
                        <p className="text-sm text-[#6B7280]">Complete each step in order to progress your application.</p>
                    </div>

                    {loadingTimeline ? (
                        <div className="p-6 space-y-4 animate-pulse">
                            {[...Array(4)].map((_, i) => (
                                <div key={i} className="h-16 bg-gray-100 rounded-xl" />
                            ))}
                        </div>
                    ) : timelineSteps?.length > 0 ? (
                        <ol className="divide-y divide-[#E5E7EB]">
                            {timelineSteps.map((step: any, idx: number) => {
                                const isComplete = step.status === 'completed';
                                const isPending = step.status === 'pending';
                                const nextPending = timelineSteps.find((s: any) => s.status === 'pending');
                                const isNext = nextPending?.id === step.id;

                                return (
                                    <li key={step.id} className={`flex items-start gap-4 p-5 ${isNext ? 'bg-blue-50/50' : ''}`}>
                                        <div className="flex-shrink-0 mt-0.5">
                                            {isComplete ? (
                                                <CheckCircle2 className="h-6 w-6 text-green-500" />
                                            ) : (
                                                <div className={`h-6 w-6 rounded-full border-2 flex items-center justify-center ${isNext ? 'border-[#0B3C91] bg-[#0B3C91]' : 'border-gray-300'}`}>
                                                    {isNext ? <span className="text-white text-[10px] font-bold">→</span> : <span className="text-gray-400 text-[10px]">{idx + 1}</span>}
                                                </div>
                                            )}
                                        </div>
                                        <div className="flex-1 min-w-0">
                                            <p className={`font-semibold ${isComplete ? 'text-gray-400 line-through' : 'text-[#1A1A1A]'}`}>{step.title}</p>
                                            {step.description && <p className="text-sm text-[#6B7280] mt-0.5">{step.description}</p>}
                                            {step.completed_at && (
                                                <p className="text-xs text-green-500 mt-1">Completed {new Date(step.completed_at).toLocaleDateString()}</p>
                                            )}
                                        </div>
                                        {isPending && isNext && (
                                            <Button
                                                size="sm"
                                                className="flex-shrink-0 bg-[#0B3C91] hover:bg-[#0B3C91]/90 text-white text-xs"
                                                onClick={() => completeMutation.mutate(step.id)}
                                                disabled={completeMutation.isPending}
                                            >
                                                {completeMutation.isPending ? <Loader2 className="h-3 w-3 animate-spin" /> : 'Mark Done'}
                                            </Button>
                                        )}
                                    </li>
                                );
                            })}
                        </ol>
                    ) : (
                        <div className="p-8 text-center text-[#6B7280]">
                            <p>No timeline steps generated yet. Your roadmap will appear here automatically once the pathway is activated.</p>
                        </div>
                    )}
                </div>
            )}
        </div>
    );
}
