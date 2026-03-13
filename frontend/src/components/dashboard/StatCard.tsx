import type { LucideIcon } from 'lucide-react';

interface StatCardProps {
    title: string;
    value: string | number;
    icon: LucideIcon;
    trend?: {
        value: number;
        isPositive: boolean;
    };
}

export function StatCard({ title, value, icon: Icon, trend }: StatCardProps) {
    return (
        <div className="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
            <div className="flex items-center justify-between mb-4">
                <div className="h-10 w-10 rounded-xl bg-blue-50 flex items-center justify-center">
                    <Icon className="h-5 w-5 text-blue-600" />
                </div>
                {trend && (
                    <div className={`flex items-center gap-1 text-xs font-bold ${trend.isPositive ? 'text-green-600 bg-green-50' : 'text-red-600 bg-red-50'} px-2 py-1 rounded-full`}>
                        {trend.isPositive ? '+' : '-'}{trend.value}%
                    </div>
                )}
            </div>
            <div>
                <p className="text-sm font-medium text-slate-500 uppercase tracking-wider">{title}</p>
                <div className="flex items-baseline gap-2 mt-1">
                    <h3 className="text-2xl font-bold text-slate-900">{value}</h3>
                </div>
            </div>
        </div>
    );
}
