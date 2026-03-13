import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import { goScoreService } from '@/services/api/goScoreService';
import { RefreshCw, Lock, TrendingUp, Loader2, User, DollarSign, Languages, FileText, Map } from 'lucide-react';
import { Button } from '@/components/ui/button';
import { useToast } from '@/hooks/use-toast';

const DIMENSION_CONFIG: Record<string, { label: string; icon: any; color: string; bg: string }> = {
    profile: { label: 'Profile', icon: User, color: 'text-violet-600', bg: 'bg-violet-50' },
    funds: { label: 'Funds', icon: DollarSign, color: 'text-emerald-600', bg: 'bg-emerald-50' },
    language: { label: 'Language', icon: Languages, color: 'text-blue-600', bg: 'bg-blue-50' },
    documents: { label: 'Documents', icon: FileText, color: 'text-amber-600', bg: 'bg-amber-50' },
    timeline: { label: 'Timeline', icon: Map, color: 'text-rose-600', bg: 'bg-rose-50' },
};

function ScoreRing({ score, size = 160 }: { score: number; size?: number }) {
    const radius = (size - 24) / 2;
    const circumference = 2 * Math.PI * radius;
    const offset = circumference - (score / 100) * circumference;

    const color = score >= 70 ? '#10b981' : score >= 45 ? '#f59e0b' : '#ef4444';
    const label = score >= 70 ? 'Strong' : score >= 45 ? 'Building' : 'Needs Work';

    return (
        <div className="relative flex items-center justify-center" style={{ width: size, height: size }}>
            <svg width={size} height={size} className="-rotate-90">
                <circle cx={size / 2} cy={size / 2} r={radius} fill="none" stroke="#f1f5f9" strokeWidth={10} />
                <circle
                    cx={size / 2} cy={size / 2} r={radius}
                    fill="none" stroke={color} strokeWidth={10}
                    strokeDasharray={circumference}
                    strokeDashoffset={offset}
                    strokeLinecap="round"
                    style={{ transition: 'stroke-dashoffset 1s ease-in-out' }}
                />
            </svg>
            <div className="absolute flex flex-col items-center">
                <span className="text-4xl font-black text-[#1A1A1A]">{score}</span>
                <span className="text-xs font-bold uppercase tracking-widest" style={{ color }}>{label}</span>
            </div>
        </div>
    );
}

function DimensionBar({ dimension, data }: { dimension: string; data: { score: number; weight: number; details: any } }) {
    const cfg = DIMENSION_CONFIG[dimension];
    const Icon = cfg.icon;
    return (
        <div className="space-y-1.5">
            <div className="flex items-center justify-between">
                <div className="flex items-center gap-2">
                    <div className={`h-6 w-6 rounded-lg ${cfg.bg} ${cfg.color} flex items-center justify-center`}>
                        <Icon className="h-3.5 w-3.5" />
                    </div>
                    <span className="text-sm font-semibold text-[#1A1A1A]">{cfg.label}</span>
                    <span className="text-[10px] text-slate-400 font-medium">{data.weight}%</span>
                </div>
                <span className="text-sm font-bold text-[#1A1A1A]">{data.score}<span className="text-slate-400 font-normal">/100</span></span>
            </div>
            <div className="h-2 bg-slate-100 rounded-full overflow-hidden">
                <div
                    className="h-full rounded-full transition-all duration-1000"
                    style={{
                        width: `${data.score}%`,
                        backgroundColor: data.score >= 70 ? '#10b981' : data.score >= 45 ? '#f59e0b' : '#ef4444'
                    }}
                />
            </div>
            {data.details?.label && (
                <p className="text-[11px] text-slate-400">{data.details.label}</p>
            )}
        </div>
    );
}

export default function GoScoreWidget() {
    const { toast } = useToast();
    const queryClient = useQueryClient();

    const { data: goScore, isLoading } = useQuery({
        queryKey: ['go-score'],
        queryFn: goScoreService.getScore,
        staleTime: 1000 * 60 * 5, // 5 min
    });

    const recalcMutation = useMutation({
        mutationFn: goScoreService.calculate,
        onSuccess: (data) => {
            queryClient.setQueryData(['go-score'], data);
            toast({ title: 'GoScore updated!' });
        },
        onError: () => toast({ title: 'Failed to update GoScore', variant: 'destructive' }),
    });

    if (isLoading) {
        return (
            <div className="bg-white rounded-3xl border border-[#E5E7EB] p-8 flex items-center justify-center h-[300px]">
                <Loader2 className="h-8 w-8 animate-spin text-[#0B3C91]" />
            </div>
        );
    }

    const score = goScore?.total ?? 0;
    const isPremium = goScore?.is_premium ?? false;
    const dimensions = goScore?.dimensions;

    // Collect all tips from breakdown for the improvement list
    const allTips: string[] = dimensions
        ? Object.values(dimensions).flatMap((d: any) => d?.details?.tips ?? []).slice(0, 3)
        : [];

    return (
        <div className="bg-white rounded-3xl border border-[#E5E7EB] shadow-sm overflow-hidden">
            {/* Header */}
            <div className="bg-gradient-to-br from-[#0B3C91] to-[#1a56c4] p-5 text-white">
                <div className="flex items-center justify-between mb-2">
                    <div>
                        <p className="text-blue-200 text-xs font-bold uppercase tracking-widest">{goScore?.labels?.readiness || 'Your Readiness'}</p>
                        <h2 className="text-2xl font-black">{goScore?.labels?.title || 'GoScore™'}</h2>
                    </div>
                    <button
                        onClick={() => recalcMutation.mutate()}
                        disabled={recalcMutation.isPending}
                        className="h-9 w-9 rounded-xl bg-white/10 hover:bg-white/20 flex items-center justify-center transition-colors"
                        title="Recalculate"
                    >
                        <RefreshCw className={`h-4 w-4 ${recalcMutation.isPending ? 'animate-spin' : ''}`} />
                    </button>
                </div>

                <div className="flex items-center justify-center py-1">
                    <ScoreRing score={score} size={140} />
                </div>

                <p className="text-center text-blue-200 text-xs mt-2">
                    out of 100 — {goScore?.calculated_at ? `Updated ${new Date(goScore.calculated_at).toLocaleDateString()}` : 'Calculating...'}
                </p>
            </div>

            {/* Body */}
            <div className="p-5 space-y-4">
                {/* Dimension Breakdown (Premium) */}
                {isPremium && dimensions ? (
                    <div className="space-y-4">
                        <p className="text-xs font-bold uppercase tracking-widest text-slate-400">{goScore?.labels?.breakdown || 'Score Breakdown'}</p>
                        {Object.entries(dimensions).map(([key, data]: [string, any]) => (
                            <DimensionBar key={key} dimension={key} data={data} />
                        ))}
                    </div>
                ) : (
                    <div className="rounded-2xl border border-dashed border-[#E5E7EB] bg-slate-50/80 p-5 flex flex-col items-center text-center gap-3">
                        <div className="h-10 w-10 rounded-2xl bg-amber-50 text-amber-500 flex items-center justify-center">
                            <Lock className="h-5 w-5" />
                        </div>
                        <div>
                            <p className="font-bold text-[#1A1A1A] text-sm">Unlock Full Breakdown</p>
                            <p className="text-xs text-slate-400 mt-0.5">See all 5 score dimensions and personalised tips</p>
                        </div>
                        <Button
                            size="sm"
                            className="bg-[#0B3C91] hover:bg-[#0B3C91]/90 text-white rounded-xl h-9 px-6 text-xs font-bold"
                            onClick={() => window.location.href = '/dashboard/pricing'}
                        >
                            Upgrade to Premium
                        </Button>
                    </div>
                )}

                {/* Improvement tips */}
                {isPremium && allTips.length > 0 && (
                    <div className="space-y-2">
                        <p className="text-xs font-bold uppercase tracking-widest text-slate-400 flex items-center gap-1.5">
                            <TrendingUp className="h-3.5 w-3.5" /> How to Improve
                        </p>
                        <ul className="space-y-1.5">
                            {allTips.map((tip, i) => (
                                <li key={i} className="flex items-start gap-2 text-xs text-slate-500">
                                    <span className="inline-block h-1.5 w-1.5 rounded-full bg-[#0B3C91] mt-1.5 shrink-0" />
                                    {tip}
                                </li>
                            ))}
                        </ul>
                    </div>
                )}
            </div>
        </div>
    );
}
