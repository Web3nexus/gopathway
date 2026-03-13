import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import { recommendationService } from '@/services/api/recommendationService';
import { pathwayService } from '@/services/api/pathwayService';
import { Globe, GraduationCap, Briefcase, Wallet, Languages, CheckCircle2, ChevronRight, Loader2, Award, AlertCircle } from 'lucide-react';
import { Button } from '@/components/ui/button';
import { useToast } from '@/hooks/use-toast';
import { useNavigate } from 'react-router-dom';
import { useCurrency } from '@/contexts/CurrencyContext';

export default function Recommendations() {
    const { toast } = useToast();
    const navigate = useNavigate();
    const queryClient = useQueryClient();
    const { formatCurrency } = useCurrency();

    const { data: recommendations = [], isLoading } = useQuery({
        queryKey: ['recommendations'],
        queryFn: async () => {
            const res = await recommendationService.getRecommendations();
            return res.data;
        }
    });

    const selectPathwayMutation = useMutation({
        mutationFn: (data: { country_id: number, visa_type_id: number }) =>
            pathwayService.selectPathway(data),
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['dashboard'] });
            queryClient.invalidateQueries({ queryKey: ['platform-flags'] });
            queryClient.invalidateQueries({ queryKey: ['settlement-steps'] });
            queryClient.invalidateQueries({ queryKey: ['relocation-kits'] });
            toast({ title: 'Pathway selected!', description: 'Your dashboard has been updated.' });
            navigate('/dashboard');
        }
    });

    if (isLoading) {
        return (
            <div className="flex flex-col items-center justify-center p-20 space-y-4">
                <Loader2 className="h-10 w-10 animate-spin text-[#0B3C91]" />
                <p className="text-slate-500 font-medium">Analyzing global opportunities for you...</p>
            </div>
        );
    }

    return (
        <div className="max-w-6xl mx-auto space-y-8">
            <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 className="text-3xl font-bold text-[#1A1A1A]">Recommended Pathways</h1>
                    <p className="text-[#6B7280] mt-1">Based on your professional profile and available funds</p>
                </div>
                <div className="flex items-center gap-2 px-4 py-2 bg-blue-50 text-[#0B3C91] rounded-xl text-sm font-bold border border-blue-100">
                    <Award className="w-4 h-4" />
                    {recommendations.length} Potential Matches Found
                </div>
            </div>

            <div className="grid grid-cols-1 gap-6">
                {recommendations.map((rec: any) => (
                    <div key={rec.visa_type.id} className="group bg-white rounded-2xl border border-[#E5E7EB] hover:border-[#00C2FF] hover:shadow-xl hover:shadow-blue-50/50 transition-all duration-300 overflow-hidden">
                        <div className="p-6 md:p-8">
                            <div className="flex flex-col md:flex-row gap-6 md:gap-8">
                                {/* Match Score Column */}
                                <div className="flex flex-col items-center justify-center text-center space-y-2 min-w-[120px]">
                                    <div className="relative h-24 w-24 flex items-center justify-center">
                                        <svg className="h-full w-full -rotate-90">
                                            <circle cx="48" cy="48" r="40" fill="transparent" stroke="#F1F5F9" strokeWidth="8" />
                                            <circle
                                                cx="48" cy="48" r="40" fill="transparent"
                                                stroke={rec.match_percentage >= 80 ? '#22C55E' : rec.match_percentage >= 50 ? '#0B3C91' : '#F59E0B'}
                                                strokeWidth="8"
                                                strokeDasharray={2 * Math.PI * 40}
                                                strokeDashoffset={2 * Math.PI * 40 * (1 - rec.match_percentage / 100)}
                                                strokeLinecap="round"
                                                className="transition-all duration-1000"
                                            />
                                        </svg>
                                        <span className="absolute text-2xl font-extrabold text-[#1A1A1A]">{rec.match_percentage}%</span>
                                    </div>
                                    <span className="text-[10px] uppercase tracking-wider font-bold text-slate-400">Match Score</span>
                                </div>

                                {/* Content Column */}
                                <div className="flex-1 space-y-4">
                                    <div className="flex items-start justify-between gap-4">
                                        <div>
                                            <div className="flex items-center gap-2 mb-1">
                                                <span className="px-2 py-0.5 bg-slate-100 rounded text-[10px] font-bold text-slate-600 uppercase tracking-tight">
                                                    {rec.visa_type.country?.name}
                                                </span>
                                            </div>
                                            <h3 className="text-xl font-bold text-[#1A1A1A] leading-tight">{rec.visa_type.name}</h3>
                                        </div>
                                        <Button
                                            onClick={() => selectPathwayMutation.mutate({
                                                country_id: rec.visa_type.country_id,
                                                visa_type_id: rec.visa_type.id
                                            })}
                                            disabled={selectPathwayMutation.isPending}
                                            className="bg-[#0B3C91] text-white hover:bg-[#0B3C91]/90 rounded-xl px-6"
                                        >
                                            {selectPathwayMutation.isPending ? 'Selecting...' : 'Select Pathway'}
                                            <ChevronRight className="ml-2 h-4 w-4" />
                                        </Button>
                                    </div>

                                    <p className="text-sm text-[#6B7280] line-clamp-2 md:max-w-2xl">{rec.visa_type.description}</p>

                                    {/* Stats Grid */}
                                    <div className="grid grid-cols-2 md:grid-cols-4 gap-4 py-4 border-t border-b border-slate-50">
                                        <div className="space-y-1">
                                            <p className="text-[10px] text-slate-400 font-bold uppercase">Estimated Time</p>
                                            <div className="flex items-center gap-1.5 text-sm font-semibold text-slate-700">
                                                <Globe className="w-3.5 h-3.5 text-blue-500" /> {rec.visa_type.processing_time}
                                            </div>
                                        </div>
                                        <div className="space-y-1">
                                            <p className="text-[10px] text-slate-400 font-bold uppercase">Min Education</p>
                                            <div className="flex items-center gap-1.5 text-sm font-semibold text-slate-700">
                                                <GraduationCap className="w-3.5 h-3.5 text-cyan-500" /> {rec.visa_type.min_education_level || 'HND / B.Sc'}
                                            </div>
                                        </div>
                                        <div className="space-y-1">
                                            <p className="text-[10px] text-slate-400 font-bold uppercase">Work Exp</p>
                                            <div className="flex items-center gap-1.5 text-sm font-semibold text-slate-700">
                                                <Briefcase className="w-3.5 h-3.5 text-indigo-500" /> {rec.visa_type.min_work_experience_years}+ Years
                                            </div>
                                        </div>
                                        <div className="space-y-1">
                                            <p className="text-[10px] text-slate-400 font-bold uppercase">Min Funds</p>
                                            <div className="flex items-center gap-1.5 text-sm font-semibold text-slate-700">
                                                <Wallet className="w-3.5 h-3.5 text-green-500" />
                                                {rec.visa_type.min_funds_required
                                                    ? formatCurrency(rec.visa_type.min_funds_required, true)
                                                    : 'Varies'}
                                            </div>
                                        </div>
                                    </div>

                                    {/* Improvements Checklist */}
                                    {rec.improvements.length > 0 && (
                                        <div className="bg-amber-50/50 rounded-xl p-4 border border-amber-100/50">
                                            <div className="flex items-center gap-2 text-amber-700 text-xs font-bold uppercase tracking-wider mb-3">
                                                <AlertCircle className="w-4 h-4" /> Recommendation to improve match
                                            </div>
                                            <div className="grid grid-cols-1 md:grid-cols-2 gap-2">
                                                {rec.improvements.map((imp: string, i: number) => (
                                                    <div key={i} className="flex items-start gap-2 text-xs text-amber-900/80">
                                                        <div className="h-1.5 w-1.5 rounded-full bg-amber-400 mt-1 shrink-0" />
                                                        {imp}
                                                    </div>
                                                ))}
                                            </div>
                                        </div>
                                    )}

                                    {rec.match_percentage === 100 && (
                                        <div className="flex items-center gap-2 text-green-600 text-xs font-bold uppercase tracking-wider">
                                            <CheckCircle2 className="w-4 h-4" /> Fully Eligible — Highly Recommended
                                        </div>
                                    )}
                                </div>
                            </div>
                        </div>
                    </div>
                ))}
            </div>

            {recommendations.length === 0 && (
                <div className="bg-white rounded-3xl p-20 text-center border shadow-sm">
                    <Globe className="w-16 h-16 text-slate-200 mx-auto mb-6" />
                    <h2 className="text-2xl font-bold text-slate-900 mb-2">Refine Your Profile</h2>
                    <p className="text-slate-500 max-w-md mx-auto mb-8">
                        We couldn't find any direct matches yet. Try updating your profile with more details about your experience and funds.
                    </p>
                    <Button onClick={() => navigate('/profile/setup')} className="bg-[#0B3C91]">
                        Update Profile
                    </Button>
                </div>
            )}
        </div>
    );
}
