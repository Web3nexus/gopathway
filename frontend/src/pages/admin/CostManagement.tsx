import { useState, useEffect } from 'react';
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import { adminService } from '@/services/api/adminService';
import { DollarSign, Plus, Pencil, Trash2, Calculator, Search, Filter, Globe, MousePointer2 } from 'lucide-react';
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
import { Badge } from '@/components/ui/badge';

export default function CostManagement() {
    const { toast } = useToast();
    const queryClient = useQueryClient();
    const [isDialogOpen, setIsDialogOpen] = useState(false);
    const [editingCost, setEditingCost] = useState<any>(null);
    const [selectedCountryId, setSelectedCountryId] = useState<string>('all');
    const [selectedVisaId, setSelectedVisaId] = useState<string>('all');

    const [formData, setFormData] = useState({
        name: '',
        amount: 0,
        description: '',
        is_mandatory: true,
        currency: 'USD',
        country_id: '',
        visa_type_id: '',
        pathway_id: null
    });

    const { data: countries = [] } = useQuery({
        queryKey: ['admin-countries'],
        queryFn: adminService.getCountries
    });

    const { data: visas = [] } = useQuery({
        queryKey: ['admin-visas', selectedCountryId],
        queryFn: () => {
            if (selectedCountryId === 'all') return [];
            return adminService.getVisaTypes(selectedCountryId);
        },
        enabled: selectedCountryId !== 'all'
    });

    const { data: costs = [], isLoading } = useQuery({
        queryKey: ['admin-costs', selectedCountryId, selectedVisaId],
        queryFn: () => adminService.getCostItems({
            country_id: selectedCountryId === 'all' ? undefined : selectedCountryId,
            visa_type_id: selectedVisaId === 'all' ? undefined : selectedVisaId,
            is_template: true
        })
    });

    const mutation = useMutation({
        mutationFn: (data: any) => {
            if (editingCost) {
                return adminService.updateCostItem(editingCost.id, data);
            }
            return adminService.createCostItem(data);
        },
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['admin-costs'] });
            toast({ title: editingCost ? 'Cost updated' : 'Cost created' });
            setIsDialogOpen(false);
            setEditingCost(null);
            resetForm();
        }
    });

    const deleteMutation = useMutation({
        mutationFn: adminService.deleteCostItem,
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['admin-costs'] });
            toast({ title: 'Cost deleted' });
        }
    });

    const resetForm = () => {
        setFormData({
            name: '',
            amount: 0,
            description: '',
            is_mandatory: true,
            currency: 'USD',
            country_id: selectedCountryId !== 'all' ? selectedCountryId : '',
            visa_type_id: selectedVisaId !== 'all' ? selectedVisaId : '',
            pathway_id: null
        });
    };

    useEffect(() => {
        if (!isDialogOpen) resetForm();
    }, [isDialogOpen, selectedCountryId, selectedVisaId]);

    return (
        <div className="space-y-6">
            <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 className="text-2xl font-bold text-slate-900">Cost Templates</h1>
                    <p className="text-slate-500 text-sm">Define fee structures by country and route</p>
                </div>
                <Button onClick={() => { setEditingCost(null); setIsDialogOpen(true); }} className="bg-blue-600 hover:bg-blue-700 rounded-xl font-bold shadow-lg shadow-blue-200">
                    <Plus className="w-4 h-4 mr-2" />
                    New Cost Item
                </Button>
            </div>

            {/* Filters */}
            <div className="grid grid-cols-1 md:grid-cols-2 gap-4 bg-white p-6 rounded-2xl border shadow-sm">
                <div className="space-y-2">
                    <Label className="text-xs uppercase tracking-wider text-slate-400 font-bold flex items-center gap-1.5">
                        <Globe className="w-3 h-3" /> Select Destination
                    </Label>
                    <Select value={selectedCountryId} onValueChange={(val) => { setSelectedCountryId(val); setSelectedVisaId('all'); }}>
                        <SelectTrigger className="rounded-xl border-slate-200 h-11">
                            <SelectValue placeholder="All Countries" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem value="all">All Countries</SelectItem>
                            {countries.map((c: any) => (
                                <SelectItem key={c.id} value={c.id.toString()}>{c.name}</SelectItem>
                            ))}
                        </SelectContent>
                    </Select>
                </div>
                <div className="space-y-2">
                    <Label className="text-xs uppercase tracking-wider text-slate-400 font-bold flex items-center gap-1.5">
                        <MousePointer2 className="w-3 h-3" /> Select Route (Visa)
                    </Label>
                    <Select value={selectedVisaId} onValueChange={setSelectedVisaId} disabled={selectedCountryId === 'all'}>
                        <SelectTrigger className="rounded-xl border-slate-200 h-11">
                            <SelectValue placeholder="All Routes" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem value="all">All Routes</SelectItem>
                            {visas.map((v: any) => (
                                <SelectItem key={v.id} value={v.id.toString()}>{v.name}</SelectItem>
                            ))}
                        </SelectContent>
                    </Select>
                </div>
            </div>

            <div className="bg-white rounded-2xl border shadow-sm overflow-hidden">
                <div className="overflow-x-auto">
                    <table className="w-full text-left border-collapse">
                        <thead>
                            <tr className="bg-slate-50/50 border-b text-xs uppercase tracking-wider text-slate-500 font-semibold">
                                <th className="px-6 py-4">Item Name</th>
                                <th className="px-6 py-4">Scope</th>
                                <th className="px-6 py-4">Amount</th>
                                <th className="px-6 py-4">Type</th>
                                <th className="px-6 py-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-slate-100">
                            {isLoading ? (
                                <tr><td colSpan={5} className="p-8 text-center text-slate-400">Loading templates...</td></tr>
                            ) : costs.length === 0 ? (
                                <tr>
                                    <td colSpan={5} className="p-20 text-center">
                                        <div className="flex flex-col items-center gap-2">
                                            <Calculator className="w-12 h-12 text-slate-200" />
                                            <p className="text-slate-400 font-medium">No cost templates found for this selection.</p>
                                        </div>
                                    </td>
                                </tr>
                            ) : costs.map((cost: any) => (
                                <tr key={cost.id} className="hover:bg-slate-50 transition-colors">
                                    <td className="px-6 py-4">
                                        <div className="font-bold text-slate-900">{cost.name}</div>
                                        <div className="text-xs text-slate-500 line-clamp-1">{cost.description}</div>
                                    </td>
                                    <td className="px-6 py-4">
                                        <div className="flex flex-wrap gap-1">
                                            {cost.country ? (
                                                <Badge variant="outline" className="text-[10px] bg-blue-50 text-blue-700 border-blue-100">{cost.country.name}</Badge>
                                            ) : (
                                                <Badge variant="outline" className="text-[10px] bg-slate-50 text-slate-500 border-slate-200">Global</Badge>
                                            )}
                                            {cost.visa_type && (
                                                <Badge variant="outline" className="text-[10px] bg-purple-50 text-purple-700 border-purple-100">{cost.visa_type.name}</Badge>
                                            )}
                                        </div>
                                    </td>
                                    <td className="px-6 py-4 font-mono font-bold text-blue-600 italic">
                                        {cost.currency} {parseFloat(cost.amount).toLocaleString()}
                                    </td>
                                    <td className="px-6 py-4 text-xs font-bold uppercase">
                                        {cost.is_mandatory ? (
                                            <span className="text-amber-600">Mandatory</span>
                                        ) : (
                                            <span className="text-slate-400">Optional</span>
                                        )}
                                    </td>
                                    <td className="px-6 py-4 text-right">
                                        <div className="flex items-center justify-end gap-2">
                                            <Button variant="ghost" size="icon" className="h-8 w-8 rounded-lg" onClick={() => { setEditingCost(cost); setFormData(cost); setIsDialogOpen(true); }}>
                                                <Pencil className="w-4 h-4" />
                                            </Button>
                                            <Button variant="ghost" size="icon" className="h-8 w-8 rounded-lg text-red-500 hover:text-red-600 hover:bg-red-50" onClick={() => deleteMutation.mutate(cost.id)}>
                                                <Trash2 className="w-4 h-4" />
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
                <DialogContent className="max-w-md rounded-2xl">
                    <DialogHeader>
                        <DialogTitle>{editingCost ? 'Edit Cost Item' : 'New Cost Item'}</DialogTitle>
                    </DialogHeader>
                    <form onSubmit={(e) => { e.preventDefault(); mutation.mutate(formData); }} className="space-y-4 py-4">
                        <div className="grid grid-cols-2 gap-4">
                            <div className="space-y-2">
                                <Label className="text-xs font-bold uppercase text-slate-400">Target Country</Label>
                                <Select value={formData.country_id?.toString() || 'none'} onValueChange={(val) => setFormData({ ...formData, country_id: val === 'none' ? '' : val, visa_type_id: '' })}>
                                    <SelectTrigger className="rounded-xl">
                                        <SelectValue placeholder="Global" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="none">Global (No Country)</SelectItem>
                                        {countries.map((c: any) => (
                                            <SelectItem key={c.id} value={c.id.toString()}>{c.name}</SelectItem>
                                        ))}
                                    </SelectContent>
                                </Select>
                            </div>
                            <div className="space-y-2">
                                <Label className="text-xs font-bold uppercase text-slate-400">Target Route</Label>
                                <Select
                                    value={formData.visa_type_id?.toString() || 'none'}
                                    onValueChange={(val) => setFormData({ ...formData, visa_type_id: val === 'none' ? '' : val })}
                                    disabled={!formData.country_id}
                                >
                                    <SelectTrigger className="rounded-xl">
                                        <SelectValue placeholder="All Routes" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="none">All Routes</SelectItem>
                                        {countries.find((c: any) => c.id.toString() === formData.country_id.toString())?.visa_types?.map((v: any) => (
                                            <SelectItem key={v.id} value={v.id.toString()}>{v.name}</SelectItem>
                                        ))}
                                        {/* Fallback to visas query if country details don't include them */}
                                        {visas.map((v: any) => (
                                            <SelectItem key={v.id} value={v.id.toString()}>{v.name}</SelectItem>
                                        ))}
                                    </SelectContent>
                                </Select>
                            </div>
                        </div>

                        <div className="space-y-2">
                            <Label className="text-xs font-bold uppercase text-slate-400">Item Name</Label>
                            <Input value={formData.name} onChange={e => setFormData({ ...formData, name: e.target.value })} placeholder="e.g. Visa Application Fee" required className="rounded-xl" />
                        </div>
                        <div className="grid grid-cols-2 gap-4">
                            <div className="space-y-2">
                                <Label className="text-xs font-bold uppercase text-slate-400">Amount</Label>
                                <Input type="number" step="0.01" value={formData.amount} onChange={e => setFormData({ ...formData, amount: parseFloat(e.target.value) })} required className="rounded-xl" />
                            </div>
                            <div className="space-y-2">
                                <Label className="text-xs font-bold uppercase text-slate-400">Currency</Label>
                                <Input value={formData.currency} onChange={e => setFormData({ ...formData, currency: e.target.value.toUpperCase() })} maxLength={3} required className="rounded-xl" />
                            </div>
                        </div>
                        <div className="space-y-2">
                            <Label className="text-xs font-bold uppercase text-slate-400">Description</Label>
                            <Input value={formData.description} onChange={e => setFormData({ ...formData, description: e.target.value })} placeholder="Brief details about this fee" className="rounded-xl" />
                        </div>
                        <div className="flex items-center justify-between p-4 bg-slate-50 rounded-2xl border">
                            <Label className="font-bold text-slate-700">Mandatory Cost</Label>
                            <Switch checked={formData.is_mandatory} onCheckedChange={(val: boolean) => setFormData({ ...formData, is_mandatory: val })} />
                        </div>
                        <DialogFooter className="pt-4">
                            <Button type="submit" disabled={mutation.isPending} className="w-full bg-blue-600 hover:bg-blue-700 rounded-xl py-6 font-bold">
                                {mutation.isPending ? 'Saving...' : 'Save Cost Template'}
                            </Button>
                        </DialogFooter>
                    </form>
                </DialogContent>
            </Dialog>
        </div>
    );
}
