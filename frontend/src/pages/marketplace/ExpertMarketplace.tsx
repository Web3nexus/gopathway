import { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { useQuery } from '@tanstack/react-query';
import { bookingService } from '@/services/api/bookingService';
import {
    Search,
    ShieldCheck,
    Star,
    MessageSquare,
    Calendar,
    Languages,
    Briefcase,
    Clock
} from 'lucide-react';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import BookingModal from '@/components/marketplace/BookingModal';

export default function ExpertMarketplace() {
    const navigate = useNavigate();
    const [searchTerm, setSearchTerm] = useState('');
    const [typeFilter, setTypeFilter] = useState<'all' | 'lawyer' | 'translator'>('all');
    const [selectedExpert, setSelectedExpert] = useState<any>(null);

    const { data: experts, isLoading } = useQuery({
        queryKey: ['marketplace-experts'],
        queryFn: bookingService.getMarketplace,
    });

    const filteredExperts = experts?.filter((expert: any) => {
        const matchesSearch = expert.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
            expert.professional_profile?.bio?.toLowerCase().includes(searchTerm.toLowerCase());
        const matchesType = typeFilter === 'all' || expert.professional_profile?.type === typeFilter;
        return matchesSearch && matchesType;
    });

    return (
        <div className="max-w-7xl mx-auto p-6 space-y-8">
            <div className="flex flex-col md:flex-row md:items-end justify-between gap-6">
                <div className="space-y-2">
                    <h1 className="text-3xl font-black text-[#1A1A1A] tracking-tight">Expert Marketplace</h1>
                    <p className="text-[#6B7280] max-w-lg">
                        Connect with verified immigration lawyers and certified translators to accelerate your relocation journey.
                    </p>
                </div>

                <div className="flex flex-col sm:flex-row gap-4 w-full md:w-auto">
                    <div className="relative flex-1 sm:w-64">
                        <Search className="absolute left-3 top-2.5 h-4 w-4 text-gray-400" />
                        <Input
                            placeholder="Search by name or expertise..."
                            className="pl-9 h-10 border-[#E5E7EB] rounded-xl focus:ring-[#0B3C91]"
                            value={searchTerm}
                            onChange={(e) => setSearchTerm(e.target.value)}
                        />
                    </div>
                    <div className="flex bg-[#F3F4F6] p-1 rounded-xl">
                        <Button
                            variant={typeFilter === 'all' ? 'secondary' : 'ghost'}
                            size="sm"
                            className={`rounded-lg px-4 ${typeFilter === 'all' ? 'bg-white shadow-sm font-bold' : ''}`}
                            onClick={() => setTypeFilter('all')}
                        >
                            All
                        </Button>
                        <Button
                            variant={typeFilter === 'lawyer' ? 'secondary' : 'ghost'}
                            size="sm"
                            className={`rounded-lg px-4 ${typeFilter === 'lawyer' ? 'bg-white shadow-sm font-bold' : ''}`}
                            onClick={() => setTypeFilter('lawyer')}
                        >
                            Lawyers
                        </Button>
                        <Button
                            variant={typeFilter === 'translator' ? 'secondary' : 'ghost'}
                            size="sm"
                            className={`rounded-lg px-4 ${typeFilter === 'translator' ? 'bg-white shadow-sm font-bold' : ''}`}
                            onClick={() => setTypeFilter('translator')}
                        >
                            Translators
                        </Button>
                    </div>
                </div>
            </div>

            {isLoading ? (
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 animate-pulse">
                    {[1, 2, 3, 4, 5, 6].map(i => (
                        <div key={i} className="h-80 bg-white rounded-3xl border border-[#E5E7EB]" />
                    ))}
                </div>
            ) : (
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    {filteredExperts?.length > 0 ? (
                        filteredExperts.map((expert: any) => (
                            <div key={expert.id} className="group bg-white rounded-3xl border border-[#E5E7EB] shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden flex flex-col">
                                <div className="p-6 flex-1 space-y-6">
                                    <div className="flex items-start justify-between">
                                        <div className="flex items-center gap-4">
                                            <div className="h-16 w-16 rounded-2xl bg-[#0B3C91]/5 flex items-center justify-center font-black text-2xl text-[#0B3C91] border border-[#0B3C91]/10">
                                                {expert.name.charAt(0)}
                                            </div>
                                            <div>
                                                <div className="flex items-center gap-2">
                                                    <h3 className="font-bold text-lg text-[#1A1A1A]">{expert.name}</h3>
                                                    {expert.professional_profile?.is_verified && (
                                                        <ShieldCheck className="h-4 w-4 text-[#00C2FF]" fill="currentColor" />
                                                    )}
                                                </div>
                                                <div className="flex items-center gap-1 text-sm text-[#0B3C91] font-bold">
                                                    <Briefcase className="h-3.5 w-3.5" />
                                                    <span className="capitalize">{expert.professional_profile?.type}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div className="flex flex-col items-end">
                                            <div className="flex items-center gap-1 text-amber-500 font-bold">
                                                <Star className="h-4 w-4 fill-current" />
                                                <span>4.9</span>
                                            </div>
                                            <span className="text-[10px] text-[#6B7280] font-medium">12+ Reviews</span>
                                        </div>
                                    </div>

                                    <p className="text-sm text-[#6B7280] line-clamp-3 leading-relaxed">
                                        {expert.professional_profile?.bio}
                                    </p>

                                    <div className="flex flex-wrap gap-2 text-xs">
                                        {expert.professional_profile?.specialization?.slice(0, 3).map((spec: string, i: number) => (
                                            <Badge key={i} variant="secondary" className="bg-[#F3F4F6] text-[#4B5563] border-none font-medium px-2.5 py-0.5">
                                                {spec}
                                            </Badge>
                                        ))}
                                        {expert.professional_profile?.specialization?.length > 3 && (
                                            <span className="text-gray-400 font-medium">+{expert.professional_profile.specialization.length - 3} more</span>
                                        )}
                                    </div>

                                    <div className="grid grid-cols-2 gap-4 pt-2">
                                        <div className="flex items-center gap-2 text-[#6B7280] text-sm">
                                            <Languages className="h-4 w-4" />
                                            <span className="truncate">{expert.professional_profile?.languages?.join(', ')}</span>
                                        </div>
                                        <div className="flex items-center gap-2 text-[#6B7280] text-sm">
                                            <Clock className="h-4 w-4" />
                                            <span>
                                                {expert.professional_profile?.hourly_rate
                                                    ? `${expert.professional_profile.currency} ${expert.professional_profile.hourly_rate}/hr`
                                                    : 'Rate on request'}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div className="p-6 bg-gray-50 border-t border-[#E5E7EB] flex items-center gap-3">
                                    <Button
                                        variant="outline"
                                        className="flex-1 rounded-xl h-11 font-bold border-[#E5E7EB] text-[#1A1A1A] hover:bg-white"
                                        onClick={() => navigate(`/messages?userId=${expert.id}&userName=${encodeURIComponent(expert.name)}`)}
                                    >
                                        <MessageSquare className="h-4 w-4 mr-2" /> Message
                                    </Button>
                                    <Button
                                        className="flex-1 rounded-xl h-11 font-extrabold bg-[#0B3C91] hover:bg-[#0B3C91]/90 text-white shadow-lg shadow-blue-900/10"
                                        onClick={() => setSelectedExpert(expert)}
                                    >
                                        <Calendar className="h-4 w-4 mr-2" /> Book
                                    </Button>
                                </div>
                            </div>
                        ))
                    ) : (
                        <div className="col-span-full py-24 bg-white rounded-3xl border-2 border-dashed border-[#E5E7EB] text-center">
                            <Briefcase className="h-16 w-16 text-gray-200 mx-auto mb-4" />
                            <h3 className="text-xl font-bold text-gray-400">No experts found matching your criteria</h3>
                        </div>
                    )}
                </div>
            )}

            {selectedExpert && (
                <BookingModal
                    expert={selectedExpert}
                    onClose={() => setSelectedExpert(null)}
                />
            )}
        </div>
    );
}
