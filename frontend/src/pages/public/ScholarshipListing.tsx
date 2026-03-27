import React, { useState, useEffect } from 'react';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { Search, MapPin, GraduationCap, DollarSign, Calendar, ExternalLink } from 'lucide-react';
import axios from 'axios';

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
    const [scholarships, setScholarships] = useState<Scholarship[]>([]);
    const [loading, setLoading] = useState(true);
    const [searchTerm, setSearchTerm] = useState('');

    useEffect(() => {
        fetchScholarships();
    }, []);

    const fetchScholarships = async () => {
        try {
            const response = await axios.get('/api/v1/scholarships');
            setScholarships(response.data.data);
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
        <div className="container mx-auto px-4 py-8">
            <div className="mb-8">
                <h1 className="text-4xl font-extrabold mb-4 text-slate-900">Scholarship Directory</h1>
                <p className="text-slate-600 text-lg max-w-2xl">
                    Discover fully funded and partial scholarships to study abroad. We aggregate the latest opportunities worldwide.
                </p>
            </div>

            <div className="relative max-w-xl mb-12">
                <Search className="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 w-5 h-5" />
                <Input 
                    placeholder="Search scholarships, providers, or countries..." 
                    className="pl-10 h-12 text-lg shadow-sm"
                    value={searchTerm}
                    onChange={(e) => setSearchTerm(e.target.value)}
                />
            </div>

            {loading ? (
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    {[1, 2, 3, 4, 5, 6].map(i => (
                        <div key={i} className="h-[300px] bg-slate-100 animate-pulse rounded-2xl" />
                    ))}
                </div>
            ) : (
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    {filteredScholarships.map((s) => (
                        <Card key={s.id} className="group hover:shadow-xl transition-all duration-300 border-none shadow-sm bg-white overflow-hidden rounded-2xl">
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
                                        <span>Deadline: {s.deadline ? new Date(s.deadline).toLocaleDateString() : 'Rolling'}</span>
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
            
            {!loading && filteredScholarships.length === 0 && (
                <div className="text-center py-20 bg-slate-50 rounded-3xl border-2 border-dashed border-slate-200">
                    <p className="text-slate-500 text-lg">No scholarships found matching your criteria.</p>
                    <Button variant="link" onClick={() => setSearchTerm('')} className="text-blue-600 font-semibold mt-2">Clear all filters</Button>
                </div>
            )}
        </div>
    );
}
