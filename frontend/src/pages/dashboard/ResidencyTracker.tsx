import { useState } from 'react';
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import api from '@/lib/api';
import { useDashboard } from '@/hooks/useDashboard';
import { Map, Clock, FileCheck, Award, Flag, MapPin, Calendar, Languages, DollarSign, CheckCircle2, Loader2, AlertCircle } from 'lucide-react';
import { Button } from '@/components/ui/button';
import { Link } from 'react-router-dom';
import { useToast } from '@/hooks/use-toast';

export default function ResidencyTracker() {
    const { data: dashboard } = useDashboard();
    const { toast } = useToast();
    const queryClient = useQueryClient();

    const countryId = dashboard?.pathway?.country?.id;
    const countryName = dashboard?.pathway?.country?.name;

    const [arrivalDate, setArrivalDate] = useState('');

    const { data: rules, isLoading: loadingRules } = useQuery({
        queryKey: ['residency-rules', countryId],
        queryFn: () => api.get(`/api/v1/residency-rules/${countryId}`).then(res => res.data.data),
        enabled: !!countryId,
    });

    const { data: tracking } = useQuery({
        queryKey: ['residency-tracking', countryId],
        queryFn: () => api.get(`/api/v1/residency-tracking?country_id=${countryId}`).then(res => res.data.data),
        enabled: !!countryId,
    });

    const saveMutation = useMutation({
        mutationFn: (data: any) => api.post('/api/v1/residency-tracking', data),
        onSuccess: () => {
            toast({ title: 'Tracking Updated', description: 'Your residency progression has been saved.' });
            queryClient.invalidateQueries({ queryKey: ['residency-tracking'] });
        },
    });

    const handleSaveArrival = () => {
        saveMutation.mutate({
            country_id: countryId,
            arrival_date: arrivalDate,
        });
    };

    if (!countryId) {
        return (
            <div className="text-center py-20 max-w-lg mx-auto bg-white rounded-3xl border shadow-sm">
                <div className="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <Map className="h-8 w-8 text-blue-500" />
                </div>
                <h2 className="text-xl font-bold mb-2">No Active Pathway</h2>
                <p className="text-slate-500 mb-6 text-sm px-6">Select a destination pathway to track your residency progression.</p>
                <Link to="/recommendations"><Button>Explore Destinations</Button></Link>
            </div>
        );
    }

    if (loadingRules) {
        return <div className="flex justify-center py-20"><Loader2 className="w-8 h-8 animate-spin text-blue-600" /></div>;
    }

    const hasRules = rules?.temporary_reqs || rules?.permanent_reqs || rules?.citizenship_reqs;

    return (
        <div className="max-w-5xl mx-auto space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500 pb-12">

            {/* Header */}
            <div className="flex items-start justify-between bg-white rounded-3xl p-8 border shadow-sm relative overflow-hidden">
                <div className="relative z-10 w-full lg:w-2/3">
                    <h1 className="text-3xl font-black text-slate-800 flex items-center gap-3 mb-2">
                        <Map className="h-8 w-8 text-emerald-600" />
                        Residency Roadmap: {countryName}
                    </h1>
                    <p className="text-slate-500 text-lg mb-6">
                        Track your progress from temporary resident to permanent resident, and eventually to citizen.
                    </p>

                    <div className="bg-slate-50 p-4 rounded-2xl border flex items-center gap-4">
                        <div className="w-12 h-12 rounded-full bg-emerald-100 flex items-center justify-center shrink-0">
                            <MapPin className="w-6 h-6 text-emerald-600" />
                        </div>
                        <div className="flex-1">
                            <label className="text-xs font-bold text-slate-500 uppercase">My Arrival Date</label>
                            {tracking?.arrival_date ? (
                                <p className="font-bold text-slate-800 mt-1">{new Date(tracking.arrival_date).toLocaleDateString()}</p>
                            ) : (
                                <div className="flex items-center gap-2 mt-1">
                                    <input
                                        type="date"
                                        value={arrivalDate}
                                        onChange={e => setArrivalDate(e.target.value)}
                                        className="bg-white border rounded-lg px-3 py-1.5 text-sm"
                                    />
                                    <Button size="sm" onClick={handleSaveArrival} disabled={!arrivalDate || saveMutation.isPending}>Save</Button>
                                </div>
                            )}
                        </div>
                    </div>
                </div>
                <Flag className="w-64 h-64 absolute -right-12 -top-12 text-slate-50/80 rotate-12" />
            </div>

            {!hasRules ? (
                <div className="bg-amber-50 border border-amber-200 rounded-2xl p-10 text-center">
                    <AlertCircle className="w-8 h-8 text-amber-500 mx-auto mb-3" />
                    <h3 className="font-bold text-amber-800 text-lg mb-1">Rules Not Available</h3>
                    <p className="text-amber-700">Detailed residency rules for {countryName} are currently being updated.</p>
                </div>
            ) : (
                <div className="grid grid-cols-1 md:grid-cols-3 gap-6 relative">
                    {/* Connecting Line */}
                    <div className="hidden md:block absolute top-[60px] left-[15%] right-[15%] h-1 bg-slate-200 -z-10" />

                    {/* Stage 1: Temporary */}
                    <div className="bg-white rounded-3xl border shadow-sm p-6 relative">
                        <div className="w-14 h-14 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-4 border-4 border-white shadow-md">
                            <Clock className="w-6 h-6" />
                        </div>
                        <h3 className="font-black text-center text-lg text-slate-800 mb-6">Temporary Residency</h3>

                        <div className="space-y-4">
                            {rules?.temporary_reqs ? (
                                <>
                                    <RuleItem icon={<FileCheck />} label="Valid Permit Type" value={rules.temporary_reqs.permits || 'Any valid Work/Study permit'} />
                                    <RuleItem icon={<Calendar />} label="Duration" value={rules.temporary_reqs.validity || '1-3 years initially'} />
                                </>
                            ) : (
                                <p className="text-sm text-slate-400 text-center">Standard work/study permit rules apply.</p>
                            )}
                        </div>
                    </div>

                    {/* Stage 2: Permanent */}
                    <div className="bg-white rounded-3xl border shadow-sm p-6 relative">
                        <div className="w-14 h-14 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center mx-auto mb-4 border-4 border-white shadow-md">
                            <Award className="w-6 h-6" />
                        </div>
                        <h3 className="font-black text-center text-lg text-slate-800 mb-6">Permanent Residency</h3>

                        <div className="space-y-4">
                            {rules?.permanent_reqs ? (
                                <>
                                    <RuleItem icon={<Calendar />} label="Time Required" value={rules.permanent_reqs.years ? `${rules.permanent_reqs.years} years continuous living` : '5 years standard'} />
                                    <RuleItem icon={<Languages />} label="Language" value={rules.permanent_reqs.language || 'B1 level required'} />
                                    <RuleItem icon={<DollarSign />} label="Income" value={rules.permanent_reqs.income || 'Stable employment required'} />
                                </>
                            ) : (
                                <p className="text-sm text-slate-400 text-center">Maintain legal status for minimum required years.</p>
                            )}

                            {tracking?.arrival_date && rules?.permanent_reqs?.years && (
                                <div className="mt-6 pt-6 border-t border-dashed">
                                    <label className="text-xs font-bold text-slate-500 uppercase block mb-2">Your Timeline</label>
                                    <div className="bg-indigo-50 text-indigo-700 text-sm font-bold p-3 rounded-xl text-center">
                                        Eligible: {new Date(new Date(tracking.arrival_date).setFullYear(new Date(tracking.arrival_date).getFullYear() + parseInt(rules.permanent_reqs.years))).getFullYear()}
                                    </div>
                                </div>
                            )}
                        </div>
                    </div>

                    {/* Stage 3: Citizenship */}
                    <div className="bg-white rounded-3xl border-2 border-emerald-400 shadow-sm p-6 relative">
                        <div className="absolute -top-3 -right-3 bg-emerald-500 text-white text-[10px] font-black uppercase px-3 py-1 rounded-full shadow-md">
                            Goal
                        </div>
                        <div className="w-14 h-14 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-4 border-4 border-white shadow-md">
                            <Flag className="w-6 h-6" />
                        </div>
                        <h3 className="font-black text-center text-lg text-emerald-800 mb-6">Citizenship</h3>

                        <div className="space-y-4">
                            {rules?.citizenship_reqs ? (
                                <>
                                    <RuleItem icon={<Calendar />} label="Time Required" value={rules.citizenship_reqs.years ? `${rules.citizenship_reqs.years} years total residency` : '5-8 years'} />
                                    <RuleItem icon={<Languages />} label="Language" value={rules.citizenship_reqs.language || 'B2 level required'} />
                                    <RuleItem icon={<CheckCircle2 />} label="Integration" value={rules.citizenship_reqs.tests || 'Civic knowledge test required'} />
                                </>
                            ) : (
                                <p className="text-sm text-slate-400 text-center">Meet all permanent residency requirements plus integration tests.</p>
                            )}
                        </div>
                    </div>
                </div>
            )}
        </div>
    );
}

function RuleItem({ icon, label, value }: { icon: React.ReactNode; label: string; value: string }) {
    return (
        <div className="flex gap-3 items-start">
            <div className="text-slate-400 mt-0.5">
                {React.cloneElement(icon as React.ReactElement<any>, { className: "w-5 h-5" })}
            </div>
            <div>
                <p className="text-[10px] font-bold text-slate-400 uppercase leading-none mb-1">{label}</p>
                <p className="text-sm font-semibold text-slate-700 leading-tight">{value}</p>
            </div>
        </div>
    );
}

// Ensure React is imported above or the above cloneElement won't work:
import React from 'react';
