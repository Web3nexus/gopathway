import React from 'react';
import { ExternalLink, Star, ShieldCheck, Info } from 'lucide-react';
import { Button } from '@/components/ui/button';

interface FinanceProvider {
    id: number;
    name: string;
    provider_type: string;
    website: string;
    description: string;
    rating: number;
}

interface FinancingCardProps {
    provider: FinanceProvider;
}

export const FinancingCard: React.FC<FinancingCardProps> = ({ provider }) => {
    return (
        <div className="bg-white rounded-3xl border border-[#E5E7EB] p-6 shadow-sm hover:shadow-md transition-all duration-300 group">
            <div className="flex justify-between items-start mb-4">
                <div className="flex items-center gap-3">
                    <div className="h-10 w-10 rounded-xl bg-[#0B3C91]/5 flex items-center justify-center text-[#0B3C91]">
                        <ShieldCheck className="h-6 w-6" />
                    </div>
                    <div>
                        <h4 className="font-bold text-[#1A1A1A] group-hover:text-[#0B3C91] transition-colors">{provider.name}</h4>
                        <span className="text-[10px] font-black uppercase tracking-widest text-[#6B7280]">{provider.provider_type}</span>
                    </div>
                </div>
                <div className="flex items-center gap-1 bg-amber-50 px-2 py-1 rounded-lg">
                    <Star className="h-3 w-3 text-amber-500 fill-amber-500" />
                    <span className="text-xs font-bold text-amber-700">{provider.rating}</span>
                </div>
            </div>

            <p className="text-sm text-[#4B5563] leading-relaxed mb-6 line-clamp-2">
                {provider.description}
            </p>

            <div className="flex items-center justify-between mt-auto pt-4 border-t border-[#F3F4F6]">
                <div className="flex items-center gap-1.5 text-[#0B3C91]">
                    <Info className="h-3.5 w-3.5" />
                    <span className="text-[10px] font-bold uppercase">Verified Partner</span>
                </div>
                <Button
                    variant="outline"
                    size="sm"
                    className="rounded-xl border-[#E5E7EB] hover:bg-[#0B3C91] hover:text-white hover:border-[#0B3C91] transition-all group/btn"
                    onClick={() => window.open(provider.website, '_blank')}
                >
                    <span className="text-xs font-bold">Apply Now</span>
                    <ExternalLink className="h-3 w-3 ml-2 group-hover/btn:translate-x-0.5 group-hover/btn:-translate-y-0.5 transition-transform" />
                </Button>
            </div>
        </div>
    );
};
