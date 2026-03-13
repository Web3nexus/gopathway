import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import api from '@/lib/api';
import {
    ShieldCheck,
    XCircle,
    CheckCircle2,
    ExternalLink,
    User,
    Calendar,
    FileText,
    Loader2,
    Search
} from 'lucide-react';
import { Button } from '@/components/ui/button';
import { Card, CardHeader, CardTitle, CardContent } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { useToast } from '@/hooks/use-toast';
import { useState } from 'react';
import { Input } from '@/components/ui/input';

export default function ProfessionalVerification() {
    const { toast } = useToast();
    const queryClient = useQueryClient();
    const [searchTerm, setSearchTerm] = useState('');

    const { data: requests, isLoading } = useQuery({
        queryKey: ['admin-verification-requests'],
        queryFn: () => api.get('/api/v1/admin/verifications').then(r => r.data),
    });

    const verifyMutation = useMutation({
        mutationFn: ({ id, status, notes }: { id: number; status: string; notes?: string }) =>
            api.post(`/api/v1/admin/verifications/${id}/review`, { status, notes }),
        onSuccess: () => {
            toast({ title: "Verification Updated" });
            queryClient.invalidateQueries({ queryKey: ['admin-verification-requests'] });
        },
    });

    if (isLoading) return <div className="flex items-center justify-center h-64"><Loader2 className="animate-spin" /></div>;

    const filteredRequests = requests?.filter((r: any) =>
        r.user.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
        r.user.email.toLowerCase().includes(searchTerm.toLowerCase())
    );

    return (
        <div className="space-y-6">
            <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 className="text-2xl font-bold text-[#1A1A1A]">Professional Verifications</h1>
                    <p className="text-sm text-[#6B7280]">Review and approve specialist credentials</p>
                </div>
                <div className="relative w-full md:w-64">
                    <Search className="absolute left-3 top-2.5 h-4 w-4 text-gray-400" />
                    <Input
                        placeholder="Search professionals..."
                        className="pl-9"
                        value={searchTerm}
                        onChange={(e) => setSearchTerm(e.target.value)}
                    />
                </div>
            </div>

            <div className="grid grid-cols-1 gap-4">
                {filteredRequests?.length > 0 ? (
                    filteredRequests.map((request: any) => (
                        <Card key={request.id} className="border-[#E5E7EB] hover:shadow-md transition-shadow overflow-hidden">
                            <div className="flex flex-col lg:flex-row">
                                <div className="p-6 flex-1 space-y-4">
                                    <div className="flex items-start justify-between">
                                        <div className="flex items-center gap-4">
                                            <div className="h-12 w-12 rounded-full bg-blue-50 flex items-center justify-center font-bold text-[#0B3C91]">
                                                {request.user.name.charAt(0)}
                                            </div>
                                            <div>
                                                <h3 className="font-bold text-lg">{request.user.name}</h3>
                                                <p className="text-sm text-gray-500">{request.user.email}</p>
                                            </div>
                                        </div>
                                        <Badge variant={request.status === 'pending' ? 'outline' : request.status === 'approved' ? 'secondary' : 'destructive'}>
                                            {request.status.toUpperCase()}
                                        </Badge>
                                    </div>

                                    <div className="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                                        <div className="flex items-center gap-2 text-gray-600">
                                            <Briefcase className="h-4 w-4" />
                                            <span className="capitalize">{request.user.professional_profile?.type}</span>
                                        </div>
                                        <div className="flex items-center gap-2 text-gray-600">
                                            <Calendar className="h-4 w-4" />
                                            <span>{request.user.professional_profile?.years_of_experience} yrs exp</span>
                                        </div>
                                        <div className="col-span-2 text-gray-600 italic">
                                            "{request.user.professional_profile?.bio?.substring(0, 80)}..."
                                        </div>
                                    </div>
                                </div>

                                <div className="bg-[#F9FAFB] border-t lg:border-t-0 lg:border-l border-[#E5E7EB] p-6 flex flex-col justify-center gap-3 min-w-[200px]">
                                    <a
                                        href={request.document_url}
                                        target="_blank"
                                        rel="noreferrer"
                                        className="inline-flex items-center justify-center gap-2 text-sm font-bold text-[#0B3C91] hover:underline mb-2"
                                    >
                                        <FileText className="h-4 w-4" /> View Credentials <ExternalLink className="h-3 w-3" />
                                    </a>
                                    {request.status === 'pending' && (
                                        <div className="flex gap-2">
                                            <Button
                                                size="sm"
                                                className="flex-1 bg-green-600 hover:bg-green-700 text-white"
                                                onClick={() => verifyMutation.mutate({ id: request.id, status: 'approved' })}
                                                disabled={verifyMutation.isPending}
                                            >
                                                <CheckCircle2 className="h-4 w-4 mr-1" /> Approve
                                            </Button>
                                            <Button
                                                size="sm"
                                                variant="outline"
                                                className="flex-1 border-red-200 text-red-600 hover:bg-red-50"
                                                onClick={() => verifyMutation.mutate({ id: request.id, status: 'rejected' })}
                                                disabled={verifyMutation.isPending}
                                            >
                                                <XCircle className="h-4 w-4 mr-1" /> Reject
                                            </Button>
                                        </div>
                                    )}
                                </div>
                            </div>
                        </Card>
                    ))
                ) : (
                    <div className="text-center py-20 bg-white rounded-3xl border-2 border-dashed border-[#E5E7EB]">
                        <ShieldCheck className="h-12 w-12 text-gray-300 mx-auto mb-4" />
                        <h3 className="text-lg font-bold text-gray-400">No pending verification requests</h3>
                    </div>
                )}
            </div>
        </div>
    );
}
