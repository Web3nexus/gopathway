import { useState } from 'react';
import { useQuery } from '@tanstack/react-query';
import api from '@/lib/api';
import { useDashboard } from '@/hooks/useDashboard';
import { Briefcase, Building2, Globe2, Link as LinkIcon, MonitorPlay, FileText, CheckCircle2, ChevronRight, Loader2 } from 'lucide-react';
import { Button } from '@/components/ui/button';
import { Link } from 'react-router-dom';

const STRATEGY_STEPS = [
    { title: 'Prepare Country-Specific CV', description: 'Ensure your resume meets local standards and ATS requirements.', icon: FileText, link: '/cv-builder' },
    { title: 'Optimize LinkedIn Profile', description: 'Update your location, headline, and open-to-work status for local recruiters.', icon: Globe2 },
    { title: 'Register on Local Platforms', description: 'Create profiles on the top job boards used in your destination.', icon: MonitorPlay },
    { title: 'Start Applying', description: 'Send out tailored applications and cover letters for each role.', icon: Briefcase },
    { title: 'Interview Preparation', description: 'Research cultural interview expectations and salary ranges.', icon: Building2 },
];

export default function JobSearchKit() {
    const { data: dashboard } = useDashboard();
    const [selectedCategory, setSelectedCategory] = useState<string>('all');

    const countryId = dashboard?.pathway?.country?.id;
    const countryName = dashboard?.pathway?.country?.name;

    const { data: platforms, isLoading } = useQuery({
        queryKey: ['job-platforms', countryId],
        queryFn: () => api.get(`/api/v1/job-platforms/${countryId}`).then(res => res.data.data),
        enabled: !!countryId,
    });

    if (!countryId) {
        return (
            <div className="text-center py-20 max-w-lg mx-auto bg-white rounded-3xl border shadow-sm">
                <div className="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <Briefcase className="h-8 w-8 text-blue-500" />
                </div>
                <h2 className="text-xl font-bold mb-2">No Active Pathway</h2>
                <p className="text-slate-500 mb-6 text-sm px-6">Select a destination pathway to access local job search tools.</p>
                <Link to="/recommendations"><Button>Explore Destinations</Button></Link>
            </div>
        );
    }

    const categories = ['all', ...Array.from(new Set(platforms?.map((p: any) => p.category).filter(Boolean)))];

    const filteredPlatforms = platforms?.filter((p: any) =>
        selectedCategory === 'all' || p.category === selectedCategory
    ) || [];

    return (
        <div className="max-w-5xl mx-auto space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500 pb-12">
            <div className="flex items-start justify-between bg-gradient-to-r from-blue-700 to-indigo-800 rounded-3xl p-8 text-white shadow-lg relative overflow-hidden">
                <div className="relative z-10">
                    <h1 className="text-3xl font-black mb-2">Job Search Kit: {countryName}</h1>
                    <p className="text-blue-100 max-w-xl text-lg">
                        Everything you need to find and secure employment in {countryName}, from top platforms to cultural interview tips.
                    </p>
                    <div className="mt-6 flex gap-3">
                        <Link to="/cv-builder">
                            <Button className="bg-white text-blue-700 hover:bg-blue-50 font-bold rounded-xl h-11 px-6">
                                <FileText className="w-4 h-4 mr-2" />
                                Build My CV
                            </Button>
                        </Link>
                    </div>
                </div>
                <Briefcase className="w-48 h-48 absolute -right-6 -bottom-12 text-white/10 rotate-12" />
            </div>

            <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {/* Main Content - Job Platforms */}
                <div className="lg:col-span-2 space-y-6">
                    <div className="flex items-center justify-between">
                        <h2 className="text-xl font-bold text-slate-800 flex items-center gap-2">
                            <MonitorPlay className="w-5 h-5 text-blue-600" />
                            Top Job Platforms
                        </h2>
                        {categories.length > 1 && (
                            <select
                                value={selectedCategory}
                                onChange={(e) => setSelectedCategory(e.target.value)}
                                className="bg-white border rounded-lg text-sm font-medium py-1.5 px-3 focus:ring-blue-500"
                            >
                                {categories.map((c: any) => (
                                    <option key={c} value={c}>{c === 'all' ? 'All Categories' : c}</option>
                                ))}
                            </select>
                        )}
                    </div>

                    {isLoading ? (
                        <div className="flex justify-center py-12"><Loader2 className="w-8 h-8 animate-spin text-blue-500" /></div>
                    ) : filteredPlatforms.length === 0 ? (
                        <div className="bg-white rounded-2xl border border-dashed p-10 text-center text-slate-500">
                            No job platforms found for {countryName} yet.
                        </div>
                    ) : (
                        <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            {filteredPlatforms.map((platform: any) => (
                                <a
                                    key={platform.id}
                                    href={platform.website_url}
                                    target="_blank"
                                    rel="noreferrer"
                                    className="bg-white rounded-2xl border p-5 hover:border-blue-300 hover:shadow-md transition-all group block"
                                >
                                    <div className="flex items-start justify-between mb-3">
                                        <div className="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                                            <Globe2 className="w-5 h-5" />
                                        </div>
                                        <LinkIcon className="w-4 h-4 text-slate-300 group-hover:text-blue-500 transition-colors" />
                                    </div>
                                    <h3 className="font-bold text-lg text-slate-800 mb-1">{platform.name}</h3>
                                    {platform.category && (
                                        <span className="inline-block px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-600 text-xs font-semibold uppercase tracking-wider mb-2">
                                            {platform.category}
                                        </span>
                                    )}
                                    {platform.tips && (
                                        <p className="text-sm text-slate-500 line-clamp-2">{platform.tips}</p>
                                    )}
                                </a>
                            ))}
                        </div>
                    )}
                </div>

                {/* Sidebar - Strategy Guide */}
                <div className="space-y-6">
                    <div className="bg-white border rounded-3xl p-6 shadow-sm">
                        <h3 className="font-bold text-lg mb-6 flex items-center gap-2">
                            <CheckCircle2 className="w-5 h-5 text-emerald-500" />
                            Job Search Strategy
                        </h3>
                        <div className="space-y-6 relative">
                            <div className="absolute left-[19px] top-6 bottom-6 w-0.5 bg-slate-100 -z-10" />
                            {STRATEGY_STEPS.map((step, idx) => (
                                <div key={idx} className="flex gap-4">
                                    <div className="w-10 h-10 rounded-full bg-white border-2 border-slate-200 flex items-center justify-center shrink-0 text-slate-500 shadow-sm">
                                        <step.icon className="w-4 h-4" />
                                    </div>
                                    <div className="pt-2">
                                        <h4 className="font-bold text-sm text-slate-800">{step.title}</h4>
                                        <p className="text-xs text-slate-500 mt-1 mb-2">{step.description}</p>
                                        {step.link && (
                                            <Link to={step.link} className="text-xs font-bold text-blue-600 hover:text-blue-700 flex items-center gap-1">
                                                Go to Tool <ChevronRight className="w-3 h-3" />
                                            </Link>
                                        )}
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}
