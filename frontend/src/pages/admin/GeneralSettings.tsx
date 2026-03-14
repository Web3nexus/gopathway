import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import { adminService } from '@/services/api/adminService';
import { Settings, Save, Loader2, Globe, Mail, Phone, DollarSign, Palette, ShieldCheck } from 'lucide-react';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Switch } from '@/components/ui/switch';
import { useToast } from '@/hooks/use-toast';
import { useState, useEffect } from 'react';

export default function GeneralSettings() {
    const { toast } = useToast();
    const queryClient = useQueryClient();
    const [formData, setFormData] = useState<Record<string, any>>({});

    const { data: settingsData, isLoading } = useQuery({
        queryKey: ['admin-settings'],
        queryFn: adminService.getSettings
    });

    useEffect(() => {
        if (settingsData) {
            const initial: Record<string, any> = {};
            Object.values(settingsData).forEach((group: any) => {
                if (Array.isArray(group)) {
                    group.forEach((s: any) => {
                        initial[s.key] = s.value;
                    });
                }
            });
            setFormData(initial);
        }
    }, [settingsData]);

    const updateMutation = useMutation({
        mutationFn: adminService.updateSettings,
        onSuccess: () => {
            toast({ title: 'Settings saved successfully' });
            queryClient.invalidateQueries({ queryKey: ['admin-settings'] });
        },
        onError: () => {
            toast({ title: 'Failed to save settings', variant: 'destructive' });
        }
    });

    const handleSave = () => {
        const payload = Object.entries(formData).map(([key, value]) => ({
            key,
            value
        }));
        updateMutation.mutate(payload);
    };

    if (isLoading) {
        return <div className="flex justify-center p-20"><Loader2 className="h-8 w-8 animate-spin text-[#0B3C91]" /></div>;
    }

    const groups = settingsData || {};

    const getIcon = (groupName: string) => {
        switch (groupName) {
            case 'general': return <Globe className="h-5 w-5" />;
            case 'appearance': return <Palette className="h-5 w-5" />;
            case 'system': return <ShieldCheck className="h-5 w-5" />;
            case 'payment': return <DollarSign className="h-5 w-5" />;
            default: return <Settings className="h-5 w-5" />;
        }
    };

    return (
        <div className="max-w-4xl mx-auto space-y-8">
            <div className="flex items-center justify-between">
                <div>
                    <h1 className="text-3xl font-black text-[#1A1A1A] tracking-tight">General Settings</h1>
                    <p className="text-[#6B7280]">Configure global application parameters and branding.</p>
                </div>
                <Button
                    onClick={handleSave}
                    disabled={updateMutation.isPending}
                    className="bg-[#0B3C91] hover:bg-[#0B3C91]/90 text-white rounded-xl h-11 px-6 font-bold flex items-center gap-2"
                >
                    {updateMutation.isPending ? <Loader2 className="h-4 w-4 animate-spin" /> : <Save className="h-4 w-4" />}
                    Save Changes
                </Button>
            </div>

            <div className="space-y-6">
                {Object.entries(groups).map(([groupName, groupSettings]: [string, any]) => (
                    <div key={groupName} className="bg-white rounded-3xl border border-[#E5E7EB] shadow-sm overflow-hidden">
                        <div className="px-8 py-4 bg-slate-50 border-b border-[#E5E7EB] flex items-center gap-3">
                            <div className="h-8 w-8 rounded-lg bg-white border border-[#E5E7EB] flex items-center justify-center text-[#0B3C91]">
                                {getIcon(groupName)}
                            </div>
                            <h3 className="font-bold text-[#1A1A1A] capitalize">{groupName} Settings</h3>
                        </div>
                        <div className="p-8 space-y-6">
                            {Array.isArray(groupSettings) && groupSettings.map((s: any) => (
                                <div key={s.key} className="grid grid-cols-1 md:grid-cols-3 gap-4 items-start pb-6 border-b border-slate-100 last:border-0 last:pb-0">
                                    <div className="md:col-span-1">
                                        <label className="text-sm font-bold text-[#1A1A1A] block mb-1">{s.label}</label>
                                        <p className="text-xs text-[#6B7280] leading-relaxed">{s.description}</p>
                                    </div>
                                    <div className="md:col-span-2">
                                        {s.type === 'boolean' ? (
                                            <div className="flex items-center h-10">
                                                <Switch
                                                    checked={formData[s.key] === true || formData[s.key] === '1' || formData[s.key] === 1}
                                                    onCheckedChange={(checked) => setFormData(p => ({ ...p, [s.key]: checked }))}
                                                />
                                            </div>
                                        ) : s.key === 'payment_gateway_active' ? (
                                            <select
                                                value={formData[s.key] || 'paystack'}
                                                onChange={(e) => setFormData(p => ({ ...p, [s.key]: e.target.value }))}
                                                className="flex h-11 w-full rounded-xl border border-[#E5E7EB] bg-transparent px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#0B3C91] focus:border-transparent"
                                            >
                                                <option value="paystack">Paystack Only</option>
                                                <option value="flutterwave">Flutterwave Only</option>
                                                <option value="both">Both Gateways</option>
                                            </select>
                                        ) : (
                                            <Input
                                                type={s.type === 'encrypted_string' ? 'password' : 'text'}
                                                value={formData[s.key] || ''}
                                                onChange={(e) => setFormData(p => ({ ...p, [s.key]: e.target.value }))}
                                                className="rounded-xl border-[#E5E7EB] focus:ring-[#0B3C91] h-11"
                                            />
                                        )}
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>
                ))}
            </div>
        </div>
    );
}
