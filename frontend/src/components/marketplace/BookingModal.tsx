import { useState } from 'react';
import { useMutation, useQueryClient } from '@tanstack/react-query';
import { bookingService } from '@/services/api/bookingService';
import { useToast } from '@/hooks/use-toast';
import {
    Dialog,
    DialogContent,
    DialogHeader,
    DialogTitle,
    DialogDescription,
    DialogFooter
} from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { Input } from '@/components/ui/input';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue
} from '@/components/ui/select';
import { Calendar, Clock, ShieldCheck, Mail } from 'lucide-react';

interface BookingModalProps {
    expert: any;
    onClose: () => void;
}

export default function BookingModal({ expert, onClose }: BookingModalProps) {
    const { toast } = useToast();
    const queryClient = useQueryClient();
    const [formData, setFormData] = useState({
        type: expert.professional_profile?.type === 'lawyer' ? 'consultation' : 'translation',
        scheduled_at: '',
        notes: '',
    });

    const mutation = useMutation({
        mutationFn: bookingService.createBooking,
        onSuccess: () => {
            toast({
                title: "Booking Request Sent",
                description: `Your request has been sent to ${expert.name}. They will review and confirm shortly.`,
            });
            queryClient.invalidateQueries({ queryKey: ['my-bookings'] });
            onClose();
        },
        onError: (error: any) => {
            toast({
                title: "Booking Failed",
                description: error.response?.data?.message || "Something went wrong.",
                variant: "destructive",
            });
        },
    });

    const handleSubmit = () => {
        mutation.mutate({
            professional_id: expert.id,
            ...formData
        });
    };

    return (
        <Dialog open={true} onOpenChange={onClose}>
            <DialogContent className="sm:max-w-[500px] rounded-3xl overflow-hidden p-0 border-none shadow-2xl">
                <div className="bg-[#0B3C91] p-8 text-white relative">
                    <div className="absolute top-0 right-0 w-32 h-32 bg-[#00C2FF]/20 rounded-full blur-3xl" />
                    <DialogHeader>
                        <DialogTitle className="text-2xl font-black tracking-tight text-white mb-2">Book an Expert</DialogTitle>
                        <DialogDescription className="text-blue-100 flex items-center gap-2 opacity-90">
                            Professional guidance for your journey with <span className="font-bold text-white underline decoration-[#00C2FF] decoration-2 underline-offset-4">{expert.name}</span>
                        </DialogDescription>
                    </DialogHeader>
                </div>

                <div className="p-8 space-y-6 bg-white">
                    <div className="flex items-center gap-4 p-4 rounded-2xl bg-gray-50 border border-gray-100 mb-2">
                        <div className="h-10 w-10 rounded-xl bg-white shadow-sm flex items-center justify-center font-bold text-[#0B3C91] border border-gray-100">
                            {expert.name.charAt(0)}
                        </div>
                        <div>
                            <p className="font-bold text-[#1A1A1A]">{expert.name}</p>
                            <p className="text-xs text-[#6B7280] flex items-center gap-1">
                                <ShieldCheck className="h-3 w-3" /> Verified {expert.professional_profile?.type}
                            </p>
                        </div>
                    </div>

                    <div className="space-y-4">
                        <div className="space-y-2">
                            <Label htmlFor="type" className="text-xs font-bold uppercase tracking-widest text-[#6B7280]">Service Type</Label>
                            <Select
                                value={formData.type}
                                onValueChange={(v) => setFormData({ ...formData, type: v })}
                            >
                                <SelectTrigger className="h-12 rounded-xl">
                                    <SelectValue placeholder="Select service" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="consultation">Initial Consultation</SelectItem>
                                    <SelectItem value="translation">Document Translation</SelectItem>
                                </SelectContent>
                            </Select>
                        </div>

                        <div className="space-y-2">
                            <Label htmlFor="date" className="text-xs font-bold uppercase tracking-widest text-[#6B7280]">Requested Date & Time</Label>
                            <div className="relative">
                                <Calendar className="absolute left-3 top-3.5 h-4 w-4 text-gray-400" />
                                <Input
                                    id="date"
                                    type="datetime-local"
                                    className="pl-10 h-12 rounded-xl"
                                    value={formData.scheduled_at}
                                    onChange={(e) => setFormData({ ...formData, scheduled_at: e.target.value })}
                                />
                            </div>
                        </div>

                        <div className="space-y-2">
                            <Label htmlFor="notes" className="text-xs font-bold uppercase tracking-widest text-[#6B7280]">Notes / Context</Label>
                            <Textarea
                                id="notes"
                                placeholder="Describe your situation or what documents need translating..."
                                className="min-h-[100px] rounded-xl"
                                value={formData.notes}
                                onChange={(e) => setFormData({ ...formData, notes: e.target.value })}
                            />
                        </div>
                    </div>

                    <div className="bg-blue-50/50 p-4 rounded-xl flex gap-3 border border-blue-50">
                        <Clock className="h-5 w-5 text-[#0B3C91] shrink-0" />
                        <p className="text-[11px] text-[#0B3C91] leading-relaxed font-medium">
                            Bookings are requests and must be confirmed by the expert.
                            Pricing and payment terms will be discussed directly after confirmation.
                        </p>
                    </div>
                </div>

                <DialogFooter className="p-8 bg-gray-50 border-t border-gray-100 flex gap-4">
                    <Button variant="ghost" className="flex-1 font-bold rounded-xl h-12" onClick={onClose}>
                        Discard
                    </Button>
                    <Button
                        className="flex-1 bg-[#00C2FF] hover:bg-[#00C2FF]/90 text-[#0B3C91] font-black rounded-xl h-12 shadow-lg shadow-blue-100"
                        onClick={handleSubmit}
                        disabled={mutation.isPending}
                    >
                        {mutation.isPending ? 'Sending...' : 'Send Request'}
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    );
}
