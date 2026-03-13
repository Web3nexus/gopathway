import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { useEmployabilityScore } from '@/hooks/useEmployability';
import { Briefcase, Loader2 } from 'lucide-react';
import { Button } from '@/components/ui/button';
import { Link } from 'react-router-dom';

export function EmployabilityScoreCard() {
    const { data, isLoading } = useEmployabilityScore();

    if (isLoading) {
        return (
            <Card className="h-full border-[#E5E7EB] shadow-sm rounded-2xl flex flex-col justify-center items-center py-10">
                <Loader2 className="h-6 w-6 animate-spin text-[#0B3C91]" />
            </Card>
        );
    }

    if (!data?.score) {
        return (
            <Card className="h-full border-[#E5E7EB] shadow-sm rounded-2xl flex flex-col">
                <CardHeader className="pb-2">
                    <CardTitle className="text-base font-bold text-[#1A1A1A] flex items-center gap-2">
                        <Briefcase className="w-4 h-4 text-purple-600" />
                        Employability Score
                    </CardTitle>
                </CardHeader>
                <CardContent className="flex-1 flex flex-col justify-center items-center text-center p-6 bg-slate-50/50 m-4 rounded-xl">
                    <p className="text-sm text-slate-500 mb-4">Complete your profile to see your global employability rating.</p>
                    <Button asChild variant="outline" size="sm" className="rounded-xl font-bold">
                        <Link to="/dashboard/settings">
                            Update Profile
                        </Link>
                    </Button>
                </CardContent>
            </Card>
        );
    }

    const { score, rating, breakdown } = data;

    // Color code based on score
    let colorClass = 'text-green-600';
    let bgClass = 'bg-green-50';
    if (score < 50) {
        colorClass = 'text-red-500';
        bgClass = 'bg-red-50';
    } else if (score < 70) {
        colorClass = 'text-amber-500';
        bgClass = 'bg-amber-50';
    }

    return (
        <Card className="h-full border-[#E5E7EB] shadow-sm rounded-2xl flex flex-col hover:border-[#D1D5DB] transition-all">
            <CardHeader className="pb-2">
                <div className="flex justify-between items-start">
                    <CardTitle className="text-base font-bold text-[#1A1A1A] flex items-center gap-2">
                        <Briefcase className="w-4 h-4 text-purple-600" />
                        Employability Score
                    </CardTitle>
                    <div className={`text-xs font-bold px-2 py-1 rounded-md ${bgClass} ${colorClass}`}>
                        {rating}
                    </div>
                </div>
            </CardHeader>
            <CardContent className="flex-1 flex flex-col">
                <div className="flex items-end justify-between mb-6">
                    <div>
                        <span className="text-4xl font-extrabold text-[#1A1A1A]">{score}</span>
                        <span className="text-sm font-semibold text-slate-400">/100</span>
                    </div>
                    <div className="text-right">
                        <p className="text-xs text-slate-500 font-medium">Market Demand</p>
                    </div>
                </div>

                <div className="space-y-3 mt-auto">
                    <div className="flex justify-between items-center text-sm">
                        <span className="text-slate-500">Occupation Target</span>
                        <span className="font-bold text-slate-900">{breakdown.occupation || 0} pts</span>
                    </div>
                    <div className="flex justify-between items-center text-sm">
                        <span className="text-slate-500">Experience Bonus</span>
                        <span className="font-bold text-slate-900">{breakdown.experience || 0} pts</span>
                    </div>
                    <div className="flex justify-between items-center text-sm">
                        <span className="text-slate-500">Education Level</span>
                        <span className="font-bold text-slate-900">{breakdown.education || 0} pts</span>
                    </div>
                    <div className="flex justify-between items-center text-sm">
                        <span className="text-slate-500">Language (IELTS)</span>
                        <span className="font-bold text-slate-900">{breakdown.language || 0} pts</span>
                    </div>
                </div>
            </CardContent>
        </Card>
    );
}
