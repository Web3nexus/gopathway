import { useState } from 'react';
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import { adminService } from '@/services/api/adminService';
import { useToast } from '@/hooks/use-toast';
import {
    DollarSign,
    CheckCircle2,
    XCircle,
    Loader2,
    Wallet,
    Info,
    RefreshCw
} from 'lucide-react';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { Textarea } from '@/components/ui/textarea';
import {
    Dialog,
    DialogContent,
    DialogHeader,
    DialogTitle,
    DialogDescription,
} from '@/components/ui/dialog';

export default function ExpertWithdrawals() {
    const { toast } = useToast();
    const queryClient = useQueryClient();
    const [selectedWithdrawal, setSelectedWithdrawal] = useState<any>(null);
    const [adminNotes, setAdminNotes] = useState('');
    const [actionStatus, setActionStatus] = useState<'approved' | 'rejected' | 'processed'>('approved');
    const [isReviewOpen, setIsReviewOpen] = useState(false);

    const { data, isLoading } = useQuery({
        queryKey: ['admin-expert-withdrawals'],
        queryFn: adminService.getExpertWithdrawals,
    });

    const reviewMutation = useMutation({
        mutationFn: ({ id, status, admin_notes }: { id: number; status: string; admin_notes: string }) =>
            adminService.reviewExpertWithdrawal(id, { status, admin_notes }),
        onSuccess: () => {
            toast({ title: 'Withdrawal status updated successfully' });
            setIsReviewOpen(false);
            setAdminNotes('');
            setSelectedWithdrawal(null);
            queryClient.invalidateQueries({ queryKey: ['admin-expert-withdrawals'] });
        },
        onError: () => {
            toast({
                title: 'Error updating withdrawal',
                variant: 'destructive',
            });
        }
    });

    const openReviewModal = (withdrawal: any, status: 'approved' | 'rejected' | 'processed') => {
        setSelectedWithdrawal(withdrawal);
        setActionStatus(status);
        setAdminNotes(withdrawal.admin_notes || '');
        setIsReviewOpen(true);
    };

    const handleReviewSubmit = () => {
        if (!selectedWithdrawal) return;
        reviewMutation.mutate({
            id: selectedWithdrawal.id,
            status: actionStatus,
            admin_notes: adminNotes,
        });
    };

    if (isLoading) {
        return (
            <div className="flex justify-center p-20">
                <Loader2 className="h-8 w-8 animate-spin text-[#0B3C91]" />
            </div>
        );
    }

    const withdrawals = data?.data || [];

    return (
        <div className="max-w-7xl mx-auto p-6 space-y-8">
            <div className="flex justify-between items-center mb-8">
                <div>
                    <h1 className="text-3xl font-black text-[#1A1A1A] tracking-tight">Expert Withdrawals</h1>
                    <p className="text-[#6B7280]">Manage and process payout requests from professionals.</p>
                </div>
                <div className="flex gap-4">
                    <Button variant="outline" className="border-slate-200" onClick={() => queryClient.invalidateQueries({ queryKey: ['admin-expert-withdrawals'] })}>
                        <RefreshCw className="h-4 w-4 mr-2" /> Refresh
                    </Button>
                </div>
            </div>

            <div className="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
                <div className="overflow-x-auto">
                    <table className="w-full text-left border-collapse">
                        <thead>
                            <tr className="bg-slate-50 border-b border-slate-200">
                                <th className="p-4 font-bold text-slate-600 text-sm">Expert</th>
                                <th className="p-4 font-bold text-slate-600 text-sm">Amount</th>
                                <th className="p-4 font-bold text-slate-600 text-sm">Payout Details</th>
                                <th className="p-4 font-bold text-slate-600 text-sm">Date</th>
                                <th className="p-4 font-bold text-slate-600 text-sm">Status</th>
                                <th className="p-4 font-bold text-slate-600 text-sm text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-slate-100">
                            {withdrawals.length === 0 ? (
                                <tr>
                                    <td colSpan={6} className="p-8 text-center text-slate-500">
                                        No withdrawal requests found.
                                    </td>
                                </tr>
                            ) : (
                                withdrawals.map((wd: any) => (
                                    <tr key={wd.id} className="hover:bg-slate-50 transition-colors">
                                        <td className="p-4">
                                            <p className="font-bold text-[#1A1A1A]">{wd.expert?.name}</p>
                                            <p className="text-xs text-slate-500">{wd.expert?.email}</p>
                                        </td>
                                        <td className="p-4">
                                            <p className="font-black text-[#0B3C91]">${wd.amount} {wd.currency}</p>
                                        </td>
                                        <td className="p-4 max-w-[200px]">
                                            <p className="text-xs truncate" title={wd.payout_details}>{wd.payout_details}</p>
                                        </td>
                                        <td className="p-4">
                                            <p className="text-sm">{new Date(wd.created_at).toLocaleDateString()}</p>
                                        </td>
                                        <td className="p-4">
                                            <Badge variant={
                                                wd.status === 'pending' ? 'outline' : 
                                                wd.status === 'approved' ? 'default' : 
                                                wd.status === 'processed' ? 'secondary' : 'destructive'
                                            }>
                                                {wd.status}
                                            </Badge>
                                        </td>
                                        <td className="p-4 text-right">
                                            {wd.status === 'pending' && (
                                                <div className="flex justify-end gap-2">
                                                    <Button size="sm" className="bg-green-600 hover:bg-green-700" onClick={() => openReviewModal(wd, 'approved')}>
                                                        <CheckCircle2 className="h-4 w-4 mr-1" /> Approve
                                                    </Button>
                                                    <Button size="sm" variant="destructive" onClick={() => openReviewModal(wd, 'rejected')}>
                                                        <XCircle className="h-4 w-4 mr-1" /> Reject
                                                    </Button>
                                                </div>
                                            )}
                                            {wd.status === 'approved' && (
                                                <Button size="sm" className="bg-blue-600 hover:bg-blue-700" onClick={() => openReviewModal(wd, 'processed')}>
                                                    <Wallet className="h-4 w-4 mr-1" /> Mark Paid
                                                </Button>
                                            )}
                                            {(wd.status === 'processed' || wd.status === 'rejected') && (
                                                <Button size="sm" variant="ghost" onClick={() => openReviewModal(wd, wd.status)}>
                                                    <Info className="h-4 w-4 mr-1" /> View Note
                                                </Button>
                                            )}
                                        </td>
                                    </tr>
                                ))
                            )}
                        </tbody>
                    </table>
                </div>
            </div>

            <Dialog open={isReviewOpen} onOpenChange={setIsReviewOpen}>
                <DialogContent className="sm:max-w-[500px] rounded-3xl">
                    <DialogHeader>
                        <DialogTitle className="text-2xl font-black capitalize">{actionStatus} Withdrawal</DialogTitle>
                        <DialogDescription>
                            Review the details and add a note.
                        </DialogDescription>
                    </DialogHeader>
                    {selectedWithdrawal && (
                        <div className="space-y-4 py-4">
                            <div className="bg-slate-50 p-4 rounded-xl border border-slate-100">
                                <div className="grid grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <span className="block text-slate-500 font-bold mb-1">Expert</span>
                                        <span className="font-bold text-[#1A1A1A]">{selectedWithdrawal.expert?.name}</span>
                                    </div>
                                    <div>
                                        <span className="block text-slate-500 font-bold mb-1">Amount</span>
                                        <span className="font-black text-[#0B3C91]">${selectedWithdrawal.amount} {selectedWithdrawal.currency}</span>
                                    </div>
                                    <div className="col-span-2">
                                        <span className="block text-slate-500 font-bold mb-1">Payout Details</span>
                                        <span className="text-[#1A1A1A] break-all">{selectedWithdrawal.payout_details}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div className="space-y-2">
                                <label className="text-sm font-bold text-[#1A1A1A]">Admin Notes (Required for rejection)</label>
                                <Textarea 
                                    className="rounded-xl"
                                    placeholder="Enter reason for rejection or payment receipt number..."
                                    value={adminNotes}
                                    onChange={(e) => setAdminNotes(e.target.value)}
                                />
                            </div>

                            <Button 
                                className={`w-full font-bold h-12 rounded-xl text-white ${
                                    actionStatus === 'approved' ? 'bg-green-600 hover:bg-green-700' :
                                    actionStatus === 'processed' ? 'bg-blue-600 hover:bg-blue-700' :
                                    actionStatus === 'rejected' ? 'bg-red-600 hover:bg-red-700' :
                                    'bg-slate-600 hover:bg-slate-700'
                                }`}
                                onClick={handleReviewSubmit}
                                disabled={reviewMutation.isPending || (actionStatus === 'rejected' && !adminNotes.trim())}
                            >
                                {reviewMutation.isPending ? <Loader2 className="h-5 w-5 animate-spin" /> : 'Confirm Action'}
                            </Button>
                        </div>
                    )}
                </DialogContent>
            </Dialog>
        </div>
    );
}
