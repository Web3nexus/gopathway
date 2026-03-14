import { useState, useMemo } from 'react';
import { useQuery } from '@tanstack/react-query';
import { publicService } from '@/services/api/publicService';
import { Loader2, BookOpen, Search, ChevronRight, MessageCircle } from 'lucide-react';
import { Link } from 'react-router-dom';

interface DocSection {
    id: string;
    title: string;
    content: string;
}

// Parse an HTML string into individual sections split by <h3> tags
function parseSections(html: string): DocSection[] {
    if (!html) return [];
    const parser = new DOMParser();
    const doc = parser.parseFromString(html, 'text/html');
    const sections: DocSection[] = [];
    let currentSection: DocSection | null = null;
    let introContent = '';

    doc.body.childNodes.forEach((node) => {
        const el = node as HTMLElement;
        const tagName = el.tagName?.toLowerCase();

        if (tagName === 'h1' || tagName === 'h2') {
            // Skip the top-level heading — we render it separately
            return;
        }

        if (tagName === 'h3') {
            if (currentSection) sections.push(currentSection);
            const title = el.textContent || '';
            const id = title.toLowerCase().replace(/[^a-z0-9]+/g, '-');
            currentSection = { id, title, content: '' };
        } else if (currentSection) {
            currentSection.content += el.outerHTML || el.textContent || '';
        } else {
            introContent += el.outerHTML || el.textContent || '';
        }
    });

    if (currentSection) sections.push(currentSection);

    // If there's no h3 breakdown at all, treat the whole thing as one section
    if (sections.length === 0 && (introContent || html)) {
        sections.push({ id: 'overview', title: 'Overview', content: introContent || html });
    }

    return sections;
}

export default function Documentation() {
    const [activeSection, setActiveSection] = useState<string | null>(null);
    const [search, setSearch] = useState('');

    const { data: settingsData, isLoading } = useQuery({
        queryKey: ['public-settings'],
        queryFn: publicService.getSettings,
        staleTime: 1000 * 60 * 60,
    });

    const settings = settingsData?.data || {};
    const rawContent = settings.documentation_content || '';

    const sections = useMemo(() => parseSections(rawContent), [rawContent]);

    const filteredSections = useMemo(() => {
        if (!search.trim()) return sections;
        const q = search.toLowerCase();
        return sections.filter(
            (s) => s.title.toLowerCase().includes(q) || s.content.toLowerCase().includes(q)
        );
    }, [sections, search]);

    const currentSection = activeSection
        ? filteredSections.find((s) => s.id === activeSection) || filteredSections[0]
        : filteredSections[0];

    if (isLoading) {
        return (
            <div className="flex justify-center items-center min-h-[60vh]">
                <Loader2 className="h-8 w-8 animate-spin text-[#0B3C91]" />
            </div>
        );
    }

    return (
        <div className="bg-[#F5F7FA] min-h-screen">
            {/* Hero */}
            <div className="bg-white border-b border-[#E5E7EB]">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
                    <div className="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div className="flex items-center gap-4">
                            <div className="h-12 w-12 rounded-2xl bg-blue-50 flex items-center justify-center text-[#0B3C91] shrink-0">
                                <BookOpen className="h-6 w-6" />
                            </div>
                            <div>
                                <h1 className="text-2xl font-black text-[#1A1A1A] tracking-tight">Documentation</h1>
                                <p className="text-sm text-[#6B7280]">Everything you need to know about using GoPathway.</p>
                            </div>
                        </div>
                        {/* Search */}
                        <div className="relative w-full md:max-w-xs">
                            <Search className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-[#9CA3AF]" />
                            <input
                                type="text"
                                value={search}
                                onChange={(e) => { setSearch(e.target.value); setActiveSection(null); }}
                                placeholder="Search documentation..."
                                className="w-full pl-9 pr-4 py-2.5 text-sm bg-[#F5F7FA] border border-[#E5E7EB] rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0B3C91]/20 focus:border-[#0B3C91] text-[#1A1A1A] placeholder-[#9CA3AF]"
                            />
                        </div>
                    </div>
                </div>
            </div>

            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
                <div className="flex gap-8">
                    {/* Sidebar */}
                    <aside className="hidden lg:block w-64 shrink-0">
                        <div className="sticky top-24">
                            <div className="bg-white rounded-2xl border border-[#E5E7EB] shadow-sm overflow-hidden">
                                <div className="px-4 py-3 border-b border-[#F3F4F6]">
                                    <p className="text-xs font-bold uppercase tracking-widest text-[#9CA3AF]">
                                        Contents
                                    </p>
                                </div>
                                <nav className="p-2">
                                    {filteredSections.length === 0 ? (
                                        <p className="text-sm text-center py-4 text-[#9CA3AF]">No results</p>
                                    ) : (
                                        filteredSections.map((section) => {
                                            const isActive = currentSection?.id === section.id;
                                            return (
                                                <button
                                                    key={section.id}
                                                    onClick={() => setActiveSection(section.id)}
                                                    className={`w-full text-left flex items-center justify-between gap-2 px-3 py-2.5 rounded-xl text-sm transition-all ${
                                                        isActive
                                                            ? 'bg-[#0B3C91] text-white font-semibold'
                                                            : 'text-[#4B5563] hover:bg-[#F5F7FA] hover:text-[#1A1A1A]'
                                                    }`}
                                                >
                                                    <span className="truncate">{section.title}</span>
                                                    {isActive && <ChevronRight className="h-3.5 w-3.5 shrink-0" />}
                                                </button>
                                            );
                                        })
                                    )}
                                </nav>
                            </div>

                            {/* Help Card */}
                            <div className="mt-4 p-4 bg-[#0B3C91]/5 rounded-2xl border border-[#0B3C91]/10">
                                <MessageCircle className="h-5 w-5 text-[#0B3C91] mb-2" />
                                <p className="text-sm font-semibold text-[#1A1A1A] mb-1">Need more help?</p>
                                <p className="text-xs text-[#6B7280] mb-3">Our team is ready to assist you.</p>
                                <Link
                                    to="/support"
                                    className="block text-center text-xs font-bold bg-[#0B3C91] text-white px-4 py-2 rounded-xl hover:bg-[#0B3C91]/90 transition-colors"
                                >
                                    Contact Support
                                </Link>
                            </div>
                        </div>
                    </aside>

                    {/* Main content */}
                    <main className="flex-1 min-w-0">
                        {/* Mobile section list */}
                        <div className="lg:hidden mb-6 flex gap-2 overflow-x-auto pb-1 -mx-1 px-1">
                            {filteredSections.map((section) => {
                                const isActive = currentSection?.id === section.id;
                                return (
                                    <button
                                        key={section.id}
                                        onClick={() => setActiveSection(section.id)}
                                        className={`shrink-0 px-3 py-1.5 rounded-full text-xs font-semibold transition-all border ${
                                            isActive
                                                ? 'bg-[#0B3C91] text-white border-transparent'
                                                : 'bg-white text-[#4B5563] border-[#E5E7EB] hover:border-[#0B3C91]/30'
                                        }`}
                                    >
                                        {section.title}
                                    </button>
                                );
                            })}
                        </div>

                        {currentSection ? (
                            <div className="bg-white rounded-3xl border border-[#E5E7EB] shadow-sm overflow-hidden">
                                {/* Article header */}
                                <div className="px-8 md:px-12 pt-10 pb-6 border-b border-[#F3F4F6]">
                                    <div className="flex items-center gap-2 text-xs text-[#9CA3AF] mb-3">
                                        <span>Documentation</span>
                                        <ChevronRight className="h-3 w-3" />
                                        <span className="text-[#4B5563] font-medium">{currentSection.title}</span>
                                    </div>
                                    <h2 className="text-2xl font-black text-[#1A1A1A]">{currentSection.title}</h2>
                                </div>

                                {/* Article body */}
                                <div className="px-8 md:px-12 py-8">
                                    <div
                                        className="prose prose-slate max-w-none
                                        text-[#374151] leading-relaxed
                                        prose-headings:text-[#1A1A1A] prose-headings:font-bold
                                        prose-h2:text-xl prose-h3:text-lg
                                        prose-p:mb-5 prose-p:text-[#374151]
                                        prose-li:mb-2 prose-li:text-[#374151]
                                        prose-strong:text-[#1A1A1A]
                                        prose-a:text-[#0B3C91] prose-a:no-underline hover:prose-a:underline
                                        prose-ul:pl-5 prose-ol:pl-5"
                                        dangerouslySetInnerHTML={{ __html: currentSection.content }}
                                    />
                                </div>

                                {/* Prev / Next navigation */}
                                <div className="px-8 md:px-12 pb-10 pt-4 border-t border-[#F3F4F6]">
                                    <div className="flex justify-between gap-4">
                                        {(() => {
                                            const idx = filteredSections.findIndex(s => s.id === currentSection.id);
                                            const prev = idx > 0 ? filteredSections[idx - 1] : null;
                                            const next = idx < filteredSections.length - 1 ? filteredSections[idx + 1] : null;
                                            return (
                                                <>
                                                    {prev ? (
                                                        <button
                                                            onClick={() => setActiveSection(prev.id)}
                                                            className="flex items-center gap-2 text-sm text-[#6B7280] hover:text-[#0B3C91] transition-colors group"
                                                        >
                                                            <ChevronRight className="h-4 w-4 rotate-180 group-hover:-translate-x-0.5 transition-transform" />
                                                            <span>{prev.title}</span>
                                                        </button>
                                                    ) : <div />}
                                                    {next ? (
                                                        <button
                                                            onClick={() => setActiveSection(next.id)}
                                                            className="flex items-center gap-2 text-sm text-[#6B7280] hover:text-[#0B3C91] transition-colors group ml-auto"
                                                        >
                                                            <span>{next.title}</span>
                                                            <ChevronRight className="h-4 w-4 group-hover:translate-x-0.5 transition-transform" />
                                                        </button>
                                                    ) : null}
                                                </>
                                            );
                                        })()}
                                    </div>
                                </div>
                            </div>
                        ) : (
                            <div className="bg-white rounded-3xl border border-[#E5E7EB] shadow-sm p-12 text-center">
                                <p className="text-[#6B7280]">No documentation available yet.</p>
                                <Link to="/support" className="mt-4 inline-block text-[#0B3C91] font-semibold text-sm hover:underline">
                                    Contact support
                                </Link>
                            </div>
                        )}
                    </main>
                </div>
            </div>
        </div>
    );
}
