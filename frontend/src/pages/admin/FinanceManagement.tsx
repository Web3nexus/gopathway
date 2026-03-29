import React, { useState } from 'react';
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import { adminService } from '@/services/api/adminService';
import {
    Card,
    CardContent,
    CardHeader,
    CardTitle,
    CardDescription,
} from '@/components/ui/card';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { Badge } from '@/components/ui/badge';
import { Switch } from '@/components/ui/switch';
import { 
    Plus, 
    Search, 
    Edit2, 
    Trash2, 
    ExternalLink, 
    Star, 
    Globe, 
    Building2,
    CheckCircle2,
    XCircle,
    Loader2
} from 'lucide-react';
import { toast } from 'sonner';

interface FinanceProvider {
    id: number;
    name: string;
    provider_type: string;
    supported_countries: string[] | string;
    supported_pathways: string[] | string;
    website: string;
    contact_email?: string;
    description?: string;
    logo_url?: string;
    rating: number;
    is_active: boolean;
}

export default function FinanceManagement() {
    const queryClient = useQueryClient();
    const [searchTerm, setSearchTerm] = useState('');
    const [isDialogOpen, setIsDialogOpen] = useState(false);
    const [editingProvider, setEditingProvider] = useState<FinanceProvider | null>(null);

    // Form State
    const [formData, setFormData] = useState({
        name: '',
        provider_type: 'Education Loan',
        supported_countries: '',
        supported_pathways: '',
        website: '',
        contact_email: '',
        description: '',
        logo_url: '',
        rating: 5,
        is_active: true
    });

    const { data: providersData, isLoading } = useQuery({
        queryKey: ['admin-finance-providers'],
        queryFn: () => adminService.getFinanceProviders(),
    });

    const providers = providersData?.data || [];

    const createMutation = useMutation({
        mutationFn: adminService.createFinanceProvider,
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['admin-finance-providers'] });
            toast.success('Provider created successfully');
            setIsDialogOpen(false);
            resetForm();
        },
    });

    const updateMutation = useMutation({
        mutationFn: ({ id, data }: { id: number; data: any }) => adminService.updateFinanceProvider(id, data),
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['admin-finance-providers'] });
            toast.success('Provider updated successfully');
            setIsDialogOpen(false);
            setEditingProvider(null);
        },
    });

    const deleteMutation = useMutation({
        mutationFn: adminService.deleteFinanceProvider,
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['admin-finance-providers'] });
            toast.success('Provider deleted successfully');
        },
    });

    const toggleMutation = useMutation({
        mutationFn: adminService.toggleFinanceProvider,
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['admin-finance-providers'] });
        },
    });

    const resetForm = () => {
        setFormData({
            name: '',
            provider_type: 'Education Loan',
            supported_countries: '',
            supported_pathways: '',
            website: '',
            contact_email: '',
            description: '',
            logo_url: '',
            rating: 5,
            is_active: true
        });
    };

    const handleEdit = (provider: FinanceProvider) => {
        setEditingProvider(provider);
        setFormData({
            name: provider.name,
            provider_type: provider.provider_type,
            supported_countries: Array.isArray(provider.supported_countries) ? provider.supported_countries.join(', ') : '',
            supported_pathways: Array.isArray(provider.supported_pathways) ? provider.supported_pathways.join(', ') : '',
            website: provider.website,
            contact_email: provider.contact_email || '',
            description: provider.description || '',
            logo_url: provider.logo_url || '',
            rating: provider.rating,
            is_active: provider.is_active
        });
        setIsDialogOpen(true);
    };

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        const payload = {
            ...formData,
            supported_countries: formData.supported_countries.split(',').map(s => s.trim()).filter(Boolean),
            supported_pathways: formData.supported_pathways.split(',').map(s => s.trim()).filter(Boolean),
        };

        if (editingProvider) {
            updateMutation.mutate({ id: editingProvider.id, data: payload });
        } else {
            createMutation.mutate(payload);
        }
    };

    const filteredProviders = providers.filter((p: FinanceProvider) =>
        p.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
        p.provider_type.toLowerCase().includes(searchTerm.toLowerCase())
    );

    return (
        <div className="space-y-8">
            <div className="flex justify-between items-center">
                <div>
                    <h1 className="text-3xl font-bold text-slate-900 tracking-tight">Finance Management</h1>
                    <p className="text-slate-500 mt-1 text-sm">Manage funding and loan recommendations for relocators.</p>
                </div>
                <Dialog open={isDialogOpen} onOpenChange={(open) => {
                    setIsDialogOpen(open);
                    if (!open) {
                        setEditingProvider(null);
                        resetForm();
                    }
                }}>
                    <DialogTrigger asChild>
                        <Button className="bg-blue-600 hover:bg-blue-700 shadow-lg shadow-blue-200 gap-2">
                            <Plus className="w-4 h-4" /> Add Provider
                        </Button>
                    </DialogTrigger>
                    <DialogContent className="sm:max-w-[600px] max-h-[90vh] overflow-y-auto">
                        <DialogHeader>
                            <DialogTitle>{editingProvider ? 'Edit Provider' : 'Add New Provider'}</DialogTitle>
                            <DialogDescription>
                                Fill in the details for the financing company.
                            </DialogDescription>
                        </DialogHeader>
                        <form onSubmit={handleSubmit} className="space-y-6 pt-4">
                            <div className="grid grid-cols-2 gap-4">
                                <div className="space-y-2">
                                    <Label htmlFor="name">Company Name</Label>
                                    <Input 
                                        id="name" 
                                        value={formData.name} 
                                        onChange={e => setFormData({...formData, name: e.target.value})}
                                        placeholder="e.g. MPOWER Financing" 
                                        required 
                                    />
                                </div>
                                <div className="space-y-2">
                                    <Label htmlFor="type">Provider Type</Label>
                                    <Input 
                                        id="type" 
                                        value={formData.provider_type} 
                                        onChange={e => setFormData({...formData, provider_type: e.target.value})}
                                        placeholder="e.g. Education Loan" 
                                        required 
                                    />
                                </div>
                            </div>

                            <div className="space-y-2">
                                <Label htmlFor="website">Website URL</Label>
                                <Input 
                                    id="website" 
                                    type="url"
                                    value={formData.website} 
                                    onChange={e => setFormData({...formData, website: e.target.value})}
                                    placeholder="https://..." 
                                    required 
                                />
                            </div>

                            <div className="grid grid-cols-2 gap-4">
                                <div className="space-y-2">
                                    <Label htmlFor="countries">Supported Countries (Comma separated)</Label>
                                    <Input 
                                        id="countries" 
                                        value={formData.supported_countries} 
                                        onChange={e => setFormData({...formData, supported_countries: e.target.value})}
                                        placeholder="Global, USA, UK" 
                                    />
                                </div>
                                <div className="space-y-2">
                                    <Label htmlFor="pathways">Supported Pathways (Comma separated)</Label>
                                    <Input 
                                        id="pathways" 
                                        value={formData.supported_pathways} 
                                        onChange={e => setFormData({...formData, supported_pathways: e.target.value})}
                                        placeholder="Student Visa, Work Visa" 
                                    />
                                </div>
                            </div>

                            <div className="space-y-2">
                                <Label htmlFor="description">Description</Label>
                                <Textarea 
                                    id="description" 
                                    value={formData.description} 
                                    onChange={e => setFormData({...formData, description: e.target.value})}
                                    placeholder="Brief overview of their services..." 
                                    rows={3}
                                />
                            </div>

                            <div className="grid grid-cols-2 gap-4">
                                <div className="space-y-2">
                                    <Label htmlFor="rating">Rating (0-5)</Label>
                                    <Input 
                                        id="rating" 
                                        type="number" 
                                        step="0.1"
                                        min="0"
                                        max="5"
                                        value={formData.rating} 
                                        onChange={e => setFormData({...formData, rating: parseFloat(e.target.value)})}
                                    />
                                </div>
                                <div className="space-y-2">
                                    <Label htmlFor="logo">Logo URL</Label>
                                    <Input 
                                        id="logo" 
                                        value={formData.logo_url} 
                                        onChange={e => setFormData({...formData, logo_url: e.target.value})}
                                        placeholder="https://icon.horse/..." 
                                    />
                                </div>
                            </div>

                            <div className="flex items-center justify-between p-4 bg-slate-50 rounded-xl">
                                <div className="space-y-0.5">
                                    <Label>Active Status</Label>
                                    <p className="text-xs text-slate-500">Enable or disable this provider globally.</p>
                                </div>
                                <Switch 
                                    checked={formData.is_active}
                                    onCheckedChange={checked => setFormData({...formData, is_active: checked})}
                                />
                            </div>

                            <DialogFooter>
                                <Button type="submit" disabled={createMutation.isPending || updateMutation.isPending} className="w-full">
                                    {(createMutation.isPending || updateMutation.isPending) && <Loader2 className="w-4 h-4 mr-2 animate-spin" />}
                                    {editingProvider ? 'Update Provider' : 'Create Provider'}
                                </Button>
                            </DialogFooter>
                        </form>
                    </DialogContent>
                </Dialog>
            </div>

            <Card className="border-none shadow-sm overflow-hidden">
                <CardHeader className="bg-white border-b border-slate-50 py-4">
                    <div className="flex items-center gap-4">
                        <div className="relative flex-1">
                            <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" />
                            <Input 
                                placeholder="Search by name or type..." 
                                className="pl-9 h-10"
                                value={searchTerm}
                                onChange={e => setSearchTerm(e.target.value)}
                            />
                        </div>
                    </div>
                </CardHeader>
                <CardContent className="p-0">
                    <Table>
                        <TableHeader className="bg-slate-50/50">
                            <TableRow>
                                <TableHead className="w-[200px]">Provider</TableHead>
                                <TableHead>Type</TableHead>
                                <TableHead>Supported Countries</TableHead>
                                <TableHead>Rating</TableHead>
                                <TableHead>Status</TableHead>
                                <TableHead className="text-right">Actions</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            {isLoading ? (
                                <TableRow>
                                    <TableCell colSpan={6} className="h-48 text-center text-slate-500">
                                        <Loader2 className="w-8 h-8 animate-spin mx-auto mb-2 text-blue-500 opacity-50" />
                                        Loading providers...
                                    </TableCell>
                                </TableRow>
                            ) : filteredProviders.length === 0 ? (
                                <TableRow>
                                    <TableCell colSpan={6} className="h-48 text-center text-slate-500">
                                        <Building2 className="w-12 h-12 mx-auto mb-2 opacity-10" />
                                        No providers found.
                                    </TableCell>
                                </TableRow>
                            ) : (
                                filteredProviders.map((p: FinanceProvider) => (
                                    <TableRow key={p.id}>
                                        <TableCell>
                                            <div className="flex items-center gap-3">
                                                <div className="w-10 h-10 rounded-lg bg-slate-100 flex-shrink-0 flex items-center justify-center overflow-hidden border border-slate-50">
                                                    {p.logo_url ? (
                                                        <img src={p.logo_url} alt={p.name} className="w-full h-full object-cover" />
                                                    ) : (
                                                        <Building2 className="w-5 h-5 text-slate-400" />
                                                    )}
                                                </div>
                                                <div className="min-w-0">
                                                    <p className="font-semibold text-slate-900 truncate">{p.name}</p>
                                                    <a href={p.website} target="_blank" rel="noopener noreferrer" className="text-[10px] text-blue-600 hover:underline flex items-center gap-0.5">
                                                        Visit Website <ExternalLink className="w-2.5 h-2.5" />
                                                    </a>
                                                </div>
                                            </div>
                                        </TableCell>
                                        <TableCell>
                                            <Badge variant="outline" className="font-medium bg-slate-50 text-slate-600 border-slate-200">
                                                {p.provider_type}
                                            </Badge>
                                        </TableCell>
                                        <TableCell>
                                            <div className="flex flex-wrap gap-1 max-w-[200px]">
                                                {Array.isArray(p.supported_countries) ? p.supported_countries.map(c => (
                                                    <Badge key={c} variant="secondary" className="text-[10px] bg-blue-50 text-blue-700 hover:bg-blue-100 border-none">
                                                        {c}
                                                    </Badge>
                                                )) : 'All'}
                                            </div>
                                        </TableCell>
                                        <TableCell>
                                            <div className="flex items-center gap-1">
                                                <Star className="w-3.5 h-3.5 fill-amber-400 text-amber-400" />
                                                <span className="text-sm font-bold text-slate-700">{p.rating}</span>
                                            </div>
                                        </TableCell>
                                        <TableCell>
                                            <Button 
                                                variant="ghost" 
                                                size="sm"
                                                onClick={() => toggleMutation.mutate(p.id)}
                                                className="p-0 hover:bg-transparent"
                                            >
                                                {p.is_active ? (
                                                    <Badge className="bg-emerald-50 text-emerald-600 hover:bg-emerald-100 border-none uppercase text-[10px] font-bold">
                                                        <CheckCircle2 className="w-3 h-3 mr-1" /> Active
                                                    </Badge>
                                                ) : (
                                                    <Badge className="bg-slate-100 text-slate-500 hover:bg-slate-200 border-none uppercase text-[10px] font-bold">
                                                        <XCircle className="w-3 h-3 mr-1" /> Inactive
                                                    </Badge>
                                                )}
                                            </Button>
                                        </TableCell>
                                        <TableCell className="text-right">
                                            <div className="flex justify-end gap-2">
                                                <Button size="icon" variant="ghost" onClick={() => handleEdit(p)} className="h-8 w-8 text-slate-600">
                                                    <Edit2 className="w-4 h-4" />
                                                </Button>
                                                <Button 
                                                    size="icon" 
                                                    variant="ghost" 
                                                    onClick={() => confirm('Are you sure?') && deleteMutation.mutate(p.id)}
                                                    className="h-8 w-8 text-rose-600 hover:text-rose-700 hover:bg-rose-50"
                                                >
                                                    <Trash2 className="w-4 h-4" />
                                                </Button>
                                            </div>
                                        </TableCell>
                                    </TableRow>
                                ))
                            )}
                        </TableBody>
                    </Table>
                </CardContent>
            </Card>
        </div>
    );
}
