import { useState } from 'react';
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import api from '@/lib/api';
import { useDashboard } from '@/hooks/useDashboard';
import {
    GraduationCap, BookOpen, Globe2, Clock, DollarSign, FileText, CheckCircle2,
    ChevronDown, ChevronUp, ExternalLink, Search, Filter, Loader2,
    Shield, Briefcase, AlertCircle, Star, Building2, Languages, Award
} from 'lucide-react';
import { cn } from '@/lib/utils';
import { Button } from '@/components/ui/button';
import { Link } from 'react-router-dom';
import { useFeatures } from '@/hooks/useFeatures';
import { Lock, Sparkles } from 'lucide-react';

const DEGREE_COLORS: Record<string, string> = {
    certificate: 'bg-slate-100 text-slate-700',
    diploma: 'bg-blue-50 text-blue-700',
    bachelor: 'bg-indigo-50 text-indigo-700',
    master: 'bg-purple-50 text-purple-700',
    phd: 'bg-amber-50 text-amber-700',
};

const ADMISSION_STAGES = [
    { key: 'research', label: 'Research Schools', icon: '🔍', description: 'Explore institutions and programs that match your goals.' },
    { key: 'documents', label: 'Prepare Documents', icon: '📁', description: 'Gather academic transcripts, language test results, and references.' },
    { key: 'apply', label: 'Apply to School', icon: '📝', description: 'Submit your application through the official portal.' },
    { key: 'offer', label: 'Receive Offer', icon: '📬', description: 'Receive and review your admission offer letter.' },
    { key: 'deposit', label: 'Pay Deposit', icon: '💳', description: 'Secure your spot by paying the acceptance deposit.' },
    { key: 'visa', label: 'Apply for Visa', icon: '🛂', description: 'Submit your student visa application with required documents.' },
    { key: 'travel', label: 'Book & Travel', icon: '✈️', description: 'Arrange flights, accommodation, and health insurance.' },
];

interface SchoolProgram {
    id: number;
    name: string;
    degree_type: string;
    field_of_study?: string;
    duration_years?: number;
    tuition_per_year?: number;
    currency?: string;
    application_deadline?: string;
    intake_periods?: string[];
    ielts_min?: number;
    toefl_min?: number;
    pte_min?: number;
    min_gpa?: number;
    admission_requirements?: string[];
}

interface School {
    id: number;
    name: string;
    location?: string;
    type?: string;
    ranking?: string;
    description?: string;
    website?: string;
    application_portal?: string;
    program_count?: number;
    programs?: SchoolProgram[];
}

interface SchoolApplication {
    id: number;
    school_id: number;
    status: string;
    school?: School;
    program?: SchoolProgram;
    completed_at?: string;
}

export default function SchoolExplorer() {
    const { getFeatureAccess } = useFeatures();
    const { data: dashboard } = useDashboard();
    const queryClient = useQueryClient();

    const countryId = dashboard?.pathway?.country?.id;
    const countryName = dashboard?.pathway?.country?.name;

    const [search, setSearch] = useState('');
    const [degreeFilter, setDegreeFilter] = useState('all');
    const [expandedSchool, setExpandedSchool] = useState<number | null>(null);
    const [expandedProgram, setExpandedProgram] = useState<number | null>(null);
    const [activeTab, setActiveTab] = useState<'schools' | 'visa' | 'timeline'>('schools');

    const { data: schoolsData, isLoading: loadingSchools } = useQuery({
        queryKey: ['schools', countryId],
        queryFn: () => api.get(`/api/v1/countries/${countryId}/schools`).then(r => r.data.data),
        enabled: !!countryId,
    });

    const { data: visaData } = useQuery({
        queryKey: ['student-visa', countryId],
        queryFn: () => api.get(`/api/v1/countries/${countryId}/student-visa`).then(r => r.data.data),
        enabled: !!countryId,
    });

    const { data: myApplications } = useQuery({
        queryKey: ['school-applications'],
        queryFn: () => api.get('/api/v1/school-applications').then(r => r.data.data),
    });

    const saveMutation = useMutation({
        mutationFn: (data: { school_id: number; status: string }) => api.post('/api/v1/school-applications', data),
        onSuccess: () => queryClient.invalidateQueries({ queryKey: ['school-applications'] }),
    });

    const schools: School[] = schoolsData || [];

    const filtered = schools.filter(s => {
        const matchSearch = !search || s.name.toLowerCase().includes(search.toLowerCase()) || s.location?.toLowerCase().includes(search.toLowerCase());
        const matchDegree = degreeFilter === 'all' || s.programs?.some((p) => p.degree_type === degreeFilter);
        return matchSearch && matchDegree;
    });

    const isApplied = (schoolId: number) => myApplications?.some((a: SchoolApplication) => a.school_id === schoolId);

    if (!countryId) {
        return (
            <div className="text-center py-20 max-w-lg mx-auto bg-white rounded-3xl border shadow-sm">
                <div className="w-16 h-16 bg-indigo-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <GraduationCap className="h-8 w-8 text-indigo-500" />
                </div>
                <h2 className="text-xl font-bold mb-2">No Active Pathway</h2>
                <p className="text-slate-500 mb-6 text-sm px-6">Select a destination pathway to explore schools and study programs.</p>
                <Link to="/recommendations"><Button>Explore Destinations</Button></Link>
            </div>
        );
    }

    return (
        <div className="max-w-5xl mx-auto space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500 pb-12">
            {/* Header */}
            <div>
                <h1 className="text-3xl font-black text-foreground flex items-center gap-3">
                    <GraduationCap className="h-8 w-8 text-indigo-600" />
                    School Explorer
                </h1>
                <p className="text-muted-foreground mt-2 text-lg">
                    Universities, colleges and programs in <strong className="text-foreground">{countryName}</strong>.
                </p>
            </div>

            {/* Tabs */}
            <div className="flex p-1 bg-white rounded-2xl border border-slate-200 shadow-sm w-fit">
                {[
                    { key: 'schools', label: 'Schools & Programs', icon: Building2 },
                    { key: 'visa', label: 'Student Visa', icon: Shield },
                    { key: 'timeline', label: 'Admission Timeline', icon: Clock },
                ].map(({ key, label, icon: Icon }) => (
                    <button key={key} onClick={() => setActiveTab(key as any)}
                        className={cn("flex items-center gap-2 px-5 py-2.5 text-sm font-bold rounded-xl transition-all",
                            activeTab === key ? "bg-indigo-600 text-white shadow-md" : "text-slate-500 hover:text-slate-800 hover:bg-slate-50"
                        )}>
                        <Icon className="w-4 h-4" />
                        {label}
                    </button>
                ))}
            </div>

            <div className="relative">
                {getFeatureAccess('SCHOOL_EXPLORER') === 'locked' && (
                    <div className="absolute inset-0 z-20 backdrop-blur-[6px] bg-white/40 flex items-center justify-center rounded-[32px] border border-blue-50 shadow-inner min-h-[400px]">
                        <div className="bg-white/95 p-10 rounded-[40px] shadow-2xl border border-indigo-100 text-center max-w-md mx-4 transform transition-all hover:scale-[1.01]">
                            <div className="h-20 w-20 bg-indigo-50 rounded-[28px] flex items-center justify-center mx-auto mb-6 rotate-3">
                                <Lock className="h-10 w-10 text-indigo-600" />
                            </div>
                            <h2 className="text-2xl font-black text-slate-900 mb-3 tracking-tight">Premium School Explorer</h2>
                            <p className="text-slate-500 mb-8 leading-relaxed">
                                Access our curated database of <strong className="text-indigo-600">universities, visa requirements, and admission roadmaps</strong> for your chosen destination.
                            </p>
                            <Link to="/pricing">
                                <Button size="lg" className="bg-indigo-600 hover:bg-indigo-700 text-white w-full rounded-2xl h-14 font-bold shadow-lg shadow-indigo-100 group">
                                    Unlock Full Access
                                    <Sparkles className="ml-2 h-5 w-5 group-hover:scale-110 transition-transform" />
                                </Button>
                            </Link>
                        </div>
                    </div>
                )}

                <div className={cn("transition-all duration-500", getFeatureAccess('SCHOOL_EXPLORER') === 'locked' && "opacity-40 grayscale-[0.8] blur-[2px] pointer-events-none select-none max-h-[600px] overflow-hidden")}>

            {/* ── Schools Tab ── */}
            {activeTab === 'schools' && (
                <div className="space-y-6">
                    {/* Search & Filter */}
                    <div className="flex flex-col sm:flex-row gap-3">
                        <div className="relative flex-1">
                            <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" />
                            <input
                                type="text" placeholder="Search schools or cities..."
                                value={search} onChange={e => setSearch(e.target.value)}
                                className="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            />
                        </div>
                        <select
                            value={degreeFilter} onChange={e => setDegreeFilter(e.target.value)}
                            className="px-4 py-2.5 rounded-xl border border-slate-200 bg-white text-sm font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        >
                            <option value="all">All Degrees</option>
                            <option value="certificate">Certificate</option>
                            <option value="diploma">Diploma</option>
                            <option value="bachelor">Bachelor's</option>
                            <option value="master">Master's</option>
                            <option value="phd">PhD</option>
                        </select>
                    </div>

                    {loadingSchools ? (
                        <div className="flex justify-center py-16"><Loader2 className="w-8 h-8 animate-spin text-indigo-500" /></div>
                    ) : filtered.length === 0 ? (
                        <div className="bg-white rounded-2xl border p-12 text-center text-slate-500">
                            No schools found for your search.
                        </div>
                    ) : (
                        filtered.map((school) => {
                            const isOpen = expandedSchool === school.id;
                            const applied = isApplied(school.id);

                            return (
                                <div key={school.id} className={cn("bg-white border rounded-2xl shadow-sm overflow-hidden transition-all",
                                    isOpen ? "ring-2 ring-indigo-500/20 border-indigo-200" : "hover:border-slate-300")}>
                                    {/* School header */}
                                    <button onClick={() => setExpandedSchool(isOpen ? null : school.id)}
                                        className="w-full p-6 flex items-start justify-between text-left gap-4">
                                        <div className="flex items-start gap-4">
                                            <div className="w-12 h-12 rounded-2xl bg-indigo-50 flex items-center justify-center shrink-0">
                                                <Building2 className="w-6 h-6 text-indigo-600" />
                                            </div>
                                            <div>
                                                <div className="flex flex-wrap items-center gap-2">
                                                    <h3 className="font-bold text-lg text-foreground">{school.name}</h3>
                                                    {applied && (
                                                        <span className="flex items-center gap-1 text-xs font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full">
                                                            <CheckCircle2 className="w-3 h-3" /> Tracking
                                                        </span>
                                                    )}
                                                </div>
                                                <div className="flex flex-wrap gap-3 mt-1 text-sm text-slate-500">
                                                    <span className="flex items-center gap-1"><Globe2 className="w-3.5 h-3.5" />{school.location}</span>
                                                    <span className="capitalize px-2 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-600">{school.type}</span>
                                                    {school.ranking && <span className="flex items-center gap-1 text-xs"><Star className="w-3 h-3 text-amber-500" />{school.ranking}</span>}
                                                </div>
                                                {school.description && <p className="text-sm text-slate-500 mt-2 line-clamp-2">{school.description}</p>}
                                            </div>
                                        </div>
                                        <div className="flex items-center gap-3 shrink-0">
                                            <span className="text-xs font-semibold text-slate-400">{school.program_count} programs</span>
                                            {isOpen ? <ChevronUp className="w-5 h-5 text-slate-400" /> : <ChevronDown className="w-5 h-5 text-slate-400" />}
                                        </div>
                                    </button>

                                    {/* Expanded programs */}
                                    {isOpen && (
                                        <div className="border-t bg-slate-50/40">
                                            {/* School actions */}
                                            <div className="flex gap-2 p-4 border-b">
                                                {school.website && (
                                                    <a href={school.website} target="_blank" rel="noreferrer">
                                                        <Button variant="outline" size="sm" className="rounded-xl gap-1.5">
                                                            <Globe2 className="w-3.5 h-3.5" />Website
                                                        </Button>
                                                    </a>
                                                )}
                                                {school.application_portal && (
                                                    <a href={school.application_portal} target="_blank" rel="noreferrer">
                                                        <Button size="sm" className="rounded-xl bg-indigo-600 hover:bg-indigo-700 gap-1.5">
                                                            <ExternalLink className="w-3.5 h-3.5" />Apply Online
                                                        </Button>
                                                    </a>
                                                )}
                                                {!applied && (
                                                    <Button variant="outline" size="sm" className="rounded-xl gap-1.5"
                                                        onClick={() => saveMutation.mutate({ school_id: school.id, status: 'researching' })}>
                                                        <BookOpen className="w-3.5 h-3.5" />Track This School
                                                    </Button>
                                                )}
                                            </div>

                                            {/* Programs list */}
                                            <div className="divide-y">
                                                {school.programs?.length === 0 ? (
                                                    <p className="p-6 text-sm text-slate-400">No programs available yet.</p>
                                                ) : (
                                                    school.programs?.map((prog) => {
                                                        const progOpen = expandedProgram === prog.id;
                                                        return (
                                                            <div key={prog.id}>
                                                                <button onClick={() => setExpandedProgram(progOpen ? null : prog.id)}
                                                                    className="w-full p-5 flex items-start justify-between text-left hover:bg-white transition-colors">
                                                                    <div className="flex items-start gap-3">
                                                                        <div className="mt-0.5">
                                                                            <GraduationCap className="w-5 h-5 text-slate-400" />
                                                                        </div>
                                                                        <div>
                                                                            <div className="flex flex-wrap items-center gap-2">
                                                                                <h4 className="font-semibold text-foreground">{prog.name}</h4>
                                                                                <span className={cn("text-[10px] font-bold uppercase px-2 py-0.5 rounded-full", DEGREE_COLORS[prog.degree_type])}>
                                                                                    {prog.degree_type}
                                                                                </span>
                                                                            </div>
                                                                            <div className="flex flex-wrap gap-3 mt-1 text-xs text-slate-500">
                                                                                {prog.field_of_study && <span>{prog.field_of_study}</span>}
                                                                                {prog.duration_years && <span className="flex items-center gap-1"><Clock className="w-3 h-3" />{prog.duration_years} yr{prog.duration_years !== 1 ? 's' : ''}</span>}
                                                                                {prog.tuition_per_year != null && (
                                                                                    <span className="flex items-center gap-1 font-semibold text-slate-700">
                                                                                        <DollarSign className="w-3 h-3" />
                                                                                        {prog.tuition_per_year === 0 ? 'Free / No Tuition'
                                                                                            : `${prog.currency} ${prog.tuition_per_year.toLocaleString()}/yr`}
                                                                                    </span>
                                                                                )}
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    {progOpen ? <ChevronUp className="w-4 h-4 text-slate-400 shrink-0" /> : <ChevronDown className="w-4 h-4 text-slate-400 shrink-0" />}
                                                                </button>

                                                                {progOpen && (
                                                                    <div className="px-5 pb-5 bg-white grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                                                                        {prog.application_deadline && (
                                                                            <InfoBlock icon={<Clock className="w-4 h-4 text-rose-500" />} label="Deadline" value={prog.application_deadline} />
                                                                        )}
                                                                        {prog.intake_periods && prog.intake_periods.length > 0 && (
                                                                            <InfoBlock icon={<Award className="w-4 h-4 text-indigo-500" />} label="Intake" value={prog.intake_periods.join(', ')} />
                                                                        )}
                                                                        {prog.ielts_min && (
                                                                            <InfoBlock icon={<Languages className="w-4 h-4 text-emerald-500" />} label="IELTS Min" value={`${prog.ielts_min}`} />
                                                                        )}
                                                                        {prog.toefl_min && (
                                                                            <InfoBlock icon={<Languages className="w-4 h-4 text-blue-500" />} label="TOEFL Min" value={`${prog.toefl_min}`} />
                                                                        )}
                                                                        {prog.pte_min && (
                                                                            <InfoBlock icon={<Languages className="w-4 h-4 text-violet-500" />} label="PTE Min" value={`${prog.pte_min}`} />
                                                                        )}
                                                                        {prog.min_gpa && (
                                                                            <InfoBlock icon={<Star className="w-4 h-4 text-amber-500" />} label="Min GPA" value={`${prog.min_gpa} / 4.0`} />
                                                                        )}
                                                                        {prog.admission_requirements && prog.admission_requirements.length > 0 && (
                                                                            <div className="sm:col-span-2">
                                                                                <p className="text-xs font-semibold text-slate-500 uppercase mb-2">Requirements</p>
                                                                                <ul className="space-y-1">
                                                                                    {prog.admission_requirements.map((req: string, i: number) => (
                                                                                        <li key={i} className="flex items-start gap-2 text-slate-600">
                                                                                            <CheckCircle2 className="w-4 h-4 text-emerald-500 mt-0.5 shrink-0" />
                                                                                            {req}
                                                                                        </li>
                                                                                    ))}
                                                                                </ul>
                                                                            </div>
                                                                        )}
                                                                    </div>
                                                                )}
                                                            </div>
                                                        );
                                                    })
                                                )}
                                            </div>
                                        </div>
                                    )}
                                </div>
                            );
                        })
                    )}
                </div>
            )}

            {/* ── Student Visa Tab ── */}
            {activeTab === 'visa' && (
                <div className="space-y-6">
                    {!visaData ? (
                        <div className="bg-white rounded-2xl border p-12 text-center text-slate-500">
                            No student visa data available for this country yet.
                        </div>
                    ) : (
                        <>
                            <div className="bg-gradient-to-br from-indigo-600 to-indigo-800 rounded-2xl p-8 text-white">
                                <div className="flex items-center gap-3 mb-2">
                                    <Shield className="w-7 h-7 text-indigo-200" />
                                    <h2 className="text-2xl font-black">{visaData.visa_name}</h2>
                                </div>
                                <p className="text-indigo-200 text-sm">Official student visa for {countryName}</p>
                            </div>

                            <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                {[
                                    { icon: <DollarSign className="w-5 h-5 text-emerald-600" />, label: 'Visa Fee', value: visaData.visa_fee ? `${visaData.visa_fee_currency} ${visaData.visa_fee.toLocaleString()}` : 'Check embassy' },
                                    { icon: <Clock className="w-5 h-5 text-indigo-600" />, label: 'Processing Time', value: visaData.processing_time || 'Varies' },
                                    { icon: <Briefcase className="w-5 h-5 text-amber-600" />, label: 'Work Rights', value: visaData.work_hours_per_week ? `${visaData.work_hours_per_week} hrs/week` : 'No work rights' },
                                    { icon: <DollarSign className="w-5 h-5 text-teal-600" />, label: 'Min Funds', value: visaData.min_funds_required ? `${visaData.min_funds_currency} ${visaData.min_funds_required?.toLocaleString()} ${visaData.min_funds_description || ''}` : 'Not specified' },
                                    { icon: <Award className="w-5 h-5 text-violet-600" />, label: 'Post-Study Work', value: visaData.post_study_work_permit ? visaData.post_study_work_duration || 'Available' : 'Not available' },
                                ].map(({ icon, label, value }) => (
                                    <div key={label} className="bg-white rounded-2xl border p-5 flex items-start gap-3">
                                        <div className="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center shrink-0">{icon}</div>
                                        <div>
                                            <p className="text-xs font-bold text-slate-400 uppercase mb-0.5">{label}</p>
                                            <p className="font-semibold text-foreground text-sm">{value}</p>
                                        </div>
                                    </div>
                                ))}
                            </div>

                            {visaData.required_documents?.length > 0 && (
                                <div className="bg-white rounded-2xl border p-6">
                                    <h3 className="font-bold text-foreground mb-4 flex items-center gap-2">
                                        <FileText className="w-5 h-5 text-indigo-600" />
                                        Required Documents
                                    </h3>
                                    <div className="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                        {visaData.required_documents.map((doc: string, i: number) => (
                                            <div key={i} className="flex items-start gap-2 text-sm text-slate-700 p-3 rounded-xl bg-slate-50">
                                                <CheckCircle2 className="w-4 h-4 text-emerald-500 mt-0.5 shrink-0" />
                                                {doc}
                                            </div>
                                        ))}
                                    </div>
                                </div>
                            )}

                            {visaData.notes && (
                                <div className="bg-amber-50 border border-amber-200 rounded-2xl p-5 flex gap-3">
                                    <AlertCircle className="w-5 h-5 text-amber-600 shrink-0 mt-0.5" />
                                    <p className="text-sm text-amber-800">{visaData.notes}</p>
                                </div>
                            )}
                        </>
                    )}
                </div>
            )}

            {/* ── Admission Timeline Tab ── */}
            {activeTab === 'timeline' && (
                <div className="space-y-6">
                    <div className="bg-white rounded-2xl border p-6">
                        <h2 className="text-xl font-bold text-foreground mb-6 flex items-center gap-2">
                            <Clock className="w-5 h-5 text-indigo-600" />
                            7-Stage Admission Roadmap
                        </h2>
                        <div className="relative">
                            {/* Timeline line */}
                            <div className="absolute left-6 top-0 bottom-0 w-0.5 bg-gradient-to-b from-indigo-200 via-indigo-300 to-indigo-100 hidden sm:block" />
                            <div className="space-y-6">
                                {ADMISSION_STAGES.map((stage, index) => (
                                    <div key={stage.key} className="flex gap-4">
                                        <div className="relative z-10 w-12 h-12 rounded-2xl bg-gradient-to-br from-indigo-500 to-indigo-700 flex items-center justify-center text-xl shadow-md shrink-0">
                                            {stage.icon}
                                        </div>
                                        <div className="flex-1 bg-slate-50 rounded-2xl p-5 border">
                                            <div className="flex items-center gap-2 mb-1">
                                                <span className="text-xs font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-full">Stage {index + 1}</span>
                                                <h3 className="font-bold text-foreground">{stage.label}</h3>
                                            </div>
                                            <p className="text-sm text-slate-500">{stage.description}</p>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        </div>
                    </div>

                    {/* My Applications Tracker */}
                    {myApplications && myApplications.length > 0 && (
                        <div className="bg-white rounded-2xl border p-6">
                            <h3 className="font-bold text-foreground mb-4">🏫 My School Applications</h3>
                            <div className="space-y-3">
                                {myApplications.map((app: SchoolApplication) => (
                                    <div key={app.id} className="flex items-center justify-between p-4 rounded-xl bg-slate-50 border">
                                        <div>
                                            <p className="font-semibold text-sm">{app.school?.name}</p>
                                            {app.program && <p className="text-xs text-slate-500">{app.program.name}</p>}
                                        </div>
                                        <span className="text-xs font-bold capitalize px-3 py-1 rounded-full bg-indigo-50 text-indigo-700">
                                            {app.status?.replace(/_/g, ' ')}
                                        </span>
                                    </div>
                                ))}
                            </div>
                        </div>
                    )}
                </div>
            )}
                </div>
            </div>
        </div>
    );
}

function InfoBlock({ icon, label, value }: { icon: React.ReactNode; label: string; value: string }) {
    return (
        <div className="flex items-start gap-2 p-3 rounded-xl bg-slate-50">
            <div className="mt-0.5">{icon}</div>
            <div>
                <p className="text-[10px] font-bold text-slate-400 uppercase">{label}</p>
                <p className="font-semibold text-slate-700">{value}</p>
            </div>
        </div>
    );
}
