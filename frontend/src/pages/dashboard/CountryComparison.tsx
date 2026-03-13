import { useState } from 'react';
import { useQuery } from '@tanstack/react-query';
import { countryService } from '@/services/api/countryService';
import { Radar, RadarChart, PolarGrid, PolarAngleAxis, PolarRadiusAxis, ResponsiveContainer, Legend, Tooltip } from 'recharts';
import { Scale, Check, Loader2, ShieldCheck, Lock, Globe } from 'lucide-react';
import { Button } from '@/components/ui/button';
import { useAuth } from '@/hooks/useAuth';
import { useCurrency } from '@/contexts/CurrencyContext';
import { cn } from '@/lib/utils';
import { Link } from 'react-router-dom';

const dimensions = [
    { key: 'visa_difficulty', label: 'Visa Ease', fullLabel: 'Ease of Obtaining Visa', description: 'Measures complexity, rejection rates, and documentation burden.' },
    { key: 'cost_index', label: 'Affordability', fullLabel: 'Cost of Living & Process', description: 'Includes government fees, proof of funds, and local living costs.' },
    { key: 'processing_speed', label: 'Speed', fullLabel: 'Processing Speed', description: 'Average time from submission to visa approval.' },
    { key: 'pr_ease', label: 'PR Ease', fullLabel: 'Ease of Permanent Residency', description: 'Likelihood and timeframe for transitioning to residency.' },
    { key: 'job_market', label: 'Job Market', fullLabel: 'Employability & Market', description: 'Demand for international talent and salary benchmarks.' },
];

export default function CountryComparison() {
    const { user } = useAuth();
    const { currency, supported, setCurrency } = useCurrency();
    const isPremium = user?.is_premium;
    const [selectedIds, setSelectedIds] = useState<any[]>([]);

    const { data: countryScores, isLoading: loadingScores } = useQuery({
        queryKey: ['country-scores'],
        queryFn: countryService.getScores,
    });

    // Use local data instead of a second API call for faster response
    const comparisonData = countryScores?.filter((c: any) => 
        selectedIds.some(sid => sid == c.id)
    );

    const toggleCountry = (id: any) => {
        setSelectedIds(prev => 
            prev.some(sid => sid == id) 
                ? prev.filter(sid => sid != id) 
                : prev.length < 3 ? [...prev, id] : prev
        );
    };

    // Transform data for Recharts
    const chartData = dimensions.map(d => {
        const entry: any = { subject: d.label, fullLabel: d.fullLabel };
        comparisonData?.forEach((c: any) => {
            let val = c.scores?.[d.key] ?? 50;
            if (d.key === 'visa_difficulty' || d.key === 'cost_index') {
                val = 100 - val;
            }
            entry[c.name] = val;
        });
        return entry;
    });

    const colors = ['#0B3C91', '#00C2FF', '#10B981'];

    // Helper to find the "Winning Feature"
    const getWinner = (country: any) => {
        const scores = dimensions.map(d => ({
            label: d.label,
            val: (d.key === 'visa_difficulty' || d.key === 'cost_index') ? 100 - country.scores[d.key] : country.scores[d.key]
        }));
        const top = scores.sort((a, b) => b.val - a.val)[0];
        return top.label;
    };

    return (
        <div className="max-w-6xl mx-auto space-y-8">
            {/* Header */}
            <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 className="text-3xl font-black text-[#1A1A1A] flex items-center gap-3">
                        <Scale className="h-8 w-8 text-[#0B3C91]" />
                        Country Competitiveness Index
                    </h1>
                    <p className="text-slate-500 mt-2">Compare destination countries side-by-side to make data-driven immigration decisions.</p>
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
                    <h2 className="text-lg font-bold text-[#1A1A1A]">Select up to 3 countries</h2>
                    <span className="text-xs font-semibold text-slate-400 uppercase tracking-widest">
                        {selectedIds.length} / 3 selected
                    </span>
                </div>

                {loadingScores ? (
                    <div className="flex justify-center py-10">
                        <Loader2 className="h-8 w-8 animate-spin text-[#0B3C91]" />
                    </div>
                ) : countryScores?.length > 0 ? (
                    <div className="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                        {countryScores.map((c: any) => {
                            const isSelected = selectedIds.includes(c.id);
                            return (
                                <button
                                    key={c.id}
                                    onClick={() => toggleCountry(c.id)}
                                    className={cn(
                                        "group relative p-4 rounded-2xl border-2 transition-all duration-200 text-left",
                                        isSelected 
                                            ? "border-[#0B3C91] bg-blue-50/50" 
                                            : "border-slate-100 hover:border-slate-300 bg-slate-50/30"
                                    )}
                                >
                                    <div className="flex items-center justify-between mb-2">
                                        <div className="w-8 h-8 rounded-full bg-white shadow-sm flex items-center justify-center overflow-hidden border border-slate-100">
                                            {c.image_url ? (
                                                <img src={c.image_url} alt={c.name} className="w-full h-full object-cover" />
                                            ) : (
                                                <span className="text-[10px] font-bold text-slate-400">{c.code}</span>
                                            )}
                                        </div>
                                        {isSelected && <div className="h-5 w-5 bg-[#0B3C91] rounded-full flex items-center justify-center shadow-sm">
                                            <Check className="h-3 w-3 text-white" />
                                        </div>}
                                    </div>
                                    <p className={cn("text-xs font-bold truncate", isSelected ? "text-[#0B3C91]" : "text-slate-600")}>
                                        {c.name}
                                    </p>
                                </button>
                            );
                        })}
                    </div>
                ) : (
                    <div className="text-center py-10">
                        <p className="text-sm text-slate-400">No active countries available for comparison yet.</p>
                    </div>
                )}
            </div>

            {/* Comparison Results */}
            {selectedIds.length > 0 && (
                <>
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 animate-in fade-in slide-in-from-bottom-4 duration-500 mt-8">
                    {comparisonData?.map((c: any, i: number) => (
                        <div key={c.id} className="bg-white rounded-3xl border border-[#E5E7EB] p-6 shadow-sm border-t-4" style={{ borderTopColor: colors[i % colors.length] }}>
                            <div className="flex items-center gap-3 mb-4">
                                <div className="w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center overflow-hidden border border-slate-100">
                                    <img src={c.image_url} alt={c.name} className="w-full h-full object-cover" />
                                </div>
                                <div>
                                    <h4 className="font-bold text-[#1A1A1A] leading-tight">{c.name}</h4>
                                    <p className="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Winning Feature: {getWinner(c)}</p>
                                </div>
                            </div>
                            <div className="flex items-baseline gap-1">
                                <span className="text-2xl font-black text-[#1A1A1A]">
                                    {Math.round((
                                        (100 - c.scores.visa_difficulty) + 
                                        (100 - c.scores.cost_index) + 
                                        c.scores.processing_speed + 
                                        c.scores.pr_ease + 
                                        c.scores.job_market
                                    ) / 5)}%
                                </span>
                                <span className="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">Strategic Index</span>
                            </div>
                        </div>
                    ))}
                </div>

                <div className="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
                    {/* Visual Strategy Card */}
                    <div className="bg-white rounded-3xl border border-[#E5E7EB] p-8 shadow-sm flex flex-col min-h-[500px]">
                        <div className="flex items-center justify-between mb-8">
                            <div>
                                <h3 className="text-lg font-bold text-[#1A1A1A]">Strategic Comparison</h3>
                                <p className="text-xs text-slate-400">Holistic view of competitiveness across 5 metrics.</p>
                            </div>
                            {!isPremium && (
                                <div className="flex items-center gap-1.5 px-3 py-1 bg-amber-50 rounded-full text-[10px] font-bold text-amber-600 border border-amber-100 uppercase tracking-widest">
                                    <Lock className="h-3 w-3" /> Premium
                                </div>
                            )}
                        </div>

                        <div className="flex-1 relative flex items-center justify-center">
                            {isPremium ? (
                                <div className="w-full h-[350px]">
                                    <ResponsiveContainer width="100%" height="100%">
                                        <RadarChart cx="50%" cy="50%" outerRadius="80%" data={chartData}>
                                            <PolarGrid stroke="#E5E7EB" />
                                            <PolarAngleAxis dataKey="subject" tick={{ fill: '#64748b', fontSize: 10, fontWeight: 700 }} />
                                            <PolarRadiusAxis angle={30} domain={[0, 100]} tick={false} axisLine={false} />
                                            {comparisonData?.map((c: any, i: number) => (
                                                <Radar
                                                    key={c.id}
                                                    name={c.name}
                                                    dataKey={c.name}
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
                            ) : (
                                <div className="absolute inset-0 flex flex-col items-center justify-center text-center p-8 bg-white/40 backdrop-blur-[2px] z-10 rounded-2xl">
                                    <div className="h-16 w-16 bg-blue-50 rounded-2xl flex items-center justify-center mb-4">
                                        <Lock className="h-8 w-8 text-[#0B3C91]" />
                                    </div>
                                    <h4 className="text-base font-bold text-[#1A1A1A] mb-2">Visual Index Locked</h4>
                                    <p className="text-xs text-slate-500 mb-6 max-w-xs">Upgrade to Premium to unlock interactive radar charts and deep strategic insights.</p>
                                    <Link to="/pricing">
                                        <Button className="bg-[#0B3C91] hover:bg-[#0B3C91]/90 text-white rounded-xl font-bold px-8">
                                            View Plans
                                        </Button>
                                    </Link>
                                    
                                    <div className="mt-8 opacity-10 pointer-events-none select-none">
                                        <Scale className="h-32 w-32" />
                                    </div>
                                </div>
                            )}
                        </div>
                    </div>

                    {/* Data Table Card */}
                    <div className="bg-white rounded-3xl border border-[#E5E7EB] p-8 shadow-sm">
                        <div className="flex items-center justify-between mb-6">
                            <h3 className="text-lg font-bold text-[#1A1A1A]">Detailed Metrics</h3>
                            <span className="text-[10px] font-medium text-slate-400 uppercase tracking-widest">Normalized Score (0-100)</span>
                        </div>
                        
                        <div className="space-y-8">
                            {dimensions.map(d => (
                                <div key={d.key} className="space-y-3">
                                    <div>
                                        <div className="flex items-center justify-between">
                                            <span className="text-sm font-bold text-slate-700">{d.fullLabel}</span>
                                            <span className="text-[10px] font-medium text-slate-400 uppercase tracking-widest">Higher is Better</span>
                                        </div>
                                        <p className="text-[10px] text-slate-400 mt-0.5 leading-relaxed">{d.description}</p>
                                    </div>
                                    <div className="space-y-2">
                                        {comparisonData?.map((c: any, i: number) => {
                                            let val = c.scores?.[d.key] ?? 50;
                                            if (d.key === 'visa_difficulty' || d.key === 'cost_index') {
                                                val = 100 - val;
                                            }
                                            return (
                                                <div key={c.id} className="flex items-center gap-3">
                                                    <div className="w-24 text-[10px] font-bold text-slate-400 truncate tracking-tight">{c.name}</div>
                                                    <div className="flex-1 h-2 bg-slate-50 rounded-full overflow-hidden">
                                                        <div 
                                                            className="h-full rounded-full transition-all duration-700" 
                                                            style={{ 
                                                                width: `${val}%`, 
                                                                backgroundColor: colors[i % colors.length] 
                                                            }}
                                                        />
                                                    </div>
                                                    <div className="w-8 text-right text-xs font-black text-[#1A1A1A]">{val}</div>
                                                </div>
                                            );
                                        })}
                                    </div>
                                </div>
                            ))}
                        </div>

                        <div className="mt-10 p-4 bg-blue-50/50 rounded-2xl border border-blue-100">
                            <div className="flex gap-3">
                                <ShieldCheck className="h-5 w-5 text-[#0B3C91] shrink-0" />
                                <div>
                                    <p className="text-xs font-bold text-[#0B3C91] mb-1">Methodology Note</p>
                                    <p className="text-[10px] text-blue-800/70 leading-relaxed">
                                        Scores are calculated based on official government processing times, current living cost indices, and recent PR success rates. Data is updated monthly.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </>
            )}

            {!selectedIds.length && (
                <div className="bg-slate-50/50 rounded-3xl border-2 border-dashed border-slate-200 py-20 flex flex-col items-center justify-center text-center">
                    <div className="h-20 w-20 bg-white rounded-3xl shadow-sm flex items-center justify-center mb-6">
                        <Scale className="h-10 w-10 text-slate-300" />
                    </div>
                    <h3 className="text-lg font-bold text-slate-600 mb-2">Ready to compare?</h3>
                    <p className="text-sm text-slate-400 max-w-sm">Select at least one country from the grid above to start analyzing competitiveness.</p>
                </div>
            )}
        </div>
    );
}
