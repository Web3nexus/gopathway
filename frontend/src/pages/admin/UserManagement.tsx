import { useState } from 'react';
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import { adminService } from '@/services/api/adminService';
import {
    Users,
    ShieldCheck,
    LogIn,
    MoreHorizontal,
    Search,
    Mail,
    Loader2,
    ShieldAlert,
    Crown,
    UserCog,
    Briefcase
} from 'lucide-react';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow
} from '@/components/ui/table';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Badge } from '@/components/ui/badge';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogHeader,
    DialogTitle,
    DialogFooter,
} from '@/components/ui/dialog';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { useToast } from '@/hooks/use-toast';

type TabType = 'all' | 'user' | 'admin' | 'lawyer' | 'translator';

const TABS: { key: TabType; label: string; icon: any }[] = [
    { key: 'all', label: 'All Accounts', icon: Users },
    { key: 'user', label: 'Regular Users', icon: UserCog },
    { key: 'admin', label: 'Admins', icon: ShieldCheck },
    { key: 'lawyer', label: 'Lawyers', icon: Briefcase },
    { key: 'translator', label: 'Translators', icon: Briefcase },
];

function getRoleType(user: any): TabType {
    const roles = Array.isArray(user.roles) ? user.roles.map((r: any) => r.name) : [];
    if (roles.includes('admin')) return 'admin';
    if (roles.includes('lawyer')) return 'lawyer';
    if (roles.includes('translator')) return 'translator';
    return 'user';
}

export default function UserManagement() {
    const { toast } = useToast();
    const queryClient = useQueryClient();
    const [search, setSearch] = useState('');
    const [activeTab, setActiveTab] = useState<TabType>('all');
    const [selectedUser, setSelectedUser] = useState<any>(null);
    const [accessModalOpen, setAccessModalOpen] = useState(false);
    const [duration, setDuration] = useState('30');

    const { data, isLoading } = useQuery({
        queryKey: ['admin-users', activeTab, search],
        queryFn: () => adminService.getUsers({
            role: activeTab === 'all' ? undefined : activeTab,
            search: search || undefined
        }),
    });

    const grantPremiumMutation = useMutation({
        mutationFn: (data: { userId: number; days: number | 'lifetime' }) =>
            adminService.grantPremium(data.userId, data.days),
        onSuccess: (res: any) => {
            toast({ title: 'Success', description: res.message });
            setAccessModalOpen(false);
            queryClient.invalidateQueries({ queryKey: ['admin-users'] });
        },
        onError: (err: any) => toast({
            title: 'Failed',
            description: err.response?.data?.message || 'Could not grant premium.',
            variant: 'destructive',
        }),
    });

    const removePremiumMutation = useMutation({
        mutationFn: (userId: number) => adminService.removePremium(userId),
        onSuccess: (res: any) => {
            toast({ title: 'Access Revoked', description: res.message });
            setAccessModalOpen(false);
            queryClient.invalidateQueries({ queryKey: ['admin-users'] });
        },
        onError: (err: any) => toast({
            title: 'Failed',
            description: err.response?.data?.message || 'Could not remove premium.',
            variant: 'destructive',
        }),
    });

    const impersonateMutation = useMutation({
        mutationFn: adminService.impersonate,
        onSuccess: (res: any) => {
            toast({ title: 'Impersonation started', description: res.message });
            // Session is now handled by the backend (auth:sanctum session)
            // We just need to reload to pick up the new user state
            window.location.href = '/dashboard';
        },
        onError: (err: any) => toast({
            title: 'Impersonation failed',
            description: err.response?.data?.message || 'Something went wrong.',
            variant: 'destructive',
        }),
    });

    const allUsers: any[] = Array.isArray(data) ? data : [];

    if (isLoading) {
        return (
            <div className="flex flex-col items-center justify-center p-20 space-y-4">
                <Loader2 className="h-10 w-10 animate-spin text-[#0B3C91]" />
                <p className="text-slate-500 font-medium">Loading user database...</p>
            </div>
        );
    }

    return (
        <div className="space-y-6">
            {/* Header */}
            <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 className="text-2xl font-bold text-[#1A1A1A]">User Management</h1>
                    <p className="text-[#6B7280]">Oversee accounts, manage tiers, and assist users via impersonation</p>
                </div>
                <div className="relative">
                    <Search className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-400" />
                    <Input
                        placeholder="Search users..."
                        className="pl-10 w-64 rounded-xl border-slate-200"
                        value={search}
                        onChange={(e) => setSearch(e.target.value)}
                    />
                </div>
            </div>

            {/* Role Tabs */}
            <div className="flex flex-wrap gap-2 border-b border-slate-200 pb-0">
                {TABS.map(tab => {
                    const Icon = tab.icon;
                    const isActive = activeTab === tab.key;
                    return (
                        <button
                            key={tab.key}
                            onClick={() => setActiveTab(tab.key)}
                            className={`flex items-center gap-2 px-4 py-2.5 text-sm font-medium rounded-t-xl transition-all border border-b-0 ${isActive
                                ? 'bg-white text-[#0B3C91] border-slate-200 shadow-sm -mb-px z-10'
                                : 'bg-slate-50 text-slate-500 border-transparent hover:text-slate-700 hover:bg-slate-100'
                                }`}
                        >
                            <Icon className="h-4 w-4" />
                            {tab.label}
                        </button>
                    );
                })}
            </div>

            {/* Table */}
            <div className="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <Table>
                    <TableHeader className="bg-slate-50/50">
                        <TableRow>
                            <TableHead className="w-[280px]">User</TableHead>
                            <TableHead>Role</TableHead>
                            <TableHead>Tier Status</TableHead>
                            <TableHead>Joined</TableHead>
                            <TableHead className="text-right">Actions</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        {allUsers.map((user: any) => {
                            const isPremium = user.subscriptions?.some((s: any) => s.status === 'active');
                            const roleType = getRoleType(user);

                            return (
                                <TableRow key={user.id} className="hover:bg-slate-50/50 transition-colors">
                                    <TableCell>
                                        <div className="flex items-center gap-3">
                                            <div className="h-10 w-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 font-bold shrink-0 uppercase">
                                                {user.name?.charAt(0) || '?'}
                                            </div>
                                            <div className="min-w-0">
                                                <p className="text-sm font-bold text-slate-900 truncate">{user.name}</p>
                                                <div className="flex items-center gap-1 text-xs text-slate-500">
                                                    <Mail className="h-3 w-3 flex-shrink-0" />
                                                    <span className="truncate">{user.email}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </TableCell>
                                    <TableCell>
                                        {roleType === 'admin' ? (
                                            <Badge variant="outline" className="bg-purple-50 text-purple-700 border-purple-100 gap-1">
                                                <ShieldCheck className="h-3 w-3" /> Admin
                                            </Badge>
                                        ) : roleType === 'lawyer' ? (
                                            <Badge variant="outline" className="bg-blue-50 text-blue-700 border-blue-100 gap-1">
                                                <Briefcase className="h-3 w-3" /> Lawyer
                                            </Badge>
                                        ) : roleType === 'translator' ? (
                                            <Badge variant="outline" className="bg-emerald-50 text-emerald-700 border-emerald-100 gap-1">
                                                <Briefcase className="h-3 w-3" /> Translator
                                            </Badge>
                                        ) : (
                                            <Badge variant="outline" className="bg-slate-50 text-slate-600 border-slate-100">
                                                User
                                            </Badge>
                                        )}
                                    </TableCell>
                                    <TableCell>
                                        {isPremium ? (
                                            <div className="flex items-center gap-1.5 text-green-600 font-semibold text-xs border border-green-100 bg-green-50 px-2.5 py-1 rounded-full w-fit">
                                                <Crown className="h-3 w-3" /> Premium
                                            </div>
                                        ) : (
                                            <div className="text-slate-400 font-medium text-xs border border-slate-100 bg-slate-50/50 px-2.5 py-1 rounded-full w-fit">
                                                Free Tier
                                            </div>
                                        )}
                                    </TableCell>
                                    <TableCell className="text-sm text-slate-500">
                                        {new Date(user.created_at).toLocaleDateString()}
                                    </TableCell>
                                    <TableCell className="text-right">
                                        <DropdownMenu>
                                            <DropdownMenuTrigger asChild>
                                                <Button variant="ghost" size="sm" className="h-8 w-8 p-0">
                                                    <MoreHorizontal className="h-4 w-4" />
                                                </Button>
                                            </DropdownMenuTrigger>
                                            <DropdownMenuContent align="end" className="w-52 rounded-xl p-1.5">
                                                {!isPremium && roleType !== 'admin' && (
                                                    <DropdownMenuItem
                                                        className="rounded-lg gap-2 text-green-600 font-medium focus:text-green-600 focus:bg-green-50"
                                                        onClick={() => {
                                                            setSelectedUser(user);
                                                            setAccessModalOpen(true);
                                                        }}
                                                    >
                                                        <Crown className="h-4 w-4" />
                                                        Grant Premium
                                                    </DropdownMenuItem>
                                                )}
                                                {roleType !== 'admin' && (
                                                    <DropdownMenuItem
                                                        className="rounded-lg gap-2"
                                                        onClick={() => impersonateMutation.mutate(user.id)}
                                                        disabled={impersonateMutation.isPending}
                                                    >
                                                        <LogIn className="h-4 w-4" />
                                                        {impersonateMutation.isPending ? 'Starting…' : 'Impersonate'}
                                                    </DropdownMenuItem>
                                                )}
                                                <DropdownMenuItem
                                                    className="rounded-lg gap-2 text-red-600 focus:text-red-600 focus:bg-red-50"
                                                    onClick={() => {
                                                        setSelectedUser(user);
                                                        setAccessModalOpen(true);
                                                    }}
                                                >
                                                    <ShieldAlert className="h-4 w-4" /> Manage Access
                                                </DropdownMenuItem>
                                            </DropdownMenuContent>
                                        </DropdownMenu>
                                    </TableCell>
                                </TableRow>
                            );
                        })}
                    </TableBody>
                </Table>

                {allUsers.length === 0 && (
                    <div className="p-12 text-center">
                        <Users className="h-12 w-12 text-slate-200 mx-auto mb-3" />
                        <p className="text-slate-500 font-medium">No users found{search ? ' matching your search' : ' in this category'}.</p>
                    </div>
                )}

                {/* Manage Access Modal */}
                <Dialog open={accessModalOpen} onOpenChange={setAccessModalOpen}>
                    <DialogContent className="rounded-3xl max-w-md">
                        <DialogHeader>
                            <DialogTitle className="text-2xl font-black flex items-center gap-2">
                                <ShieldAlert className="h-6 w-6 text-[#0B3C91]" />
                                Manage Access
                            </DialogTitle>
                            <DialogDescription>
                                Modify subscription status for <strong>{selectedUser?.name}</strong>.
                            </DialogDescription>
                        </DialogHeader>

                        <div className="py-6 space-y-6">
                            {selectedUser?.subscriptions?.some((s: any) => s.status === 'active') ? (
                                <div className="bg-red-50 border border-red-100 p-4 rounded-2xl">
                                    <h4 className="text-red-800 font-bold mb-1">Active Premium Subscription</h4>
                                    <p className="text-red-600 text-sm mb-4">This user currently has an active premium plan. revoking access will instantly return them to the free tier.</p>
                                    <Button
                                        variant="destructive"
                                        className="w-full rounded-xl font-bold"
                                        onClick={() => removePremiumMutation.mutate(selectedUser.id)}
                                        disabled={removePremiumMutation.isPending}
                                    >
                                        {removePremiumMutation.isPending ? <Loader2 className="h-4 w-4 animate-spin mr-2" /> : <ShieldAlert className="h-4 w-4 mr-2" />}
                                        Revoke Premium Access
                                    </Button>
                                </div>
                            ) : (
                                <div className="space-y-4">
                                    <div className="space-y-2">
                                        <label className="text-sm font-bold text-slate-700">Premium Duration</label>
                                        <Select value={duration} onValueChange={setDuration}>
                                            <SelectTrigger className="rounded-xl border-slate-200 h-12">
                                                <SelectValue placeholder="Select duration" />
                                            </SelectTrigger>
                                            <SelectContent className="rounded-xl">
                                                <SelectItem value="7">7 Days (Trial)</SelectItem>
                                                <SelectItem value="30">30 Days (Standard)</SelectItem>
                                                <SelectItem value="90">90 Days (Quarterly)</SelectItem>
                                                <SelectItem value="365">365 Days (Annual)</SelectItem>
                                                <SelectItem value="lifetime">Lifetime Access</SelectItem>
                                            </SelectContent>
                                        </Select>
                                    </div>
                                    <Button
                                        className="w-full h-12 bg-[#0B3C91] hover:bg-[#0A2A66] rounded-xl font-bold gap-2"
                                        onClick={() => grantPremiumMutation.mutate({
                                            userId: selectedUser.id,
                                            days: duration === 'lifetime' ? 'lifetime' : parseInt(duration)
                                        })}
                                        disabled={grantPremiumMutation.isPending}
                                    >
                                        {grantPremiumMutation.isPending ? <Loader2 className="h-4 w-4 animate-spin" /> : <Crown className="h-4 w-4" />}
                                        Grant Premium Access
                                    </Button>
                                </div>
                            )}
                        </div>

                        <DialogFooter>
                            <Button variant="ghost" className="rounded-xl font-bold" onClick={() => setAccessModalOpen(false)}>
                                Cancel
                            </Button>
                        </DialogFooter>
                    </DialogContent>
                </Dialog>
            </div>

            {/* Security footer */}
            <div className="bg-[#0B3C91]/5 border border-[#0B3C91]/10 rounded-2xl p-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div className="flex items-center gap-4">
                    <div className="h-12 w-12 bg-[#0B3C91] rounded-xl flex items-center justify-center shrink-0">
                        <ShieldCheck className="h-6 w-6 text-white" />
                    </div>
                    <div>
                        <p className="font-bold text-[#1A1A1A]">Admin Security Protocol</p>
                        <p className="text-sm text-[#6B7280]">All impersonation sessions and manual tier overrides are logged for auditing.</p>
                    </div>
                </div>
                <Button className="bg-[#0B3C91] hover:bg-[#0A2A66] rounded-xl font-bold">
                    Export User Audit
                </Button>
            </div>
        </div>
    );
}
