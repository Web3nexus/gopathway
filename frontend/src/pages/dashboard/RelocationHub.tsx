import { useState } from 'react';
import { useQuery } from '@tanstack/react-query';
import api from '@/lib/api';
import { useDashboard } from '@/hooks/useDashboard';
import { useFeatures } from '@/hooks/useFeatures';
import { useAuth } from '@/hooks/useAuth';
import { FileText, ChevronDown, ChevronUp, Lock, CheckCircle2, Circle, Loader2, BookOpen, Map, CheckSquare } from 'lucide-react';
import { cn } from '@/lib/utils';
import { Button } from '@/components/ui/button';
import { Link } from 'react-router-dom';
import SettlementChecklist from '@/components/dashboard/SettlementChecklist';

export default function RelocationHub() {
    const { data: dashboard, isLoading: loadingDashboard } = useDashboard();
    const { user } = useAuth();
    const { canAccessFeature } = useFeatures();
    const [expandedKits, setExpandedKits] = useState<number[]>([]);
    const [activeTab, setActiveTab] = useState<'kits' | 'settlement'>('kits');

    // Fallback ID if no pathway context
    const countryId = dashboard?.pathway?.country?.id;

    const { data: kitsResponse, isLoading: loadingKits } = useQuery({
        queryKey: ['relocation-kits', countryId],
        queryFn: () => api.get(`/api/v1/countries/${countryId}/relocation-kits`).then(res => res.data),
        enabled: !!countryId,
    });

    const toggleKit = (id: number) => {
        setExpandedKits(prev =>
            prev.includes(id) ? prev.filter(kId => kId !== id) : [...prev, id]
        );
    };

    if (loadingDashboard || loadingKits) {
        return (
            <div className="flex justify-center items-center py-20">
                <Loader2 className="h-8 w-8 animate-spin text-primary" />
            </div>
        );
    }

    if (!countryId) {
        return (
            <div className="text-center py-20 mt-10 max-w-lg mx-auto bg-white rounded-3xl border border-border shadow-sm">
                <div className="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <BookOpen className="h-8 w-8 text-primary" />
                </div>
                <h2 className="text-xl font-bold mb-2 text-foreground">No active pathway found</h2>
                <p className="text-slate-500 mb-6 text-sm px-6">You need to have an active pathway selected to see the relocation kits and checklists specific to that destination.</p>
                <Link to="/recommendations">
                    <Button>Explore Destinations</Button>
                </Link>
            </div>
        );
    }

    interface KitItem {
        id: number;
        title: string;
        description: string;
        content: string;
        is_premium: boolean;
    }

    interface Kit {
        id: number;
        title: string;
        description: string;
        is_premium: boolean;
        items: KitItem[];
    }

    const kits: Kit[] = kitsResponse?.data || [];

    return (
        <div className="max-w-4xl mx-auto space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500 pb-12">
            <div>
                <h1 className="text-3xl font-black text-foreground flex items-center gap-3">
                    <BookOpen className="h-8 w-8 text-primary" />
                    Digital Relocation Hub
                </h1>
                <p className="text-muted-foreground mt-2 text-lg">
                    Everything you need to successfully move to <strong className="text-foreground">{dashboard?.pathway?.country?.name}</strong>.
                </p>
            </div>

            {/* Hub Tabs */}
            <div className="flex p-1 bg-white rounded-2xl border border-slate-200 shadow-sm w-fit">
                <button
                    onClick={() => setActiveTab('kits')}
                    className={cn(
                        "flex items-center gap-2 px-6 py-2.5 text-sm font-bold rounded-xl transition-all",
                        activeTab === 'kits'
                            ? "bg-[#0B3C91] text-white shadow-md ring-1 ring-[#0B3C91]/20"
                            : "text-slate-500 hover:text-slate-800 hover:bg-slate-50"
                    )}
                >
                    <Map className="w-4 h-4" />
                    Preparation Kits
                </button>
                <button
                    onClick={() => setActiveTab('settlement')}
                    className={cn(
                        "flex items-center gap-2 px-6 py-2.5 text-sm font-bold rounded-xl transition-all",
                        activeTab === 'settlement'
                            ? "bg-[#0B3C91] text-white shadow-md ring-1 ring-[#0B3C91]/20"
                            : "text-slate-500 hover:text-slate-800 hover:bg-slate-50"
                    )}
                >
                    <CheckSquare className="w-4 h-4" />
                    Settling In
                </button>
            </div>

            {activeTab === 'settlement' ? (
                <SettlementChecklist />
            ) : (
                <div className="space-y-6">
                    {kits.length === 0 ? (
                        <div className="bg-white rounded-2xl border p-12 text-center text-slate-500 shadow-sm">
                            No preparation kits are currently available for this destination.
                        </div>
                    ) : (
                        kits.map((kit) => {
                            const isExpanded = expandedKits.includes(kit.id);
                            const isLocked = kit.is_premium && !canAccessFeature('relocation-hub');

                            return (
                                <div key={kit.id} className={cn(
                                    "border rounded-2xl bg-white shadow-sm overflow-hidden transition-all duration-300",
                                    isExpanded ? "ring-2 ring-primary/20 border-primary/30" : "hover:border-slate-300"
                                )}>
                                    {/* Kit Header */}
                                    <button
                                        onClick={() => toggleKit(kit.id)}
                                        className="w-full flex items-center justify-between p-6 text-left"
                                    >
                                        <div className="flex items-center gap-4">
                                            <div className={cn(
                                                "w-12 h-12 rounded-2xl flex items-center justify-center shrink-0 shadow-sm",
                                                isLocked ? "bg-slate-100" : "bg-blue-50/50"
                                            )}>
                                                {isLocked ? (
                                                    <Lock className="w-5 h-5 text-slate-400" />
                                                ) : (
                                                    <FileText className="w-5 h-5 text-primary" />
                                                )}
                                            </div>
                                            <div>
                                                <div className="flex items-center gap-2">
                                                    <h3 className="font-bold text-lg text-foreground">{kit.title}</h3>
                                                    {kit.is_premium && (
                                                        <span className="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-orange-100 text-orange-700">Premium</span>
                                                    )}
                                                </div>
                                                <p className="text-sm text-slate-500 mt-1">{kit.description}</p>
                                            </div>
                                        </div>
                                        <div className="shrink-0 ml-4 flex items-center gap-3">
                                            <span className="text-xs font-semibold text-slate-400">
                                                {kit.items?.length || 0} items
                                            </span>
                                            <div className="w-8 h-8 rounded-full bg-slate-50 flex items-center justify-center border border-slate-100">
                                                {isExpanded ? <ChevronUp className="w-4 h-4 text-slate-500" /> : <ChevronDown className="w-4 h-4 text-slate-500" />}
                                            </div>
                                        </div>
                                    </button>

                                    {/* Kit Items List */}
                                    <div className={cn(
                                        "grid transition-all duration-300 ease-in-out border-t",
                                        isExpanded ? "grid-rows-[1fr] opacity-100" : "grid-rows-[0fr] opacity-0 border-transparent"
                                    )}>
                                        <div className="overflow-hidden bg-slate-50/50">
                                            {isLocked ? (
                                                <div className="p-8 text-center flex flex-col items-center justify-center">
                                                    <div className="w-16 h-16 rounded-3xl bg-orange-50 mb-4 flex items-center justify-center">
                                                        <Lock className="w-8 h-8 text-orange-500" />
                                                    </div>
                                                    <h4 className="font-bold text-foreground mb-2">Premium Kit Locked</h4>
                                                    <p className="text-sm text-slate-500 max-w-sm mb-6">Upgrade to GoPathway Premium to access this expert relocation guide and exclusive checklists.</p>
                                                    <Link to="/pricing">
                                                        <Button className="rounded-xl font-bold px-8 shadow-sm">Upgrade Now</Button>
                                                    </Link>
                                                </div>
                                            ) : (
                                                <ul className="divide-y divide-border">
                                                    {kit.items?.map((item) => {
                                                        const itemLocked = item.is_premium && !canAccessFeature('relocation-hub');
                                                        return (
                                                            <li key={item.id} className="p-6 pl-8 flex gap-4 hover:bg-white transition-colors">
                                                                <div className="pt-1">
                                                                    <Circle className="w-5 h-5 text-slate-300" />
                                                                </div>
                                                                <div className="flex-1 space-y-2">
                                                                    <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                                                                        <div className="flex items-center gap-2">
                                                                            <h4 className={cn("font-bold", itemLocked ? "text-slate-500" : "text-foreground")}>
                                                                                {item.title}
                                                                            </h4>
                                                                            {item.is_premium && (
                                                                                <Lock className="w-3.5 h-3.5 text-slate-400 shrink-0" />
                                                                            )}
                                                                        </div>
                                                                    </div>

                                                                    {itemLocked ? (
                                                                        <div className="text-sm text-slate-500 bg-slate-100/50 p-4 rounded-xl border border-dashed border-slate-200 mt-2 flex items-center justify-between">
                                                                            <span>Premium tip locked.</span>
                                                                            <Link to="/pricing" className="text-primary font-bold hover:underline">Upgrade</Link>
                                                                        </div>
                                                                    ) : (
                                                                        <div className="text-sm text-slate-600 prose prose-sm max-w-none">
                                                                            {item.content}
                                                                        </div>
                                                                    )}
                                                                </div>
                                                            </li>
                                                        );
                                                    })}
                                                </ul>
                                            )}
                                        </div>
                                    </div>
                                </div>
                            );
                        }))}
                </div>
            )}
        </div>
    );
}
