import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { Search, MapPin, GraduationCap, DollarSign, Calendar, ExternalLink, Lock } from 'lucide-react';
import api from '@/lib/api';
import { useAuth } from '@/hooks/useAuth';

interface Scholarship {
    id: number;
    title: string;
    provider: string;
    country?: { name: string };
    funding_type: string;
    program_level?: string;
    deadline?: string;
    application_link: string;
}

export default function ScholarshipListing() {
    const { user } = useAuth();
    const [scholarships, setScholarships] = useState<Scholarship[]>([]);
    const [loading, setLoading] = useState(true);
    const [searchTerm, setSearchTerm] = useState('');
    const [isLimited, setIsLimited] = useState(false);
    const [limitReason, setLimitReason] = useState<'guest' | 'upgrade' | null>(null);
    const [totalCount, setTotalCount] = useState(0);

    useEffect(() => {
        setLoading(true);
        fetchScholarships();
    }, [user]);

    const fetchScholarships = async () => {
        try {
            const response = await api.get('/api/v1/scholarships');
            const isAdmin = user?.roles?.some((role: any) => role.name === 'admin');
            
            setScholarships(response.data.data);
            setIsLimited(!isAdmin && (response.data.is_limited || false));
            setLimitReason(response.data.reason || null);
            setTotalCount(response.data.total_count || 0);
        } catch (error) {
            console.error('Error fetching scholarships:', error);
        } finally {
            setLoading(false);
        }
    };

    const filteredScholarships = scholarships.filter(s => 
        s.title.toLowerCase().includes(searchTerm.toLowerCase()) ||
        s.provider.toLowerCase().includes(searchTerm.toLowerCase())
    );

    return (
        <div className="container mx-auto px-4 py-8 relative">
            <div className="mb-8">
                <h1 className="text-4xl font-extrabold mb-4 text-slate-900">Scholarship Directory</h1>
                <p className="text-slate-600 text-lg max-w-2xl">
                    Discover fully funded and partial scholarships to study abroad. We aggregate the latest opportunities worldwide.
                </p>
            </div>

            <div className="relative max-w-xl mb-12">
                <Search className="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 w-5 h-5" />
                <Input 
                    placeholder={isLimited ? "Sign up to search all scholarships..." : "Search scholarships, providers, or countries..."} 
                    className="pl-10 h-12 text-lg shadow-sm"
                    value={searchTerm}
                    onChange={(e) => setSearchTerm(e.target.value)}
                    disabled={isLimited}
                />
                {isLimited && (
                    <div className="absolute right-3 top-1/2 -translate-y-1/2">
                        <Badge variant="outline" className="text-[10px] uppercase tracking-wider text-slate-400 border-slate-200">
                             Auth Required
                        </Badge>
                    </div>
                )}
            </div>

            <div className="relative">
                {loading ? (
                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        {[1, 2, 3, 4, 5, 6].map(i => (
                            <div key={i} className="h-[300px] bg-slate-100 animate-pulse rounded-2xl" />
                        ))}
                    </div>
                ) : (
                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        {filteredScholarships.map((s, index) => (
                            <Card 
                                key={s.id} 
                                className={`group hover:shadow-xl transition-all duration-300 border-none shadow-sm bg-white overflow-hidden rounded-2xl ${isLimited && index >= 3 ? 'opacity-40 blur-[2px]' : ''}`}
                            >
                                <CardHeader className="p-6 pb-2">
                                    <div className="flex justify-between items-start mb-2">
                                        <Badge variant="secondary" className="bg-blue-50 text-blue-600 hover:bg-blue-100 border-none px-3 py-1 font-semibold">
                                            {s.funding_type === 'full' ? 'Fully Funded' : 'Partial Funding'}
                                        </Badge>
                                        {s.country && <span className="text-sm font-medium text-slate-500 flex items-center gap-1"><MapPin className="w-3.5 h-3.5" /> {s.country.name}</span>}
                                    </div>
                                    <CardTitle className="text-xl font-bold group-hover:text-blue-600 transition-colors line-clamp-2 min-h-[3.5rem]">
                                        {s.title}
                                    </CardTitle>
                                    <p className="text-sm font-medium text-slate-500">{s.provider}</p>
                                </CardHeader>
                                <CardContent className="p-6 pt-2">
                                    <div className="space-y-3 mb-6">
                                        <div className="flex items-center gap-2 text-sm text-slate-600">
                                            <GraduationCap className="w-4 h-4 text-slate-400" />
                                            <span>{s.program_level || 'All Levels'}</span>
                                        </div>
                                        <div className="flex items-center gap-2 text-sm text-slate-600">
                                            <Calendar className="w-4 h-4 text-slate-400" />
                                            <span>Deadline: {s.deadline ? new Date(s.deadline).toLocaleDateString() : 'Anytime (Year-Round)'}</span>
                                        </div>
                                    </div>
                                    <Button asChild className="w-full bg-slate-900 hover:bg-slate-800 text-white font-semibold py-6 rounded-xl shadow-lg hover:shadow-slate-200 transition-all">
                                        <a href={s.application_link} target="_blank" rel="noopener noreferrer" className="flex items-center justify-center gap-2">
                                            Apply Now <ExternalLink className="w-4 h-4" />
                                        </a>
                                    </Button>
                                </CardContent>
                            </Card>
                        ))}
                    </div>
                )}

                {/* Guest / Free-user Overlay */}
                {isLimited && !loading && (
                    <div className="absolute inset-x-0 bottom-0 flex items-end justify-center pb-12 bg-gradient-to-t from-white via-white/95 to-transparent z-10" style={{ height: '65%' }}>
                        <div className="text-center max-w-md w-full mx-4 bg-white/70 backdrop-blur-md p-10 rounded-3xl border border-slate-100 shadow-2xl shadow-blue-100">
                            <div className="w-16 h-16 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-xl shadow-blue-300 rotate-6">
                                <Lock className="text-white w-8 h-8" />
                            </div>

                            {limitReason === 'upgrade' ? (
                                <>
                                    <h2 className="text-2xl font-bold text-slate-900 mb-3">Premium Feature</h2>
                                    <p className="text-slate-600 mb-8 leading-relaxed">
                                        You're on the free plan. Upgrade to <span className="font-bold text-blue-600">GoPathway Premium</span> to unlock the full directory of <span className="font-bold text-blue-600">{totalCount}+ scholarships</span> across 20+ countries and filter by level, funding type, and more.
                                    </p>
                                    <Button asChild className="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white py-8 text-lg font-bold rounded-2xl shadow-xl shadow-blue-200 transition-all duration-300 hover:-translate-y-1">
                                        <Link to="/dashboard/pricing">
                                            Upgrade to Premium
                                        </Link>
                                    </Button>
                                    <p className="mt-4 text-sm text-slate-400">
                                        Already premium? <button onClick={fetchScholarships} className="text-blue-600 font-semibold hover:underline">Refresh</button>
                                    </p>
                                </>
                            ) : (
                                <>
                                    <h2 className="text-2xl font-bold text-slate-900 mb-3">Unlock Full Access</h2>
                                    <p className="text-slate-600 mb-8 leading-relaxed">
                                        Create a free account, then upgrade to <span className="font-bold text-blue-600">GoPathway Premium</span> to explore <span className="font-bold text-blue-600">{totalCount}+ scholarships</span> across 20+ countries — with powerful filters and deadline tracking.
                                    </p>
                                    <Button asChild className="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white py-8 text-lg font-bold rounded-2xl shadow-xl shadow-blue-200 transition-all duration-300 hover:-translate-y-1">
                                        <Link to="/register">
                                            Create Free Account
                                        </Link>
                                    </Button>
                                    <p className="mt-4 text-sm text-slate-400">
                                        Already have an account? <Link to="/login" className="text-blue-600 font-semibold hover:underline">Log in</Link>
                                    </p>
                                </>
                            )}
                        </div>
                    </div>
                )}
            </div>
            
            {!loading && filteredScholarships.length === 0 && (
                <div className="text-center py-20 bg-slate-50 rounded-3xl border-2 border-dashed border-slate-200">
                    <p className="text-slate-500 text-lg">No scholarships found matching your criteria.</p>
                    <Button variant="link" onClick={() => setSearchTerm('')} className="text-blue-600 font-semibold mt-2">Clear all filters</Button>
                </div>
            )}
        </div>
    );
}
