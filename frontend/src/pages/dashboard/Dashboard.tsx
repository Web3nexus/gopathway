import { useDashboard } from '@/hooks/useDashboard';
import { useCurrency } from '@/contexts/CurrencyContext';
import { MapPin, FileCheck, CreditCard, Bell, ArrowRight, Clock, AlertCircle, TrendingUp, Briefcase, Sparkles, MailWarning, Award } from 'lucide-react';
import { Link } from 'react-router-dom';
import { Button } from '@/components/ui/button';
import GoScoreWidget from '@/components/dashboard/GoScoreWidget';
import { EmployabilityScoreCard } from '@/components/dashboard/EmployabilityScoreCard';
import RelocationRoadmapWidget from '@/components/dashboard/RelocationRoadmapWidget';
import { useState } from 'react';
import { api } from '@/lib/api';
import { useToast } from '@/hooks/use-toast';

export default function Dashboard() {
    const { data: summary, isLoading } = useDashboard();
    const { formatCurrency } = useCurrency();
    const { toast } = useToast();
    const [isResending, setIsResending] = useState(false);

    const handleResendVerification = async () => {
        setIsResending(true);
        try {
            await api.post('/api/v1/email/verification-notification');
            toast({
                title: 'Verification email sent',
                description: 'Please check your inbox (and spam folder) for the link.',
            });
        } catch (error: any) {
            toast({
                variant: 'destructive',
                title: 'Error',
                description: error.response?.data?.message || 'Failed to resend verification email.',
            });
        } finally {
            setIsResending(false);
        }
    };

    if (isLoading) {
        return (
            <div className="grid grid-cols-1 md:grid-cols-3 gap-6 animate-pulse">
                <div className="col-span-1 md:col-span-2 h-64 bg-white rounded-2xl border border-[#E5E7EB]" />
                <div className="col-span-1 h-64 bg-white rounded-2xl border border-[#E5E7EB]" />
                <div className="col-span-1 md:col-span-3 h-32 bg-white rounded-2xl border border-[#E5E7EB]" />
            </div>
        );
    }

    const isEmailVerified = !!summary?.user?.email_verified_at;

    return (
        <div className="max-w-6xl mx-auto space-y-6">
            {!isEmailVerified && (
                <div className="bg-amber-50 border border-amber-200 rounded-2xl p-4 flex flex-col md:flex-row items-center justify-between gap-4">
                    <div className="flex items-center gap-3">
                        <div className="h-10 w-10 rounded-full bg-amber-100 flex items-center justify-center shrink-0">
                            <MailWarning className="h-5 w-5 text-amber-600" />
                        </div>
                        <div>
                            <p className="text-sm font-bold text-amber-900">Please verify your email address</p>
                            <p className="text-xs text-amber-700">Check your inbox for a verification link to unlock all system features.</p>
                        </div>
                    </div>
                    <Button 
                        size="sm" 
                        variant="outline" 
                        onClick={handleResendVerification}
                        className="bg-white border-amber-300 text-amber-700 hover:bg-amber-100 font-bold text-xs"
                        disabled={isResending}
                    >
                        {isResending ? 'Sending...' : 'Resend Verification Email'}
                    </Button>
                </div>
            )}
            <div className="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
                {/* Primary Column: Pathway Selection & Action Center */}
                <div className="lg:col-span-2 space-y-6">
                    {/* Destination Card & Prompt */}
                    <div className="bg-gradient-to-br from-[#0B3C91] to-[#0A2A66] rounded-2xl p-6 shadow-md relative overflow-hidden flex flex-col justify-center text-white min-h-[200px]">
                        <div className="absolute top-[-20%] right-[-10%] w-[250px] h-[250px] bg-[#00C2FF]/20 rounded-full blur-[70px]" />

                        {summary?.pathway ? (
                            <div className="relative z-10">
                                <div className="inline-flex items-center gap-1.5 px-2 py-0.5 bg-white/10 rounded-full text-[10px] font-bold tracking-widest uppercase mb-3 text-blue-100 backdrop-blur-sm">
                                    <MapPin className="h-3 w-3" /> Current Pathway
                                </div>
                                <h2 className="text-2xl sm:text-3xl font-black mb-1">{summary.pathway.country?.name}</h2>
                                <p className="text-lg text-blue-200/90 mb-5">{summary.pathway.visa_type?.name}</p>

                                <Link to="/pathway">
                                    <Button className="bg-[#00C2FF] hover:bg-[#00C2FF]/90 text-[#0B3C91] h-9 px-5 rounded-xl font-black border-none transition-all hover:scale-105 active:scale-95">
                                        View Roadmap <ArrowRight className="ml-2 h-4 w-4" />
                                    </Button>
                                </Link>
                            </div>
                        ) : summary?.profile_completeness === 100 ? (
                            <div className="relative z-10">
                                <div className="inline-flex items-center gap-1.5 px-3 py-1 bg-white/10 rounded-full text-[10px] font-bold tracking-widest uppercase mb-4 text-blue-100 backdrop-blur-sm">
                                    <TrendingUp className="h-3.5 w-3.5" /> Discovery Ready
                                </div>
                                <h2 className="text-2xl sm:text-3xl font-black mb-3">Find Your Ideal Pathway</h2>
                                <p className="text-blue-100/80 mb-6 max-w-md text-sm">Your profile is complete! Let our relocation engine recommend the best countries and visa types.</p>
                                <Link to="/recommendations">
                                    <Button className="bg-[#00C2FF] hover:bg-[#00C2FF]/90 text-[#0B3C91] h-10 px-6 rounded-xl font-black border-none transition-all hover:scale-105 active:scale-95">
                                        Browse Recommendations <ArrowRight className="ml-2 h-5 w-5" />
                                    </Button>
                                </Link>
                            </div>
                        ) : (
                            <div className="relative z-10">
                                <div className="inline-flex items-center gap-1.5 px-3 py-1 bg-white/10 rounded-full text-xs font-semibold tracking-wider uppercase mb-4 text-blue-100 backdrop-blur-sm">
                                    <AlertCircle className="h-3.5 w-3.5" /> Action Required
                                </div>
                                <h2 className="text-2xl sm:text-3xl font-bold mb-3">Complete Your Profile</h2>
                                <p className="text-blue-200 mb-6 max-w-md">Tell us about your background so we can recommend the best immigration pathway.</p>
                                <Link to="/profile/setup">
                                    <Button className="bg-[#00C2FF] hover:bg-[#00C2FF]/90 text-[#0B3C91] font-semibold border-none">
                                        Start Profile Wizard <ArrowRight className="ml-2 h-4 w-4" />
                                    </Button>
                                </Link>
                            </div>
                        )}
                    </div>

                    {/* Onboarding Roadmap */}
                    <RelocationRoadmapWidget 
                        profileCompleteness={summary?.profile_completeness || 0}
                        hasPathway={!!summary?.pathway}
                        documentsCount={summary?.documents_uploaded || 0}
                    />

                    {/* Action Hub: Stats & Next Steps */}
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {/* Next Step Card */}
                        <div className="bg-white p-5 rounded-2xl border border-[#E5E7EB] shadow-sm flex flex-col justify-between">
                            <div>
                                <div className="flex items-center gap-2 mb-3">
                                    <div className="h-8 w-8 rounded-lg bg-amber-50 flex items-center justify-center">
                                        <Clock className="h-4 w-4 text-amber-600" />
                                    </div>
                                    <p className="text-[10px] font-black text-[#6B7280] uppercase tracking-widest">Immediate Next Step</p>
                                </div>
                                <p className="text-base font-bold text-[#1A1A1A] leading-tight mb-1">
                                    {summary?.next_step?.title || (summary?.pathway ? 'All caught up!' : 'Setup your destination profile')}
                                </p>
                                <p className="text-xs text-[#6B7280] line-clamp-2">
                                    {summary?.next_step?.description || 'Your roadmap will guide you through the next requirements.'}
                                </p>
                            </div>
                            <Link to="/pathway" className="mt-4">
                                <Button variant="ghost" size="sm" className="w-full justify-between text-[#0B3C91] hover:bg-blue-50 font-bold rounded-lg h-10 px-4 group">
                                    Continue Pathway <ArrowRight className="h-4 w-4 transition-transform group-hover:translate-x-1" />
                                </Button>
                            </Link>
                        </div>

                        {/* Quick Metrics Sub-grid */}
                        <div className="grid grid-rows-2 gap-4">
                            <div className="bg-white p-4 rounded-2xl border border-[#E5E7EB] shadow-sm flex items-center gap-4">
                                <div className="h-10 w-10 rounded-xl bg-blue-50 flex items-center justify-center shrink-0">
                                    <FileCheck className="h-5 w-5 text-[#0B3C91]" />
                                </div>
                                <div className="flex-1 min-w-0">
                                    <p className="text-[9px] font-black text-[#6B7280] uppercase tracking-widest">Docs Uploaded</p>
                                    <p className="text-lg font-black text-[#1A1A1A]">{summary?.documents_uploaded || 0}</p>
                                </div>
                                <Link to="/documents">
                                    <Button variant="ghost" size="sm" className="h-8 w-8 p-0 rounded-lg hover:bg-blue-50">
                                        <ArrowRight className="h-4 w-4 text-[#0B3C91]" />
                                    </Button>
                                </Link>
                            </div>
                            <div className="bg-white p-4 rounded-2xl border border-[#E5E7EB] shadow-sm flex items-center gap-4">
                                <div className="h-10 w-10 rounded-xl bg-green-50 flex items-center justify-center shrink-0">
                                    <CreditCard className="h-5 w-5 text-green-600" />
                                </div>
                                <div className="flex-1 min-w-0">
                                    <p className="text-[9px] font-black text-[#6B7280] uppercase tracking-widest">Est. Costs</p>
                                    <p className="text-lg font-black text-[#1A1A1A]">{formatCurrency(summary?.total_costs || 0, true)}</p>
                                </div>
                                <div className="h-8 w-8 rounded-lg bg-slate-50 flex items-center justify-center">
                                    <AlertCircle className="h-4 w-4 text-slate-300" />
                                </div>
                            </div>
                        </div>
                    </div>

                    {/* Activity Feed — Moved inside primary column to eliminate gap */}
                    <div className="bg-white rounded-2xl border border-[#E5E7EB] shadow-sm overflow-hidden">
                        <div className="px-6 py-4 border-b border-[#E5E7EB] bg-[#F5F7FA] flex items-center justify-between">
                            <h3 className="font-semibold text-[#1A1A1A] flex items-center gap-2">
                                <Bell className="h-4 w-4 text-[#6B7280]" /> Recent Activity
                            </h3>
                        </div>
                        <div className="p-6">
                            {Array.isArray(summary?.recent_notifications) && summary.recent_notifications.length > 0 ? (
                                <ul className="space-y-4">
                                    {summary.recent_notifications.map((notif: any) => (
                                        <li key={notif.id} className="flex gap-4 items-start pb-4 border-b border-[#E5E7EB] last:border-0 last:pb-0">
                                            <div className={`h-2 w-2 rounded-full mt-2 flex-shrink-0 ${notif.is_read ? 'bg-gray-300' : 'bg-[#00C2FF]'}`} />
                                            <div>
                                                <p className={`text-sm ${notif.is_read ? 'text-[#6B7280]' : 'text-[#1A1A1A] font-medium'}`}>{notif.title}</p>
                                                <p className="text-xs text-gray-400 mt-1">{new Date(notif.created_at).toLocaleDateString()}</p>
                                            </div>
                                        </li>
                                    ))}
                                </ul>
                            ) : (
                                <div className="text-center py-6">
                                    <p className="text-[#6B7280] text-sm">No recent activity.</p>
                                </div>
                            )}
                        </div>
                    </div>
                </div>

                {/* Sidebar Column */}
                <div className="lg:col-span-1 space-y-6">
                    {/* GoScore Widget */}
                    <GoScoreWidget />

                    {/* Employability Score Widget */}
                    <EmployabilityScoreCard />

                    {/* Join as Professional CTA — Moved to sidebar */}
                    <div className="bg-gradient-to-br from-[#F9FAFB] to-white rounded-2xl border-2 border-dashed border-[#E5E7EB] p-8 flex flex-col items-center justify-center text-center">
                        <div className="h-16 w-16 rounded-2xl bg-blue-50 flex items-center justify-center mb-6">
                            <Briefcase className="h-8 w-8 text-[#0B3C91]" />
                        </div>
                        <h3 className="text-lg font-bold text-[#1A1A1A] mb-2">Are you an expert?</h3>
                        <Link to="/professional/onboarding" className="w-full">
                            <Button variant="outline" className="w-full border-[#0B3C91] text-[#0B3C91] hover:bg-blue-50 font-bold">
                                Join as Specialist
                            </Button>
                        </Link>
                    </div>

                    {/* Scholarship Promo */}
                    <div className="bg-gradient-to-br from-blue-50 to-white rounded-2xl border border-blue-100 p-6 shadow-sm">
                        <div className="flex items-center gap-3 mb-4">
                            <div className="h-10 w-10 rounded-xl bg-blue-100 flex items-center justify-center shrink-0">
                                <Award className="h-5 w-5 text-blue-600" />
                            </div>
                            <div>
                                <p className="text-[10px] font-black text-blue-600 uppercase tracking-widest">New Feature</p>
                                <h4 className="text-sm font-bold text-[#1A1A1A]">Scholarships</h4>
                            </div>
                        </div>
                        <p className="text-xs text-slate-500 mb-4 leading-relaxed">
                            Find fully funded and partial scholarships to support your study abroad journey.
                        </p>
                        <Link to="/scholarships">
                            <Button className="w-full bg-[#0B3C91] hover:bg-[#0B3C91]/90 text-white rounded-xl h-9 font-bold text-xs gap-2">
                                Browse Opportunities <ArrowRight className="h-3.5 w-3.5" />
                            </Button>
                        </Link>
                    </div>

                    {/* SOP Builder Promo */}
                    <div className="bg-gradient-to-br from-indigo-50 to-white rounded-2xl border border-indigo-100 p-6 shadow-sm">
                        <div className="flex items-center gap-3 mb-4">
                            <div className="h-10 w-10 rounded-xl bg-indigo-100 flex items-center justify-center shrink-0">
                                <Sparkles className="h-5 w-5 text-indigo-600" />
                            </div>
                            <div>
                                <p className="text-[10px] font-black text-indigo-600 uppercase tracking-widest">Premium Tool</p>
                                <h4 className="text-sm font-bold text-[#1A1A1A]">SOP Builder</h4>
                            </div>
                        </div>
                        <p className="text-xs text-slate-500 mb-4 leading-relaxed">
                            Generate a professional Statement of Purpose in minutes with our guided wizard.
                        </p>
                        <Link to="/sop-builder">
                            <Button className="w-full bg-[#0B3C91] hover:bg-[#0B3C91]/90 text-white rounded-xl h-9 font-bold text-xs gap-2">
                                Launch Builder <ArrowRight className="h-3.5 w-3.5" />
                            </Button>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    );
}
