import { Link } from 'react-router-dom';
import { Button } from '@/components/ui/button';
import { Helmet } from 'react-helmet-async';
import {
    ArrowRight, Globe, FileText, TrendingUp, Users,
    ShieldCheck, Zap, Wallet, FolderKanban, CheckCircle2,
    BarChart3, Search, Sparkles, MessageSquare, ChevronDown
} from 'lucide-react';
import { useCountries } from '@/hooks/useCountries';
import { useQuery } from '@tanstack/react-query';
import { planService } from '@/services/api/planService';
import { motion, AnimatePresence } from 'framer-motion';
import { useState } from 'react';
import { WorldConnectionMap } from '@/components/landing/WorldConnectionMap';

const containerVariants = {
    hidden: { opacity: 0 },
    visible: {
        opacity: 1,
        transition: {
            staggerChildren: 0.1,
            delayChildren: 0.3
        }
    }
};

const itemVariants = {
    hidden: { y: 20, opacity: 0 },
    visible: { y: 0, opacity: 1, transition: { duration: 0.5, ease: "easeOut" } }
};

const faqData = [
    { q: "How does GoPathway help with my visa?", a: "We provide comprehensive intelligence on visa types, costs, and requirements. Our platform breaks down complex immigration laws into actionable steps, tracking your progress through a personalized roadmap." },
    { q: "Is my personal data secure?", a: "Yes. We use bank-grade encryption (AES-256) to protect your sensitive documents in the Secure Document Vault. Your data is only accessible to you and experts you explicitly choose to share it with." },
    { q: "Do you provide lawyers or consultants?", a: "We have a curated network of verified immigration professionals, certified translators, and settlement advisors. You can browse their profiles and book sessions directly through the platform." },
    { q: "Can I use GoPathway for multiple countries?", a: "Absolutely. Our platform allows you to assess and compare pathways for over 15+ countries simultaneously, helping you make the most informed decision for your future." }
];

export default function Home() {
    const { data: countries, isLoading } = useCountries();
    const { data: plans } = useQuery({
        queryKey: ['plans'],
        queryFn: planService.getPlans,
        retry: false,
    });
    const [openFaq, setOpenFaq] = useState<number | null>(null);

    const activePlan = Array.isArray(plans) ? plans.find((p: any) => p.slug?.includes('monthly') || p.price > 0) : null;

    return (
        <div className="overflow-x-hidden bg-white">
            <Helmet>
                <title>GoPathway | Your Global Intelligence for Seamless Relocation</title>
                <meta name="description" content="Master your move with GoPathway. Get personalized readiness scores, expert guidance, and track your global migration journey in one place." />
                <meta name="keywords" content="relocation, immigration, visa guide, global talent, study abroad, moving to canada, moving to uk" />
            </Helmet>

            {/* Hero Section */}
            <section className="relative pt-20 pb-20 lg:pt-32 lg:pb-32 overflow-hidden min-h-[90vh] flex items-center">
                <div className="absolute inset-0 -z-10 bg-slate-50/50" />
                <div className="absolute inset-0 -z-10 opacity-40">
                    <WorldConnectionMap />
                </div>
                <div className="absolute inset-0 -z-10 bg-gradient-to-b from-white/0 via-white/80 to-white" />

                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full">
                    <motion.div
                        initial="hidden"
                        animate="visible"
                        variants={containerVariants}
                        className="flex flex-col items-center text-center"
                    >
                        <motion.span
                            variants={itemVariants}
                            className="inline-flex items-center gap-2 bg-blue-600/10 text-blue-700 text-xs font-bold px-4 py-1.5 rounded-full mb-8 uppercase tracking-widest border border-blue-200 backdrop-blur-sm"
                        >
                            <Sparkles className="w-3 h-3" />
                            Global Mobility Reinvented
                        </motion.span>

                        <motion.h1
                            variants={itemVariants}
                            className="text-5xl lg:text-8xl font-black text-slate-900 leading-[1.1] mb-8 max-w-5xl tracking-tighter"
                        >
                            Your Entire Global <br />
                            <span className="text-blue-600 relative inline-block">
                                Journey Managed.
                                <svg className="absolute -bottom-2 left-0 w-full h-3 text-blue-200" viewBox="0 0 100 10" preserveAspectRatio="none">
                                    <path d="M0 5 Q 50 10 100 5" fill="none" stroke="currentColor" strokeWidth="4" />
                                </svg>
                            </span>
                        </motion.h1>

                        <motion.p
                            variants={itemVariants}
                            className="text-xl text-slate-600 mb-10 max-w-2xl font-medium leading-relaxed"
                        >
                            GoPathway is the definitive intelligence platform for international relocation.
                            Visa strategy, financial planning, and expert networks — all in a single dashboard.
                        </motion.p>

                        <motion.div
                            variants={itemVariants}
                            className="flex flex-col sm:flex-row gap-5"
                        >
                            <Link to="/register">
                                <Button size="lg" className="bg-blue-600 hover:bg-blue-700 text-white px-10 h-16 text-lg rounded-2xl shadow-xl shadow-blue-200 transition-all hover:scale-105 active:scale-95 group">
                                    Get Started Free
                                    <ArrowRight className="ml-2 h-5 w-5 transition-transform group-hover:translate-x-1" />
                                </Button>
                            </Link>
                            <Link to="/countries">
                                <Button size="lg" variant="outline" className="px-10 h-16 text-lg rounded-2xl border-2 hover:bg-slate-50 transition-all backdrop-blur-sm bg-white/50">
                                    Explore Destinations
                                </Button>
                            </Link>
                        </motion.div>
                    </motion.div>
                </div>
            </section>

            {/* Stats Section */}
            <section className="py-20 bg-slate-900 text-white overflow-hidden relative">
                <div className="absolute top-0 right-0 w-96 h-96 bg-blue-500/10 rounded-full blur-[100px]" />
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="grid grid-cols-2 lg:grid-cols-4 gap-12 lg:gap-8">
                        {[
                            { label: "Countries Tracked", value: "15+", icon: Globe },
                            { label: "Active Pathways", value: "150+", icon: Zap },
                            { label: "Document Types", value: "200+", icon: FileText },
                            { label: "Expert Network", value: "1k+", icon: Users },
                        ].map((stat, i) => (
                            <motion.div
                                key={i}
                                initial={{ opacity: 0, y: 20 }}
                                whileInView={{ opacity: 1, y: 0 }}
                                viewport={{ once: true }}
                                transition={{ delay: i * 0.1 }}
                                className="flex flex-col items-center text-center group"
                            >
                                <div className="h-12 w-12 bg-white/5 rounded-xl flex items-center justify-center mb-4 group-hover:bg-blue-600/20 transition-colors">
                                    <stat.icon className="h-6 w-6 text-blue-400" />
                                </div>
                                <span className="text-4xl lg:text-5xl font-black mb-2 text-glow">{stat.value}</span>
                                <span className="text-slate-400 text-sm font-bold uppercase tracking-widest">{stat.label}</span>
                            </motion.div>
                        ))}
                    </div>
                </div>
            </section>

            {/* Countries Preview */}
            <section className="py-24 bg-slate-50">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="flex flex-col md:flex-row justify-between items-center mb-16 gap-6">
                        <div className="text-center md:text-left">
                            <h2 className="text-3xl lg:text-4xl font-black text-slate-900 mb-4 tracking-tight">Top Destinations</h2>
                            <p className="text-slate-600 text-lg font-medium leading-relaxed">Where will your next chapter begin?</p>
                        </div>
                        <Link to="/countries">
                            <Button size="lg" className="bg-white text-slate-900 border-2 shadow-sm px-8 rounded-xl font-bold hover:bg-slate-50 transition-all flex items-center gap-2 group">
                                View Intelligence Reports
                                <ArrowRight className="h-4 w-4 transition-transform group-hover:translate-x-1" />
                            </Button>
                        </Link>
                    </div>

                    {isLoading ? (
                        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                            {[...Array(3)].map((_, i) => (
                                <div key={i} className="h-72 rounded-3xl bg-slate-200 animate-pulse" />
                            ))}
                        </div>
                    ) : (
                        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                            {Array.isArray(countries) ? countries.slice(0, 6).map((country: any, idx: number) => (
                                <motion.div
                                    key={country.id}
                                    initial={{ opacity: 0, scale: 0.95 }}
                                    whileInView={{ opacity: 1, scale: 1 }}
                                    viewport={{ once: true }}
                                    transition={{ delay: idx * 0.05 }}
                                >
                                    <Link to={`/countries/${country.id}`}>
                                        <div className="relative h-80 rounded-[32px] overflow-hidden group cursor-pointer shadow-xl transition-all duration-500 hover:-translate-y-2">
                                            <img
                                                src={country.image_url}
                                                alt={country.name}
                                                className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                                            />
                                            <div className="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/20 to-transparent" />

                                            {/* Top Badges */}
                                            <div className="absolute top-5 left-5 right-5 flex justify-between items-center">
                                                <div className="px-3 py-1.5 glass rounded-2xl flex items-center gap-2">
                                                    <span className="text-[10px] font-black text-white/60 uppercase tracking-tighter">Rank</span>
                                                    <span className="text-sm font-black text-white">#{idx + 1}</span>
                                                </div>
                                                <div className="px-3 py-1.5 bg-cyan-400 text-slate-950 rounded-2xl flex items-center gap-2 font-black text-xs uppercase shadow-lg shadow-cyan-400/20">
                                                    <TrendingUp className="h-3 w-3" />
                                                    {country.competitiveness_score || 50} Score
                                                </div>
                                            </div>

                                            <div className="absolute bottom-0 left-0 right-0 p-8">
                                                <div className="flex items-center gap-2 mb-3">
                                                    <div className="h-1.5 w-10 bg-cyan-400 rounded-full" />
                                                    <span className="text-xs font-black text-white/70 uppercase tracking-widest">{country.visa_types_count} Pathways</span>
                                                </div>
                                                <h3 className="text-3xl font-black text-white tracking-tight">{country.name}</h3>
                                            </div>
                                        </div>
                                    </Link>
                                </motion.div>
                            )) : null}
                        </div>
                    )}
                </div>
            </section>

            {/* How It Works - Detailed */}
            <section className="py-24 bg-white relative">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center mb-20">
                        <span className="text-blue-600 font-black tracking-[0.2em] uppercase text-sm mb-4 block">The Ecosystem</span>
                        <h2 className="text-4xl lg:text-5xl font-black text-slate-900 tracking-tight">How GoPathway Works</h2>
                        <p className="text-slate-600 mt-6 text-xl max-w-2xl mx-auto font-medium">A structured approach to the most complex move of your life.</p>
                    </div>

                    <div className="grid grid-cols-1 lg:grid-cols-3 gap-12 relative">
                        <div className="hidden lg:block absolute top-20 left-[15%] right-[15%] h-px bg-slate-100 -z-10" />

                        {[
                            {
                                id: "01",
                                title: "Intelligence & Assessment",
                                desc: "Sync your profile with global migration databases. Identify every viable pathway and understand your probability of success instantly.",
                                icon: Search,
                                color: "bg-blue-600"
                            },
                            {
                                id: "02",
                                title: "Financial & Strategy",
                                desc: "Map out the complete cost of your move. Use our mobility tools to plan tuition, proof of funds, living expenses, and application fees.",
                                icon: Wallet,
                                color: "bg-cyan-500"
                            },
                            {
                                id: "03",
                                title: "Secure Execution",
                                desc: "Execute with precision. Compile your document vault, build your SOP, and connect with legal and settlement experts for final submission.",
                                icon: ShieldCheck,
                                color: "bg-slate-900"
                            }
                        ].map((step, i) => (
                            <motion.div
                                key={i}
                                initial={{ opacity: 0, y: 20 }}
                                whileInView={{ opacity: 1, y: 0 }}
                                viewport={{ once: true }}
                                transition={{ delay: i * 0.1 }}
                                className="relative p-10 glossy-card rounded-[40px] flex flex-col group hover:shadow-2xl transition-all duration-500 overflow-hidden"
                            >
                                <div className={`h-16 w-16 ${step.color} rounded-2xl flex items-center justify-center text-white mb-8 shadow-xl transition-transform group-hover:rotate-12`}>
                                    <step.icon className="h-8 w-8" />
                                </div>
                                <span className="absolute top-10 right-10 text-6xl font-black text-slate-100 group-hover:text-blue-50 transition-colors pointer-events-none">{step.id}</span>
                                <h3 className="text-2xl font-black text-slate-900 mb-4 tracking-tight group-hover:text-blue-700 transition-colors">{step.title}</h3>
                                <p className="text-slate-600 leading-relaxed font-medium">{step.desc}</p>
                            </motion.div>
                        ))}
                    </div>
                </div>
            </section>

            {/* Smart Mobility Section */}
            <section className="py-24 bg-slate-900 relative overflow-hidden">
                <div className="absolute inset-0 bg-[radial-gradient(circle_at_30%_50%,rgba(0,194,255,0.08),transparent_50%)]" />
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                    <div className="grid grid-cols-1 lg:grid-cols-2 gap-20 items-center">
                        <div>
                            <span className="text-cyan-400 font-black tracking-widest uppercase text-xs mb-4 block">The Toolkit</span>
                            <h2 className="text-4xl lg:text-6xl font-black text-white mb-8 leading-tight tracking-tight">Mobility Planning, <br /><span className="text-cyan-400">Simplified.</span></h2>

                            <div className="space-y-10">
                                {[
                                    { icon: BarChart3, title: "Fintech-Grade Cost Planning", desc: "Automated calculations for application fees, IHS, and proof of funds localized into your base currency." },
                                    { icon: FolderKanban, title: "Secure Document Vault", desc: "Military-grade encryption for your most vital credentials. Organize everything for embassy submission." },
                                    { icon: Zap, title: "Live Readiness Score", desc: "A proprietary scoring system that measures your strengths across 5 dimensions: profile, funds, language, docs, and timeline." }
                                ].map((item, i) => (
                                    <motion.div
                                        key={i}
                                        initial={{ opacity: 0, x: -20 }}
                                        whileInView={{ opacity: 1, x: 0 }}
                                        viewport={{ once: true }}
                                        className="flex gap-6"
                                    >
                                        <div className="h-14 w-14 glass rounded-2xl flex-shrink-0 flex items-center justify-center text-cyan-400 group cursor-default">
                                            <item.icon className="h-7 w-7 transition-all group-hover:scale-110" />
                                        </div>
                                        <div>
                                            <h4 className="text-xl font-black text-white mb-2 leading-none">{item.title}</h4>
                                            <p className="text-slate-400 font-medium leading-relaxed">{item.desc}</p>
                                        </div>
                                    </motion.div>
                                ))}
                            </div>
                        </div>

                        <div className="relative">
                            <motion.div
                                initial={{ opacity: 0, scale: 0.9, rotate: -3 }}
                                whileInView={{ opacity: 1, scale: 1, rotate: 0 }}
                                viewport={{ once: true }}
                                className="relative z-10"
                            >
                                <div className="absolute -inset-1 bg-gradient-to-r from-cyan-400 to-blue-500 rounded-[48px] blur-xl opacity-30" />
                                <div className="relative glass p-10 rounded-[48px] border-white/20 shadow-3xl bg-slate-900/60 transition-all hover:bg-slate-900/80">
                                    <div className="flex items-center justify-between mb-10 pb-8 border-b border-white/10">
                                        <div>
                                            <h3 className="text-white font-black text-2xl tracking-tight">Cost Tracking</h3>
                                            <p className="text-slate-400 text-sm font-bold uppercase tracking-widest mt-1">Live Pathway Stats</p>
                                        </div>
                                        <div className="h-16 w-16 bg-cyan-400/10 rounded-3xl flex items-center justify-center">
                                            <Wallet className="h-8 w-8 text-cyan-400" />
                                        </div>
                                    </div>
                                    <div className="space-y-5">
                                        {[
                                            { label: "Required Proof of Funds", value: "£13,340", color: "bg-cyan-400" },
                                            { label: "Government Fees (IHS/App)", value: "£1,450", color: "bg-blue-500" },
                                            { label: "Other Admin & Flights", value: "£2,100", color: "bg-slate-700" }
                                        ].map((item, i) => (
                                            <div key={i} className="flex justify-between items-center p-5 rounded-3xl glass transition-all hover:translate-x-2">
                                                <div className="flex items-center gap-4">
                                                    <div className={`w-3 h-3 rounded-full ${item.color}`} />
                                                    <span className="text-white/80 font-black text-sm">{item.label}</span>
                                                </div>
                                                <span className="text-white font-black text-lg">{item.value}</span>
                                            </div>
                                        ))}
                                    </div>
                                    <div className="mt-12">
                                        <div className="flex justify-between text-xs font-black text-cyan-400 uppercase tracking-[0.2em] mb-4">
                                            <span>Tracking Progress</span>
                                            <span>82% Complete</span>
                                        </div>
                                        <div className="h-4 w-full bg-white/5 rounded-full overflow-hidden border border-white/5 p-1">
                                            <motion.div
                                                initial={{ width: 0 }}
                                                whileInView={{ width: "82%" }}
                                                viewport={{ once: true }}
                                                className="h-full bg-cyan-400 rounded-full shadow-[0_0_15px_rgba(0,194,255,0.5)]"
                                            />
                                        </div>
                                    </div>
                                </div>
                            </motion.div>
                        </div>
                    </div>
                </div>
            </section>

            {/* Network Section */}
            <section className="py-24 bg-white overflow-hidden">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center mb-16">
                        <span className="text-blue-600 font-black tracking-widest uppercase text-xs mb-4 block">Expert Verified</span>
                        <h2 className="text-4xl lg:text-5xl font-black text-slate-900 tracking-tight">The Professional Network</h2>
                        <p className="text-slate-600 mt-6 text-xl max-w-2xl mx-auto font-medium">Connect with licensed experts who specialize in your destination.</p>
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-3 gap-10">
                        {[
                            { icon: ShieldCheck, title: "Immigration Lawyers", color: "text-blue-600", bg: "bg-blue-50" },
                            { icon: FileText, title: "Certified Translators", color: "text-cyan-500", bg: "bg-cyan-50" },
                            { icon: Users, title: "Settlement Advisors", color: "text-slate-700", bg: "bg-slate-50" }
                        ].map((item, i) => (
                            <motion.div
                                key={i}
                                whileHover={{ scale: 1.05 }}
                                className="p-10 rounded-[40px] border-2 border-slate-50 transition-all hover:bg-white hover:shadow-2xl hover:border-blue-100 flex flex-col items-center sm:items-start text-center sm:text-left"
                            >
                                <div className={`h-20 w-20 ${item.bg} rounded-[32px] flex items-center justify-center mb-8`}>
                                    <item.icon className={`h-10 w-10 ${item.color}`} />
                                </div>
                                <h3 className="text-2xl font-black text-slate-900 mb-4">{item.title}</h3>
                                <p className="text-slate-600 font-medium leading-relaxed">Vetted and verified professionals ready to help you navigate the final steps of your move.</p>
                            </motion.div>
                        ))}
                    </div>
                </div>
            </section>

            {/* FAQ Section */}
            <section className="py-24 bg-slate-50">
                <div className="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
                    <h2 className="text-4xl font-black text-slate-900 mb-12 text-center tracking-tight">Frequently Asked Questions</h2>
                    <div className="space-y-4">
                        {faqData.map((faq, i) => (
                            <div key={i} className="bg-white border rounded-2xl overflow-hidden shadow-sm transition-all hover:shadow-md">
                                <button
                                    onClick={() => setOpenFaq(openFaq === i ? null : i)}
                                    className="w-full p-6 flex items-center justify-between text-left group"
                                >
                                    <span className={`text-lg font-black transition-colors ${openFaq === i ? 'text-blue-600' : 'text-slate-700 group-hover:text-blue-600'}`}>
                                        {faq.q}
                                    </span>
                                    <ChevronDown className={`w-5 h-5 text-slate-400 transition-transform duration-300 ${openFaq === i ? 'rotate-180 text-blue-600' : ''}`} />
                                </button>
                                <AnimatePresence>
                                    {openFaq === i && (
                                        <motion.div
                                            initial={{ height: 0, opacity: 0 }}
                                            animate={{ height: "auto", opacity: 1 }}
                                            exit={{ height: 0, opacity: 0 }}
                                            transition={{ duration: 0.3 }}
                                        >
                                            <div className="px-6 pb-6 text-slate-600 font-medium leading-relaxed border-t border-slate-50 pt-4">
                                                {faq.a}
                                            </div>
                                        </motion.div>
                                    )}
                                </AnimatePresence>
                            </div>
                        ))}
                    </div>
                </div>
            </section>

            {/* Final CTA */}
            <section className="py-24 px-4 bg-white">
                <motion.div
                    initial={{ opacity: 0, y: 30 }}
                    whileInView={{ opacity: 1, y: 0 }}
                    viewport={{ once: true }}
                    className="max-w-7xl mx-auto"
                >
                    <div className="relative rounded-[60px] bg-slate-900 p-12 lg:p-24 overflow-hidden border border-white/5">
                        <div className="absolute top-0 right-0 w-[800px] h-[800px] bg-blue-600/10 rounded-full blur-[120px] -translate-y-1/2 translate-x-1/2" />
                        <div className="absolute bottom-0 left-0 w-[600px] h-[600px] bg-cyan-500/10 rounded-full blur-[120px] translate-y-1/2 -translate-x-1/2" />

                        <div className="relative z-10 flex flex-col items-center text-center max-w-4xl mx-auto">
                            <span className="text-cyan-400 font-black tracking-[0.3em] uppercase text-xs mb-8 block">Ready to Begin?</span>
                            <h2 className="text-4xl lg:text-7xl font-black text-white mb-10 leading-[1.1] tracking-tight">
                                Your journey of a <br /> thousand miles <span className="text-glow text-blue-400">starts here.</span>
                            </h2>
                            <p className="text-slate-400 text-xl lg:text-2xl font-medium mb-12 leading-relaxed">
                                Join 5,000+ users who are using GoPathway intelligence to move smarter, faster, and with complete confidence.
                            </p>
                            <div className="flex flex-col sm:flex-row gap-6 w-full justify-center">
                                <Link to="/register" className="w-full sm:w-auto">
                                    <Button size="lg" className="h-20 px-12 rounded-3xl bg-blue-600 hover:bg-blue-700 text-white text-xl font-black transition-all hover:scale-105 shadow-2xl shadow-blue-600/20 active:scale-95 group w-full">
                                        Create Your Free Account
                                        <Zap className="ml-2 h-6 w-6 text-cyan-300 transition-transform group-hover:scale-125" />
                                    </Button>
                                </Link>
                                <Link to="/pricing" className="w-full sm:w-auto">
                                    <Button size="lg" variant="ghost" className="h-20 px-12 rounded-3xl text-white text-xl font-bold hover:bg-white/5 border border-white/10 w-full transition-all">
                                        View Premium Plans
                                    </Button>
                                </Link>
                            </div>
                        </div>
                    </div>
                </motion.div>
            </section>
        </div>
    );
}
