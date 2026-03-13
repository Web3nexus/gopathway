import { useParams, Link } from 'react-router-dom';
import { ArrowLeft, Clock, CheckCircle } from 'lucide-react';
import { Button } from '@/components/ui/button';
import { useCountry } from '@/hooks/useCountries';
import {
    Accordion,
    AccordionContent,
    AccordionItem,
    AccordionTrigger,
} from '@/components/ui/accordion';

export default function CountryDetail() {
    const { id } = useParams<{ id: string }>();
    const { data: country, isLoading } = useCountry(Number(id));

    if (isLoading) {
        return (
            <div className="max-w-5xl mx-auto px-4 py-12 animate-pulse">
                <div className="h-72 bg-gray-200 rounded-2xl mb-8" />
                <div className="h-8 bg-gray-200 rounded w-1/3 mb-4" />
                <div className="h-4 bg-gray-200 rounded w-2/3 mb-2" />
            </div>
        );
    }

    if (!country) return <div className="p-12 text-center text-[#6B7280]">Country not found.</div>;

    return (
        <div className="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <Link to="/countries" className="inline-flex items-center gap-2 text-sm text-[#6B7280] hover:text-[#0B3C91] mb-6 transition-colors">
                <ArrowLeft className="h-4 w-4" /> Back to Countries
            </Link>

            {/* Hero Banner */}
            <div className="relative h-72 rounded-2xl overflow-hidden mb-8 shadow-md">
                <img src={country.image_url} alt={country.name} className="w-full h-full object-cover" />
                <div className="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent" />
                <div className="absolute bottom-0 p-8 text-white">
                    <h1 className="text-4xl font-bold mb-2">{country.name}</h1>
                    <p className="text-blue-100 max-w-lg">{country.description}</p>
                </div>
            </div>

            {/* Visa Types */}
            <div className="mb-8">
                <h2 className="text-2xl font-bold text-[#1A1A1A] mb-2">Available Visa Types</h2>
                <p className="text-[#6B7280] mb-6">Explore all available pathways to {country.name}</p>

                {country.visa_types?.length === 0 ? (
                    <p className="text-[#6B7280]">No visa types listed yet.</p>
                ) : (
                    <Accordion type="single" collapsible className="space-y-3">
                        {Array.isArray(country.visa_types) ? country.visa_types.map((visa: any) => (
                            <AccordionItem
                                key={visa.id}
                                value={String(visa.id)}
                                className="bg-white border border-[#E5E7EB] rounded-xl px-5 shadow-sm"
                            >
                                <AccordionTrigger className="hover:no-underline py-4">
                                    <div className="flex items-center justify-between w-full mr-4">
                                        <span className="font-semibold text-[#1A1A1A] text-left">{visa.name}</span>
                                        <span className="flex items-center gap-1.5 text-xs text-[#6B7280] font-normal">
                                            <Clock className="h-3.5 w-3.5" />
                                            {visa.processing_time}
                                        </span>
                                    </div>
                                </AccordionTrigger>
                                <AccordionContent className="pb-5">
                                    <p className="text-[#6B7280] text-sm mb-4">{visa.description}</p>
                                    {Array.isArray(visa.requirements) && visa.requirements.length > 0 && (
                                        <div>
                                            <h4 className="text-sm font-semibold text-[#1A1A1A] mb-2">Requirements</h4>
                                            <ul className="space-y-2">
                                                {Array.isArray(visa.requirements) ? visa.requirements.map((req: string, i: number) => (
                                                    <li key={i} className="flex items-start gap-2 text-sm text-[#6B7280]">
                                                        <CheckCircle className="h-4 w-4 text-[#00C2FF] mt-0.5 flex-shrink-0" />
                                                        {req}
                                                    </li>
                                                )) : null}
                                            </ul>
                                        </div>
                                    )}
                                </AccordionContent>
                            </AccordionItem>
                        )) : null}
                    </Accordion>
                )}
            </div>

            {/* CTA */}
            <div className="bg-[#0B3C91] text-white rounded-2xl p-8 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div>
                    <h3 className="text-xl font-bold mb-1">Ready to plan your move to {country.name}?</h3>
                    <p className="text-blue-200 text-sm">Create a free account and get your personalised readiness score.</p>
                </div>
                <Link to="/register">
                    <Button className="bg-[#00C2FF] hover:bg-[#00C2FF]/90 text-[#0B3C91] font-semibold whitespace-nowrap">
                        Start Planning
                    </Button>
                </Link>
            </div>
        </div>
    );
}
