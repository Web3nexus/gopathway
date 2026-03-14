import { useState } from 'react';
import { HelpCircle, MessageSquare, BookOpen, ChevronDown, ChevronUp, Mail, ExternalLink, Shield, Zap, FileText, Users, Loader2 } from 'lucide-react';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';
import { useToast } from '@/hooks/use-toast';
import api from '@/lib/api';
import { Turnstile } from '@marsidev/react-turnstile';
import { useQuery } from '@tanstack/react-query';
import { publicService } from '@/services/api/publicService';

const faqs = [
    {
        q: 'How do I select a relocation pathway?',
        a: 'Go to the Pathway Planner page, choose your destination country and visa type, then click "Generate My Roadmap." Your personalized timeline steps will be generated automatically.',
    },
    {
        q: 'What is the Cost Planner and how does it work?',
        a: "The Cost Planner shows estimated costs for your chosen pathway, including visa fees, accommodation, and living expenses. It's a premium feature that provides real-time cost breakdowns based on admin-defined templates.",
    },
    {
        q: 'How do I upgrade to Premium?',
        a: 'Visit the Pricing page from the sidebar. Toggle between Monthly and Yearly plans, then click "Upgrade Now." Payments are processed securely via Paystack.',
    },
    {
        q: 'Can I talk to an immigration expert?',
        a: 'Yes! Visit the Experts page to browse verified lawyers and translators in our marketplace. You can book consultation or translation sessions directly through the platform.',
    },
    {
        q: 'How do I upload my documents?',
        a: 'Go to the Document Vault in your dashboard. You can upload passports, qualification certificates, and other required documents. Premium users have access to the full document vault.',
    },
    {
        q: 'What happens if I change my pathway?',
        a: 'If you select a new pathway, your old timeline steps will be replaced with new ones matching the new visa type. Your uploaded documents and billing history are preserved.',
    },
    {
        q: 'How is my data protected?',
        a: 'All data is encrypted in transit using HTTPS. Payments are processed by Paystack (PCI compliant). We never store your card details on our servers.',
    },
];

const guides = [
    { icon: Zap, title: 'Getting Started', desc: 'Complete your profile and discover your best pathway' },
    { icon: FileText, title: 'Document Preparation', desc: 'What documents you need and how to upload them' },
    { icon: Users, title: 'Working with Experts', desc: 'How to find and book immigration professionals' },
    { icon: Shield, title: 'Premium Features', desc: 'Everything included in your premium subscription' },
];

export default function Support() {
    const { toast } = useToast();
    const [openFaq, setOpenFaq] = useState<number | null>(0);
    const [contactForm, setContactForm] = useState({ subject: '', message: '', cf_turnstile_response: '' });
    const [sending, setSending] = useState(false);

    const { data: settingsData } = useQuery({
        queryKey: ['public-settings'],
        queryFn: publicService.getSettings,
        staleTime: 1000 * 60 * 60,
    });

    const turnstileSiteKey = settingsData?.data?.turnstile_site_key;

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        if (!contactForm.subject.trim() || !contactForm.message.trim()) {
            toast({ title: 'Please fill in all fields', variant: 'destructive' });
            return;
        }
        setSending(true);
        try {
            await api.post('/api/v1/support', {
                subject: contactForm.subject,
                message: contactForm.message,
                cf_turnstile_response: contactForm.cf_turnstile_response
            });
            toast({ 
                title: 'Message sent!', 
                description: 'Our support team will respond within 24 hours.' 
            });
            setContactForm({ subject: '', message: '', cf_turnstile_response: '' });
        } catch (error: any) {
            console.error('Support error:', error);
            toast({ 
                title: 'Failed to send message', 
                description: error.response?.data?.message || 'Something went wrong. Please try again later.',
                variant: 'destructive' 
            });
        } finally {
            setSending(false);
        }
    };

    return (
        <div className="max-w-4xl mx-auto space-y-8">
            {/* Header */}
            <div className="text-center space-y-3">
                <div className="inline-flex items-center justify-center h-16 w-16 rounded-2xl bg-blue-50 mx-auto">
                    <HelpCircle className="w-8 h-8 text-[#0B3C91]" />
                </div>
                <h1 className="text-3xl font-bold text-[#1A1A1A]">Help & Support</h1>
                <p className="text-[#6B7280] max-w-lg mx-auto">Find answers to common questions, explore guides, or reach out to our support team.</p>
            </div>

            {/* Quick Guides */}
            <div>
                <h2 className="text-lg font-bold text-[#1A1A1A] mb-4 flex items-center gap-2">
                    <BookOpen className="w-5 h-5 text-[#0B3C91]" /> Quick Guides
                </h2>
                <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    {guides.map((guide, i) => (
                        <div key={i} className="bg-white rounded-2xl border border-[#E5E7EB] shadow-sm p-6 hover:shadow-md hover:border-blue-200 transition-all duration-200 cursor-pointer group">
                            <div className="flex items-start gap-4">
                                <div className="h-10 w-10 rounded-xl bg-blue-50 flex items-center justify-center shrink-0 group-hover:bg-blue-100 transition-colors">
                                    <guide.icon className="w-5 h-5 text-[#0B3C91]" />
                                </div>
                                <div>
                                    <h3 className="font-bold text-sm text-[#1A1A1A] group-hover:text-[#0B3C91] transition-colors">{guide.title}</h3>
                                    <p className="text-xs text-slate-500 mt-1">{guide.desc}</p>
                                </div>
                            </div>
                        </div>
                    ))}
                </div>
            </div>

            {/* FAQ Accordion */}
            <div>
                <h2 className="text-lg font-bold text-[#1A1A1A] mb-4 flex items-center gap-2">
                    <HelpCircle className="w-5 h-5 text-amber-500" /> Frequently Asked Questions
                </h2>
                <div className="bg-white rounded-2xl border border-[#E5E7EB] shadow-sm overflow-hidden divide-y divide-slate-100">
                    {faqs.map((faq, i) => (
                        <div key={i}>
                            <button
                                onClick={() => setOpenFaq(openFaq === i ? null : i)}
                                className="w-full flex items-center justify-between px-6 py-4 text-left hover:bg-slate-50 transition-colors"
                            >
                                <span className={`text-sm font-semibold ${openFaq === i ? 'text-[#0B3C91]' : 'text-[#1A1A1A]'}`}>{faq.q}</span>
                                {openFaq === i ? <ChevronUp className="w-4 h-4 text-[#0B3C91] shrink-0" /> : <ChevronDown className="w-4 h-4 text-slate-300 shrink-0" />}
                            </button>
                            {openFaq === i && (
                                <div className="px-6 pb-4 text-sm text-slate-600 leading-relaxed animate-in fade-in slide-in-from-top-1 duration-200">
                                    {faq.a}
                                </div>
                            )}
                        </div>
                    ))}
                </div>
            </div>

            {/* Contact Form */}
            <div>
                <h2 className="text-lg font-bold text-[#1A1A1A] mb-4 flex items-center gap-2">
                    <MessageSquare className="w-5 h-5 text-green-500" /> Contact Support
                </h2>
                <div className="bg-white rounded-2xl border border-[#E5E7EB] shadow-sm overflow-hidden">
                    <div className="px-8 py-6 border-b border-[#E5E7EB] bg-slate-50/50">
                        <p className="text-sm text-slate-600">Can't find what you're looking for? Send us a message and we'll get back to you within 24 hours.</p>
                    </div>
                    <form onSubmit={handleSubmit} className="p-8 space-y-4">
                        <div className="space-y-2">
                            <label className="text-xs font-bold uppercase text-slate-400">Subject</label>
                            <Input
                                value={contactForm.subject}
                                onChange={e => setContactForm({ ...contactForm, subject: e.target.value })}
                                placeholder="e.g. Issue with document upload"
                                className="rounded-xl"
                                required
                            />
                        </div>
                        <div className="space-y-2">
                            <label className="text-xs font-bold uppercase text-slate-400">Your Message</label>
                            <Textarea
                                value={contactForm.message}
                                onChange={e => setContactForm({ ...contactForm, message: e.target.value })}
                                placeholder="Describe your issue or question in detail..."
                                rows={5}
                                className="rounded-xl"
                                required
                            />
                        </div>

                        {turnstileSiteKey && (
                            <div className="flex justify-start py-2">
                                <Turnstile 
                                    siteKey={turnstileSiteKey} 
                                    onSuccess={(token: string) => setContactForm({ ...contactForm, cf_turnstile_response: token })}
                                />
                            </div>
                        )}

                        <Button type="submit" disabled={sending} className="bg-[#0B3C91] hover:bg-[#0A2A66] text-white rounded-xl font-bold px-8">
                            {sending ? <Loader2 className="w-4 h-4 animate-spin mr-2" /> : null}
                            {sending ? 'Sending...' : 'Send Message'}
                        </Button>
                    </form>
                </div>
            </div>

            {/* Contact Info Footer */}
            <div className="bg-gradient-to-br from-[#0B3C91] to-[#0A2A66] rounded-2xl p-8 text-white flex flex-col md:flex-row items-center justify-between gap-6">
                <div>
                    <h3 className="text-lg font-bold mb-1">Need urgent help?</h3>
                    <p className="text-blue-200 text-sm">Reach out directly to our support team via email.</p>
                </div>
                <a href="mailto:support@gopathway.net" className="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 px-6 py-3 rounded-xl font-bold text-sm transition-colors backdrop-blur-sm border border-white/10">
                    <Mail className="w-4 h-4" /> support@gopathway.net <ExternalLink className="w-3 h-3 ml-1" />
                </a>
            </div>
        </div>
    );
}
