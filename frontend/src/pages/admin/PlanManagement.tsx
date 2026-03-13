import { useState } from 'react';
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import { adminService } from '@/services/api/adminService';
import { CreditCard, Plus, Pencil, Trash2, ToggleLeft, ToggleRight } from 'lucide-react';
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
import { Textarea } from '@/components/ui/textarea';

export default function PlanManagement() {
    const { toast } = useToast();
    const queryClient = useQueryClient();
    const [isDialogOpen, setIsDialogOpen] = useState(false);
    const [editingPlan, setEditingPlan] = useState<any>(null);
    const [formData, setFormData] = useState({
        name: '',
        slug: '',
        tier: 'starter',
        price: 0,
        currency: 'USD',
        interval: 'month' as 'month' | 'year',
        features: [] as string[],
        description: '',
        is_active: true,
    });
    const [featureInput, setFeatureInput] = useState('');

    const { data: plans = [], isLoading } = useQuery({
        queryKey: ['admin-subscription-plans'],
        queryFn: adminService.getSubscriptionPlans
    });

    const mutation = useMutation({
        mutationFn: (data: any) => {
            if (editingPlan) {
                return adminService.updateSubscriptionPlan(editingPlan.id, data);
            }
            return adminService.createSubscriptionPlan(data);
        },
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['admin-subscription-plans'] });
            toast({ title: editingPlan ? 'Plan updated' : 'Plan created' });
            setIsDialogOpen(false);
            setEditingPlan(null);
            resetForm();
        },
        onError: (err: any) => {
            toast({
                title: 'Error saving plan',
                description: err.response?.data?.message || 'Check your inputs',
                variant: 'destructive'
            });
        }
    });

    const deleteMutation = useMutation({
        mutationFn: adminService.deleteSubscriptionPlan,
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['admin-subscription-plans'] });
            toast({ title: 'Plan deleted' });
        }
    });

    const toggleMutation = useMutation({
        mutationFn: ({ id, is_active }: { id: number; is_active: boolean }) =>
            adminService.updateSubscriptionPlan(id, { is_active }),
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['admin-subscription-plans'] });
            toast({ title: 'Plan status updated' });
        }
    });

    const resetForm = () => {
        setFormData({
            name: '', slug: '', tier: 'starter', price: 0, currency: 'USD',
            interval: 'month', features: [], description: '', is_active: true,
        });
        setFeatureInput('');
    };

    const handleEdit = (plan: any) => {
        setEditingPlan(plan);
        const features = Array.isArray(plan.features) ? plan.features : JSON.parse(plan.features || '[]');
        setFormData({
            name: plan.name,
            slug: plan.slug,
            tier: plan.tier || 'starter',
            price: parseFloat(plan.price),
            currency: plan.currency,
            interval: plan.interval,
            features,
            description: plan.description || '',
            is_active: plan.is_active,
        });
        setIsDialogOpen(true);
    };

    const addFeature = () => {
        if (featureInput.trim()) {
            setFormData({ ...formData, features: [...formData.features, featureInput.trim()] });
            setFeatureInput('');
        }
    };

    const removeFeature = (index: number) => {
        setFormData({ ...formData, features: formData.features.filter((_, i) => i !== index) });
    };

    const tierColors: Record<string, string> = {
        free: 'bg-slate-50 text-slate-600 border-slate-200',
        starter: 'bg-blue-50 text-blue-700 border-blue-100',
        premium: 'bg-purple-50 text-purple-700 border-purple-100',
    };

    return (
        <div className="space-y-6">
            <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 className="text-2xl font-bold text-slate-900">Subscription Plans</h1>
                    <p className="text-slate-500 text-sm">Manage pricing tiers, features, and billing intervals</p>
                </div>
                <Button onClick={() => { setEditingPlan(null); resetForm(); setIsDialogOpen(true); }} className="bg-blue-600 hover:bg-blue-700 rounded-xl font-bold shadow-lg shadow-blue-200">
                    <Plus className="w-4 h-4 mr-2" />
                    New Plan
                </Button>
            </div>

            <div className="bg-white rounded-2xl border shadow-sm overflow-hidden">
                <div className="overflow-x-auto">
                    <table className="w-full text-left border-collapse">
                        <thead>
                            <tr className="bg-slate-50/50 border-b text-xs uppercase tracking-wider text-slate-500 font-semibold">
                                <th className="px-6 py-4">Plan Name</th>
                                <th className="px-6 py-4">Tier</th>
                                <th className="px-6 py-4">Price</th>
                                <th className="px-6 py-4">Interval</th>
                                <th className="px-6 py-4">Status</th>
                                <th className="px-6 py-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-slate-100">
                            {isLoading ? (
                                <tr><td colSpan={6} className="p-8 text-center text-slate-400">Loading plans...</td></tr>
                            ) : plans.length === 0 ? (
                                <tr><td colSpan={6} className="p-20 text-center text-slate-400">
                                    <CreditCard className="w-12 h-12 mx-auto mb-2 opacity-20" />
                                    No plans defined yet.
                                </td></tr>
                            ) : plans.map((plan: any) => (
                                <tr key={plan.id} className="hover:bg-slate-50 transition-colors">
                                    <td className="px-6 py-4">
                                        <div className="font-bold text-slate-900">{plan.name}</div>
                                        <div className="text-xs text-slate-400 font-mono">{plan.slug}</div>
                                    </td>
                                    <td className="px-6 py-4">
                                        <Badge variant="outline" className={`text-[10px] ${tierColors[plan.tier] || 'bg-slate-50 text-slate-500 border-slate-200'}`}>
                                            {plan.tier}
                                        </Badge>
                                    </td>
                                    <td className="px-6 py-4 font-mono font-bold text-blue-600">
                                        {plan.currency} {parseFloat(plan.price).toFixed(2)}
                                    </td>
                                    <td className="px-6 py-4 text-sm font-medium capitalize">{plan.interval}ly</td>
                                    <td className="px-6 py-4">
                                        <button
                                            onClick={() => toggleMutation.mutate({ id: plan.id, is_active: !plan.is_active })}
                                            className="flex items-center gap-1.5"
                                        >
                                            {plan.is_active ? (
                                                <><ToggleRight className="w-5 h-5 text-green-500" /><span className="text-xs font-bold text-green-600">Active</span></>
                                            ) : (
                                                <><ToggleLeft className="w-5 h-5 text-slate-300" /><span className="text-xs font-bold text-slate-400">Inactive</span></>
                                            )}
                                        </button>
                                    </td>
                                    <td className="px-6 py-4 text-right">
                                        <div className="flex items-center justify-end gap-2">
                                            <Button variant="ghost" size="icon" className="h-8 w-8 rounded-lg" onClick={() => handleEdit(plan)}>
                                                <Pencil className="w-4 h-4" />
                                            </Button>
                                            <Button variant="ghost" size="icon" className="h-8 w-8 rounded-lg text-red-500 hover:text-red-600 hover:bg-red-50" onClick={() => { if (confirm('Delete this plan?')) deleteMutation.mutate(plan.id); }}>
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
                <DialogContent className="max-w-lg rounded-2xl">
                    <DialogHeader>
                        <DialogTitle>{editingPlan ? 'Edit Plan' : 'New Subscription Plan'}</DialogTitle>
                    </DialogHeader>
                    <form onSubmit={(e) => { e.preventDefault(); mutation.mutate(formData); }} className="space-y-4 py-4">
                        <div className="grid grid-cols-2 gap-4">
                            <div className="space-y-2">
                                <Label className="text-xs font-bold uppercase text-slate-400">Plan Name</Label>
                                <Input value={formData.name} onChange={e => setFormData({ ...formData, name: e.target.value })} placeholder="e.g. Premium Monthly" required className="rounded-xl" />
                            </div>
                            <div className="space-y-2">
                                <Label className="text-xs font-bold uppercase text-slate-400">Slug</Label>
                                <Input value={formData.slug} onChange={e => setFormData({ ...formData, slug: e.target.value })} placeholder="e.g. premium-monthly" required className="rounded-xl" />
                            </div>
                        </div>

                        <div className="grid grid-cols-3 gap-4">
                            <div className="space-y-2">
                                <Label className="text-xs font-bold uppercase text-slate-400">Tier</Label>
                                <Select value={formData.tier} onValueChange={(val) => setFormData({ ...formData, tier: val })}>
                                    <SelectTrigger className="rounded-xl"><SelectValue /></SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="free">Free</SelectItem>
                                        <SelectItem value="starter">Starter</SelectItem>
                                        <SelectItem value="premium">Premium</SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>
                            <div className="space-y-2">
                                <Label className="text-xs font-bold uppercase text-slate-400">Price</Label>
                                <Input type="number" step="0.01" value={formData.price} onChange={e => setFormData({ ...formData, price: parseFloat(e.target.value) })} required className="rounded-xl" />
                            </div>
                            <div className="space-y-2">
                                <Label className="text-xs font-bold uppercase text-slate-400">Interval</Label>
                                <Select value={formData.interval} onValueChange={(val: 'month' | 'year') => setFormData({ ...formData, interval: val })}>
                                    <SelectTrigger className="rounded-xl"><SelectValue /></SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="month">Monthly</SelectItem>
                                        <SelectItem value="year">Yearly</SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>
                        </div>

                        <div className="grid grid-cols-2 gap-4">
                            <div className="space-y-2">
                                <Label className="text-xs font-bold uppercase text-slate-400">Currency</Label>
                                <Input value={formData.currency} onChange={e => setFormData({ ...formData, currency: e.target.value.toUpperCase() })} maxLength={3} required className="rounded-xl" />
                            </div>
                            <div className="flex items-center justify-between p-4 bg-slate-50 rounded-2xl border">
                                <Label className="font-bold text-slate-700">Active</Label>
                                <Switch checked={formData.is_active} onCheckedChange={(val: boolean) => setFormData({ ...formData, is_active: val })} />
                            </div>
                        </div>

                        <div className="space-y-2">
                            <Label className="text-xs font-bold uppercase text-slate-400">Description</Label>
                            <Textarea value={formData.description} onChange={e => setFormData({ ...formData, description: e.target.value })} placeholder="Short description for pricing card" rows={2} className="rounded-xl" />
                        </div>

                        <div className="space-y-2">
                            <Label className="text-xs font-bold uppercase text-slate-400">Features</Label>
                            <div className="flex gap-2">
                                <Input
                                    value={featureInput}
                                    onChange={e => setFeatureInput(e.target.value)}
                                    placeholder="e.g. Unlimited Pathway Access"
                                    className="rounded-xl"
                                    onKeyDown={e => { if (e.key === 'Enter') { e.preventDefault(); addFeature(); } }}
                                />
                                <Button type="button" variant="outline" onClick={addFeature} className="rounded-xl shrink-0">Add</Button>
                            </div>
                            <div className="flex flex-wrap gap-2 mt-2">
                                {formData.features.map((f, i) => (
                                    <span key={i} className="inline-flex items-center gap-1 text-xs bg-slate-100 px-2.5 py-1 rounded-full font-medium">
                                        {f}
                                        <button type="button" onClick={() => removeFeature(i)} className="text-slate-400 hover:text-red-500 ml-0.5">×</button>
                                    </span>
                                ))}
                            </div>
                        </div>

                        <DialogFooter className="pt-4">
                            <Button type="submit" disabled={mutation.isPending} className="w-full bg-blue-600 hover:bg-blue-700 rounded-xl py-6 font-bold">
                                {mutation.isPending ? 'Saving...' : 'Save Plan'}
                            </Button>
                        </DialogFooter>
                    </form>
                </DialogContent>
            </Dialog>
        </div>
    );
}
