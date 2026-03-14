import { useQuery } from '@tanstack/react-query';
import { publicService } from '@/services/api/publicService';
import { Loader2, BookOpen, ChevronRight, HelpCircle } from 'lucide-react';
import { Link } from 'react-router-dom';

export default function Documentation() {
    const { data: settingsData, isLoading } = useQuery({
        queryKey: ['public-settings'],
        queryFn: publicService.getSettings,
        staleTime: 1000 * 60 * 60,
    });

    const settings = settingsData?.data || {};
    const content = settings.documentation_content;

    if (isLoading) {
        return (
            <div className="flex justify-center items-center min-h-[60vh]">
                <Loader2 className="h-8 w-8 animate-spin text-[#0B3C91]" />
            </div>
        );
    }

    return (
        <div className="bg-[#F5F7FA] min-h-screen pb-20">
            {/* Hero Section */}
            <div className="bg-white border-b border-[#E5E7EB]">
                <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center">
                    <div className="h-16 w-16 rounded-2xl bg-blue-50 flex items-center justify-center text-[#0B3C91] mx-auto mb-6">
                        <BookOpen className="h-8 w-8" />
                    </div>
                    <h1 className="text-4xl font-black text-[#1A1A1A] tracking-tight mb-2">Documentation</h1>
                    <p className="text-lg text-[#6B7280]">Learn how to use the GoPathway platform and maximize your relocation strategy.</p>
                </div>
            </div>

            <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div className="bg-white rounded-3xl border border-[#E5E7EB] shadow-sm overflow-hidden p-8 md:p-12">
                    <div 
                        className="prose prose-slate max-w-none text-[#4B5563] leading-relaxed
                        prose-headings:text-[#1A1A1A] prose-headings:font-bold
                        prose-h1:text-3xl prose-h2:text-2xl prose-h3:text-xl
                        prose-p:mb-6 prose-li:mb-2 prose-strong:text-[#0B3C91]"
                        dangerouslySetInnerHTML={{ __html: content || '<p>Documentation is being compiled. Please contact support if you have specific questions.</p>' }}
                    />

                    <div className="mt-16 p-8 bg-blue-50 rounded-2xl border border-blue-100 flex flex-col md:flex-row items-center justify-between gap-6">
                        <div>
                            <h3 className="text-xl font-bold text-[#0B3C91] mb-1">Still need help?</h3>
                            <p className="text-sm text-blue-700">Our support team is available to help you with any questions or issues.</p>
                        </div>
                        <Link to="/support">
                            <button className="bg-[#0B3C91] hover:bg-[#0B3C91]/90 text-white px-6 py-3 rounded-xl font-bold transition-all whitespace-nowrap">
                                Contact Support
                            </button>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    );
}
