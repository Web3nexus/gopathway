import { useState } from 'react';
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import api from '@/lib/api';
import { 
    MessageSquare, 
    User, 
    Calendar, 
    ChevronRight, 
    Send, 
    Loader2, 
    Mail, 
    ArrowLeft,
    CheckCircle2,
    Clock
} from 'lucide-react';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { useToast } from '@/hooks/use-toast';
import { format } from 'date-fns';

export default function SupportManagement() {
    const [selectedId, setSelectedId] = useState<number | null>(null);
    const [replyBody, setReplyBody] = useState('');
    const { toast } = useToast();
    const queryClient = useQueryClient();

    // Fetch all support conversations
    const { data: convosRaw, isLoading: listLoading } = useQuery({
        queryKey: ['admin', 'support'],
        queryFn: async () => {
            const res = await api.get('/api/v1/admin/support');
            return res.data.data;
        }
    });
    const conversations = Array.isArray(convosRaw) ? convosRaw : [];

    // Fetch specific conversation messages
    const { data: detailData, isLoading: detailLoading } = useQuery({
        queryKey: ['admin', 'support', selectedId],
        queryFn: async () => {
            const res = await api.get(`/api/v1/admin/support/${selectedId}`);
            return res.data.data;
        },
        enabled: !!selectedId
    });

    const replyMutation = useMutation({
        mutationFn: async (body: string) => {
            const res = await api.post(`/api/v1/admin/support/${selectedId}/reply`, { body });
            return res.data;
        },
        onSuccess: () => {
            toast({ title: 'Reply sent', description: 'Your message has been sent to the user.' });
            setReplyBody('');
            queryClient.invalidateQueries({ queryKey: ['admin', 'support', selectedId] });
            queryClient.invalidateQueries({ queryKey: ['admin', 'support'] });
        }
    });

    const handleReply = (e: React.FormEvent) => {
        e.preventDefault();
        if (!replyBody.trim()) return;
        replyMutation.mutate(replyBody);
    };

    if (selectedId && detailData) {
        return (
            <div className="space-y-6 animate-in fade-in duration-500">
                <div className="flex items-center gap-4">
                    <Button variant="outline" size="icon" onClick={() => setSelectedId(null)}>
                        <ArrowLeft className="w-4 h-4" />
                    </Button>
                    <div>
                        <h2 className="text-2xl font-bold tracking-tight">{detailData.conversation.subject}</h2>
                        <p className="text-muted-foreground flex items-center gap-2">
                             User: {detailData.conversation.userOne.id === detailData.conversation.user_one_id ? detailData.conversation.userOne.name : detailData.conversation.userTwo.name}
                        </p>
                    </div>
                </div>

                <div className="grid grid-cols-1 lg:grid-cols-3 gap-8 h-[calc(100vh-250px)]">
                    <div className="lg:col-span-2 flex flex-col gap-4">
                        <Card className="flex-1 flex flex-col overflow-hidden">
                            <CardContent className="flex-1 overflow-y-auto p-6 space-y-4">
                                {detailData.messages.map((msg: any) => (
                                    <div 
                                        key={msg.id} 
                                        className={`flex flex-col ${msg.sender_id === detailData.conversation.userOne.id || msg.sender_id === detailData.conversation.userTwo.id && !msg.sender.roles?.some((r:any) => r.name === 'admin') ? 'items-start' : 'items-end'}`}
                                    >
                                        <div className={`max-w-[80%] rounded-2xl px-4 py-2 text-sm ${
                                            msg.sender.roles?.some((r:any) => r.name === 'admin') 
                                                ? 'bg-blue-600 text-white rounded-tr-none' 
                                                : 'bg-slate-100 dark:bg-slate-800 rounded-tl-none'
                                        }`}>
                                            {msg.body}
                                        </div>
                                        <span className="text-[10px] text-muted-foreground mt-1">
                                            {format(new Date(msg.created_at), 'MMM d, h:mm a')}
                                        </span>
                                    </div>
                                ))}
                            </CardContent>
                            <div className="p-4 border-t bg-muted/30">
                                <form onSubmit={handleReply} className="flex gap-2">
                                    <Input 
                                        placeholder="Type your reply..." 
                                        value={replyBody}
                                        onChange={(e) => setReplyBody(e.target.value)}
                                        disabled={replyMutation.isPending}
                                    />
                                    <Button type="submit" disabled={replyMutation.isPending || !replyBody.trim()}>
                                        {replyMutation.isPending ? <Loader2 className="w-4 h-4 animate-spin" /> : <Send className="w-4 h-4" />}
                                    </Button>
                                </form>
                            </div>
                        </Card>
                    </div>

                    <Card>
                        <CardHeader>
                            <CardTitle>Conversation Stats</CardTitle>
                        </CardHeader>
                        <CardContent className="space-y-4">
                            <div className="flex items-center gap-2 text-sm">
                                <Clock className="w-4 h-4 text-muted-foreground" />
                                <span>Started: {format(new Date(detailData.conversation.created_at), 'MMM d, yyyy')}</span>
                            </div>
                            <div className="flex items-center gap-2 text-sm">
                                <MessageSquare className="w-4 h-4 text-muted-foreground" />
                                <span>Total Messages: {detailData.messages.length}</span>
                            </div>
                            <div className="pt-4 border-t">
                                <h4 className="text-xs font-bold uppercase tracking-wider text-muted-foreground mb-2">Customer Info</h4>
                                <div className="space-y-2">
                                    <p className="text-sm font-medium">{detailData.conversation.user_one_id ? detailData.conversation.userOne.name : detailData.conversation.userTwo.name}</p>
                                    <p className="text-sm text-muted-foreground flex items-center gap-1.5">
                                        <Mail className="w-3 h-3" /> {detailData.conversation.user_one_id ? detailData.conversation.userOne.email : detailData.conversation.userTwo.email}
                                    </p>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>
        );
    }

    return (
        <div className="space-y-6">
            <div className="flex items-center justify-between">
                <div>
                    <h1 className="text-3xl font-bold tracking-tight">Support Messages</h1>
                    <p className="text-muted-foreground">Manage and respond to user support inquiries.</p>
                </div>
                <Badge variant="secondary" className="px-3 py-1">
                    {conversations?.length || 0} Open Tickets
                </Badge>
            </div>

            {listLoading ? (
                <div className="flex items-center justify-center p-20">
                    <Loader2 className="w-8 h-8 animate-spin text-primary" />
                </div>
            ) : conversations?.length === 0 ? (
                <Card className="flex flex-col items-center justify-center p-12 text-center border-dashed">
                    <div className="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center mb-4">
                        <MessageSquare className="w-6 h-6 text-slate-400" />
                    </div>
                    <CardTitle>No support messages yet</CardTitle>
                    <CardDescription>When users reach out for help, they will appear here.</CardDescription>
                </Card>
            ) : (
                <div className="grid gap-4">
                    {conversations?.map((ticket: any) => (
                        <Card 
                            key={ticket.id} 
                            className="hover:shadow-md transition-all cursor-pointer border-l-4 border-l-blue-500 overflow-hidden"
                            onClick={() => setSelectedId(ticket.id)}
                        >
                            <CardContent className="p-0">
                                <div className="flex items-center p-4">
                                    <div className="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center mr-4">
                                        <User className="w-5 h-5 text-slate-500" />
                                    </div>
                                    <div className="flex-1 min-w-0">
                                        <div className="flex items-center justify-between mb-1">
                                            <h3 className="font-bold truncate pr-4">{ticket.subject}</h3>
                                            <span className="text-[10px] text-muted-foreground shrink-0 uppercase tracking-widest flex items-center gap-1">
                                                <Calendar className="w-3 h-3" /> 
                                                {format(new Date(ticket.updated_at), 'MMM d')}
                                            </span>
                                        </div>
                                        <p className="text-sm text-muted-foreground truncate font-medium">
                                            From: {ticket.customer.name} ({ticket.customer.email})
                                        </p>
                                        <p className="text-xs text-muted-foreground/60 truncate mt-1 italic">
                                            Last: {ticket.last_message?.body || 'No messages'}
                                        </p>
                                    </div>
                                    <ChevronRight className="w-5 h-5 text-slate-300 ml-4 group-hover:text-blue-500 transition-colors" />
                                </div>
                            </CardContent>
                        </Card>
                    ))}
                </div>
            )}
        </div>
    );
}
