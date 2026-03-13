import { useState } from 'react';
import { usePathways } from '@/hooks/usePathways';
import { usePathwayComparison } from '@/hooks/useComparison';
import { useCurrency } from '@/contexts/CurrencyContext';
import { Radar, RadarChart, PolarGrid, PolarAngleAxis, PolarRadiusAxis, ResponsiveContainer, Legend, Tooltip } from 'recharts';
import { GitCompare, Loader2, Check, AlertCircle, Clock, CreditCard, ChevronRight, Globe } from 'lucide-react';
import { Button } from '@/components/ui/button';
import { cn } from '@/lib/utils';
import { Link } from 'react-router-dom';

const dimensions = [
    { key: 'readiness', label: 'Readiness', description: 'Overall GoScore match and profile completeness.' },
    { key: 'risk', label: 'Safety (Risk)', description: 'Lower rejection risk yields a higher score here.' },
    { key: 'timeline', label: 'Speed', description: 'Fewer roadmap steps yields a higher score.' },
    { key: 'costs', label: 'Affordability', description: 'Lower total costs yields a higher score.' },
    { key: 'pr_potential', label: 'PR Potential', description: 'Ease of transition to permanent residency.' },
];

export default function StrategyComparison() {
    const [selectedIds, setSelectedIds] = useState<number[]>([]);
    const { formatCurrency, currency, supported, setCurrency } = useCurrency();

    const { data: pathways, isLoading: loadingPathways } = usePathways();
    const { data: comparisonData, isLoading: loadingComparison } = usePathwayComparison(selectedIds);

    const togglePathway = (id: number) => {
        setSelectedIds(prev => 
            prev.includes(id) 
                ? prev.filter(sid => sid !== id) 
                : prev.length < 3 ? [...prev, id] : prev
        );
    };

    // Transform data for Recharts
    const chartData = dimensions.map(d => {
        const entry: any = { subject: d.label };
        comparisonData?.forEach((c: any) => {
            entry[c.country] = c.dimensions?.[d.key] ?? 50;
        });
        return entry;
    });

    const colors = ['#0B3C91', '#00C2FF', '#10B981'];

    return (
        <div className="max-w-6xl mx-auto space-y-8">
            {/* Header */}
            <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 className="text-3xl font-black text-[#1A1A1A] flex items-center gap-3">
                        <GitCompare className="h-8 w-8 text-[#0B3C91]" />
                        Strategy Mode
                    </h1>
                    <p className="text-slate-500 mt-2">Compare your saved pathways side-by-side to find the optimal relocation strategy.</p>
                </div>
                
                <div className="flex items-center gap-2 bg-white px-3 py-2 rounded-xl border border-slate-200 shadow-sm self-start">
                    <Globe className="h-4 w-4 text-slate-400" />
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

            {/* Selection Area */}
            <div className="bg-white rounded-3xl border border-[#E5E7EB] p-8 shadow-sm">
                <div className="flex items-center justify-between mb-6">
                    <h2 className="text-lg font-bold text-[#1A1A1A]">Select up to 3 pathways</h2>
                    <span className="text-xs font-semibold text-slate-400 uppercase tracking-widest">
                        {selectedIds.length} / 3 selected
                    </span>
                </div>

                {loadingPathways ? (
                    <div className="flex justify-center py-10">
                        <Loader2 className="h-8 w-8 animate-spin text-[#0B3C91]" />
                    </div>
                ) : pathways?.length > 0 ? (
                    <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                        {pathways.map((p: any) => {
                            const isSelected = selectedIds.includes(p.id);
                            return (
                                <button
                                    key={p.id}
                                    onClick={() => togglePathway(p.id)}
                                    className={cn(
                                        "group relative p-4 rounded-2xl border-2 transition-all duration-200 text-left flex items-start gap-4",
                                        isSelected 
                                            ? "border-[#0B3C91] bg-blue-50/50" 
                                            : "border-slate-100 hover:border-slate-300 bg-slate-50/30"
                                    )}
                                >
                                    <div className="w-10 h-10 rounded-full bg-white shadow-sm flex items-center justify-center overflow-hidden border border-slate-100 shrink-0">
                                        {p.country?.image_url ? (
                                            <img src={p.country.image_url} alt={p.country.name} className="w-full h-full object-cover" />
                                        ) : (
                                            <span className="text-xs font-bold text-slate-400">{p.country?.code}</span>
                                        )}
                                    </div>
                                    <div className="flex-1 min-w-0">
                                        <p className={cn("text-sm font-bold truncate", isSelected ? "text-[#0B3C91]" : "text-[#1A1A1A]")}>
                                            {p.country?.name}
                                        </p>
                                        <p className="text-xs text-slate-500 truncate">{p.visa_type?.name}</p>
                                    </div>
                                    <div className="shrink-0 pt-1">
                                        {isSelected && <div className="h-5 w-5 bg-[#0B3C91] rounded-full flex items-center justify-center shadow-sm">
                                            <Check className="h-3 w-3 text-white" />
                                        </div>}
                                    </div>
                                </button>
                            );
                        })}
                    </div>
                ) : (
                    <div className="text-center py-10">
                        <p className="text-sm text-slate-400">You don't have any saved pathways yet.</p>
                        <Link to="/recommendations">
                            <Button variant="outline" className="mt-4 rounded-xl border-[#0B3C91] text-[#0B3C91]">
                                Find Pathways
                            </Button>
                        </Link>
                    </div>
                )}
            </div>

            {/* Comparison Results */}
            {selectedIds.length >= 2 && !loadingComparison && comparisonData && (
                <>
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 animate-in fade-in slide-in-from-bottom-4 duration-500 mt-8">
                    {comparisonData.map((c: any, i: number) => (
                        <div key={c.id} className="bg-white rounded-3xl border border-[#E5E7EB] p-6 shadow-sm border-t-4 flex flex-col" style={{ borderTopColor: colors[i % colors.length] }}>
                            <div className="mb-4 pb-4 border-b border-slate-100 flex justify-between items-start">
                                <div>
                                    <h4 className="font-bold text-[#1A1A1A] text-lg leading-tight truncate">{c.country}</h4>
                                    <p className="text-xs text-[#0B3C91] font-bold uppercase tracking-tight mt-0.5">{c.pathway_type || 'General'}</p>
                                </div>
                                {c.pr_possibility && (
                                    <span className="bg-emerald-50 text-emerald-700 text-[10px] font-black px-2 py-0.5 rounded-full border border-emerald-100">PR PATHWAY</span>
                                )}
                            </div>
                            
                            <div className="space-y-4 flex-1">
                                <div className="flex flex-col gap-1.5 mb-2">
                                    <div className="flex items-center justify-between">
                                        <span className="text-[10px] font-black text-slate-400 uppercase tracking-widest">Success Probability</span>
                                        <span className={cn("text-xs font-black", c.success_probability > 70 ? "text-emerald-600" : c.success_probability > 40 ? "text-amber-600" : "text-red-600")}>
                                            {c.success_probability}%
                                        </span>
                                    </div>
                                    <div className="h-1.5 w-full bg-slate-100 rounded-full overflow-hidden">
                                        <div 
                                            className={cn("h-full transition-all duration-1000", c.success_probability > 70 ? "bg-emerald-500" : c.success_probability > 40 ? "bg-amber-500" : "bg-red-500")}
                                            style={{ width: `${c.success_probability}%` }}
                                        />
                                    </div>
                                </div>

                                <div className="flex items-center justify-between">
                                    <div className="flex items-center gap-2">
                                        <div className="h-6 w-6 rounded-md bg-blue-50 flex items-center justify-center">
                                            <Check className="h-3 w-3 text-[#0B3C91]" />
                                        </div>
                                        <span className="text-xs font-bold text-slate-600">Readiness</span>
                                    </div>
                                    <span className="font-bold text-[#1A1A1A]">{c.readiness_score}/100</span>
                                </div>
                                <div className="flex items-center justify-between">
                                    <div className="flex items-center gap-2">
                                        <div className="h-6 w-6 rounded-md bg-green-50 flex items-center justify-center">
                                            <CreditCard className="h-3 w-3 text-green-600" />
                                        </div>
                                        <span className="text-xs font-bold text-slate-600">Total Costs</span>
                                    </div>
                                    <span className="font-bold text-[#1A1A1A]">{formatCurrency(c.total_cost, true)}</span>
                                </div>
                                
                                <div className="grid grid-cols-2 gap-2 pt-2">
                                    <div className="bg-slate-50/50 p-2 rounded-xl border border-slate-100">
                                        <p className="text-[9px] font-black text-slate-400 uppercase leading-none mb-1">Min. IELTS</p>
                                        <p className="text-xs font-bold text-slate-700">{c.min_ielts || 'N/A'}</p>
                                    </div>
                                    <div className="bg-slate-50/50 p-2 rounded-xl border border-slate-100">
                                        <p className="text-[9px] font-black text-slate-400 uppercase leading-none mb-1">Experience</p>
                                        <p className="text-xs font-bold text-slate-700">{c.min_experience ? `${c.min_experience}Y+` : '0Y+'}</p>
                                    </div>
                                </div>

                                {c.benefits && c.benefits.length > 0 && (
                                    <div className="pt-2">
                                        <p className="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Key Benefits</p>
                                        <div className="flex flex-wrap gap-1.5">
                                            {c.benefits.slice(0, 3).map((benefit: string, bidx: number) => (
                                                <span key={bidx} className="text-[10px] bg-slate-50 text-slate-600 px-2 py-0.5 rounded-md border border-slate-100">{benefit}</span>
                                            ))}
                                        </div>
                                    </div>
                                )}
                            </div>

                            <div className="mt-6 pt-4 border-t border-slate-50 flex items-center justify-between">
                                {c.official_source_link ? (
                                    <a 
                                        href={c.official_source_link} 
                                        target="_blank" 
                                        rel="noopener noreferrer"
                                        className="text-[10px] font-bold text-[#0B3C91] flex items-center gap-1 hover:underline"
                                    >
                                        Official Source <Globe className="h-3 w-3" />
                                    </a>
                                ) : <div />}
                                <div className={cn("font-bold text-[10px] uppercase tracking-wider", c.risk_level === 'High' ? 'text-red-600' : c.risk_level === 'Low' ? 'text-green-600' : 'text-amber-600')}>
                                    {c.risk_level} Risk
                                </div>
                            </div>
                        </div>
                    ))}
                </div>

                <div className="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
                    {/* Visual Strategy Card */}
                    <div className="bg-white rounded-3xl border border-[#E5E7EB] p-8 shadow-sm flex flex-col min-h-[500px]">
                        <div className="mb-8">
                            <h3 className="text-lg font-bold text-[#1A1A1A]">Pathway Radar</h3>
                            <p className="text-xs text-slate-400">Visual overlap of normalized pathway metrics.</p>
                        </div>

                        <div className="flex-1 relative flex items-center justify-center">
                            <div className="w-full h-[350px]">
                                <ResponsiveContainer width="100%" height="100%">
                                    <RadarChart cx="50%" cy="50%" outerRadius="80%" data={chartData}>
                                        <PolarGrid stroke="#E5E7EB" />
                                        <PolarAngleAxis dataKey="subject" tick={{ fill: '#64748b', fontSize: 10, fontWeight: 700 }} />
                                        <PolarRadiusAxis angle={30} domain={[0, 100]} tick={false} axisLine={false} />
                                        {comparisonData.map((c: any, i: number) => (
                                            <Radar
                                                key={c.id}
                                                name={c.country}
                                                dataKey={c.country}
                                                stroke={colors[i % colors.length]}
                                                fill={colors[i % colors.length]}
                                                fillOpacity={0.15}
                                                strokeWidth={3}
                                            />
                                        ))}
                                        <Tooltip 
                                            contentStyle={{ borderRadius: '12px', border: 'none', boxShadow: '0 10px 15px -3px rgb(0 0 0 / 0.1)' }}
                                            itemStyle={{ fontSize: '12px', fontWeight: 'bold' }}
                                        />
                                        <Legend wrapperStyle={{ paddingTop: '20px', fontSize: '10px', fontWeight: 'bold' }} />
                                    </RadarChart>
                                </ResponsiveContainer>
                            </div>
                        </div>
                    </div>

                    {/* Action Card */}
                    <div className="bg-gradient-to-br from-[#0B3C91] to-[#0A2A66] rounded-3xl p-8 shadow-md relative overflow-hidden flex flex-col text-white">
                        <div className="absolute top-[-20%] right-[-10%] w-[300px] h-[300px] bg-[#00C2FF]/20 rounded-full blur-[80px]" />
                        
                        <div className="relative z-10 space-y-6">
                            <div>
                                <h3 className="text-2xl font-black mb-2">Make your choice</h3>
                                <p className="text-blue-200">After reviewing the metrics, select the pathway you want to commit to. We'll set it as your active roadmap.</p>
                            </div>

                            <div className="space-y-3">
                                {comparisonData.map((c: any) => (
                                    <div key={c.id} className="bg-white/10 backdrop-blur-md rounded-2xl p-4 border border-white/10 flex items-center justify-between">
                                        <div>
                                            <div className="font-bold">{c.country}</div>
                                            <div className="text-xs text-blue-200">{c.visa_type}</div>
                                        </div>
                                        {c.status === 'active' ? (
                                            <div className="px-3 py-1.5 bg-green-500/20 text-green-300 rounded-lg text-xs font-bold uppercase tracking-wider flex items-center gap-1.5 border border-green-500/30">
                                                <Check className="w-3 h-3" /> Active
                                            </div>
                                        ) : (
                                            <Button variant="ghost" className="h-8 bg-white text-[#0B3C91] hover:bg-white hover:opacity-90 font-bold rounded-lg text-xs gap-1">
                                                Activate <ChevronRight className="w-3 h-3" />
                                            </Button>
                                        )}
                                    </div>
                                ))}
                            </div>
                        </div>
                    </div>
                </div>
                </>
            )}

            {selectedIds.length > 0 && selectedIds.length < 2 && (
                <div className="bg-slate-50/50 rounded-3xl border-2 border-dashed border-slate-200 py-20 flex flex-col items-center justify-center text-center">
                    <div className="h-20 w-20 bg-white rounded-3xl shadow-sm flex items-center justify-center mb-6">
                        <GitCompare className="h-10 w-10 text-slate-300" />
                    </div>
                    <h3 className="text-lg font-bold text-slate-600 mb-2">Select one more pathway</h3>
                    <p className="text-sm text-slate-400 max-w-sm">You need to select at least two pathways to generate a comparison matrix.</p>
                </div>
            )}
        </div>
    );
}
