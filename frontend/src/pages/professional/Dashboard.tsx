import { useState, useEffect } from 'react';
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import { professionalService } from '@/services/api/professionalService';
import { bookingService } from '@/services/api/bookingService';
import { useToast } from '@/hooks/use-toast';
import { useNavigate } from 'react-router-dom';
import {
    ShieldCheck,
    Clock,
    AlertCircle,
    FileText,
    Briefcase,
    Languages,
    ArrowRight,
    Search,
    CheckCircle2,
    XCircle,
    Settings,
    MessageSquare,
    DollarSign,
    Save,
    Loader2
} from 'lucide-react';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';
import {
    Dialog,
    DialogContent,
    DialogHeader,
    DialogTitle,
    DialogTrigger
} from '@/components/ui/dialog';

export default function ProfessionalDashboard() {
    const { toast } = useToast();
    const navigate = useNavigate();
    const queryClient = useQueryClient();
    const [isEditing, setIsEditing] = useState(false);

    const { data: status, isLoading: statusLoading } = useQuery({
        queryKey: ['professional-status'],
        queryFn: professionalService.getStatus,
    });

    const { data: bookings, isLoading: bookingsLoading } = useQuery({
        queryKey: ['professional-bookings'],
        queryFn: bookingService.getBookings,
    });

    // Profile state for editing
    const [editData, setEditData] = useState<any>({
        bio: '',
        hourly_rate: 0,
        currency: 'USD',
        is_available: true
    });

    useEffect(() => {
        if (status?.profile) {
            setEditData({
                bio: status.profile.bio || '',
                hourly_rate: status.profile.hourly_rate || 0,
                currency: status.profile.currency || 'USD',
                is_available: status.profile.is_available ?? true
            });
        }
    }, [status]);

    const updateStatusMutation = useMutation({
        mutationFn: ({ id, status }: { id: number; status: string }) =>
            bookingService.updateStatus(id, status),
        onSuccess: () => {
            toast({ title: "Booking status updated" });
            queryClient.invalidateQueries({ queryKey: ['professional-bookings'] });
        },
    });

    const updateProfileMutation = useMutation({
        mutationFn: professionalService.updateProfile,
        onSuccess: () => {
            toast({ title: "Profile updated successfully" });
            setIsEditing(false);
            queryClient.invalidateQueries({ queryKey: ['professional-status'] });
        },
    });

    const isLoading = statusLoading || bookingsLoading;

    if (isLoading) {
        return (
            <div className="max-w-6xl mx-auto p-6 space-y-6 animate-pulse">
                <div className="h-48 bg-white rounded-2xl border border-[#E5E7EB]" />
                <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div className="h-32 bg-white rounded-2xl border border-[#E5E7EB]" />
                    <div className="h-32 bg-white rounded-2xl border border-[#E5E7EB]" />
                    <div className="h-32 bg-white rounded-2xl border border-[#E5E7EB]" />
                </div>
            </div>
        );
    }

    const isVerified = status?.profile?.is_verified;
    const latestVerification = status?.latest_verification;
    const profile = status?.profile;

    return (
        <div className="max-w-6xl mx-auto p-6 space-y-8">
            {/* Header / Welcome Section */}
            <div className="bg-gradient-to-br from-[#0B3C91] to-[#0A2A66] rounded-3xl p-10 text-white relative overflow-hidden shadow-xl">
                <div className="absolute top-[-20%] right-[-10%] w-[400px] h-[400px] bg-[#00C2FF]/10 rounded-full blur-[100px]" />
                <div className="relative z-10 flex flex-col md:flex-row items-center gap-8">
                    <div className="h-24 w-24 rounded-full bg-white/10 backdrop-blur-md flex items-center justify-center border border-white/20">
                        {isVerified ? (
                            <ShieldCheck className="h-12 w-12 text-[#00C2FF]" />
                        ) : (
                            <Clock className="h-12 w-12 text-blue-200" />
                        )}
                    </div>
                    <div className="flex-1 text-center md:text-left">
                        <h1 className="text-4xl font-extrabold tracking-tight mb-2">
                            Welcome back, Specialist
                        </h1>
                        <p className="text-blue-100 text-lg max-w-xl opacity-90">
                            {isVerified
                                ? "Your expert profile is active. You are now visible to clients seeking relocation assistance."
                                : "Your application is currently being reviewed. We'll notify you once your credentials are verified."
                            }
                        </p>
                    </div>
                    <div className="flex flex-col gap-3">
                        {!isVerified && (
                            <div className="bg-white/10 backdrop-blur-md border border-white/20 p-4 rounded-2xl text-center min-w-[200px]">
                                <p className="text-xs uppercase font-bold tracking-widest text-blue-200 mb-1">Status</p>
                                <p className="text-xl font-bold flex items-center justify-center gap-2">
                                    <span className="h-2 w-2 bg-amber-400 rounded-full" /> Pending
                                </p>
                            </div>
                        )}
                        <Button
                            className="bg-white text-[#0B3C91] hover:bg-blue-50 font-bold rounded-xl h-12"
                            onClick={() => navigate('/messages')}
                        >
                            <MessageSquare className="mr-2 h-4 w-4" /> Go to Inbox
                        </Button>
                    </div>
                </div>
            </div>

            {/* Verification Journey (Only if not verified) */}
            {!isVerified && (latestVerification || profile) && (
                <Card className="border-amber-100 bg-amber-50/30 overflow-hidden">
                    <CardContent className="p-6 flex flex-col md:flex-row items-center gap-6">
                        <div className="h-12 w-12 rounded-full bg-amber-100 flex items-center justify-center shrink-0">
                            <AlertCircle className="h-6 w-6 text-amber-600" />
                        </div>
                        <div className="flex-1 text-center md:text-left">
                            <h3 className="text-lg font-bold text-amber-900">Verification in Progress</h3>
                            <p className="text-sm text-amber-800 opacity-80">
                                Submitted on {latestVerification?.created_at ? new Date(latestVerification.created_at).toLocaleDateString() : 'recently'}.
                                Our admins are currently reviewing your {profile?.type} license/certification.
                            </p>
                        </div>
                    </CardContent>
                </Card>
            )}

            {/* Stats / Overview Row */}
            <div className="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div className="bg-white p-6 rounded-2xl border border-[#E5E7EB] shadow-sm">
                    <div className="h-10 w-10 rounded-full bg-blue-50 flex items-center justify-center mb-4">
                        <Briefcase className="h-5 w-5 text-[#0B3C91]" />
                    </div>
                    <p className="text-xs font-bold text-[#6B7280] uppercase tracking-wider mb-1">Experience</p>
                    <p className="text-2xl font-black text-[#1A1A1A]">{profile?.years_of_experience || 0} Years</p>
                </div>
                <div className="bg-white p-6 rounded-2xl border border-[#E5E7EB] shadow-sm">
                    <div className="h-10 w-10 rounded-full bg-blue-50 flex items-center justify-center mb-4">
                        <DollarSign className="h-5 w-5 text-[#0B3C91]" />
                    </div>
                    <p className="text-xs font-bold text-[#6B7280] uppercase tracking-wider mb-1">Hourly Rate</p>
                    <p className="text-2xl font-black text-[#1A1A1A]">
                        {profile?.currency} {profile?.hourly_rate || 0}
                    </p>
                </div>
                <div className="bg-white p-6 rounded-2xl border border-[#E5E7EB] shadow-sm">
                    <div className="h-10 w-10 rounded-full bg-blue-50 flex items-center justify-center mb-4">
                        <Languages className="h-5 w-5 text-[#0B3C91]" />
                    </div>
                    <p className="text-xs font-bold text-[#6B7280] uppercase tracking-wider mb-1">Languages</p>
                    <p className="text-2xl font-black text-[#1A1A1A] flex gap-1 truncate">
                        {profile?.languages?.slice(0, 1).join(', ') || 'None set'}
                        {profile?.languages?.length > 1 && <span className="text-xs text-gray-400">+{profile.languages.length - 1}</span>}
                    </p>
                </div>
                <div className="bg-white p-6 rounded-2xl border border-[#E5E7EB] shadow-sm">
                    <div className="h-10 w-10 rounded-full bg-blue-50 flex items-center justify-center mb-4">
                        <Settings className="h-5 w-5 text-[#0B3C91]" />
                    </div>
                    <p className="text-xs font-bold text-[#6B7280] uppercase tracking-wider mb-1">Profile</p>
                    <Dialog open={isEditing} onOpenChange={setIsEditing}>
                        <DialogTrigger asChild>
                            <Button variant="link" className="p-0 h-auto text-[#0B3C91] font-black text-lg">
                                Edit Settings <ArrowRight className="ml-1 h-4 w-4" />
                            </Button>
                        </DialogTrigger>
                        <DialogContent className="sm:max-w-[500px] rounded-3xl">
                            <DialogHeader>
                                <DialogTitle className="text-2xl font-black">Edit Expert Profile</DialogTitle>
                            </DialogHeader>
                            <div className="space-y-6 pt-4">
                                <div className="space-y-2">
                                    <label className="text-sm font-bold text-[#1A1A1A]">Professional Bio</label>
                                    <Textarea
                                        value={editData.bio}
                                        onChange={(e) => setEditData({ ...editData, bio: e.target.value })}
                                        placeholder="Describe your expertise and how you help clients..."
                                        className="h-32 rounded-xl border-[#E5E7EB]"
                                    />
                                </div>
                                <div className="grid grid-cols-2 gap-4">
                                    <div className="space-y-2">
                                        <label className="text-sm font-bold text-[#1A1A1A]">Hourly Rate</label>
                                        <Input
                                            type="number"
                                            value={editData.hourly_rate}
                                            onChange={(e) => setEditData({ ...editData, hourly_rate: parseFloat(e.target.value) })}
                                            className="rounded-xl border-[#E5E7EB]"
                                        />
                                    </div>
                                    <div className="space-y-2">
                                        <label className="text-sm font-bold text-[#1A1A1A]">Currency</label>
                                        <Input
                                            value={editData.currency}
                                            onChange={(e) => setEditData({ ...editData, currency: e.target.value })}
                                            className="rounded-xl border-[#E5E7EB]"
                                            maxLength={3}
                                        />
                                    </div>
                                </div>
                                <Button
                                    className="w-full h-12 bg-[#0B3C91] hover:bg-[#0B3C91]/90 rounded-xl font-extrabold"
                                    onClick={() => updateProfileMutation.mutate(editData)}
                                    disabled={updateProfileMutation.isPending}
                                >
                                    {updateProfileMutation.isPending ? (
                                        <Loader2 className="h-5 w-5 animate-spin mr-2" />
                                    ) : (
                                        <Save className="h-5 w-5 mr-2" />
                                    )}
                                    Save Profile Changes
                                </Button>
                            </div>
                        </DialogContent>
                    </Dialog>
                </div>
            </div>

            {/* Main Action Grid */}
            <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
                {/* Upcoming Bookings/Jobs */}
                <div className="bg-white rounded-3xl border border-[#E5E7EB] shadow-sm overflow-hidden flex flex-col">
                    <div className="px-8 py-6 border-b border-[#E5E7EB] flex items-center justify-between">
                        <h3 className="font-bold text-xl text-[#1A1A1A]">Incoming Requests</h3>
                        {bookings?.filter((b: any) => b.status === 'pending').length > 0 && (
                            <Badge className="bg-amber-500 hover:bg-amber-600">
                                {bookings.filter((b: any) => b.status === 'pending').length} New
                            </Badge>
                        )}
                    </div>

                    <div className="flex-1 overflow-y-auto max-h-[400px]">
                        {bookings?.length > 0 ? (
                            <div className="divide-y divide-gray-100">
                                {bookings.map((booking: any) => (
                                    <div key={booking.id} className="p-6 hover:bg-gray-50 transition-colors">
                                        <div className="flex items-start justify-between mb-3">
                                            <div className="flex items-center gap-3">
                                                <div className="h-10 w-10 rounded-full bg-blue-50 flex items-center justify-center font-bold text-[#0B3C91]">
                                                    {booking.user?.name?.charAt(0)}
                                                </div>
                                                <div>
                                                    <p className="font-bold text-[#1A1A1A]">{booking.user?.name}</p>
                                                    <p className="text-xs text-[#6B7280]">{new Date(booking.created_at).toLocaleDateString()}</p>
                                                </div>
                                            </div>
                                            <Badge variant={booking.status === 'pending' ? 'outline' : booking.status === 'confirmed' ? 'secondary' : 'default'}>
                                                {booking.status}
                                            </Badge>
                                        </div>

                                        <div className="bg-[#F8FAFC] p-3 rounded-xl mb-4 text-sm text-[#4B5563]">
                                            <div className="flex items-center gap-2 mb-1 font-bold text-[#1A1A1A]">
                                                <FileText className="h-3.5 w-3.5" /> {booking.type === 'consultation' ? 'Initial Consultation' : 'Translation Service'}
                                            </div>
                                            <p className="line-clamp-2 italic">"{booking.notes || 'No extra notes provided'}"</p>
                                        </div>

                                        {booking.status === 'pending' && (
                                            <div className="flex gap-2">
                                                <Button
                                                    size="sm"
                                                    className="flex-1 bg-green-600 hover:bg-green-700 text-white font-bold rounded-lg"
                                                    onClick={() => updateStatusMutation.mutate({ id: booking.id, status: 'confirmed' })}
                                                    disabled={updateStatusMutation.isPending}
                                                >
                                                    <CheckCircle2 className="h-4 w-4 mr-2" /> Accept
                                                </Button>
                                                <Button
                                                    size="sm"
                                                    variant="outline"
                                                    className="flex-1 border-red-200 text-red-600 hover:bg-red-50 font-bold rounded-lg"
                                                    onClick={() => updateStatusMutation.mutate({ id: booking.id, status: 'cancelled' })}
                                                    disabled={updateStatusMutation.isPending}
                                                >
                                                    <XCircle className="h-4 w-4 mr-2" /> Decline
                                                </Button>
                                            </div>
                                        )}
                                    </div>
                                ))}
                            </div>
                        ) : (
                            <div className="p-10 flex flex-col items-center justify-center text-center">
                                <div className="h-20 w-20 rounded-full bg-gray-50 flex items-center justify-center mb-6">
                                    <FileText className="h-10 w-10 text-gray-300" />
                                </div>
                                <h4 className="text-lg font-bold mb-2">No booking requests</h4>
                                <p className="text-[#6B7280] max-w-xs px-4">
                                    Requests from users will appear here once they find you in the marketplace.
                                </p>
                            </div>
                        )}
                    </div>
                </div>

                {/* Profile Completion / Checklist */}
                <div className="space-y-6">
                    <h3 className="font-bold text-xl text-[#1A1A1A] px-2">Onboarding Checklist</h3>
                    <div className="space-y-4">
                        {[
                            { label: "Account Registered", done: true },
                            { label: "Professional Profile Created", done: true },
                            { label: "Credentials Uploaded", done: true },
                            { label: "Admin Identity Verification", done: isVerified },
                            { label: "Marketplace Listing Published", done: isVerified },
                        ].map((item, i) => (
                            <div key={i} className={`p-5 rounded-2xl border flex items-center gap-4 transition-all ${item.done ? 'bg-white border-[#E5E7EB]' : 'bg-gray-50 border-gray-200 opacity-60'}`}>
                                <div className={`h-6 w-6 rounded-full flex items-center justify-center ${item.done ? 'bg-green-100 text-green-600' : 'bg-gray-200 text-gray-400'}`}>
                                    {item.done ? <CheckCircle2 className="h-4 w-4" /> : <div className="h-2 w-2 rounded-full bg-current" />}
                                </div>
                                <span className={`flex-1 font-bold ${item.done ? 'text-[#1A1A1A]' : 'text-gray-500'}`}>{item.label}</span>
                                {item.done && <span className="text-[10px] uppercase font-black text-green-600 tracking-tighter">Done</span>}
                            </div>
                        ))}
                    </div>
                </div>
            </div>
        </div>
    );
}
