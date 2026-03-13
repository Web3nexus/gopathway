import React, { useState } from 'react';
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import { adminService } from '@/services/api/adminService';
import { Globe, Plus, Pencil, Trash2, CheckCircle2, XCircle, Search, ExternalLink, TrendingUp } from 'lucide-react';
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

export default function CountryManagement() {
    const { toast } = useToast();
    const queryClient = useQueryClient();
    const [searchTerm, setSearchTerm] = useState('');
    const [isDialogOpen, setIsDialogOpen] = useState(false);
    const [editingCountry, setEditingCountry] = useState<any>(null);
    const [formData, setFormData] = useState({
        name: '',
        code: '',
        description: '',
        image_url: '',
        competitiveness_score: 50,
        is_active: true
    });

    const { data: countries = [], isLoading } = useQuery({
        queryKey: ['admin-countries'],
        queryFn: adminService.getCountries
    });

    const mutation = useMutation({
        mutationFn: (data: any) => {
            if (editingCountry) {
                return adminService.updateCountry(editingCountry.id, data);
            }
            return adminService.createCountry(data);
        },
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['admin-countries'] });
            toast({ title: editingCountry ? 'Country updated' : 'Country created' });
            setIsDialogOpen(false);
            setEditingCountry(null);
            setFormData({ name: '', code: '', description: '', image_url: '', competitiveness_score: 50, is_active: true });
        }
    });

    const deleteMutation = useMutation({
        mutationFn: adminService.deleteCountry,
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['admin-countries'] });
            toast({ title: 'Country deleted' });
        }
    });

    const handleEdit = (country: any) => {
        setEditingCountry(country);
        setFormData({
            name: country.name,
            code: country.code,
            description: country.description,
            image_url: country.image_url || '',
            competitiveness_score: country.competitiveness_score || 50,
            is_active: country.is_active
        });
        setIsDialogOpen(true);
    };

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        mutation.mutate(formData);
    };

    const filteredCountries = countries.filter((c: any) =>
        c.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
        c.code.toLowerCase().includes(searchTerm.toLowerCase())
    );

    return (
        <div className="space-y-6">
            <div className="flex items-center justify-between">
                <div>
                    <h1 className="text-2xl font-bold text-slate-900">Destinations</h1>
                    <p className="text-slate-500 text-sm">Manage available countries on the platform</p>
                </div>
                <Button onClick={() => { setEditingCountry(null); setIsDialogOpen(true); }} className="bg-blue-600 hover:bg-blue-700">
                    <Plus className="w-4 h-4 mr-2" />
                    Add Country
                </Button>
            </div>

            <div className="bg-white rounded-2xl border shadow-sm overflow-hidden">
                <div className="p-4 border-b bg-slate-50/50 flex items-center gap-4">
                    <div className="relative flex-1 max-w-sm">
                        <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" />
                        <Input
                            placeholder="Filter countries..."
                            className="pl-9 bg-white"
                            value={searchTerm}
                            onChange={(e) => setSearchTerm(e.target.value)}
                        />
                    </div>
                </div>

                <div className="overflow-x-auto">
                    <table className="w-full text-left border-collapse">
                        <thead>
                            <tr className="bg-slate-50/50 border-b text-xs uppercase tracking-wider text-slate-500 font-semibold">
                                <th className="px-6 py-4">Country</th>
                                <th className="px-6 py-4">Code</th>
                                <th className="px-6 py-4">Global Score</th>
                                <th className="px-6 py-4">Rank</th>
                                <th className="px-6 py-4">Status</th>
                                <th className="px-6 py-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-slate-100">
                            {isLoading ? (
                                [...Array(5)].map((_, i) => (
                                    <tr key={i} className="animate-pulse">
                                        <td colSpan={4} className="px-6 py-4"><div className="h-6 bg-slate-100 rounded w-full" /></td>
                                    </tr>
                                ))
                            ) : filteredCountries.map((country: any, idx: number) => (
                                <tr key={country.id} className="hover:bg-slate-50 transition-colors">
                                    <td className="px-6 py-4">
                                        <div className="flex items-center gap-3">
                                            {country.image_url ? (
                                                <img src={country.image_url} className="w-10 h-6 object-cover rounded shadow-sm" alt={country.name} />
                                            ) : (
                                                <div className="w-10 h-6 bg-slate-100 rounded flex items-center justify-center">
                                                    <Globe className="w-3 h-3 text-slate-400" />
                                                </div>
                                            )}
                                            <div>
                                                <p className="font-bold text-slate-900">{country.name}</p>
                                                <p className="text-xs text-slate-500 truncate max-w-[200px]">{country.description}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td className="px-6 py-4">
                                        <span className="px-2 py-1 bg-slate-100 rounded text-[10px] font-bold text-slate-600 uppercase tracking-tight border">
                                            {country.code}
                                        </span>
                                    </td>
                                    <td className="px-6 py-4">
                                        <div className="flex items-center gap-1.5 font-bold text-slate-700">
                                            <TrendingUp className="w-3.5 h-3.5 text-blue-500" />
                                            {country.competitiveness_score || 50}
                                        </div>
                                    </td>
                                    <td className="px-6 py-4">
                                        <span className="font-black text-blue-600">#{idx + 1}</span>
                                    </td>
                                    <td className="px-6 py-4">
                                        {country.is_active ? (
                                            <span className="flex items-center gap-1.5 text-xs font-medium text-green-600">
                                                <CheckCircle2 className="w-3.5 h-3.5" /> Published
                                            </span>
                                        ) : (
                                            <span className="flex items-center gap-1.5 text-xs font-medium text-slate-400">
                                                <XCircle className="w-3.5 h-3.5" /> Draft
                                            </span>
                                        )}
                                    </td>
                                    <td className="px-6 py-4 text-right">
                                        <div className="flex items-center justify-end gap-2">
                                            <Button variant="ghost" size="icon" onClick={() => handleEdit(country)}>
                                                <Pencil className="w-4 h-4 text-slate-400 hover:text-blue-600" />
                                            </Button>
                                            <Button variant="ghost" size="icon" onClick={() => deleteMutation.mutate(country.id)}>
                                                <Trash2 className="w-4 h-4 text-slate-400 hover:text-red-600" />
                                            </Button>
                                        </div>
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>
            </div>

            {/* Edit/Create Dialog */}
            <Dialog open={isDialogOpen} onOpenChange={setIsDialogOpen}>
                <DialogContent className="sm:max-w-[500px]">
                    <DialogHeader>
                        <DialogTitle>{editingCountry ? 'Edit Country' : 'Add New Country'}</DialogTitle>
                    </DialogHeader>
                    <form onSubmit={handleSubmit} className="space-y-4 py-4">
                        <div className="grid grid-cols-2 gap-4">
                            <div className="space-y-2">
                                <Label htmlFor="name">Country Name</Label>
                                <Input id="name" value={formData.name} onChange={e => setFormData({ ...formData, name: e.target.value })} placeholder="e.g. Canada" required />
                            </div>
                            <div className="space-y-2">
                                <Label htmlFor="code">Country Code (ISO 2)</Label>
                                <Input id="code" value={formData.code} onChange={e => setFormData({ ...formData, code: e.target.value.toUpperCase() })} placeholder="e.g. CA" maxLength={2} required />
                            </div>
                        </div>
                        <div className="space-y-2">
                            <Label htmlFor="description">Short Description</Label>
                            <Input id="description" value={formData.description} onChange={e => setFormData({ ...formData, description: e.target.value })} placeholder="Main value proposition..." required />
                        </div>
                        <div className="grid grid-cols-2 gap-4">
                            <div className="space-y-2">
                                <Label htmlFor="image">Image Preview URL</Label>
                                <Input id="image" value={formData.image_url} onChange={e => setFormData({ ...formData, image_url: e.target.value })} placeholder="https://unsplash..." />
                            </div>
                            <div className="space-y-2">
                                <Label htmlFor="score">Competitiveness Score (0-100)</Label>
                                <Input id="score" type="number" min="0" max="100" value={formData.competitiveness_score} onChange={e => setFormData({ ...formData, competitiveness_score: parseInt(e.target.value) })} />
                            </div>
                        </div>
                        <div className="flex items-center justify-between p-4 bg-slate-50 rounded-xl border border-slate-100">
                            <div className="space-y-0.5">
                                <Label className="text-sm font-bold">Active / Published</Label>
                                <p className="text-xs text-slate-500">Visible to all relocation users</p>
                            </div>
                            <Switch checked={formData.is_active} onCheckedChange={val => setFormData({ ...formData, is_active: val })} />
                        </div>
                        <DialogFooter className="pt-4">
                            <Button type="button" variant="ghost" onClick={() => setIsDialogOpen(false)}>Cancel</Button>
                            <Button type="submit" disabled={mutation.isPending} className="bg-blue-600 hover:bg-blue-700">
                                {mutation.isPending ? 'Saving...' : 'Save Country'}
                            </Button>
                        </DialogFooter>
                    </form>
                </DialogContent>
            </Dialog>
        </div>
    );
}
