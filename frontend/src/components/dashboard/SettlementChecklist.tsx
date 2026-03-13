import React, { useState } from 'react';
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import api from '@/lib/api';
import { useDashboard } from '@/hooks/useDashboard';
import { CheckCircle2, Circle, Clock, ExternalLink, Info, Loader2, Milestone } from 'lucide-react';
import { cn } from '@/lib/utils';
import { Button } from '@/components/ui/button';
import { Progress } from '@/components/ui/progress';
import { useFeatures } from '@/hooks/useFeatures';
import { Lock, Sparkles } from 'lucide-react';
import { Link } from 'react-router-dom';

interface SettlementStep {
    id: number;
    phase: 'week1' | 'month1' | 'long_term';
    title: string;
    description: string;
    required_documents?: string[];
    official_link?: string;
    estimated_time?: string;
    mandatory: boolean;
    completed_at: string | null;
}

export default function SettlementChecklist() {
    const { canAccessFeature, isLoading: loadingFeatures } = useFeatures();
    const { data: dashboard } = useDashboard();
    const queryClient = useQueryClient();
    const [activePhase, setActivePhase] = useState<'week1' | 'month1' | 'long_term'>('week1');

    const countryName = dashboard?.pathway?.country?.name || 'your destination';

    const countryId = dashboard?.pathway?.country?.id;

    const { data: response, isLoading } = useQuery({
        queryKey: ['settlement-steps', countryId],
        queryFn: () => api.get('/api/v1/settlement/steps', { params: { country_id: countryId } }).then(res => res.data),
        enabled: !!countryId,
    });

    const toggleMutation = useMutation({
        mutationFn: (stepId: number) => api.post(`/api/v1/settlement/steps/${stepId}/toggle`),
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['settlement-steps'] });
        },
    });

    if (isLoading || loadingFeatures) {
        return (
            <div className="flex justify-center items-center py-12">
                <Loader2 className="h-8 w-8 animate-spin text-primary" />
            </div>
        );
    }

    if (!canAccessFeature('settlement-checklist')) {
        return (
            <div className="bg-white border border-slate-200 rounded-[32px] shadow-sm p-12 text-center animate-in fade-in zoom-in-95 duration-500 overflow-hidden relative group">
                {/* Background Decor */}
                <div className="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-600 via-primary to-blue-600" />
                <div className="absolute -top-24 -right-24 w-48 h-48 bg-primary/5 rounded-full blur-3xl group-hover:bg-primary/10 transition-colors duration-500" />

                <div className="h-20 w-20 bg-primary/5 rounded-3xl flex items-center justify-center mx-auto mb-8 relative">
                    <div className="absolute inset-0 bg-primary/5 rounded-3xl animate-pulse" />
                    <Lock className="h-10 w-10 text-primary relative z-10" />
                </div>

                <h2 className="text-3xl font-black text-slate-900 mb-4 tracking-tight">Your A-Z Settlement Guide</h2>
                <p className="text-lg text-slate-500 max-w-xl mx-auto mb-10 leading-relaxed">
                    Unlock your personalized, step-by-step survival guide for <strong className="text-primary">{countryName}</strong>.
                    From bank accounts to healthcare, we’ve mapped every task for your first week, month, and beyond.
                </p>

                <Link to="/pricing">
                    <Button size="lg" className="h-14 px-10 rounded-2xl text-lg font-bold shadow-xl shadow-primary/10 hover:shadow-primary/20 transition-all hover:-translate-y-0.5 active:translate-y-0">
                        Maximize Your Move
                        <Sparkles className="ml-2 h-5 w-5" />
                    </Button>
                </Link>

                <div className="mt-12 pt-8 border-t border-slate-100 flex items-center justify-center gap-8 opacity-50">
                    <div className="flex items-center gap-2 text-xs font-bold text-slate-400 uppercase tracking-widest">
                        <CheckCircle2 className="w-4 h-4" /> Comprehensive Tasks
                    </div>
                    <div className="flex items-center gap-2 text-xs font-bold text-slate-400 uppercase tracking-widest">
                        <Clock className="w-4 h-4" /> Estimated Timelines
                    </div>
                    <div className="flex items-center gap-2 text-xs font-bold text-slate-400 uppercase tracking-widest">
                        <Info className="w-4 h-4" /> Required Documents
                    </div>
                </div>
            </div>
        );
    }

    const steps: SettlementStep[] = response?.data || [];
    const summary = response?.summary || { total: 0, completed: 0 };
    const progressPercent = summary.total > 0 ? Math.round((summary.completed / summary.total) * 100) : 0;

    const filteredSteps = steps.filter(s => s.phase === activePhase);

    const phases = [
        { id: 'week1', label: 'First Week', icon: Clock, desc: 'Immediate Priorities' },
        { id: 'month1', label: 'First Month', icon: Milestone, desc: 'Setting Foundations' },
        { id: 'long_term', label: 'Settlement', icon: CheckCircle2, desc: 'Long-term Integration' },
    ];

    return (
        <div className="space-y-8 pb-12">
            {/* Progress Header */}
            <div className="bg-[#0B3C91] rounded-[24px] p-8 text-white shadow-xl shadow-blue-900/10 overflow-hidden relative">
                <div className="absolute top-0 right-0 p-8 opacity-10">
                    <Milestone className="w-32 h-32 rotate-12" />
                </div>

                <div className="relative z-10 grid md:grid-cols-[1fr,200px] items-center gap-8">
                    <div>
                        <div className="flex items-center gap-2 mb-2">
                            <span className="px-2 py-0.5 rounded bg-white/20 text-[10px] font-bold uppercase tracking-widest border border-white/20">Active Checklist</span>
                            <span className="text-blue-200 text-xs font-medium">• {countryName}</span>
                        </div>
                        <h3 className="text-3xl font-black tracking-tight">Relocation Success</h3>
                        <p className="text-blue-100/80 text-lg mt-2 font-medium">
                            You have completed <strong className="text-white">{summary.completed}</strong> of <strong className="text-white">{summary.total}</strong> essential steps.
                        </p>
                    </div>
                    <div className="space-y-3">
                        <div className="flex justify-between items-end">
                            <span className="text-4xl font-black italic">{progressPercent}%</span>
                            <span className="text-[10px] font-bold uppercase tracking-wider text-blue-200 mb-1">Done</span>
                        </div>
                        <Progress value={progressPercent} className="h-2.5 bg-white/20" />
                    </div>
                </div>
            </div>

            {/* Phase Tabs */}
            <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                {phases.map(phase => {
                    const Icon = phase.icon;
                    const isActive = activePhase === phase.id;
                    return (
                        <button
                            key={phase.id}
                            onClick={() => setActivePhase(phase.id as any)}
                            className={cn(
                                "flex flex-col items-start p-5 rounded-2xl transition-all duration-300 border text-left",
                                isActive
                                    ? "bg-white border-primary shadow-md ring-4 ring-primary/5"
                                    : "bg-slate-50 border-slate-200 hover:bg-white hover:border-slate-300 text-slate-500"
                            )}
                        >
                            <div className={cn(
                                "w-10 h-10 rounded-xl flex items-center justify-center mb-3",
                                isActive ? "bg-primary text-white" : "bg-slate-200 text-slate-500"
                            )}>
                                <Icon className="w-5 h-5" />
                            </div>
                            <span className={cn("font-bold text-sm", isActive ? "text-slate-900" : "text-slate-500 text-sm")}>{phase.label}</span>
                            <span className="text-[10px] font-medium opacity-60 mt-0.5 uppercase tracking-wide">{phase.desc}</span>
                        </button>
                    );
                })}
            </div>

            {/* Steps List */}
            <div className="space-y-4">
                {filteredSteps.length === 0 ? (
                    <div className="py-12 text-center bg-white rounded-2xl border border-dashed border-slate-300">
                        <p className="text-slate-400 text-sm font-medium">No tasks defined for this phase yet.</p>
                    </div>
                ) : (
                    filteredSteps.map((step) => (
                        <div
                            key={step.id}
                            className={cn(
                                "bg-white border rounded-2xl p-5 transition-all duration-300 hover:shadow-md group",
                                step.completed_at ? "border-green-100 bg-green-50/10" : "border-slate-200"
                            )}
                        >
                            <div className="flex gap-4">
                                <button
                                    onClick={() => toggleMutation.mutate(step.id)}
                                    disabled={toggleMutation.isPending}
                                    className="pt-1 shrink-0"
                                >
                                    {step.completed_at ? (
                                        <CheckCircle2 className="w-6 h-6 text-green-500 fill-green-50/50" />
                                    ) : (
                                        <Circle className="w-6 h-6 text-slate-300 group-hover:text-primary transition-colors" />
                                    )}
                                </button>
                                <div className="flex-1 space-y-3">
                                    <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                                        <h4 className={cn("font-bold text-lg", step.completed_at && "text-slate-500 line-through")}>
                                            {step.title}
                                            {step.mandatory && <span className="ml-2 text-[10px] text-red-500 bg-red-50 px-1.5 py-0.5 rounded border border-red-100 uppercase vertical-align-middle">Required</span>}
                                        </h4>
                                        {step.estimated_time && (
                                            <span className="text-[10px] font-black uppercase text-slate-400 bg-slate-100 px-2 py-1 rounded">
                                                Est: {step.estimated_time}
                                            </span>
                                        )}
                                    </div>
                                    <p className="text-sm text-slate-600 leading-relaxed">{step.description}</p>

                                    {step.required_documents && step.required_documents.length > 0 && (
                                        <div className="bg-slate-50 rounded-xl p-4 border border-slate-100">
                                            <p className="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 flex items-center gap-1.5">
                                                <Info className="w-3 h-3" /> Documents Needed
                                            </p>
                                            <ul className="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                                {step.required_documents.map((doc, idx) => (
                                                    <li key={idx} className="text-xs text-slate-600 flex items-center gap-2">
                                                        <div className="w-1 h-1 rounded-full bg-slate-300" />
                                                        {doc}
                                                    </li>
                                                ))}
                                            </ul>
                                        </div>
                                    )}

                                    {step.official_link && (
                                        <a
                                            href={step.official_link}
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            className="inline-flex items-center gap-1.5 text-xs font-bold text-primary hover:underline"
                                        >
                                            View Official Portal <ExternalLink className="w-3 h-3" />
                                        </a>
                                    )}
                                </div>
                            </div>
                        </div>
                    ))
                )}
            </div>
        </div>
    );
}
