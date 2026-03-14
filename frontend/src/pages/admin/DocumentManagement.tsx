import { useState } from 'react';
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import { adminService } from '@/services/api/adminService';
import { FileText, Plus, Pencil, Trash2, ShieldCheck, Loader2, Globe, AlertCircle } from 'lucide-react';
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
import { Switch } from '@/components/ui/switch';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/components/ui/select";

export default function DocumentManagement() {
    const { toast } = useToast();
    const queryClient = useQueryClient();
    const [isDialogOpen, setIsDialogOpen] = useState(false);
    const [editingType, setEditingType] = useState<any>(null);
    const [formData, setFormData] = useState({
        name: '',
        description: '',
        is_required: true,
        visa_type_id: ''
    });

    const { data: docTypesRaw, isLoading } = useQuery({
        queryKey: ['admin-document-types'],
        queryFn: adminService.getDocumentTypes
    });
    const documentTypes = Array.isArray(docTypesRaw) ? docTypesRaw : [];

    const { data: countriesRes } = useQuery({
        queryKey: ['admin-countries-full'],
        queryFn: () => adminService.getCountries(),
    });

    const mutation = useMutation({
        mutationFn: (data: any) => {
            const payload = {
                ...data,
                visa_type_id: data.visa_type_id === 'global' ? null : parseInt(data.visa_type_id)
            };
            if (editingType) {
                return adminService.updateDocumentType(editingType.id, payload);
            }
            return adminService.createDocumentType(payload);
        },
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['admin-document-types'] });
            toast({ title: editingType ? 'Document type updated' : 'Document type created' });
            setIsDialogOpen(false);
            setEditingType(null);
            setFormData({ name: '', description: '', is_required: true, visa_type_id: '' });
        },
        onError: (err: any) => {
            toast({
                title: 'Error saving document type',
                description: err.response?.data?.message || 'Check your inputs',
                variant: 'destructive'
            });
        }
    });

    const deleteMutation = useMutation({
        mutationFn: adminService.deleteDocumentType,
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['admin-document-types'] });
            toast({ title: 'Document type deleted' });
        }
    });

    const countries = Array.isArray(countriesRes) ? countriesRes : [];

    const handleEdit = (type: any) => {
        setEditingType(type);
        setFormData({
            name: type.name,
            description: type.description || '',
            is_required: !!type.is_required,
            visa_type_id: type.visa_type_id ? type.visa_type_id.toString() : 'global'
        });
        setIsDialogOpen(true);
    };

    return (
        <div className="space-y-6">
            <div className="flex items-center justify-between">
                <div>
                    <h1 className="text-2xl font-bold text-slate-900">Document Requirements</h1>
                    <p className="text-slate-500 text-sm">Define required files and evidence for visa applications</p>
                </div>
                <Button onClick={() => {
                    setEditingType(null);
                    setFormData({ name: '', description: '', is_required: true, visa_type_id: 'global' });
                    setIsDialogOpen(true);
                }} className="bg-blue-600 hover:bg-blue-700">
                    <Plus className="w-4 h-4 mr-2" />
                    New Requirement
                </Button>
            </div>

            <div className="bg-white rounded-2xl border shadow-sm overflow-hidden">
                <div className="overflow-x-auto">
                    <table className="w-full text-left border-collapse">
                        <thead>
                            <tr className="bg-slate-50/50 border-b text-xs uppercase tracking-wider text-slate-500 font-semibold">
                                <th className="px-6 py-4">Document Name</th>
                                <th className="px-6 py-4">Pathway Scope</th>
                                <th className="px-6 py-4">Requirment</th>
                                <th className="px-6 py-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-slate-100">
                            {isLoading ? (
                                <tr><td colSpan={4} className="p-8 text-center text-slate-400">Loading document types...</td></tr>
                            ) : documentTypes.length === 0 ? (
                                <tr><td colSpan={4} className="p-20 text-center text-slate-400">No document requirements defined.</td></tr>
                            ) : documentTypes.map((type: any) => (
                                <tr key={type.id} className="hover:bg-slate-50 transition-colors">
                                    <td className="px-6 py-4">
                                        <div className="flex items-center gap-3">
                                            <div className="h-9 w-9 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center">
                                                <FileText className="w-4 h-4" />
                                            </div>
                                            <div>
                                                <div className="font-bold text-slate-900">{type.name}</div>
                                                <div className="text-xs text-slate-500">{type.description}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td className="px-6 py-4">
                                        {type.visa_type ? (
                                            <div className="flex items-center gap-1.5 text-xs font-medium text-slate-600">
                                                <span className="px-1.5 py-0.5 bg-slate-100 rounded font-bold uppercase text-[9px]">{type.visa_type.country?.code}</span>
                                                {type.visa_type.name}
                                            </div>
                                        ) : (
                                            <span className="text-xs font-bold text-blue-600 uppercase tracking-tight">Standard / Global</span>
                                        )}
                                    </td>
                                    <td className="px-6 py-4">
                                        {type.is_required ? (
                                            <span className="inline-flex items-center gap-1 text-[10px] font-extrabold uppercase text-amber-600 px-2 py-0.5 bg-amber-50 rounded">
                                                <AlertCircle className="w-3 h-3" /> Mandatory
                                            </span>
                                        ) : (
                                            <span className="text-[10px] font-bold uppercase text-slate-400 px-2 py-0.5 bg-slate-50 rounded">Optional</span>
                                        )}
                                    </td>
                                    <td className="px-6 py-4 text-right">
                                        <div className="flex items-center justify-end gap-2">
                                            <Button variant="ghost" size="icon" onClick={() => handleEdit(type)}>
                                                <Pencil className="w-4 h-4" />
                                            </Button>
                                            <Button variant="ghost" size="icon" onClick={() => {
                                                if (confirm('Delete this template?')) deleteMutation.mutate(type.id);
                                            }}>
                                                <Trash2 className="w-4 h-4 text-red-500" />
                                            </Button>
                                        </div>
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>
            </div>

            <Dialog open={isDialogOpen} onOpenChange={setIsDialogOpen}>
                <DialogContent className="sm:max-w-[500px]">
                    <DialogHeader>
                        <DialogTitle>{editingType ? 'Edit Document Requirement' : 'New Document Requirement'}</DialogTitle>
                    </DialogHeader>
                    <form onSubmit={(e) => { e.preventDefault(); mutation.mutate(formData); }} className="space-y-4 py-4">
                        <div className="space-y-2">
                            <Label>Applies To</Label>
                            <Select
                                value={formData.visa_type_id}
                                onValueChange={(v) => setFormData({ ...formData, visa_type_id: v })}
                            >
                                <SelectTrigger>
                                    <SelectValue placeholder="Select target pathway" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="global">All Pathways (Standard Document)</SelectItem>
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
                            <Label>Document Name</Label>
                            <Input
                                value={formData.name}
                                onChange={e => setFormData({ ...formData, name: e.target.value })}
                                placeholder="e.g. Proof of Financial Means"
                                required
                            />
                        </div>

                        <div className="space-y-2">
                            <Label>Description / Instructions</Label>
                            <Textarea
                                value={formData.description}
                                onChange={e => setFormData({ ...formData, description: e.target.value })}
                                placeholder="Explain what document is needed and any specifics (e.g. PDF only, last 6 months)"
                                rows={3}
                            />
                        </div>

                        <div className="flex items-center justify-between p-4 bg-slate-50 rounded-xl border">
                            <div className="space-y-0.5">
                                <Label>Mandatory Requirement</Label>
                                <p className="text-xs text-slate-500">Users cannot proceed without this file</p>
                            </div>
                            <Switch
                                checked={formData.is_required}
                                onCheckedChange={(val) => setFormData({ ...formData, is_required: val })}
                            />
                        </div>

                        <DialogFooter className="pt-4">
                            <Button type="submit" disabled={mutation.isPending} className="bg-blue-600 hover:bg-blue-700">
                                {mutation.isPending ? 'Saving...' : 'Save Requirement'}
                            </Button>
                        </DialogFooter>
                    </form>
                </DialogContent>
            </Dialog>
        </div>
    );
}
