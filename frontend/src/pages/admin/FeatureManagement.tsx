import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import { featureService } from '@/services/api/featureService';
import { Shield, Zap, Settings, Loader2, Save } from 'lucide-react';
import { Button } from '@/components/ui/button';
import { Switch } from '@/components/ui/switch';
import { useToast } from '@/hooks/use-toast';

export default function AdminFeatureManagement() {
    const { toast } = useToast();
    const queryClient = useQueryClient();

    const { data: featuresRaw, isLoading } = useQuery({
        queryKey: ['admin-features'],
        queryFn: featureService.adminGetFeatures
    });
    const features = Array.isArray(featuresRaw) ? featuresRaw : [];

    const { data: platformsRaw, isLoading: loadingPlatform } = useQuery({
        queryKey: ['admin-platform-features'],
        queryFn: featureService.adminGetPlatformFeatures
    });
    const platformFeatures = Array.isArray(platformsRaw) ? platformsRaw : [];

    const updateFeatureMutation = useMutation({
        mutationFn: ({ id, is_premium }: { id: number, is_premium: boolean }) =>
            featureService.adminUpdateFeature(id, { is_premium }),
        onSuccess: () => {
            toast({ title: 'Feature status updated' });
            queryClient.invalidateQueries({ queryKey: ['admin-features'] });
            queryClient.invalidateQueries({ queryKey: ['features'] });
        }
    });

    const togglePlatformMutation = useMutation({
        mutationFn: ({ id, is_active, is_premium }: { id: number, is_active?: boolean, is_premium?: boolean }) =>
            featureService.adminTogglePlatformFeature(id, { is_active, is_premium }),
        onSuccess: () => {
            toast({ title: 'Platform release status updated' });
            queryClient.invalidateQueries({ queryKey: ['admin-platform-features'] });
            queryClient.invalidateQueries({ queryKey: ['platform-flags'] });
        }
    });

    if (isLoading || loadingPlatform) {
        return <div className="flex justify-center p-20"><Loader2 className="h-8 w-8 animate-spin text-[#0B3C91]" /></div>;
    }

    return (
        <div className="max-w-5xl mx-auto space-y-8">
            <div>
                <h1 className="text-3xl font-black text-[#1A1A1A] tracking-tight">Feature Management</h1>
                <p className="text-[#6B7280]">Toggle premium requirements for various platform features.</p>
            </div>

            <div className="grid grid-cols-1 gap-6">
                {features.map((feature: any) => (
                    <div key={feature.id} className="bg-white rounded-3xl border border-[#E5E7EB] shadow-sm p-8 flex items-center justify-between group hover:border-[#0B3C91]/30 transition-all duration-300">
                        <div className="flex items-center gap-6">
                            <div className={`h-14 w-14 rounded-2xl flex items-center justify-center shrink-0 ${feature.is_premium ? 'bg-amber-50 text-amber-600' : 'bg-green-50 text-green-600'}`}>
                                {feature.is_premium ? <Shield className="h-7 w-7" /> : <Zap className="h-7 w-7" />}
                            </div>
                            <div>
                                <h3 className="text-xl font-bold text-[#1A1A1A] group-hover:text-[#0B3C91] transition-colors">{feature.name}</h3>
                                <p className="text-sm text-[#6B7280] mt-1 max-w-lg">{feature.description}</p>
                                <div className="flex items-center gap-2 mt-2">
                                    <code className="text-[10px] font-black uppercase tracking-widest bg-slate-100 px-2 py-0.5 rounded text-slate-500">Slug: {feature.slug}</code>
                                </div>
                            </div>
                        </div>

                        <div className="flex flex-col items-end gap-3">
                            <div className="flex items-center gap-3 bg-slate-50 px-4 py-2 rounded-2xl border border-[#E5E7EB]">
                                <span className="text-xs font-bold text-[#1A1A1A]">Premium Required</span>
                                <Switch
                                    checked={feature.is_premium}
                                    onCheckedChange={(checked) => updateFeatureMutation.mutate({ id: feature.id, is_premium: checked })}
                                    disabled={updateFeatureMutation.isPending}
                                />
                            </div>
                            <span className={`text-[10px] font-black uppercase tracking-tighter ${feature.is_premium ? 'text-amber-600' : 'text-green-600'}`}>
                                Currently: {feature.is_premium ? 'Premium' : 'Free'}
                            </span>
                        </div>
                    </div>
                ))}
            </div>

            <div className="pt-8 border-t border-[#E5E7EB]">
                <h2 className="text-2xl font-black text-[#1A1A1A] tracking-tight mb-2">Platform Release Controls</h2>
                <p className="text-[#6B7280] mb-8">Enable or disable major system upgrades globally. No code deploy required.</p>

                <div className="grid grid-cols-1 gap-6">
                    {platformFeatures.map((feature: any) => (
                        <div key={feature.id} className="bg-slate-50 rounded-3xl border border-[#E5E7EB] p-8 flex items-center justify-between group hover:bg-white hover:border-[#0B3C91]/30 transition-all duration-300">
                            <div className="flex items-center gap-6">
                                <div className={`h-14 w-14 rounded-2xl flex items-center justify-center shrink-0 ${feature.is_active ? 'bg-blue-50 text-[#0B3C91]' : 'bg-slate-200 text-slate-400'}`}>
                                    <Settings className="h-7 w-7" />
                                </div>
                                <div>
                                    <h3 className="text-xl font-bold text-[#1A1A1A] group-hover:text-[#0B3C91] transition-colors">{feature.feature_name}</h3>
                                    <p className="text-sm text-[#6B7280] mt-1 max-w-lg">{feature.description}</p>
                                    <div className="flex items-center gap-2 mt-2">
                                        <code className="text-[10px] font-black uppercase tracking-widest bg-white px-2 py-0.5 rounded text-slate-500 border border-slate-100">KEY: {feature.feature_key}</code>
                                    </div>
                                </div>
                            </div>

                            <div className="flex flex-col items-end gap-3">
                                <div className="flex items-center gap-3 bg-white px-4 py-2 rounded-2xl border border-[#E5E7EB]">
                                    <span className="text-xs font-bold text-[#1A1A1A]">Premium Required</span>
                                    <Switch
                                        checked={feature.is_premium}
                                        onCheckedChange={(checked) => {
                                            togglePlatformMutation.mutate({ id: feature.id, is_premium: checked });
                                        }}
                                        disabled={togglePlatformMutation.isPending}
                                    />
                                </div>
                                <div className="flex items-center gap-3 bg-white px-4 py-2 rounded-2xl border border-[#E5E7EB]">
                                    <span className="text-xs font-bold text-[#1A1A1A]">Release Active</span>
                                    <Switch
                                        checked={feature.is_active}
                                        onCheckedChange={(checked) => {
                                            if (confirm(`Are you sure you want to ${checked ? 'ENABLE' : 'DISABLE'} this feature for all users?`)) {
                                                togglePlatformMutation.mutate({ id: feature.id, is_active: checked });
                                            }
                                        }}
                                        disabled={togglePlatformMutation.isPending}
                                    />
                                </div>
                                <div className="flex items-center gap-4">
                                    <span className={`text-[10px] font-black uppercase tracking-tighter ${feature.is_premium ? 'text-amber-600' : 'text-green-600'}`}>
                                        Pricing: {feature.is_premium ? 'PREMIUM' : 'FREE'}
                                    </span>
                                    <span className={`text-[10px] font-black uppercase tracking-tighter ${feature.is_active ? 'text-[#0B3C91]' : 'text-slate-400'}`}>
                                        Status: {feature.is_active ? 'LIVE' : 'DISABLED'}
                                    </span>
                                </div>
                            </div>
                        </div>
                    ))}
                </div>
            </div>

            <div className="bg-[#1A1A1A] rounded-3xl p-10 text-white relative overflow-hidden shadow-2xl">
                <div className="absolute top-[-20%] right-[-10%] w-[400px] h-[400px] bg-blue-500/10 rounded-full blur-[100px]" />
                <div className="relative z-10 flex flex-col md:flex-row items-center justify-between gap-8">
                    <div className="max-w-xl">
                        <h2 className="text-2xl font-bold mb-3">Understanding Feature Gating</h2>
                        <p className="text-slate-400 text-sm leading-relaxed">
                            When a feature is marked as **Premium**, users will need an active subscription to access it.
                            Admins always have full access regardless of these settings. Changes take effect immediately
                            for all users.
                        </p>
                    </div>
                    <Button variant="secondary" className="bg-white text-[#1A1A1A] hover:bg-white/90 rounded-xl h-12 font-bold px-8 border-none">
                        View Documentation
                    </Button>
                </div>
            </div>
        </div>
    );
}
