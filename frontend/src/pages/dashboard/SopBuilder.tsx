import { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import { sopService, type SopDraft } from '@/services/api/sopService';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';
import { useToast } from '@/hooks/use-toast';
import { 
    GraduationCap, 
    Briefcase, 
    Globe, 
    School, 
    ArrowRight, 
    ArrowLeft, 
    Sparkles, 
    Loader2, 
    CheckCircle2,
    FileText,
    Download,
    AlertCircle
} from 'lucide-react';
import { useDashboard } from '@/hooks/useDashboard';

const STEPS = [
    { id: 1, title: 'Education', icon: GraduationCap, description: 'Your academic background' },
    { id: 2, title: 'Career Goals', icon: Briefcase, description: 'What you want to achieve' },
    { id: 3, title: 'Why Country', icon: Globe, description: 'Your motivation for this destination' },
    { id: 4, title: 'Why School', icon: School, description: 'Specific interest in the institution' },
];

export default function SopBuilder() {
    const navigate = useNavigate();
    const { toast } = useToast();
    const queryClient = useQueryClient();
    const [currentStep, setCurrentStep] = useState(1);
    const { data: dashboard } = useDashboard();

    // Redirect if not premium
    useEffect(() => {
        if (dashboard && !dashboard.is_premium) {
            toast({ 
                title: 'Premium Feature', 
                description: 'SOP Builder is only available to premium users.',
                variant: 'destructive'
            });
            navigate('/pricing');
        }
    }, [dashboard, navigate, toast]);

    const { data: draft, isLoading, error } = useQuery({
        queryKey: ['sop-draft'],
        queryFn: () => sopService.start().then(r => r.data),
        retry: false, // Don't retry indefinitely if it's a 403/404
    });

    const isNoPathway = (error as any)?.response?.status === 404;
    const isForbidden = (error as any)?.response?.status === 403;

    const [aiFeedback, setAiFeedback] = useState<any>(null);

    const saveMutation = useMutation({
        mutationFn: (answers: Record<string, any>) => sopService.save(draft!.id, answers),
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['sop-draft'] });
            if (currentStep < 4) setCurrentStep(currentStep + 1);
        }
    });

    const generateMutation = useMutation({
        mutationFn: () => sopService.generate(draft!.id),
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['sop-draft'] });
            toast({ title: 'SOP Generated Successfully!' });
        }
    });

    const reviewMutation = useMutation({
        mutationFn: () => sopService.review(
            draft?.generated_text || '', 
            dashboard?.pathway?.country?.name || 'Unknown', 
            dashboard?.pathway?.visa_type?.name || 'Unknown'
        ),
        onSuccess: (res) => {
            setAiFeedback(res.data);
            toast({ title: 'AI Review Complete!' });
        },
        onError: () => {
            toast({ title: 'Failed to generate review', variant: 'destructive' });
        }
    });

    if (isLoading) {
        return (
            <div className="flex flex-col items-center justify-center min-h-[400px] space-y-4">
                <Loader2 className="h-8 w-8 animate-spin text-[#0B3C91]" />
                <p className="text-slate-500 font-medium">Initializing your SOP draft...</p>
            </div>
        );
    }

    if (isNoPathway) {
        return (
            <div className="max-w-md mx-auto my-20 bg-white p-10 rounded-[32px] border border-slate-100 shadow-xl text-center">
                <div className="h-20 w-20 bg-amber-50 rounded-3xl flex items-center justify-center mx-auto mb-6">
                    <AlertCircle className="h-10 w-10 text-amber-500" />
                </div>
                <h2 className="text-2xl font-bold text-slate-900 mb-2">No Active Pathway</h2>
                <p className="text-slate-500 mb-8">You need to select a country and visa pathway before we can help you build your SOP.</p>
                <Button 
                    onClick={() => navigate('/dashboard')} 
                    className="w-full bg-[#0B3C91] h-12 rounded-xl font-bold"
                >
                    Go to Dashboard
                </Button>
            </div>
        );
    }

    if (isForbidden || (dashboard && !dashboard.is_premium)) {
        return (
            <div className="max-w-md mx-auto my-20 bg-white p-10 rounded-[32px] border border-blue-50 shadow-xl text-center">
                <div className="h-20 w-20 bg-blue-50 rounded-3xl flex items-center justify-center mx-auto mb-6">
                    <Sparkles className="h-10 w-10 text-[#0B3C91]" />
                </div>
                <h2 className="text-2xl font-bold text-slate-900 mb-2">Premium Feature</h2>
                <p className="text-slate-500 mb-8">The AI SOP Builder is reserved for our premium members to ensure high-quality visa support.</p>
                <Button 
                    onClick={() => navigate('/settings/billing')} 
                    className="w-full bg-[#0B3C91] h-12 rounded-xl font-bold shadow-lg shadow-blue-100"
                >
                    Upgrade Now
                </Button>
            </div>
        );
    }

    if (!draft) return null;

    const answers = draft.answers || {};

    const handleNext = (e: React.FormEvent) => {
        e.preventDefault();
        const formData = new FormData(e.target as HTMLFormElement);
        const stepAnswers = Object.fromEntries(formData.entries());
        saveMutation.mutate(stepAnswers);
    };

    return (
        <div className="max-w-4xl mx-auto py-8 px-4">
            <div className="mb-8">
                <h1 className="text-3xl font-black text-[#1A1A1A] mb-2 flex items-center gap-2">
                    <Sparkles className="h-8 w-8 text-amber-500" /> SOP Builder
                </h1>
                <p className="text-[#6B7280]">Generate a professional Statement of Purpose in 4 simple steps.</p>
            </div>

            {/* Step Tracker */}
            <div className="grid grid-cols-4 gap-4 mb-10">
                {STEPS.map((step) => {
                    const Icon = step.icon;
                    const isActive = currentStep === step.id;
                    const isCompleted = currentStep > step.id || draft.status !== 'drafting';
                    
                    return (
                        <div key={step.id} className="relative">
                            <div className={`
                                flex flex-col items-center gap-2 p-4 rounded-2xl border transition-all
                                ${isActive ? 'bg-white border-[#0B3C91] shadow-md scale-105 z-10' : 'bg-slate-50 border-transparent'}
                            `}>
                                <div className={`
                                    h-10 w-10 rounded-xl flex items-center justify-center
                                    ${isActive ? 'bg-[#0B3C91] text-white' : isCompleted ? 'bg-green-100 text-green-600' : 'bg-slate-200 text-slate-400'}
                                `}>
                                    {isCompleted ? <CheckCircle2 className="h-5 w-5" /> : <Icon className="h-5 w-5" />}
                                </div>
                                <div className="text-center hidden sm:block">
                                    <p className={`text-[10px] font-black uppercase tracking-widest ${isActive ? 'text-[#0B3C91]' : 'text-slate-400'}`}>Step {step.id}</p>
                                    <p className={`text-xs font-bold ${isActive ? 'text-[#1A1A1A]' : 'text-slate-500'}`}>{step.title}</p>
                                </div>
                            </div>
                        </div>
                    );
                })}
            </div>

            <div className="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
                {/* Form Section */}
                <div className="bg-white p-8 rounded-3xl border border-[#E5E7EB] shadow-sm">
                    {draft.status === 'generated' ? (
                        <div className="space-y-6">
                            <div className="p-6 bg-slate-50 rounded-2xl border border-slate-100 italic text-slate-600 text-sm leading-relaxed whitespace-pre-wrap">
                                {draft.generated_text}
                            </div>
                            <div className="flex gap-4">
                                <Button 
                                    className="flex-1 bg-green-600 hover:bg-green-700 text-white rounded-xl h-12 font-bold flex items-center justify-center gap-2"
                                    onClick={() => window.print()}
                                >
                                    <Download className="h-5 w-5" /> Export PDF
                                </Button>
                                <Button 
                                    variant="outline" 
                                    className="flex-1 rounded-xl h-12 font-bold flex items-center justify-center gap-2 text-[#0B3C91] hover:bg-blue-50 hover:text-[#0B3C91]"
                                    onClick={() => reviewMutation.mutate()}
                                    disabled={reviewMutation.isPending}
                                >
                                    {reviewMutation.isPending ? <Loader2 className="h-5 w-5 animate-spin" /> : <Sparkles className="h-5 w-5" />}
                                    AI Review
                                </Button>
                            </div>
                            <Button 
                                variant="ghost" 
                                className="w-full text-slate-500 rounded-xl"
                                onClick={() => setCurrentStep(1)}
                            >
                                Edit Answers
                            </Button>
                        </div>
                    ) : (
                        <form onSubmit={handleNext} className="space-y-6">
                            {currentStep === 1 && (
                                <div className="space-y-4">
                                    <h3 className="text-xl font-bold">Academic Background</h3>
                                    <div className="space-y-2">
                                        <label className="text-xs font-bold uppercase tracking-widest text-slate-500">Highest Qualification</label>
                                        <Input name="education_background" defaultValue={answers.education_background} placeholder="e.g. Bachelor of Science in Computer Science" required />
                                    </div>
                                    <div className="space-y-2">
                                        <label className="text-xs font-bold uppercase tracking-widest text-slate-500">Key Achievements & Specific Projects</label>
                                        <Textarea name="education_details" defaultValue={answers.education_details} placeholder="Detail your thesis, specific high-grade modules, or research papers that prove your foundation in this field." rows={4} />
                                    </div>
                                </div>
                            )}

                            {currentStep === 2 && (
                                <div className="space-y-4">
                                    <h3 className="text-xl font-bold">Career Ambitions</h3>
                                    <div className="space-y-2">
                                        <label className="text-xs font-bold uppercase tracking-widest text-slate-500">Long-Term Career Goal</label>
                                        <Input name="career_goals" defaultValue={answers.career_goals} placeholder="e.g. Senior Machine Learning Engineer at a Global Tech Firm" required />
                                    </div>
                                    <div className="space-y-2">
                                        <label className="text-xs font-bold uppercase tracking-widest text-slate-500">Gap Analysis & Logic</label>
                                        <Textarea name="career_relevance" defaultValue={answers.career_relevance} placeholder="Why is this specific program the MISSING LINK in your career? How will it bridge your current skills to your goal?" rows={4} />
                                    </div>
                                </div>
                            )}

                            {currentStep === 3 && (
                                <div className="space-y-4">
                                    <h3 className="text-xl font-bold">Motivation for Destination</h3>
                                    <div className="space-y-2">
                                        <label className="text-xs font-bold uppercase tracking-widest text-slate-500">Why this specific country?</label>
                                        <Textarea name="why_country" defaultValue={answers.why_country} placeholder="Mention quality of life, industry standards, or cultural affinity." rows={6} required />
                                    </div>
                                </div>
                            )}

                            {currentStep === 4 && (
                                <div className="space-y-4">
                                    <h3 className="text-xl font-bold">Institutional Selection</h3>
                                    <div className="space-y-2">
                                        <label className="text-xs font-bold uppercase tracking-widest text-slate-500">Why this specific institution?</label>
                                        <Textarea name="why_school" defaultValue={answers.why_school} placeholder="Mention specific professors, unique laboratory facilities, or elective modules that aren't available elsewhere." rows={6} required />
                                    </div>
                                    <Button 
                                        type="button" 
                                        onClick={() => generateMutation.mutate()}
                                        disabled={generateMutation.isPending}
                                        className="w-full bg-amber-500 hover:bg-amber-600 text-white rounded-xl h-12 font-black text-lg gap-2 shadow-lg shadow-amber-200"
                                    >
                                        {generateMutation.isPending ? <Loader2 className="h-5 w-5 animate-spin" /> : <Sparkles className="h-5 w-5" />}
                                        Generate Final SOP
                                    </Button>
                                </div>
                            )}

                            <div className="flex justify-between pt-4">
                                {currentStep > 1 && (
                                    <Button type="button" variant="ghost" onClick={() => setCurrentStep(currentStep - 1)}>
                                        <ArrowLeft className="mr-2 h-4 w-4" /> Back
                                    </Button>
                                )}
                                {currentStep < 4 && (
                                    <Button type="submit" className="ml-auto bg-[#0B3C91] hover:bg-[#0B3C91]/90 text-white rounded-xl h-11 px-6 font-bold">
                                        Next Step <ArrowRight className="ml-2 h-4 w-4" />
                                    </Button>
                                )}
                            </div>
                        </form>
                    )}
                </div>

                {/* Preview / Tips Section */}
                <div className="space-y-6">
                    {aiFeedback ? (
                        <div className="bg-white rounded-3xl border border-blue-200 shadow-md overflow-hidden">
                            <div className="bg-gradient-to-r from-[#0B3C91] to-[#0A2A66] p-6 text-white flex items-center gap-3">
                                <Sparkles className="h-6 w-6 text-amber-400" />
                                <div>
                                    <h3 className="font-bold text-lg">AI Polish & Review</h3>
                                    <p className="text-blue-200 text-xs">Expert feedback on your draft.</p>
                                </div>
                            </div>
                            
                            <div className="p-6 space-y-6">
                                <div>
                                    <h4 className="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Overall Feedback</h4>
                                    <p className="text-sm text-slate-700 bg-slate-50 p-4 rounded-xl">{aiFeedback.overall_feedback}</p>
                                </div>

                                <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div className="bg-green-50/50 border border-green-100 p-4 rounded-xl">
                                        <h4 className="text-xs font-bold uppercase tracking-wider text-green-600 mb-3 flex items-center gap-1">
                                            <CheckCircle2 className="h-4 w-4" /> Strengths
                                        </h4>
                                        <ul className="space-y-2 text-sm text-green-800">
                                            {aiFeedback.strengths?.map((item: string, i: number) => (
                                                <li key={i} className="flex gap-2">
                                                    <span className="shrink-0 mt-0.5">•</span>
                                                    <span>{item}</span>
                                                </li>
                                            ))}
                                        </ul>
                                    </div>
                                    <div className="bg-red-50/50 border border-red-100 p-4 rounded-xl">
                                        <h4 className="text-xs font-bold uppercase tracking-wider text-red-600 mb-3 flex items-center gap-1">
                                            <AlertCircle className="h-4 w-4" /> Areas to Fix
                                        </h4>
                                        <ul className="space-y-2 text-sm text-red-800">
                                            {aiFeedback.weaknesses?.map((item: string, i: number) => (
                                                <li key={i} className="flex gap-2">
                                                    <span className="shrink-0 mt-0.5">•</span>
                                                    <span>{item}</span>
                                                </li>
                                            ))}
                                        </ul>
                                    </div>
                                </div>

                                <div>
                                    <h4 className="text-xs font-bold uppercase tracking-wider text-amber-600 mb-2 flex items-center gap-1">
                                        <Sparkles className="h-4 w-4" /> Actionable Tips
                                    </h4>
                                    <ul className="space-y-2 text-sm text-slate-700 bg-amber-50/50 border border-amber-100 p-4 rounded-xl">
                                        {aiFeedback.actionable_tips?.map((item: string, i: number) => (
                                            <li key={i} className="flex gap-2">
                                                <span className="shrink-0 mt-0.5 font-bold text-amber-500">{i + 1}.</span>
                                                <span>{item}</span>
                                            </li>
                                        ))}
                                    </ul>
                                </div>
                            </div>
                        </div>
                    ) : (
                        <>
                            <div className="bg-[#0B3C91] text-white p-8 rounded-3xl shadow-xl">
                                <h4 className="text-lg font-bold mb-4 flex items-center gap-2">
                                    <GraduationCap className="h-5 w-5" /> Expert Tip
                                </h4>
                                <p className="text-blue-100 text-sm leading-relaxed">
                                    {currentStep === 1 && "Admissions officers look for continuity. Connect your previous degree directly to the rigor of this new program. Mention GPA or Honors if strong."}
                                    {currentStep === 2 && "Embassies look for 'Intent to Return' or logical career progression. Explain how this international exposure is unavailable in your home country."}
                                    {currentStep === 3 && "Focus on the ecosystem. Why UK for Tech? Why Canada for Healthcare? Avoid generic statements about 'beautiful landscapes'."}
                                    {currentStep === 4 && "This is the 'Personalization' anchor. If you don't mention a specific module or research center, your SOP may look like a template bot wrote it."}
                                </p>
                            </div>

                            <div className="bg-white p-6 rounded-3xl border border-[#E5E7EB] shadow-sm">
                                <div className="flex items-center gap-2 mb-4">
                                    <FileText className="h-4 w-4 text-[#0B3C91]" />
                                    <span className="text-xs font-black uppercase tracking-widest text-slate-500">Live Draft Progress</span>
                                </div>
                                <div className="space-y-3">
                                    {STEPS.map(s => (
                                        <div key={s.id} className="flex items-center justify-between text-xs">
                                            <span className="text-slate-600">{s.title}</span>
                                            {Object.keys(answers).some(k => k.startsWith(s.title.toLowerCase().split(' ')[0])) ? (
                                                <span className="text-green-500 font-bold">Completed</span>
                                            ) : (
                                                <span className="text-slate-300">Pending</span>
                                            )}
                                        </div>
                                    ))}
                                </div>
                            </div>
                        </>
                    )}
                </div>
            </div>
        </div>
    );
}
