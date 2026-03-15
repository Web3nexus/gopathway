import { useState, useEffect } from 'react';
import { useAuth } from '@/hooks/useAuth';
import { adminService } from '@/services/api/adminService';
import { useMutation, useQuery, useQueryClient } from '@tanstack/react-query';
import { useToast } from '@/hooks/use-toast';
import { 
    User, 
    Lock, 
    ShieldCheck, 
    ShieldAlert, 
    Loader2, 
    Save, 
    QrCode, 
    CheckCircle2, 
    XCircle 
} from 'lucide-react';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Card, CardHeader, CardTitle, CardDescription, CardContent, CardFooter } from '@/components/ui/card';
import { 
    Dialog, 
    DialogContent, 
    DialogHeader, 
    DialogTitle, 
    DialogDescription,
    DialogFooter
} from '@/components/ui/dialog';

export default function AdminProfile() {
    const { user } = useAuth();
    const { toast } = useToast();
    const queryClient = useQueryClient();
    const [name, setName] = useState(user?.name || '');
    const [email, setEmail] = useState(user?.email || '');
    const [currentPassword, setCurrentPassword] = useState('');
    const [newPassword, setNewPassword] = useState('');
    const [confirmPassword, setConfirmPassword] = useState('');
    
    // 2FA State
    const [show2FASetup, setShow2FASetup] = useState(false);
    const [otpCode, setOtpCode] = useState('');
    const [setupData, setSetupData] = useState<any>(null);
    const [disable2FAModal, setDisable2FAModal] = useState(false);
    const [disablePassword, setDisablePassword] = useState('');

    useEffect(() => {
        if (user) {
            setName(user.name);
            setEmail(user.email);
        }
    }, [user]);

    const profileMutation = useMutation({
        mutationFn: adminService.updateAdminProfile,
        onSuccess: () => {
            toast({ title: 'Profile updated successfully' });
            queryClient.invalidateQueries({ queryKey: ['user'] });
        },
        onError: (err: any) => {
            toast({ 
                title: 'Update failed', 
                description: err.response?.data?.message || 'Something went wrong',
                variant: 'destructive' 
            });
        }
    });

    const passwordMutation = useMutation({
        mutationFn: (data: any) => adminService.updatePassword(data), // We need to check if updatePassword exists or use changePassword
        onSuccess: () => {
            toast({ title: 'Password changed successfully' });
            setCurrentPassword('');
            setNewPassword('');
            setConfirmPassword('');
        },
        onError: (err: any) => {
            toast({ 
                title: 'Change failed', 
                description: err.response?.data?.message || 'Something went wrong',
                variant: 'destructive' 
            });
        }
    });

    const setup2FAMutation = useMutation({
        mutationFn: adminService.get2FASetup,
        onSuccess: (data) => {
            setSetupData(data);
            setShow2FASetup(true);
        }
    });

    const enable2FAMutation = useMutation({
        mutationFn: adminService.enable2FA,
        onSuccess: () => {
            toast({ title: '2FA enabled successfully' });
            setShow2FASetup(false);
            setOtpCode('');
            queryClient.invalidateQueries({ queryKey: ['user'] });
        },
        onError: (err: any) => {
            toast({ 
                title: 'Verification failed', 
                description: err.response?.data?.message || 'Invalid code',
                variant: 'destructive' 
            });
        }
    });

    const disable2FAMutation = useMutation({
        mutationFn: adminService.disable2FA,
        onSuccess: () => {
            toast({ title: '2FA disabled successfully' });
            setDisable2FAModal(false);
            setDisablePassword('');
            queryClient.invalidateQueries({ queryKey: ['user'] });
        },
        onError: (err: any) => {
            toast({ 
                title: 'Disable failed', 
                description: err.response?.data?.message || 'Invalid password',
                variant: 'destructive' 
            });
        }
    });

    const handleProfileSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        profileMutation.mutate({ name, email });
    };

    const handlePasswordSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        if (newPassword !== confirmPassword) {
            return toast({ title: 'Passwords do not match', variant: 'destructive' });
        }
        passwordMutation.mutate({
            current_password: currentPassword,
            password: newPassword,
            password_confirmation: confirmPassword
        });
    };

    return (
        <div className="max-w-4xl mx-auto space-y-8 p-6">
            <div className="flex items-center justify-between">
                <div>
                    <h1 className="text-3xl font-black text-[#1A1A1A] tracking-tight">My Profile</h1>
                    <p className="text-[#6B7280]">Manage your admin account details and security settings.</p>
                </div>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
                {/* Profile Information */}
                <Card className="rounded-3xl border-[#E5E7EB] shadow-sm">
                    <CardHeader>
                        <CardTitle className="flex items-center gap-2">
                            <User className="h-5 w-5 text-[#0B3C91]" />
                            Public Details
                        </CardTitle>
                        <CardDescription>Update your name and contact email.</CardDescription>
                    </CardHeader>
                    <form onSubmit={handleProfileSubmit}>
                        <CardContent className="space-y-4">
                            <div className="space-y-2">
                                <label className="text-sm font-bold">Full Name</label>
                                <Input 
                                    value={name} 
                                    onChange={(e) => setName(e.target.value)} 
                                    className="rounded-xl h-11 border-[#E5E7EB]"
                                />
                            </div>
                            <div className="space-y-2">
                                <label className="text-sm font-bold">Email Address</label>
                                <Input 
                                    type="email" 
                                    value={email} 
                                    onChange={(e) => setEmail(e.target.value)} 
                                    className="rounded-xl h-11 border-[#E5E7EB]"
                                />
                            </div>
                        </CardContent>
                        <CardFooter>
                            <Button 
                                type="submit" 
                                className="w-full bg-[#0B3C91] hover:bg-[#0B3C91]/90 rounded-xl font-bold h-11 gap-2"
                                disabled={profileMutation.isPending}
                            >
                                {profileMutation.isPending ? <Loader2 className="h-4 w-4 animate-spin" /> : <Save className="h-4 w-4" />}
                                Update Profile
                            </Button>
                        </CardFooter>
                    </form>
                </Card>

                {/* 2FA Security */}
                <Card className="rounded-3xl border-[#E5E7EB] shadow-sm overflow-hidden">
                    <CardHeader className={user?.two_factor_enabled ? 'bg-green-50/50' : 'bg-amber-50/50'}>
                        <CardTitle className="flex items-center gap-2">
                            <ShieldCheck className="h-5 w-5 text-[#0B3C91]" />
                            Two-Factor Authentication
                        </CardTitle>
                        <CardDescription>
                            {user?.two_factor_enabled 
                                ? 'Your account is highly secure with 2FA enabled.' 
                                : 'Add an extra layer of security to your admin account.'}
                        </CardDescription>
                    </CardHeader>
                    <CardContent className="pt-6">
                        <div className="flex flex-col items-center text-center space-y-4">
                            {user?.two_factor_enabled ? (
                                <>
                                    <div className="h-16 w-16 bg-green-100 rounded-full flex items-center justify-center text-green-600">
                                        <CheckCircle2 className="h-8 w-8" />
                                    </div>
                                    <div>
                                        <p className="font-bold text-green-700">Authenticator Active</p>
                                        <p className="text-xs text-slate-500 max-w-[200px] mt-1">
                                            Sensitive actions now require a one-time verification code.
                                        </p>
                                    </div>
                                    <Button 
                                        variant="outline" 
                                        className="rounded-xl text-red-600 hover:text-red-700 border-red-100 hover:bg-red-50 font-bold"
                                        onClick={() => setDisable2FAModal(true)}
                                    >
                                        Disable 2FA
                                    </Button>
                                </>
                            ) : (
                                <>
                                    <div className="h-16 w-16 bg-amber-100 rounded-full flex items-center justify-center text-amber-600">
                                        <ShieldAlert className="h-8 w-8" />
                                    </div>
                                    <div>
                                        <p className="font-bold text-amber-700">Highly Recommended</p>
                                        <p className="text-xs text-slate-500 max-w-[200px] mt-1">
                                            Protect your account from unauthorized access by enabling TOTP 2FA.
                                        </p>
                                    </div>
                                    <Button 
                                        className="rounded-xl bg-[#0B3C91] hover:bg-[#0B3C91]/90 font-bold h-11 gap-2"
                                        onClick={() => setup2FAMutation.mutate()}
                                        disabled={setup2FAMutation.isPending}
                                    >
                                        {setup2FAMutation.isPending ? <Loader2 className="h-4 w-4 animate-spin" /> : <QrCode className="h-4 w-4" />}
                                        Setup Authenticator
                                    </Button>
                                </>
                            )}
                        </div>
                    </CardContent>
                </Card>

                {/* Password Change */}
                <Card className="rounded-3xl border-[#E5E7EB] shadow-sm md:col-span-2">
                    <CardHeader>
                        <CardTitle className="flex items-center gap-2">
                            <Lock className="h-5 w-5 text-[#0B3C91]" />
                            Change Password
                        </CardTitle>
                        <CardDescription>Update your account security password.</CardDescription>
                    </CardHeader>
                    <form onSubmit={handlePasswordSubmit}>
                        <CardContent className="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div className="space-y-2">
                                <label className="text-sm font-bold">Current Password</label>
                                <Input 
                                    type="password" 
                                    value={currentPassword} 
                                    onChange={(e) => setCurrentPassword(e.target.value)} 
                                    className="rounded-xl h-11 border-[#E5E7EB]"
                                />
                            </div>
                            <div className="space-y-2">
                                <label className="text-sm font-bold">New Password</label>
                                <Input 
                                    type="password" 
                                    value={newPassword} 
                                    onChange={(e) => setNewPassword(e.target.value)} 
                                    className="rounded-xl h-11 border-[#E5E7EB]"
                                />
                            </div>
                            <div className="space-y-2">
                                <label className="text-sm font-bold">Confirm New Password</label>
                                <Input 
                                    type="password" 
                                    value={confirmPassword} 
                                    onChange={(e) => setConfirmPassword(e.target.value)} 
                                    className="rounded-xl h-11 border-[#E5E7EB]"
                                />
                            </div>
                        </CardContent>
                        <CardFooter>
                            <Button 
                                type="submit" 
                                className="w-full bg-[#0B3C91] hover:bg-[#0B3C91]/90 rounded-xl font-bold h-11 gap-2"
                                disabled={passwordMutation.isPending}
                            >
                                {passwordMutation.isPending ? <Loader2 className="h-4 w-4 animate-spin" /> : <ShieldCheck className="h-4 w-4" />}
                                Update Password
                            </Button>
                        </CardFooter>
                    </form>
                </Card>
            </div>

            {/* 2FA Setup Dialog */}
            <Dialog open={show2FASetup} onOpenChange={setShow2FASetup}>
                <DialogContent className="rounded-3xl max-w-sm">
                    <DialogHeader>
                        <DialogTitle className="text-2xl font-black">Enable 2FA</DialogTitle>
                        <DialogDescription>
                            Scan this QR code with your Authenticator app (Google Authenticator, Authy, etc.)
                        </DialogDescription>
                    </DialogHeader>
                    
                    <div className="flex flex-col items-center space-y-6 py-4">
                        {setupData?.qr_code_url && (
                            <div className="bg-white p-4 rounded-2xl border-2 border-[#E5E7EB]">
                                <img src={`https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=${encodeURIComponent(setupData.qr_code_url)}`} alt="2FA QR Code" />
                            </div>
                        )}
                        
                        <div className="text-center">
                            <p className="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Or enter manual key</p>
                            <code className="bg-slate-100 px-3 py-1.5 rounded-lg text-sm font-mono font-bold text-[#0B3C91]">
                                {setupData?.secret}
                            </code>
                        </div>

                        <div className="w-full space-y-2 pt-4 border-t border-slate-100">
                            <label className="text-sm font-bold">Verification Code</label>
                            <Input 
                                placeholder="000000" 
                                value={otpCode}
                                onChange={(e) => setOtpCode(e.target.value)}
                                className="text-center text-2xl tracking-[0.5em] font-black h-14 rounded-xl border-[#E5E7EB] focus:ring-[#0B3C91]"
                                maxLength={6}
                            />
                        </div>
                    </div>

                    <DialogFooter>
                        <Button 
                            className="w-full bg-[#0B3C91] h-12 rounded-xl font-bold"
                            onClick={() => enable2FAMutation.mutate(otpCode)}
                            disabled={enable2FAMutation.isPending}
                        >
                            {enable2FAMutation.isPending ? <Loader2 className="h-4 w-4 animate-spin" /> : 'Confirm & Enable'}
                        </Button>
                    </DialogFooter>
                </DialogContent>
            </Dialog>

            {/* Disable 2FA Modal */}
            <Dialog open={disable2FAModal} onOpenChange={setDisable2FAModal}>
                <DialogContent className="rounded-3xl max-w-sm">
                    <DialogHeader>
                        <DialogTitle className="text-xl font-black">Disable 2FA</DialogTitle>
                        <DialogDescription>
                            Please confirm your password to disable two-factor authentication.
                        </DialogDescription>
                    </DialogHeader>
                    <div className="py-4 space-y-4">
                        <div className="space-y-2">
                            <label className="text-sm font-bold">Your Password</label>
                            <Input 
                                type="password" 
                                value={disablePassword}
                                onChange={(e) => setDisablePassword(e.target.value)}
                                className="rounded-xl h-12 border-[#E5E7EB]"
                            />
                        </div>
                    </div>
                    <DialogFooter>
                        <Button 
                            variant="destructive"
                            className="w-full h-12 rounded-xl font-bold"
                            onClick={() => disable2FAMutation.mutate(disablePassword)}
                            disabled={disable2FAMutation.isPending}
                        >
                            {disable2FAMutation.isPending ? <Loader2 className="h-4 w-4 animate-spin" /> : 'Disable 2FA'}
                        </Button>
                    </DialogFooter>
                </DialogContent>
            </Dialog>
        </div>
    );
}
