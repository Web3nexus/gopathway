import React, { useState } from 'react';
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import { adminService } from '@/services/api/adminService';
import { FileText, Plus, Pencil, Trash2, Clock, Filter, TrendingUp } from 'lucide-react';
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

export default function VisaManagement() {
    const { toast } = useToast();
    const queryClient = useQueryClient();
    const [selectedCountryId, setSelectedCountryId] = useState<string>('');
    const [isDialogOpen, setIsDialogOpen] = useState(false);
    const [editingVisa, setEditingVisa] = useState<any>(null);
    const [formData, setFormData] = useState({
        name: '',
        description: '',
        processing_time: '',
        is_active: true,
        requirements: [] as string[],
        min_education_level: '',
        min_work_experience_years: '0',
        min_ielts_score: '',
        min_funds_required: ''
    });

    const { data: countriesRaw } = useQuery({
        queryKey: ['admin-countries-slim'],
        queryFn: adminService.getCountries
    });
    const countries = Array.isArray(countriesRaw) ? countriesRaw : [];

    const { data: visasRaw, isLoading } = useQuery({
        queryKey: ['admin-visas', selectedCountryId],
        queryFn: () => adminService.getVisaTypes(selectedCountryId),
        enabled: !!selectedCountryId
    });
    const visas = Array.isArray(visasRaw) ? visasRaw : [];

    const mutation = useMutation({
        mutationFn: (data: any) => {
            if (editingVisa) {
                return adminService.updateVisaType(editingVisa.id, data);
            }
            return adminService.createVisaType(selectedCountryId, data);
        },
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['admin-visas', selectedCountryId] });
            toast({ title: editingVisa ? 'Visa updated' : 'Visa created' });
            setIsDialogOpen(false);
            setEditingVisa(null);
            setFormData({
                name: '', description: '', processing_time: '', is_active: true, requirements: [],
                min_education_level: '', min_work_experience_years: '0', min_ielts_score: '', min_funds_required: ''
            });
        }
    });

    const deleteMutation = useMutation({
        mutationFn: adminService.deleteVisaType,
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['admin-visas', selectedCountryId] });
            toast({ title: 'Visa deleted' });
        }
    });

    const handleEdit = (visa: any) => {
        setEditingVisa(visa);
        setFormData({
            name: visa.name,
            description: visa.description,
            processing_time: visa.processing_time || '',
            is_active: visa.is_active,
            requirements: Array.isArray(visa.requirements) ? visa.requirements : [],
            min_education_level: visa.min_education_level || '',
            min_work_experience_years: visa.min_work_experience_years?.toString() || '0',
            min_ielts_score: visa.min_ielts_score?.toString() || '',
            min_funds_required: visa.min_funds_required?.toString() || ''
        });
        setIsDialogOpen(true);
    };

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        if (!selectedCountryId) {
            toast({ variant: 'destructive', title: 'Error', description: 'Please select a country first' });
            return;
        }
        mutation.mutate(formData);
    };

    return (
        <div className="space-y-6">
            <div className="flex items-center justify-between">
                <div>
                    <h1 className="text-2xl font-bold text-slate-900">Visa & Requirements</h1>
                    <p className="text-slate-500 text-sm">Configure visa types and checklists for each destination</p>
                </div>
                {selectedCountryId && (
                    <Button onClick={() => { setEditingVisa(null); setIsDialogOpen(true); }} className="bg-blue-600 hover:bg-blue-700">
                        <Plus className="w-4 h-4 mr-2" />
                        Add Visa Type
                    </Button>
                )}
            </div>

            <div className="bg-white rounded-2xl border shadow-sm">
                <div className="p-6 border-b bg-slate-50/50">
                    <div className="max-w-xs space-y-2">
                        <Label>Select Destination</Label>
                        <Select value={selectedCountryId} onValueChange={setSelectedCountryId}>
                            <SelectTrigger className="bg-white">
                                <SelectValue placeholder="Choose a country..." />
                            </SelectTrigger>
                            <SelectContent>
                                {countries.map((c: any) => (
                                    <SelectItem key={c.id} value={c.id.toString()}>
                                        <div className="flex items-center gap-2">
                                            <span className="text-[10px] font-bold bg-slate-100 px-1 rounded">{c.code}</span>
                                            {c.name}
                                        </div>
                                    </SelectItem>
                                ))}
                            </SelectContent>
                        </Select>
                    </div>
                </div>

                {!selectedCountryId ? (
                    <div className="p-20 text-center space-y-4">
                        <div className="w-16 h-16 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center mx-auto">
                            <Filter className="w-8 h-8" />
                        </div>
                        <div>
                            <p className="font-bold text-slate-900">No Country Selected</p>
                            <p className="text-sm text-slate-500">Pick a destination above to manage its visa types</p>
                        </div>
                    </div>
                ) : visas.length === 0 && !isLoading ? (
                    <div className="p-20 text-center space-y-4">
                        <div className="w-16 h-16 bg-slate-50 text-slate-400 rounded-full flex items-center justify-center mx-auto">
                            <FileText className="w-8 h-8" />
                        </div>
                        <div>
                            <p className="font-bold text-slate-900">No Visa Types Found</p>
                            <p className="text-sm text-slate-500">Get started by creating the first visa for this destination</p>
                        </div>
                    </div>
                ) : (
                    <div className="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 p-6">
                        {visas.map((visa: any) => (
                            <div key={visa.id} className="group relative bg-white rounded-2xl border border-slate-200 p-6 hover:border-blue-300 hover:shadow-md transition-all">
                                <div className="flex justify-between items-start mb-4">
                                    <div className={`px-2 py-1 rounded text-[10px] font-bold uppercase tracking-wider ${visa.is_active ? 'bg-green-50 text-green-600' : 'bg-slate-100 text-slate-500'}`}>
                                        {visa.is_active ? 'Published' : 'Draft'}
                                    </div>
                                    <div className="flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <Button variant="ghost" size="icon" className="h-8 w-8" onClick={() => handleEdit(visa)}>
                                            <Pencil className="w-3.5 h-3.5" />
                                        </Button>
                                        <Button variant="ghost" size="icon" className="h-8 w-8 text-red-500 hover:text-red-600 hover:bg-red-50" onClick={() => deleteMutation.mutate(visa.id)}>
                                            <Trash2 className="w-3.5 h-3.5" />
                                        </Button>
                                    </div>
                                </div>

                                <h3 className="text-lg font-bold text-slate-900 leading-tight mb-2">{visa.name}</h3>
                                <p className="text-sm text-slate-500 line-clamp-2 mb-4">{visa.description}</p>

                                <div className="flex items-center gap-4 pt-4 border-t border-slate-50 text-xs text-slate-600 font-medium">
                                    <div className="flex items-center gap-1.5">
                                        <Clock className="w-3.5 h-3.5 text-blue-500" />
                                        {visa.processing_time || 'N/A'}
                                    </div>
                                    <div className="flex items-center gap-1.5">
                                        <FileText className="w-3.5 h-3.5 text-amber-500" />
                                        {Array.isArray(visa.requirements) ? visa.requirements.length : 0} Requirements
                                    </div>
                                </div>
                            </div>
                        ))}
                    </div>
                )}
            </div>

            {/* Edit/Create Dialog */}
            <Dialog open={isDialogOpen} onOpenChange={setIsDialogOpen}>
                <DialogContent className="sm:max-w-[600px]">
                    <DialogHeader>
                        <DialogTitle>{editingVisa ? 'Edit Visa Type' : 'Add New Visa Type'}</DialogTitle>
                    </DialogHeader>
                    <form onSubmit={handleSubmit} className="space-y-4 py-4">
                        <div className="space-y-2">
                            <Label htmlFor="vname">Visa Name</Label>
                            <Input id="vname" value={formData.name} onChange={e => setFormData({ ...formData, name: e.target.value })} placeholder="e.g. Express Entry - Federal Skilled Worker" required />
                        </div>
                        <div className="grid grid-cols-2 gap-4">
                            <div className="space-y-2">
                                <Label htmlFor="vtime">Processing Time</Label>
                                <Input id="vtime" value={formData.processing_time} onChange={e => setFormData({ ...formData, processing_time: e.target.value })} placeholder="e.g. 6 months" />
                            </div>
                            <div className="space-y-2 flex flex-col justify-end">
                                <div className="flex items-center justify-between p-2.5 bg-slate-50 rounded-lg border border-slate-200">
                                    <Label className="text-xs font-bold">Published</Label>
                                    <Switch checked={formData.is_active} onCheckedChange={val => setFormData({ ...formData, is_active: val })} />
                                </div>
                            </div>
                        </div>
                        <div className="space-y-2">
                            <Label htmlFor="vdesc">Description</Label>
                            <Input id="vdesc" value={formData.description} onChange={e => setFormData({ ...formData, description: e.target.value })} placeholder="Brief overview of the visa class..." />
                        </div>

                        <div className="space-y-2">
                            <Label>Key Eligibility Requirements (One per line)</Label>
                            <textarea
                                className="w-full min-h-[100px] p-3 rounded-lg border border-slate-200 text-sm focus:ring-1 focus:ring-blue-500 outline-none"
                                placeholder="Min 67 points CRS score&#10;1 year continuous work experience&#10;IELTS Level 7"
                                value={formData.requirements.join('\n')}
                                onChange={e => setFormData({ ...formData, requirements: e.target.value.split('\n').filter(r => r.trim() !== '') })}
                            />
                        </div>

                        <div className="bg-blue-50/50 p-4 rounded-xl border border-blue-100 space-y-4">
                            <h4 className="text-sm font-bold text-blue-900 flex items-center gap-2">
                                <TrendingUp className="w-4 h-4" /> Recommendation Rules (MVP)
                            </h4>
                            <div className="grid grid-cols-2 gap-4">
                                <div className="space-y-2">
                                    <Label>Min Education</Label>
                                    <Select value={formData.min_education_level} onValueChange={v => setFormData({ ...formData, min_education_level: v })}>
                                        <SelectTrigger className="bg-white"><SelectValue placeholder="Level" /></SelectTrigger>
                                        <SelectContent>
                                            <SelectItem value="high_school">High School</SelectItem>
                                            <SelectItem value="bachelors">Bachelor's</SelectItem>
                                            <SelectItem value="masters">Master's</SelectItem>
                                            <SelectItem value="phd">Ph.D.</SelectItem>
                                        </SelectContent>
                                    </Select>
                                </div>
                                <div className="space-y-2">
                                    <Label>Min Experience (Yrs)</Label>
                                    <Input type="number" value={formData.min_work_experience_years} onChange={e => setFormData({ ...formData, min_work_experience_years: e.target.value })} className="bg-white" />
                                </div>
                                <div className="space-y-2">
                                    <Label>Min IELTS Score</Label>
                                    <Input type="number" step="0.5" value={formData.min_ielts_score} onChange={e => setFormData({ ...formData, min_ielts_score: e.target.value })} placeholder="e.g. 7.0" className="bg-white" />
                                </div>
                                <div className="space-y-2">
                                    <Label>Min Funds (£)</Label>
                                    <Input type="number" value={formData.min_funds_required} onChange={e => setFormData({ ...formData, min_funds_required: e.target.value })} placeholder="e.g. 12000" className="bg-white" />
                                </div>
                            </div>
                        </div>

                        <DialogFooter className="pt-4">
                            <Button type="button" variant="ghost" onClick={() => setIsDialogOpen(false)}>Cancel</Button>
                            <Button type="submit" disabled={mutation.isPending} className="bg-blue-600 hover:bg-blue-700">
                                {mutation.isPending ? 'Saving...' : 'Save Visa Type'}
                            </Button>
                        </DialogFooter>
                    </form>
                </DialogContent>
            </Dialog>
        </div>
    );
}
