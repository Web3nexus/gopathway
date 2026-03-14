import React, { useState } from 'react';
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import { adminService } from '@/services/api/adminService';
import {
    Plus, Pencil, Trash2, FileText
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


export default function RelocationManagement() {
    const { toast } = useToast();
    const queryClient = useQueryClient();
    const [selectedCountryId, setSelectedCountryId] = useState<string>('');
    const [isKitDialogOpen, setIsKitDialogOpen] = useState(false);
    const [isItemDialogOpen, setIsItemDialogOpen] = useState(false);
    const [editingKit, setEditingKit] = useState<any>(null);
    const [editingItem, setEditingItem] = useState<any>(null);

    const [kitFormData, setKitFormData] = useState({
        country_id: '',
        title: '',
        description: '',
        icon: 'home',
        is_premium: false,
        order: 0
    });

    const [itemFormData, setItemFormData] = useState({
        title: '',
        content: '',
        is_premium: false,
        order: 0
    });

    const { data: countriesData } = useQuery({
        queryKey: ['admin-countries'],
        queryFn: adminService.getCountries
    });
    const countries = Array.isArray(countriesData) ? countriesData : [];

    const { data: kitsData, isLoading } = useQuery({
        queryKey: ['admin-relocation-kits', selectedCountryId],
        queryFn: () => adminService.getRelocationKits(selectedCountryId && selectedCountryId !== 'null' ? { country_id: selectedCountryId } : undefined)
    });
    const kits = Array.isArray(kitsData) ? kitsData : [];

    const kitMutation = useMutation({
        mutationFn: (data: any) => {
            if (editingKit) return adminService.updateRelocationKit(editingKit.id, data);
            return adminService.createRelocationKit(data);
        },
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['admin-relocation-kits'] });
            toast({ title: editingKit ? 'Kit updated' : 'Kit created' });
            setIsKitDialogOpen(false);
            setEditingKit(null);
        }
    });

    const kitDeleteMutation = useMutation({
        mutationFn: adminService.deleteRelocationKit,
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['admin-relocation-kits'] });
            toast({ title: 'Kit deleted' });
        }
    });

    const itemMutation = useMutation({
        mutationFn: (data: any) => {
            if (editingItem) return adminService.updateRelocationKitItem(editingKit.id, editingItem.id, data);
            return adminService.createRelocationKitItem(editingKit.id, data);
        },
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['admin-relocation-kits'] });
            toast({ title: editingItem ? 'Item updated' : 'Item added' });
            setIsItemDialogOpen(false);
            setEditingItem(null);
        }
    });





    const handleEditKit = (kit: any) => {
        setEditingKit(kit);
        setKitFormData({
            country_id: kit.country_id.toString(),
            title: kit.title,
            description: kit.description,
            icon: kit.icon || 'home',
            is_premium: !!kit.is_premium,
            order: kit.order || 0
        });
        setIsKitDialogOpen(true);
    };



    return (
        <div className="space-y-6">
            <div className="flex items-center justify-between">
                <div>
                    <h1 className="text-2xl font-bold text-slate-900">Relocation Hub Management</h1>
                    <p className="text-slate-500 text-sm">Manage preparation kits and checklist items for all destinations</p>
                </div>
                <div className="flex items-center gap-3">
                    <select
                        className="h-10 px-3 rounded-md border border-input bg-white text-sm"
                        value={selectedCountryId}
                        onChange={(e) => setSelectedCountryId(e.target.value)}
                    >
                        <option value="">All Countries</option>
                        {countries.map((c: any) => (
                            <option key={c.id} value={c.id.toString()}>{c.name}</option>
                        ))}
                    </select>
                    <Button onClick={() => { setEditingKit(null); setKitFormData({ ...kitFormData, country_id: selectedCountryId }); setIsKitDialogOpen(true); }} className="bg-blue-600 hover:bg-blue-700">
                        <Plus className="w-4 h-4 mr-2" />
                        Create Kit
                    </Button>
                </div>
            </div>

            <div className="bg-white border rounded-2xl shadow-sm overflow-hidden">
                {isLoading ? (
                    <div className="text-center py-20 text-slate-400 animate-pulse">Loading relocation kits...</div>
                ) : kits.length === 0 ? (
                    <div className="text-center py-20 bg-white text-slate-400 font-medium">
                        {selectedCountryId && selectedCountryId !== 'null'
                            ? "No relocation kits found for this country. Click 'Create Kit' to add one."
                            : "No relocation kits found. Click 'Create Kit' to get started."}
                    </div>
                ) : (
                    <div className="overflow-x-auto">
                        <table className="w-full text-left border-collapse">
                            <thead>
                                <tr className="bg-slate-50 border-b border-slate-100">
                                    <th className="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400 w-[40px]">Icon</th>
                                    <th className="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Kit Title</th>
                                    <th className="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Country</th>
                                    <th className="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Items</th>
                                    <th className="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400 text-center">Premium</th>
                                    <th className="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-slate-50">
                                {kits.map((kit: any) => (
                                    <tr key={kit.id} className="hover:bg-slate-50/50 transition-colors group">
                                        <td className="px-6 py-4 text-center">
                                            <div className="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center">
                                                <FileText className="w-4 h-4 text-blue-600" />
                                            </div>
                                        </td>
                                        <td className="px-6 py-4">
                                            <div className="flex flex-col">
                                                <span className="font-bold text-slate-900 text-sm">{kit.title}</span>
                                                <span className="text-xs text-slate-500 line-clamp-1 max-w-[300px]">{kit.description}</span>
                                            </div>
                                        </td>
                                        <td className="px-6 py-4">
                                            <span className="px-2 py-0.5 bg-blue-50 text-blue-700 rounded text-[10px] font-bold uppercase border border-blue-100">
                                                {kit.country?.name}
                                            </span>
                                        </td>
                                        <td className="px-6 py-4">
                                            <div className="flex items-center gap-1.5 text-xs text-slate-500 font-medium">
                                                <Plus className="w-3 h-3" />
                                                {kit.items?.length || 0} Items
                                            </div>
                                        </td>
                                        <td className="px-6 py-4 text-center">
                                            {kit.is_premium ? (
                                                <span className="inline-flex items-center px-1.5 py-0.5 rounded bg-orange-50 text-orange-600 text-[9px] font-black uppercase border border-orange-100">Premium</span>
                                            ) : (
                                                <span className="inline-flex items-center px-1.5 py-0.5 rounded bg-slate-50 text-slate-400 text-[9px] font-black uppercase border border-slate-100">Free</span>
                                            )}
                                        </td>
                                        <td className="px-6 py-4 text-right">
                                            <div className="flex justify-end gap-1">
                                                <Button variant="ghost" size="icon" className="h-8 w-8 text-slate-400 hover:text-blue-600 hover:bg-blue-50" onClick={() => { setEditingKit(kit); setKitFormData({ country_id: kit.country_id.toString(), title: kit.title, description: kit.description, icon: kit.icon || 'home', is_premium: !!kit.is_premium, order: kit.order || 0 }); setIsKitDialogOpen(true); }}>
                                                    <Pencil className="w-3.5 h-3.5" />
                                                </Button>
                                                <Button variant="ghost" size="icon" className="h-8 w-8 text-slate-400 hover:text-red-600 hover:bg-red-50" onClick={() => kitDeleteMutation.mutate(kit.id)}>
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

            {/* Kit Dialog */}
            <Dialog open={isKitDialogOpen} onOpenChange={setIsKitDialogOpen}>
                <DialogContent>
                    <DialogHeader>
                        <DialogTitle>{editingKit ? 'Edit Relocation Kit' : 'New Relocation Kit'}</DialogTitle>
                    </DialogHeader>
                    <div className="space-y-4 py-4">
                        <div className="space-y-2">
                            <Label>Country</Label>
                            <select
                                className="w-full h-10 px-3 rounded-md border border-input bg-white text-sm"
                                value={kitFormData.country_id}
                                onChange={(e) => setKitFormData({ ...kitFormData, country_id: e.target.value })}
                                disabled={!!editingKit}
                            >
                                <option value="">Select Country</option>
                                {countries.map((c: any) => (
                                    <option key={c.id} value={c.id.toString()}>{c.name}</option>
                                ))}
                            </select>
                        </div>
                        <div className="space-y-2">
                            <Label>Title</Label>
                            <Input value={kitFormData.title} onChange={e => setKitFormData({ ...kitFormData, title: e.target.value })} placeholder="e.g. First 30 Days" />
                        </div>
                        <div className="space-y-2">
                            <Label>Description</Label>
                            <Input value={kitFormData.description} onChange={e => setKitFormData({ ...kitFormData, description: e.target.value })} placeholder="Short summary..." />
                        </div>
                        <div className="flex items-center justify-between p-4 bg-slate-50 rounded-xl border">
                            <div className="space-y-0.5">
                                <Label>Premium Only</Label>
                                <p className="text-[10px] text-slate-500 uppercase font-bold">Locks content for free users</p>
                            </div>
                            <Switch checked={kitFormData.is_premium} onCheckedChange={v => setKitFormData({ ...kitFormData, is_premium: v })} />
                        </div>
                    </div>
                    <DialogFooter>
                        <Button variant="ghost" onClick={() => setIsKitDialogOpen(false)}>Cancel</Button>
                        <Button onClick={() => kitMutation.mutate(kitFormData)} className="bg-blue-600 hover:bg-blue-700">Save Kit</Button>
                    </DialogFooter>
                </DialogContent>
            </Dialog>

            {/* Item Dialog */}
            <Dialog open={isItemDialogOpen} onOpenChange={setIsItemDialogOpen}>
                <DialogContent className="sm:max-w-[600px]">
                    <DialogHeader>
                        <DialogTitle>{editingItem ? 'Edit Item' : 'Add Item to Kit'}</DialogTitle>
                    </DialogHeader>
                    <div className="space-y-4 py-4">
                        <div className="space-y-2">
                            <Label>Item Title</Label>
                            <Input value={itemFormData.title} onChange={e => setItemFormData({ ...itemFormData, title: e.target.value })} placeholder="e.g. NIE Registration" />
                        </div>
                        <div className="space-y-2">
                            <Label>Content (Main Text)</Label>
                            <textarea
                                className="w-full min-h-[150px] p-3 rounded-md border border-input bg-white text-sm"
                                value={itemFormData.content}
                                onChange={e => setItemFormData({ ...itemFormData, content: e.target.value })}
                                placeholder="Detailed instructions or expert tips..."
                            />
                        </div>
                        <div className="flex items-center justify-between p-4 bg-slate-50 rounded-xl border">
                            <div className="space-y-0.5">
                                <Label>Premium Item</Label>
                                <p className="text-[10px] text-slate-500 uppercase font-bold">Only visible to premium users</p>
                            </div>
                            <Switch checked={itemFormData.is_premium} onCheckedChange={v => setItemFormData({ ...itemFormData, is_premium: v })} />
                        </div>
                    </div>
                    <DialogFooter>
                        <Button variant="ghost" onClick={() => setIsItemDialogOpen(false)}>Cancel</Button>
                        <Button onClick={() => itemMutation.mutate(itemFormData)} className="bg-blue-600 hover:bg-blue-700">Save Item</Button>
                    </DialogFooter>
                </DialogContent>
            </Dialog>
        </div>
    );
}
