import { Clock, AlertCircle, Calendar, ArrowRight } from 'lucide-react';
import { Progress } from '@/components/ui/progress';
import { Button } from '@/components/ui/button';
import { cn } from '@/lib/utils';
import { format } from 'date-fns';
import { useCurrency } from '@/contexts/CurrencyContext';

interface SavingsProjection {
    total_cost: number;
    current_savings: number;
    gap: number;
    months_to_ready: number | null;
    projected_ready_date: string | null;
    status: 'on-track' | 'ahead' | 'behind';
    message: string;
    percentage: number;
}

interface SavingsGoalCardProps {
    projection: SavingsProjection;
    onEdit: () => void;
    currentMonthlyTarget: number;
}

export function SavingsGoalCard({ projection, onEdit, currentMonthlyTarget }: SavingsGoalCardProps) {
    const isBehind = projection.status === 'behind';
    const isAhead = projection.status === 'ahead';
    const { formatCurrency } = useCurrency();

    return (
        <div className="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm">
            <div className="p-6">
                <div className="flex items-center justify-between mb-6">
                    <div>
                        <h3 className="text-lg font-bold text-slate-900">Savings Roadmap</h3>
                        <p className="text-sm text-slate-500">Your path to financial readiness</p>
                    </div>
                    <div className={cn(
                        "px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider",
                        isBehind ? "bg-red-100 text-red-600" : isAhead ? "bg-green-100 text-green-600" : "bg-blue-100 text-blue-600"
                    )}>
                        {projection.status.replace('-', ' ')}
                    </div>
                </div>

                <div className="space-y-6">
                    {/* Progress */}
                    <div>
                        <div className="flex justify-between items-end mb-2">
                            <span className="text-2xl font-black text-slate-900">
                                {projection.percentage}%
                                <span className="text-sm font-medium text-slate-400 ml-2">funded</span>
                            </span>
                            <span className="text-sm font-bold text-slate-600">
                                {formatCurrency(projection.current_savings, true)} / {formatCurrency(projection.total_cost, true)}
                            </span>
                        </div>
                        <Progress value={projection.percentage} className="h-3 bg-slate-100" />
                    </div>

                    {/* Stats Grid */}
                    <div className="grid grid-cols-2 gap-4">
                        <div className="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                            <div className="flex items-center gap-2 mb-1">
                                <Clock className="h-3.5 w-3.5 text-slate-400" />
                                <span className="text-[10px] font-bold text-slate-500 uppercase tracking-tight">Time to Ready</span>
                            </div>
                            <p className="text-lg font-bold text-slate-900">
                                {projection.months_to_ready !== null ? `${projection.months_to_ready} mo` : 'N/A'}
                            </p>
                        </div>
                        <div className="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                            <div className="flex items-center gap-2 mb-1">
                                <Calendar className="h-3.5 w-3.5 text-slate-400" />
                                <span className="text-[10px] font-bold text-slate-500 uppercase tracking-tight">Est. Date</span>
                            </div>
                            <p className="text-lg font-bold text-slate-900">
                                {projection.projected_ready_date ? format(new Date(projection.projected_ready_date), 'MMM yyyy') : 'Set Target'}
                            </p>
                        </div>
                    </div>

                    {/* Feedback Message */}
                    {(isBehind || isAhead) && (
                        <div className={cn(
                            "flex gap-3 p-4 rounded-2xl text-sm leading-relaxed",
                            isBehind ? "bg-red-50 text-red-700 border border-red-100" : "bg-green-50 text-green-700 border border-green-100"
                        )}>
                            <AlertCircle className="h-5 w-5 shrink-0" />
                            <p>{projection.message}</p>
                        </div>
                    )}

                    {/* Action */}
                    <div className="pt-2">
                        <div className="flex items-center justify-between p-4 bg-[#0B3C91]/5 rounded-2xl mb-4">
                            <div>
                                <p className="text-[10px] font-bold text-[#0B3C91] uppercase tracking-wider">Monthly Goal</p>
                                <p className="text-base font-bold text-slate-900">{formatCurrency(currentMonthlyTarget, true)}/mo</p>
                            </div>
                            <Button variant="ghost" size="sm" onClick={onEdit} className="text-[#0B3C91] hover:bg-[#0B3C91]/10 font-bold gap-2">
                                Adjust Plan <ArrowRight className="h-4 w-4" />
                            </Button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}
