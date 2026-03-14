import React, { useState } from 'react';
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import { adminService } from '@/services/api/adminService';
import {
    Plus, Pencil, Trash2, Clock
} from 'lucide-react';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { useToast } from '@/hooks/use-toast';
import {
    Dialog,
    DialogContent,
    DialogHeader,
    DialogTitle,
    DialogFooter,
} from "@/components/ui/dialog";
import { Label } from '@/components/ui/label';
import { Switch } from '@/components/ui/switch';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/components/ui/select";

export default function SettlementManagement() {
    const { toast } = useToast();
    const queryClient = useQueryClient();
    const [selectedCountryId, setSelectedCountryId] = useState<string>('');
    const [isDialogOpen, setIsDialogOpen] = useState(false);
    const [editingStep, setEditingStep] = useState<any>(null);

    const [formData, setFormData] = useState({
        country_id: '',
        phase: 'week1',
        title: '',
        description: '',
        estimated_time: '',
        official_link: '',
        mandatory: true,
        order: 0,
        required_documents: [] as string[]
    });

    const { data: countriesData } = useQuery({
        queryKey: ['admin-countries'],
        queryFn: adminService.getCountries
    });
    const countries = Array.isArray(countriesData) ? countriesData : [];

    const { data: stepsData, isLoading } = useQuery({
        queryKey: ['admin-settlement-steps', selectedCountryId],
        queryFn: () => adminService.getSettlementSteps(selectedCountryId && selectedCountryId !== 'null' ? { country_id: selectedCountryId } : undefined)
    });
    const steps = Array.isArray(stepsData) ? stepsData : [];

    const mutation = useMutation({
        mutationFn: (data: any) => {
            if (editingStep) return adminService.updateSettlementStep(editingStep.id, data);
            return adminService.createSettlementStep(data);
        },
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['admin-settlement-steps'] });
            toast({ title: editingStep ? 'Step updated' : 'Step created' });
            setIsDialogOpen(false);
            setEditingStep(null);
        }
    });

    const deleteMutation = useMutation({
        mutationFn: adminService.deleteSettlementStep,
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['admin-settlement-steps'] });
            toast({ title: 'Step deleted' });
        }
    });

    const handleEdit = (step: any) => {
        setEditingStep(step);
        setFormData({
            country_id: step.country_id.toString(),
            phase: step.phase,
            title: step.title,
            description: step.description,
            estimated_time: step.estimated_time || '',
            official_link: step.official_link || '',
            mandatory: !!step.mandatory,
            order: step.order || 0,
            required_documents: step.required_documents || []
        });
        setIsDialogOpen(true);
    };

    const groupedSteps = steps.reduce((acc: any, step: any) => {
        const phase = step.phase;
        if (!acc[phase]) acc[phase] = [];
        acc[phase].push(step);
        return acc;
    }, {});

    const phaseLabels: Record<string, string> = {
        'week1': 'First Week (Essential)',
        'month1': 'First Month (Settling)',
        'long_term': 'Long Term (Stability)'
    };

    return (
        <div className="space-y-6">
            <div className="flex items-center justify-between">
                <div>
                    <h1 className="text-2xl font-bold text-slate-900">Settlement Checklist</h1>
                    <p className="text-slate-500 text-sm">Manage post-arrival "day-by-day" guides for relocation destinations</p>
                </div>
                <div className="flex items-center gap-3">
                    <Select value={selectedCountryId} onValueChange={setSelectedCountryId}>
                        <SelectTrigger className="w-[200px] h-10 bg-white">
                            <SelectValue placeholder="All Countries" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem value="null">All Countries</SelectItem>
                            {countries.map((c: any) => (
                                <SelectItem key={c.id} value={c.id.toString()}>{c.name}</SelectItem>
                            ))}
                        </SelectContent>
                    </Select>
                    <Button onClick={() => { setEditingStep(null); setFormData({ ...formData, country_id: selectedCountryId }); setIsDialogOpen(true); }} className="bg-blue-600 hover:bg-blue-700 font-bold">
                        <Plus className="w-4 h-4 mr-2" />
                        Add Step
                    </Button>
                </div>
            </div>

            <div className="bg-white border rounded-2xl shadow-sm overflow-hidden">
                {isLoading ? (
                    <div className="text-center py-20 text-slate-400 animate-pulse">Loading checklist items...</div>
                ) : steps.length === 0 ? (
                    <div className="text-center py-20 bg-white text-slate-400 font-medium">
                        {selectedCountryId && selectedCountryId !== 'null'
                            ? "No settlement steps found for this country. Click 'Add Step' to create one."
                            : "No settlement steps found. Click 'Add Step' to get started."}
                    </div>
                ) : (
                    <div className="overflow-x-auto">
                        <table className="w-full text-left border-collapse">
                            <thead>
                                <tr className="bg-slate-50 border-b border-slate-100">
                                    <th className="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Step</th>
                                    <th className="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Country</th>
                                    <th className="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Phase</th>
                                    <th className="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Timing</th>
                                    <th className="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400 text-center">Mandatory</th>
                                    <th className="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-slate-50">
                                {steps.map((step: any) => (
                                    <tr key={step.id} className="hover:bg-slate-50/50 transition-colors group">
                                        <td className="px-6 py-4">
                                            <div className="flex flex-col">
                                                <span className="font-bold text-slate-900 text-sm">{step.title}</span>
                                                <span className="text-xs text-slate-500 line-clamp-1 max-w-[300px]">{step.description}</span>
                                            </div>
                                        </td>
                                        <td className="px-6 py-4">
                                            <span className="px-2 py-0.5 bg-blue-50 text-blue-700 rounded text-[10px] font-bold uppercase border border-blue-100">
                                                {step.country?.name}
                                            </span>
                                        </td>
                                        <td className="px-6 py-4">
                                            <span className="text-xs font-medium text-slate-600 capitalize">
                                                {step.phase.replace('_', ' ')}
                                            </span>
                                        </td>
                                        <td className="px-6 py-4">
                                            <div className="flex items-center gap-1.5 text-xs text-slate-500 font-medium">
                                                <Clock className="w-3 h-3" />
                                                {step.estimated_time || '-'}
                                            </div>
                                        </td>
                                        <td className="px-6 py-4 text-center">
                                            {step.mandatory ? (
                                                <span className="inline-flex w-2 h-2 rounded-full bg-red-500 shadow-[0_0_8px_rgba(239,68,68,0.4)]" />
                                            ) : (
                                                <span className="inline-flex w-2 h-2 rounded-full bg-slate-200" />
                                            )}
                                        </td>
                                        <td className="px-6 py-4 text-right">
                                            <div className="flex justify-end gap-1">
                                                <Button variant="ghost" size="icon" className="h-8 w-8 text-slate-400 hover:text-blue-600 hover:bg-blue-50" onClick={() => handleEdit(step)}>
                                                    <Pencil className="w-3.5 h-3.5" />
                                                </Button>
                                                <Button variant="ghost" size="icon" className="h-8 w-8 text-slate-400 hover:text-red-600 hover:bg-red-50" onClick={() => deleteMutation.mutate(step.id)}>
                                                    <Trash2 className="w-3.5 h-3.5" />
                                                </Button>
                                            </div>
                                        </td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </div>
                )}
            </div>

            {/* Step Dialog */}
            <Dialog open={isDialogOpen} onOpenChange={setIsDialogOpen}>
                <DialogContent className="sm:max-w-[600px]">
                    <DialogHeader>
                        <DialogTitle>{editingStep ? 'Edit Settlement Step' : 'New Settlement Step'}</DialogTitle>
                    </DialogHeader>
                    <div className="space-y-4 py-4 max-h-[70vh] overflow-y-auto px-1">
                        <div className="grid grid-cols-2 gap-4">
                            <div className="space-y-2">
                                <Label>Destination</Label>
                                <select
                                    className="w-full h-10 px-3 rounded-md border border-input bg-white text-sm"
                                    value={formData.country_id}
                                    onChange={(e) => setFormData({ ...formData, country_id: e.target.value })}
                                >
                                    <option value="">Select Country</option>
                                    {countries.map((c: any) => (
                                        <option key={c.id} value={c.id.toString()}>{c.name}</option>
                                    ))}
                                </select>
                            </div>
                            <div className="space-y-2">
                                <Label>Phase</Label>
                                <Select value={formData.phase} onValueChange={v => setFormData({ ...formData, phase: v })}>
                                    <SelectTrigger className="bg-white">
                                        <SelectValue />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="week1">First Week</SelectItem>
                                        <SelectItem value="month1">First Month</SelectItem>
                                        <SelectItem value="long_term">Long Term</SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>
                        </div>

                        <div className="space-y-2">
                            <Label>Step Title</Label>
                            <Input value={formData.title} onChange={e => setFormData({ ...formData, title: e.target.value })} placeholder="e.g. Address Registration (Anmeldung)" />
                        </div>

                        <div className="space-y-2">
                            <Label>Description</Label>
                            <textarea
                                className="w-full min-h-[100px] p-3 rounded-md border border-input bg-white text-sm"
                                value={formData.description}
                                onChange={e => setFormData({ ...formData, description: e.target.value })}
                                placeholder="Details on what to do, where to go, etc."
                            />
                        </div>

                        <div className="grid grid-cols-2 gap-4">
                            <div className="space-y-2">
                                <Label>Estimated Time</Label>
                                <Input value={formData.estimated_time} onChange={e => setFormData({ ...formData, estimated_time: e.target.value })} placeholder="e.g. 14 days" />
                            </div>
                            <div className="space-y-2">
                                <Label>Official Link (URL)</Label>
                                <Input value={formData.official_link} onChange={e => setFormData({ ...formData, official_link: e.target.value })} placeholder="https://..." />
                            </div>
                        </div>

                        <div className="space-y-2">
                            <Label>Required Documents (One per line)</Label>
                            <textarea
                                className="w-full min-h-[80px] p-3 rounded-md border border-input bg-white text-sm"
                                placeholder="Passport&#10;Rental Contract&#10;Landlord Confirmation"
                                value={formData.required_documents.join('\n')}
                                onChange={e => setFormData({ ...formData, required_documents: e.target.value.split('\n').filter(r => r.trim() !== '') })}
                            />
                        </div>

                        <div className="flex items-center justify-between p-4 bg-slate-50 rounded-xl border">
                            <div className="space-y-0.5">
                                <Label className="text-sm font-bold">Mandatory Step</Label>
                                <p className="text-[10px] text-slate-500 uppercase font-bold">Marks this task as critical for settlement</p>
                            </div>
                            <Switch checked={formData.mandatory} onCheckedChange={v => setFormData({ ...formData, mandatory: v })} />
                        </div>
                    </div>
                    <DialogFooter>
                        <Button variant="ghost" onClick={() => setIsDialogOpen(false)}>Cancel</Button>
                        <Button onClick={() => mutation.mutate(formData)} className="bg-blue-600 hover:bg-blue-700 font-bold">Save Step</Button>
                    </DialogFooter>
                </DialogContent>
            </Dialog>
        </div>
    );
}
