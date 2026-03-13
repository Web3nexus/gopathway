import { useState, useEffect } from 'react';
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import api from '@/lib/api';
import { useDashboard } from '@/hooks/useDashboard';
import { FileText, Download, Save, Loader2, Sparkles, AlertCircle, CheckCircle2, ChevronRight, User, Briefcase, GraduationCap } from 'lucide-react';
import { Button } from '@/components/ui/button';
import { Link } from 'react-router-dom';
import { useToast } from '@/hooks/use-toast';

export default function CvBuilder() {
    const { data: dashboard } = useDashboard();
    const { toast } = useToast();
    const queryClient = useQueryClient();

    const countryId = dashboard?.pathway?.country?.id;
    const countryName = dashboard?.pathway?.country?.name;

    const [activeTab, setActiveTab] = useState<'personal' | 'experience' | 'education' | 'skills' | 'coverletter'>('personal');
    const [cvData, setCvData] = useState<any>({
        personal: { name: '', email: '', phone: '', location: '', linkedin: '', summary: '' },
        experience: [],
        education: [],
        skills: ''
    });

    // Cover Letter state
    const [clJobTitle, setClJobTitle] = useState('');
    const [clCompanyName, setClCompanyName] = useState('');
    const [generatedCoverLetter, setGeneratedCoverLetter] = useState('');

    const { data: templates, isLoading: loadingTemplates } = useQuery({
        queryKey: ['cv-templates', countryId],
        queryFn: () => api.get(`/api/v1/cv-templates/${countryId}`).then(res => res.data.data),
        enabled: !!countryId,
    });

    const activeTemplate = templates?.[0]; // Default to first template for the country

    const saveMutation = useMutation({
        mutationFn: (data: any) => api.post('/api/v1/cv-builder/generate', data),
        onSuccess: () => {
            toast({ title: 'CV Saved', description: 'Your CV data has been saved securely.' });
            queryClient.invalidateQueries({ queryKey: ['my-cvs'] });
        },
    });

    const generateClMutation = useMutation({
        mutationFn: (data: any) => api.post('/api/v1/cover-letter/generate', data),
        onSuccess: (res) => {
            setGeneratedCoverLetter(res.data.data.cover_letter);
            toast({ title: 'Cover Letter Generated', description: 'Your AI cover letter is ready to review.' });
        },
    });

    const handleSave = () => {
        if (!activeTemplate) return;
        saveMutation.mutate({
            country_id: countryId,
            cv_template_id: activeTemplate.id,
            cv_data: cvData
        });
    };

    const handleGenerateCl = () => {
        if (!clJobTitle || !clCompanyName) {
            toast({ variant: 'destructive', title: 'Missing Info', description: 'Please provide Job Title and Company Name' });
            return;
        }
        generateClMutation.mutate({
            job_title: clJobTitle,
            company_name: clCompanyName,
            key_skills: cvData.skills.split(',').map((s: string) => s.trim()).filter(Boolean)
        });
    };

    if (!countryId) {
        return (
            <div className="text-center py-20 max-w-lg mx-auto bg-white rounded-3xl border shadow-sm">
                <div className="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <FileText className="h-8 w-8 text-blue-500" />
                </div>
                <h2 className="text-xl font-bold mb-2">No Active Pathway</h2>
                <p className="text-slate-500 mb-6 text-sm px-6">Select a destination pathway to build a country-specific CV.</p>
                <Link to="/recommendations"><Button>Explore Destinations</Button></Link>
            </div>
        );
    }

    if (loadingTemplates) {
        return <div className="flex justify-center py-20"><Loader2 className="w-8 h-8 animate-spin text-blue-600" /></div>;
    }

    const noTemplate = !activeTemplate;

    return (
        <div className="max-w-6xl mx-auto space-y-6 animate-in fade-in slide-in-from-bottom-4 duration-500 pb-12">

            <div className="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h1 className="text-3xl font-black text-slate-800 flex items-center gap-3">
                        <FileText className="h-8 w-8 text-blue-600" /> CV & Cover Letter Builder
                    </h1>
                    <p className="text-slate-500 mt-2">
                        Optimized for <strong className="text-slate-800">{countryName}</strong> employment standards.
                    </p>
                </div>
                <div className="flex gap-2">
                    <Button variant="outline" className="gap-2 rounded-xl" onClick={handleSave} disabled={saveMutation.isPending}>
                        {saveMutation.isPending ? <Loader2 className="w-4 h-4 animate-spin" /> : <Save className="w-4 h-4" />}
                        Save Draft
                    </Button>
                    <Button className="gap-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white shadow-md shadow-blue-600/20">
                        <Download className="w-4 h-4" /> Export PDF
                    </Button>
                </div>
            </div>

            {activeTemplate?.format_rules && (
                <div className="bg-white border rounded-3xl p-6 shadow-sm space-y-4">
                    <div className="flex items-center gap-3 mb-2">
                        <div className="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center shrink-0">
                            <AlertCircle className="w-5 h-5 text-blue-600" />
                        </div>
                        <div>
                            <h3 className="font-bold text-slate-800 text-lg">{countryName} CV Format Rules</h3>
                            <p className="text-sm text-slate-500">Your CV must follow these country-specific standards to be taken seriously by employers.</p>
                        </div>
                    </div>

                    <div className="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        <div className={`rounded-2xl p-4 text-center border-2 ${activeTemplate.format_rules.photo_required ? 'bg-red-50 border-red-200' : 'bg-green-50 border-green-200'}`}>
                            <div className={`text-2xl mb-1 font-black ${activeTemplate.format_rules.photo_required ? 'text-red-600' : 'text-green-600'}`}>
                                {activeTemplate.format_rules.photo_required ? '📷 Required' : '🚫 No Photo'}
                            </div>
                            <p className="text-xs font-semibold text-slate-600">Professional Photo</p>
                        </div>
                        <div className={`rounded-2xl p-4 text-center border-2 ${activeTemplate.format_rules.age_required ? 'bg-red-50 border-red-200' : 'bg-green-50 border-green-200'}`}>
                            <div className={`text-2xl mb-1 font-black ${activeTemplate.format_rules.age_required ? 'text-red-600' : 'text-green-600'}`}>
                                {activeTemplate.format_rules.age_required ? '🎂 Include DOB' : '🚫 No DOB'}
                            </div>
                            <p className="text-xs font-semibold text-slate-600">Date of Birth</p>
                        </div>
                        <div className="rounded-2xl p-4 text-center border-2 bg-blue-50 border-blue-200">
                            <div className="text-2xl mb-1 font-black text-blue-600">📄 {activeTemplate.format_rules.max_pages} Page{activeTemplate.format_rules.max_pages > 1 ? 's' : ''}</div>
                            <p className="text-xs font-semibold text-slate-600">Max Length</p>
                        </div>
                        <div className="rounded-2xl p-4 text-center border-2 bg-purple-50 border-purple-200">
                            <div className="text-2xl mb-1 font-black text-purple-600">📋 Ordered</div>
                            <p className="text-xs font-semibold text-slate-600">Specific Section Order</p>
                        </div>
                    </div>

                    {activeTemplate.format_rules.preferred_order && (
                        <div className="bg-slate-50 rounded-xl p-4 border">
                            <p className="text-xs font-bold text-slate-500 uppercase mb-1">Recommended Section Order</p>
                            <p className="text-sm text-slate-700 font-medium">{activeTemplate.format_rules.preferred_order}</p>
                        </div>
                    )}

                    {activeTemplate.format_rules.notes && (
                        <div className="bg-amber-50 border border-amber-200 rounded-xl p-4 flex gap-3 items-start">
                            <CheckCircle2 className="w-5 h-5 text-amber-600 shrink-0 mt-0.5" />
                            <p className="text-sm text-amber-800">{activeTemplate.format_rules.notes}</p>
                        </div>
                    )}
                </div>
            )}

            <div className="grid grid-cols-1 lg:grid-cols-4 gap-6">

                {/* Editor Sidebar */}
                <div className="lg:col-span-1 space-y-2">
                    {[
                        { id: 'personal', label: 'Personal Info', icon: User },
                        { id: 'experience', label: 'Work Experience', icon: Briefcase },
                        { id: 'education', label: 'Education', icon: GraduationCap },
                        { id: 'skills', label: 'Skills & Languages', icon: Sparkles },
                        { id: 'coverletter', label: 'AI Cover Letter', icon: FileText },
                    ].map((tab) => (
                        <button
                            key={tab.id}
                            onClick={() => setActiveTab(tab.id as any)}
                            className={`w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition-all ${activeTab === tab.id
                                ? 'bg-blue-600 text-white shadow-md shadow-blue-600/20'
                                : 'bg-white text-slate-600 hover:bg-slate-50 border'
                                }`}
                        >
                            <tab.icon className="w-4 h-4" />
                            {tab.label}
                            {activeTab === tab.id && <ChevronRight className="w-4 h-4 ml-auto opacity-50" />}
                        </button>
                    ))}
                </div>

                {/* Editor Form */}
                <div className="lg:col-span-3 bg-white border rounded-3xl p-6 md:p-8 shadow-sm">
                    {activeTab === 'personal' && (
                        <div className="space-y-6">
                            <h2 className="text-xl font-bold flex items-center gap-2 mb-6"><User className="w-5 h-5 text-blue-500" /> Personal Information</h2>
                            <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div className="space-y-2">
                                    <label className="text-sm font-bold text-slate-700">Full Name</label>
                                    <input type="text" value={cvData.personal.name} onChange={e => setCvData({ ...cvData, personal: { ...cvData.personal, name: e.target.value } })} className="w-full px-4 py-2 bg-slate-50 border rounded-xl" placeholder="John Doe" />
                                </div>
                                <div className="space-y-2">
                                    <label className="text-sm font-bold text-slate-700">Email Address</label>
                                    <input type="email" value={cvData.personal.email} onChange={e => setCvData({ ...cvData, personal: { ...cvData.personal, email: e.target.value } })} className="w-full px-4 py-2 bg-slate-50 border rounded-xl" placeholder="john@example.com" />
                                </div>
                                <div className="space-y-2">
                                    <label className="text-sm font-bold text-slate-700">Phone Number</label>
                                    <input type="tel" value={cvData.personal.phone} onChange={e => setCvData({ ...cvData, personal: { ...cvData.personal, phone: e.target.value } })} className="w-full px-4 py-2 bg-slate-50 border rounded-xl" placeholder="+1 234 567 890" />
                                </div>
                                <div className="space-y-2">
                                    <label className="text-sm font-bold text-slate-700">Location (City, Country)</label>
                                    <input type="text" value={cvData.personal.location} onChange={e => setCvData({ ...cvData, personal: { ...cvData.personal, location: e.target.value } })} className="w-full px-4 py-2 bg-slate-50 border rounded-xl" placeholder="Toronto, Canada" />
                                </div>
                                <div className="sm:col-span-2 space-y-2">
                                    <label className="text-sm font-bold text-slate-700">LinkedIn Profile URL</label>
                                    <input type="url" value={cvData.personal.linkedin} onChange={e => setCvData({ ...cvData, personal: { ...cvData.personal, linkedin: e.target.value } })} className="w-full px-4 py-2 bg-slate-50 border rounded-xl" placeholder="https://linkedin.com/in/johndoe" />
                                </div>
                                <div className="sm:col-span-2 space-y-2">
                                    <label className="text-sm font-bold text-slate-700">Professional Summary</label>
                                    <textarea value={cvData.personal.summary} onChange={e => setCvData({ ...cvData, personal: { ...cvData.personal, summary: e.target.value } })} rows={4} className="w-full px-4 py-2 bg-slate-50 border rounded-xl resize-none" placeholder="Brief overview of your professional background and career goals..." />
                                </div>
                            </div>
                        </div>
                    )}

                    {activeTab === 'experience' && (
                        <div className="space-y-6">
                            <h2 className="text-xl font-bold flex items-center gap-2 mb-6"><Briefcase className="w-5 h-5 text-blue-500" /> Work Experience</h2>
                            <p className="text-slate-500 text-sm">Add your relevant work history. Reverse chronological order is standard in {countryName}.</p>

                            <div className="bg-slate-50 rounded-2xl border border-dashed border-slate-300 p-8 text-center mt-4">
                                <Button variant="outline" className="gap-2 rounded-xl bg-white"><BoxSelectIcon className="w-4 h-4" /> Add Experience Entry</Button>
                            </div>
                        </div>
                    )}

                    {activeTab === 'coverletter' && (
                        <div className="space-y-6">
                            <h2 className="text-xl font-bold flex items-center gap-2 mb-6"><Sparkles className="w-5 h-5 text-purple-500" /> AI Cover Letter Generator</h2>

                            <div className="bg-purple-50 rounded-2xl p-6 border border-purple-100">
                                <p className="text-sm text-purple-800 mb-6">Enter the job details you are applying for, and we will generate a tailored cover letter matching {countryName}'s professional etiquette.</p>

                                <div className="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                                    <div className="space-y-2">
                                        <label className="text-sm font-bold text-slate-700">Job Title</label>
                                        <input type="text" value={clJobTitle} onChange={(e) => setClJobTitle(e.target.value)} placeholder="e.g. Senior Software Engineer" className="w-full px-4 py-2 bg-white border rounded-xl" />
                                    </div>
                                    <div className="space-y-2">
                                        <label className="text-sm font-bold text-slate-700">Company Name</label>
                                        <input type="text" value={clCompanyName} onChange={(e) => setClCompanyName(e.target.value)} placeholder="e.g. TechCorp Inc" className="w-full px-4 py-2 bg-white border rounded-xl" />
                                    </div>
                                </div>

                                <Button
                                    onClick={handleGenerateCl}
                                    className="w-full bg-purple-600 hover:bg-purple-700 text-white rounded-xl gap-2 h-12"
                                    disabled={generateClMutation.isPending}
                                >
                                    {generateClMutation.isPending ? <Loader2 className="w-5 h-5 animate-spin" /> : <Sparkles className="w-5 h-5" />}
                                    Generate AI Cover Letter
                                </Button>
                            </div>

                            {generatedCoverLetter && (
                                <div className="mt-8 space-y-4">
                                    <h3 className="font-bold text-slate-800 flex justify-between items-center">
                                        Generated Result
                                        <Button variant="outline" size="sm" className="gap-1 rounded-lg h-8 text-xs"><Download className="w-3 h-3" /> Copy</Button>
                                    </h3>
                                    <div className="bg-white border rounded-2xl p-6 whitespace-pre-wrap text-sm text-slate-700 shadow-inner">
                                        {generatedCoverLetter}
                                    </div>
                                </div>
                            )}
                        </div>
                    )}

                    {/* Check if tab is skills to show a simple textarea */}
                    {activeTab === 'skills' && (
                        <div className="space-y-6">
                            <h2 className="text-xl font-bold flex items-center gap-2 mb-6"><Sparkles className="w-5 h-5 text-blue-500" /> Skills & Languages</h2>
                            <div className="space-y-2">
                                <label className="text-sm font-bold text-slate-700">Comma separated skills</label>
                                <textarea value={cvData.skills} onChange={e => setCvData({ ...cvData, skills: e.target.value })} rows={5} className="w-full px-4 py-2 bg-slate-50 border rounded-xl resize-none" placeholder="React, Node.js, Project Management, Fluent English, B2 French..." />
                            </div>
                        </div>
                    )}
                </div>
            </div>

        </div>
    );
}

// Temporary icon component since BoxSelect isn't in imported lucide-react yet
function BoxSelectIcon(props: any) {
    return (
        <svg
            {...props}
            xmlns="http://www.w3.org/2000/svg"
            width="24"
            height="24"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            strokeWidth="2"
            strokeLinecap="round"
            strokeLinejoin="round"
        >
            <path d="M5 8v8c0 1.1.9 2 2 2h10c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2H7c-1.1 0-2 .9-2 2Z" />
            <path d="M12 6V3" />
            <path d="M12 21v-3" />
            <path d="M6 12H3" />
            <path d="M21 12h-3" />
        </svg>
    )
}
