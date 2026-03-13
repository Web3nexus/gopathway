import { useState } from 'react';
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import { adminService } from '@/services/api/adminService';
import { ListOrdered, Plus, Pencil, Trash2, Clock, Map, ChevronRight, Loader2, Globe } from 'lucide-react';
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
import { Textarea } from '@/components/ui/textarea';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/components/ui/select";

export default function TimelineManagement() {
    const { toast } = useToast();
    const queryClient = useQueryClient();
    const [isDialogOpen, setIsDialogOpen] = useState(false);
    const [editingStep, setEditingStep] = useState<any>(null);
    const [formData, setFormData] = useState({
        title: '',
        description: '',
        order: 0,
        visa_type_id: ''
    });

    const { data: templates = [], isLoading } = useQuery({
        queryKey: ['admin-timeline-templates'],
        queryFn: adminService.getTimelineTemplates
    });

    const { data: countriesRes } = useQuery({
        queryKey: ['admin-countries-full'],
        queryFn: () => adminService.getCountries(),
    });

    const mutation = useMutation({
        mutationFn: (data: any) => {
            if (editingStep) {
                return adminService.updateTimelineTemplate(editingStep.id, data);
            }
            return adminService.createTimelineTemplate(data);
        },
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['admin-timeline-templates'] });
            toast({ title: editingStep ? 'Roadmap step updated' : 'Roadmap step created' });
            setIsDialogOpen(false);
            setEditingStep(null);
            setFormData({ title: '', description: '', order: 0, visa_type_id: '' });
        },
        onError: (err: any) => {
            toast({
                title: 'Error saving step',
                description: err.response?.data?.message || 'Check your inputs',
                variant: 'destructive'
            });
        }
    });

    const deleteMutation = useMutation({
        mutationFn: adminService.deleteTimelineTemplate,
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['admin-timeline-templates'] });
            toast({ title: 'Step deleted' });
        }
    });

    const countries = countriesRes || [];

    // Group templates by visa type for better organization
    const groupedTemplates = templates.reduce((acc: any, template: any) => {
        const visaName = template.visa_type?.name || 'Unassigned';
        const countryName = template.visa_type?.country?.name || '';
        const key = `${countryName} - ${visaName}`;
        if (!acc[key]) acc[key] = [];
        acc[key].push(template);
        return acc;
    }, {});

    const handleEdit = (step: any) => {
        setEditingStep(step);
        setFormData({
            title: step.title,
            description: step.description || '',
            order: step.order,
            visa_type_id: step.visa_type_id.toString()
        });
        setIsDialogOpen(true);
    };

    return (
        <div className="space-y-6">
            <div className="flex items-center justify-between">
                <div>
                    <h1 className="text-2xl font-bold text-slate-900">Roadmap Templates</h1>
                    <p className="text-slate-500 text-sm">Design sequential steps for relocation pathways</p>
                </div>
                <Button onClick={() => {
                    setEditingStep(null);
                    setFormData({ title: '', description: '', order: 0, visa_type_id: '' });
                    setIsDialogOpen(true);
                }} className="bg-blue-600 hover:bg-blue-700">
                    <Plus className="w-4 h-4 mr-2" />
                    Add Step
                </Button>
            </div>

            {isLoading ? (
                <div className="flex justify-center p-12"><Loader2 className="h-8 w-8 animate-spin text-blue-600" /></div>
            ) : Object.keys(groupedTemplates).length === 0 ? (
                <div className="bg-white rounded-2xl border shadow-sm p-12 text-center text-slate-400">
                    <Map className="w-12 h-12 mx-auto mb-4 opacity-20" />
                    <p>No roadmap steps defined yet.</p>
                </div>
            ) : (
                <div className="space-y-8">
                    {Object.entries(groupedTemplates).map(([pathwayName, steps]: [string, any]) => (
                        <div key={pathwayName} className="bg-white rounded-2xl border shadow-sm overflow-hidden">
                            <div className="px-6 py-4 bg-slate-50 border-b flex items-center gap-2">
                                <Globe className="w-4 h-4 text-blue-600" />
                                <h3 className="font-bold text-slate-900">{pathwayName}</h3>
                                <span className="ml-auto text-xs font-semibold bg-blue-100 text-blue-600 px-2 py-0.5 rounded-full">
                                    {steps.length} {steps.length === 1 ? 'Step' : 'Steps'}
                                </span>
                            </div>
                            <div className="divide-y divide-slate-100">
                                {steps.map((step: any) => (
                                    <div key={step.id} className="flex items-start gap-4 p-5 hover:bg-slate-50 transition-colors">
                                        <div className="h-8 w-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 font-bold text-xs shrink-0 mt-0.5">
                                            {step.order}
                                        </div>
                                        <div className="flex-1 min-w-0">
                                            <p className="font-bold text-slate-900">{step.title}</p>
                                            <p className="text-sm text-slate-500 mt-0.5">{step.description}</p>
                                        </div>
                                        <div className="flex items-center gap-2">
                                            <Button variant="ghost" size="icon" onClick={() => handleEdit(step)}>
                                                <Pencil className="w-4 h-4" />
                                            </Button>
                                            <Button variant="ghost" size="icon" onClick={() => {
                                                if (confirm('Delete this step?')) deleteMutation.mutate(step.id);
                                            }}>
                                                <Trash2 className="w-4 h-4 text-red-500" />
                                            </Button>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        </div>
                    ))}
                </div>
            )}

            <Dialog open={isDialogOpen} onOpenChange={setIsDialogOpen}>
                <DialogContent className="sm:max-w-[500px]">
                    <DialogHeader>
                        <DialogTitle>{editingStep ? 'Edit Roadmap Step' : 'New Roadmap Step'}</DialogTitle>
                    </DialogHeader>
                    <form onSubmit={(e) => { e.preventDefault(); mutation.mutate(formData); }} className="space-y-4 py-4">
                        <div className="space-y-2">
                            <Label>Pathway (Visa Type)</Label>
                            <Select
                                value={formData.visa_type_id}
                                onValueChange={(v) => setFormData({ ...formData, visa_type_id: v })}
                            >
                                <SelectTrigger>
                                    <SelectValue placeholder="Select a visa type" />
                                </SelectTrigger>
                                <SelectContent>
                                    {countries.map((country: any) => (
                                        <div key={country.id}>
                                            <div className="px-2 py-1.5 text-xs font-bold text-slate-400 uppercase tracking-wider bg-slate-50">
                                                {country.name}
                                            </div>
                                            {country.visa_types?.map((visa: any) => (
                                                <SelectItem key={visa.id} value={visa.id.toString()}>
                                                    {visa.name}
                                                </SelectItem>
                                            ))}
                                        </div>
                                    ))}
                                </SelectContent>
                            </Select>
                        </div>

                        <div className="space-y-2">
                            <Label>Step Title</Label>
                            <Input
                                value={formData.title}
                                onChange={e => setFormData({ ...formData, title: e.target.value })}
                                placeholder="e.g. Gather Financial Documents"
                                required
                            />
                        </div>

                        <div className="space-y-2">
                            <Label>Description</Label>
                            <Textarea
                                value={formData.description}
                                onChange={e => setFormData({ ...formData, description: e.target.value })}
                                placeholder="What needs to be done in this step?"
                                rows={3}
                            />
                        </div>

                        <div className="space-y-2 w-1/3">
                            <Label>Display Order</Label>
                            <Input
                                type="number"
                                value={formData.order}
                                onChange={e => setFormData({ ...formData, order: parseInt(e.target.value) })}
                                required
                            />
                        </div>

                        <DialogFooter className="pt-4">
                            <Button type="submit" disabled={mutation.isPending} className="bg-blue-600 hover:bg-blue-700">
                                {mutation.isPending ? 'Saving...' : 'Save Step'}
                            </Button>
                        </DialogFooter>
                    </form>
                </DialogContent>
            </Dialog>
        </div>
    );
}
