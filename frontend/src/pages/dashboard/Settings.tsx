import { useState, useEffect } from 'react';
import { useProfile, useUpdateProfile } from '@/hooks/useProfile';
import { useAuth } from '@/hooks/useAuth';
import { useCountries } from '@/hooks/useCountries';
import { useOccupations } from '@/hooks/useEmployability';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { useToast } from '@/hooks/use-toast';
import { useCurrency } from '@/contexts/CurrencyContext';
import { User, Shield, Bell, Globe, Loader2, Save, Lock, Mail, MapPin } from 'lucide-react';
import api from '@/lib/api';

export default function Settings() {
    const { toast } = useToast();
    const { user } = useAuth();
    const { data: profile, isLoading } = useProfile();
    const { data: countries } = useCountries();
    const { data: occupationsGroups } = useOccupations();
    const updateMutation = useUpdateProfile();
    const { currency, supported, setCurrency } = useCurrency();

    const [activeTab, setActiveTab] = useState('profile');
    const [profileForm, setProfileForm] = useState({
        age: '',
        education_level: '',
        preferred_country_id: '',
        work_experience_years: '',
        funds_range: '',
        ielts_status: '',
        occupation_id: '',
        email_notifications: true,
    });
    const [passwordForm, setPasswordForm] = useState({
        current_password: '',
        password: '',
        password_confirmation: '',
    });
    const [changingPassword, setChangingPassword] = useState(false);

    useEffect(() => {
        if (profile) {
            setProfileForm({
                age: profile.age?.toString() || '',
                education_level: profile.education_level || '',
                preferred_country_id: profile.preferred_country_id?.toString() || '',
                work_experience_years: profile.work_experience_years?.toString() || '',
                funds_range: profile.funds_range || '',
                ielts_status: profile.ielts_status || '',
                occupation_id: profile.occupation_id?.toString() || '',
                email_notifications: user?.email_notifications ?? true,
            });
        }
    }, [profile, user]);

    const handleProfileSave = () => {
        const payload = {
            ...profileForm,
            age: profileForm.age ? parseInt(profileForm.age) : null,
            work_experience_years: profileForm.work_experience_years ? parseInt(profileForm.work_experience_years) : null,
            preferred_country_id: profileForm.preferred_country_id ? parseInt(profileForm.preferred_country_id) : null,
            occupation_id: profileForm.occupation_id ? parseInt(profileForm.occupation_id) : null,
        };
        updateMutation.mutate(payload, {
            onSuccess: () => toast({ title: 'Profile updated successfully!' }),
            onError: () => toast({ title: 'Failed to update profile', variant: 'destructive' }),
        });
    };

    const handlePasswordChange = async () => {
        if (passwordForm.password !== passwordForm.password_confirmation) {
            toast({ title: 'Passwords do not match', variant: 'destructive' });
            return;
        }
        if (passwordForm.password.length < 8) {
            toast({ title: 'Password must be at least 8 characters', variant: 'destructive' });
            return;
        }
        setChangingPassword(true);
        try {
            await api.put('/api/v1/auth/password', passwordForm);
            toast({ title: 'Password changed successfully!' });
            setPasswordForm({ current_password: '', password: '', password_confirmation: '' });
        } catch (err: any) {
            toast({ title: err.response?.data?.message || 'Failed to change password', variant: 'destructive' });
        } finally {
            setChangingPassword(false);
        }
    };

    const tabs = [
        { id: 'profile', label: 'Profile', icon: User },
        { id: 'security', label: 'Security', icon: Shield },
        { id: 'preferences', label: 'Preferences', icon: Bell },
    ];

    if (isLoading) {
        return <div className="flex justify-center p-20"><Loader2 className="h-8 w-8 animate-spin text-[#0B3C91]" /></div>;
    }

    return (
        <div className="max-w-4xl mx-auto space-y-6">
            <div>
                <h1 className="text-3xl font-bold text-[#1A1A1A]">Settings</h1>
                <p className="text-[#6B7280] mt-1">Manage your account, profile, and preferences</p>
            </div>

            {/* Tab Navigation */}
            <div className="flex gap-1 bg-slate-100 rounded-xl p-1">
                {tabs.map(tab => (
                    <button
                        key={tab.id}
                        onClick={() => setActiveTab(tab.id)}
                        className={`flex items-center gap-2 px-4 py-2.5 rounded-lg text-sm font-bold transition-all flex-1 justify-center ${activeTab === tab.id
                            ? 'bg-white shadow-sm text-[#0B3C91]'
                            : 'text-slate-500 hover:text-slate-700'
                            }`}
                    >
                        <tab.icon className="w-4 h-4" />
                        {tab.label}
                    </button>
                ))}
            </div>

            {/* Profile Tab */}
            {activeTab === 'profile' && (
                <div className="bg-white rounded-2xl border border-[#E5E7EB] shadow-sm overflow-hidden">
                    {/* Account Info (Read-only) */}
                    <div className="px-8 py-6 border-b border-[#E5E7EB] bg-slate-50/50">
                        <h3 className="font-bold text-[#1A1A1A] flex items-center gap-2">
                            <Mail className="w-4 h-4 text-blue-500" /> Account Information
                        </h3>
                    </div>
                    <div className="p-8 space-y-4">
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div className="space-y-2">
                                <Label className="text-xs font-bold uppercase text-slate-400">Full Name</Label>
                                <Input value={user?.name || ''} disabled className="bg-slate-50 rounded-xl" />
                            </div>
                            <div className="space-y-2">
                                <Label className="text-xs font-bold uppercase text-slate-400">Email Address</Label>
                                <Input value={user?.email || ''} disabled className="bg-slate-50 rounded-xl" />
                            </div>
                        </div>
                    </div>

                    {/* Profile Details (Editable) */}
                    <div className="px-8 py-6 border-t border-b border-[#E5E7EB] bg-slate-50/50">
                        <h3 className="font-bold text-[#1A1A1A] flex items-center gap-2">
                            <MapPin className="w-4 h-4 text-green-500" /> Immigration Profile
                        </h3>
                    </div>
                    <div className="p-8 space-y-4">
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div className="space-y-2">
                                <Label className="text-xs font-bold uppercase text-slate-400">Occupation</Label>
                                <Select value={profileForm.occupation_id || undefined} onValueChange={v => setProfileForm({ ...profileForm, occupation_id: v })}>
                                    <SelectTrigger className="rounded-xl"><SelectValue placeholder="Select primary occupation" /></SelectTrigger>
                                    <SelectContent>
                                        {occupationsGroups ? (
                                            Object.entries(occupationsGroups).map(([category, occs]: [string, any]) => (
                                                <div key={category}>
                                                    <div className="px-2 py-1.5 text-xs font-bold text-slate-400 bg-slate-50 uppercase tracking-wider">{category}</div>
                                                    {occs.map((o: any) => (
                                                        <SelectItem key={o.id} value={o.id.toString()}>{o.name}</SelectItem>
                                                    ))}
                                                </div>
                                            ))
                                        ) : null}
                                    </SelectContent>
                                </Select>
                            </div>
                            <div className="space-y-2">
                                <Label className="text-xs font-bold uppercase text-slate-400">Age</Label>
                                <Input type="number" value={profileForm.age} onChange={e => setProfileForm({ ...profileForm, age: e.target.value })} placeholder="Enter your age" className="rounded-xl" />
                            </div>
                            <div className="space-y-2">
                                <Label className="text-xs font-bold uppercase text-slate-400">Education Level</Label>
                                <Select value={profileForm.education_level || undefined} onValueChange={v => setProfileForm({ ...profileForm, education_level: v })}>
                                    <SelectTrigger className="rounded-xl"><SelectValue placeholder="Select level" /></SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="high_school">High School</SelectItem>
                                        <SelectItem value="bachelors">Bachelor's Degree</SelectItem>
                                        <SelectItem value="masters">Master's Degree</SelectItem>
                                        <SelectItem value="phd">PhD / Doctorate</SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>
                            <div className="space-y-2">
                                <Label className="text-xs font-bold uppercase text-slate-400">Work Experience (years)</Label>
                                <Input type="number" value={profileForm.work_experience_years} onChange={e => setProfileForm({ ...profileForm, work_experience_years: e.target.value })} placeholder="Years of experience" className="rounded-xl" />
                            </div>
                            <div className="space-y-2">
                                <Label className="text-xs font-bold uppercase text-slate-400">Preferred Country</Label>
                                <Select value={profileForm.preferred_country_id || undefined} onValueChange={v => setProfileForm({ ...profileForm, preferred_country_id: v })}>
                                    <SelectTrigger className="rounded-xl"><SelectValue placeholder="Select country" /></SelectTrigger>
                                    <SelectContent>
                                        {countries?.map((c: any) => (
                                            <SelectItem key={c.id} value={c.id.toString()}>{c.name}</SelectItem>
                                        ))}
                                    </SelectContent>
                                </Select>
                            </div>
                            <div className="space-y-2">
                                <Label className="text-xs font-bold uppercase text-slate-400">Available Funds</Label>
                                <Select value={profileForm.funds_range || undefined} onValueChange={v => setProfileForm({ ...profileForm, funds_range: v })}>
                                    <SelectTrigger className="rounded-xl"><SelectValue placeholder="Select range" /></SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="under_5k">Under $5,000</SelectItem>
                                        <SelectItem value="5k_15k">$5,000 – $15,000</SelectItem>
                                        <SelectItem value="15k_30k">$15,000 – $30,000</SelectItem>
                                        <SelectItem value="30k_50k">$30,000 – $50,000</SelectItem>
                                        <SelectItem value="over_50k">Over $50,000</SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>
                            <div className="space-y-2">
                                <Label className="text-xs font-bold uppercase text-slate-400">IELTS / Language Status</Label>
                                <Select value={profileForm.ielts_status || undefined} onValueChange={v => setProfileForm({ ...profileForm, ielts_status: v })}>
                                    <SelectTrigger className="rounded-xl"><SelectValue placeholder="Select status" /></SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="not_taken">Not Taken</SelectItem>
                                        <SelectItem value="preparing">Preparing</SelectItem>
                                        <SelectItem value="taken">Taken (Score Available)</SelectItem>
                                        <SelectItem value="not_required">Not Required</SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>
                        </div>

                        <div className="pt-4">
                            <Button onClick={handleProfileSave} disabled={updateMutation.isPending} className="bg-[#0B3C91] hover:bg-[#0A2A66] text-white rounded-xl font-bold px-8">
                                {updateMutation.isPending ? <Loader2 className="w-4 h-4 animate-spin mr-2" /> : <Save className="w-4 h-4 mr-2" />}
                                Save Changes
                            </Button>
                        </div>
                    </div>
                </div>
            )}

            {/* Security Tab */}
            {activeTab === 'security' && (
                <div className="bg-white rounded-2xl border border-[#E5E7EB] shadow-sm overflow-hidden">
                    <div className="px-8 py-6 border-b border-[#E5E7EB] bg-slate-50/50">
                        <h3 className="font-bold text-[#1A1A1A] flex items-center gap-2">
                            <Lock className="w-4 h-4 text-red-500" /> Change Password
                        </h3>
                    </div>
                    <div className="p-8 space-y-4">
                        <div className="space-y-2">
                            <Label className="text-xs font-bold uppercase text-slate-400">Current Password</Label>
                            <Input type="password" value={passwordForm.current_password} onChange={e => setPasswordForm({ ...passwordForm, current_password: e.target.value })} placeholder="Enter current password" className="rounded-xl" />
                        </div>
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div className="space-y-2">
                                <Label className="text-xs font-bold uppercase text-slate-400">New Password</Label>
                                <Input type="password" value={passwordForm.password} onChange={e => setPasswordForm({ ...passwordForm, password: e.target.value })} placeholder="Minimum 8 characters" className="rounded-xl" />
                            </div>
                            <div className="space-y-2">
                                <Label className="text-xs font-bold uppercase text-slate-400">Confirm New Password</Label>
                                <Input type="password" value={passwordForm.password_confirmation} onChange={e => setPasswordForm({ ...passwordForm, password_confirmation: e.target.value })} placeholder="Confirm new password" className="rounded-xl" />
                            </div>
                        </div>
                        <div className="pt-4">
                            <Button onClick={handlePasswordChange} disabled={changingPassword || !passwordForm.current_password || !passwordForm.password} className="bg-red-500 hover:bg-red-600 text-white rounded-xl font-bold px-8">
                                {changingPassword ? <Loader2 className="w-4 h-4 animate-spin mr-2" /> : <Shield className="w-4 h-4 mr-2" />}
                                Update Password
                            </Button>
                        </div>
                    </div>

                    <div className="px-8 py-6 border-t border-[#E5E7EB] bg-slate-50/30">
                        <div className="flex items-start gap-4">
                            <div className="h-10 w-10 rounded-xl bg-green-50 flex items-center justify-center shrink-0">
                                <Shield className="w-5 h-5 text-green-600" />
                            </div>
                            <div>
                                <h4 className="font-bold text-sm text-slate-900">Account Security</h4>
                                <p className="text-xs text-slate-500 mt-0.5">Your account is protected with encrypted password storage. We recommend changing your password every 90 days.</p>
                            </div>
                        </div>
                    </div>
                </div>
            )}

            {/* Preferences Tab */}
            {activeTab === 'preferences' && (
                <div className="space-y-6">
                    <div className="bg-white rounded-2xl border border-[#E5E7EB] shadow-sm overflow-hidden">
                        <div className="px-8 py-6 border-b border-[#E5E7EB] bg-slate-50/50">
                            <h3 className="font-bold text-[#1A1A1A] flex items-center gap-2">
                                <Bell className="w-4 h-4 text-amber-500" /> Notification Preferences
                            </h3>
                        </div>
                        <div className="divide-y divide-slate-100">
                            <div className="px-8 py-5 flex items-center justify-between">
                                <div>
                                    <p className="font-semibold text-sm text-slate-900">Email Notifications (Action Engine)</p>
                                    <p className="text-xs text-slate-400 mt-0.5">Receive helpful reminders to advance your timeline.</p>
                                </div>
                                <div
                                    onClick={() => {
                                        const newVal = !profileForm.email_notifications;
                                        setProfileForm({ ...profileForm, email_notifications: newVal });
                                        // Save immediately
                                        updateMutation.mutate({ email_notifications: newVal });
                                    }}
                                    className={`w-10 h-6 rounded-full cursor-pointer transition-colors flex items-center px-0.5 ${profileForm.email_notifications ? 'bg-[#0B3C91] justify-end' : 'bg-slate-200 justify-start'}`}
                                >
                                    <div className="w-5 h-5 bg-white rounded-full shadow-sm pointer-events-none" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div className="bg-white rounded-2xl border border-[#E5E7EB] shadow-sm overflow-hidden">
                        <div className="px-8 py-6 border-b border-[#E5E7EB] bg-slate-50/50">
                            <h3 className="font-bold text-[#1A1A1A] flex items-center gap-2">
                                <Globe className="w-4 h-4 text-blue-500" /> Display Settings
                            </h3>
                        </div>
                        <div className="p-8 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div className="space-y-2">
                                <Label className="text-xs font-bold uppercase text-slate-400">Currency Display</Label>
                                <Select value={currency} onValueChange={setCurrency}>
                                    <SelectTrigger className="rounded-xl"><SelectValue /></SelectTrigger>
                                    <SelectContent>
                                        {Object.entries(supported).map(([code, data]) => (
                                            <SelectItem key={code} value={code}>{code} ({data.symbol})</SelectItem>
                                        ))}
                                    </SelectContent>
                                </Select>
                            </div>
                            <div className="space-y-2">
                                <Label className="text-xs font-bold uppercase text-slate-400">Language</Label>
                                <Select defaultValue="en">
                                    <SelectTrigger className="rounded-xl"><SelectValue /></SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="en">English</SelectItem>
                                        <SelectItem value="fr">French</SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>
                        </div>
                    </div>
                </div>
            )}
        </div>
    );
}
