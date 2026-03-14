import { useQuery } from '@tanstack/react-query';
import { publicService } from '@/services/api/publicService';
import { Loader2, Shield } from 'lucide-react';

export default function Privacy() {
    const { data: settingsData, isLoading } = useQuery({
        queryKey: ['public-settings'],
        queryFn: publicService.getSettings,
        staleTime: 1000 * 60 * 60,
    });

    const settings = settingsData?.data || {};
    const content = settings.privacy_policy_content;

    if (isLoading) {
        return (
            <div className="flex justify-center items-center min-h-[60vh]">
                <Loader2 className="h-8 w-8 animate-spin text-[#0B3C91]" />
            </div>
        );
    }

    return (
        <div className="max-w-4xl mx-auto px-4 py-16">
            <div className="bg-white rounded-3xl border border-[#E5E7EB] shadow-sm overflow-hidden p-8 md:p-12">
                <div className="flex items-center gap-3 mb-8">
                    <div className="h-10 w-10 rounded-xl bg-blue-50 flex items-center justify-center text-[#0B3C91]">
                        <Shield className="h-6 w-6" />
                    </div>
                    <h1 className="text-3xl font-black text-[#1A1A1A] tracking-tight">Privacy Policy</h1>
                </div>
                
                <div 
                    className="prose prose-slate max-w-none text-[#4B5563] leading-relaxed
                    prose-headings:text-[#1A1A1A] prose-headings:font-bold
                    prose-h1:text-3xl prose-h2:text-2xl prose-h3:text-xl
                    prose-p:mb-4 prose-li:mb-2"
                    dangerouslySetInnerHTML={{ __html: content || '<p>Privacy policy content is currently being updated. Please check back soon.</p>' }}
                />

                <div className="mt-12 pt-8 border-t border-[#E5E7EB] text-sm text-[#6B7280]">
                    Last updated: {new Date().toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' })}
                </div>
            </div>
        </div>
    );
}
