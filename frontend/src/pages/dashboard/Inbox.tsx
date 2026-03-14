import { useState, useRef, useEffect } from 'react';
import { useConversations, useMessages, useSendMessage } from '@/hooks/useChat';
import { useAiChat } from '@/hooks/useAiChat';
import { useAuth } from '@/hooks/useAuth';
import {
    MessageSquare,
    Send,
    Search,
    ArrowLeft,
    CheckCircle2,
    Loader2,
    Lock,
    Sparkles,
    Bot,
    User,
    Trash2,
    Zap,
    ShieldCheck,
    DollarSign,
    AlertCircle
} from 'lucide-react';
import { useMutation } from '@tanstack/react-query';
import { useToast } from '@/hooks/use-toast';
import { professionalService } from '@/services/api/professionalService';
import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogTrigger, DialogDescription } from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { useSearchParams, Link } from 'react-router-dom';
import { useFeatures } from '@/hooks/useFeatures';
import { motion, AnimatePresence } from 'framer-motion';

const formatDate = (dateString: string) => {
    if (!dateString) return '';
    try {
        const date = new Date(dateString);
        if (isNaN(date.getTime())) return '';
        return date.toLocaleDateString([], { month: 'short', day: 'numeric' });
    } catch (e) {
        return '';
    }
};

const formatTime = (dateString: string) => {
    if (!dateString) return '';
    try {
        const date = new Date(dateString);
        if (isNaN(date.getTime())) return '';
        return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    } catch (e) {
        return '';
    }
};

export default function Inbox() {
    const { getFeatureAccess, isLoading: featuresLoading } = useFeatures();
    const { user } = useAuth();
    const [searchParams] = useSearchParams();
    const presetRecipientId = searchParams.get('userId');
    const presetRecipientName = searchParams.get('userName');

    const isMessagingAccessible = getFeatureAccess('messaging') !== 'hidden';
    const isAiChatAccessible = getFeatureAccess('AI_CHAT') !== 'hidden';

    const { data: conversations = [], isLoading: loadingConversations } = useConversations(getFeatureAccess('messaging') === 'active');
    const [selectedConversationId, setSelectedConversationId] = useState<number | 'ai' | null>(null);
    const { data: messages = [] } = useMessages(typeof selectedConversationId === 'number' ? selectedConversationId : null, getFeatureAccess('messaging') === 'active');

    // AI Chat Hook
    const {
        messages: aiMessages,
        isSending: isAiSending,
        sendMessage: sendAiMessage,
        activeChatId: aiChatId,
        createChat: createAiChat,
        isCreating: isAiCreating,
    } = useAiChat();

    const [newMessage, setNewMessage] = useState('');
    const [searchTerm, setSearchTerm] = useState('');
    const sendMessageMutation = useSendMessage();
    const { toast } = useToast();

    // Payment state
    const [isPaymentOpen, setIsPaymentOpen] = useState(false);
    const [paymentAmount, setPaymentAmount] = useState('');
    const [paymentDescription, setPaymentDescription] = useState('');

    const initPaymentMutation = useMutation({
        mutationFn: professionalService.initializePayment,
        onSuccess: (res: any) => {
            if (res.gateway === 'flutterwave') {
                window.location.href = res.data.data.link;
            } else {
                window.location.href = res.data.data.authorization_url;
            }
        },
        onError: (error: any) => {
            toast({
                title: 'Payment Failed',
                description: error.response?.data?.message || 'Failed to initialize payment.',
                variant: 'destructive',
            });
        }
    });

    const handlePayExpert = () => {
        let expertId = typeof selectedConversationId === 'number' && Array.isArray(conversations) 
            ? conversations.find((c: any) => c.id === selectedConversationId)?.other_user?.id 
            : parseInt(presetRecipientId || '0');

        if (!expertId) return;

        initPaymentMutation.mutate({
            expert_id: expertId,
            amount: parseFloat(paymentAmount),
            description: paymentDescription || 'Expert Consultation Services',
        });
    };

    const messagesEndRef = useRef<HTMLDivElement>(null);

    // If redirected from marketplace with a specific user
    useEffect(() => {
        if (presetRecipientId && !selectedConversationId && Array.isArray(conversations)) {
            const existing = conversations.find((c: any) => c.other_user?.id === parseInt(presetRecipientId));
            if (existing) {
                setSelectedConversationId(existing.id);
            }
        }
    }, [presetRecipientId, conversations, selectedConversationId]);

    useEffect(() => {
        messagesEndRef.current?.scrollIntoView({ behavior: 'smooth' });
    }, [messages, aiMessages]);

    const handleSend = (e: React.FormEvent) => {
        e.preventDefault();
        if (!newMessage.trim()) return;

        if (selectedConversationId === 'ai') {
            if (!aiChatId) {
                createAiChat({ title: newMessage.substring(0, 30) });
                // We'll need a way to send the message after creation if the hook doesn't handle it
                // For now, let's assume useAiChat might need a slight tweak or we handle it here
            } else {
                sendAiMessage({ message: newMessage });
            }
            setNewMessage('');
            return;
        }

        let recipientId: number | null = null;
        if (typeof selectedConversationId === 'number' && Array.isArray(conversations)) {
            const conv = conversations.find((c: any) => c.id === selectedConversationId);
            recipientId = conv?.other_user?.id;
        } else if (presetRecipientId) {
            recipientId = parseInt(presetRecipientId);
        }

        if (recipientId) {
            sendMessageMutation.mutate(
                { recipient_id: recipientId, body: newMessage },
                {
                    onSuccess: (res: any) => {
                        setNewMessage('');
                        if (!selectedConversationId) {
                            setSelectedConversationId(res.conversation_id);
                        }
                    }
                }
            );
        }
    };

    const filteredConversations = Array.isArray(conversations)
        ? conversations.filter((c: any) =>
            c.other_user?.name?.toLowerCase().includes(searchTerm.toLowerCase())
        )
        : [];

    const selectedConv = Array.isArray(conversations) ? conversations.find((c: any) => c.id === selectedConversationId) : null;
    const isAiSelected = selectedConversationId === 'ai';
    const aiAccess = getFeatureAccess('AI_CHAT');
    const msgAccess = getFeatureAccess('messaging');

    const isLoading = loadingConversations || featuresLoading;

    if (isLoading) return <div className="flex justify-center p-20"><Loader2 className="h-8 w-8 animate-spin text-[#0B3C91]" /></div>;

    if (!isMessagingAccessible && !isAiChatAccessible) {
        return (
            <div className="max-w-4xl mx-auto my-12 relative overflow-hidden bg-white border border-blue-100 rounded-[32px] shadow-2xl p-12 text-center">
                <div className="h-20 w-20 bg-blue-50 rounded-3xl flex items-center justify-center mx-auto mb-8 rotate-3">
                    <Lock className="h-10 w-10 text-[#0B3C91]" />
                </div>
                <h2 className="text-4xl font-extrabold text-[#1A1A1A] mb-4 tracking-tight">Messaging & AI Assistance</h2>
                <p className="text-lg text-[#6B7280] max-w-xl mx-auto mb-10 leading-relaxed">
                    Connect with experts and our intelligent travel assistant. Upgrade to Premium to unlock full relocation support.
                </p>
                <div className="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <Link to="/pricing">
                        <Button className="bg-[#0B3C91] hover:bg-[#0A2A66] text-white px-10 py-7 rounded-2xl text-lg font-bold shadow-xl shadow-blue-100 group transition-all">
                            Upgrade to Premium
                            <Sparkles className="ml-2 h-5 w-5 group-hover:scale-110 transition-transform" />
                        </Button>
                    </Link>
                </div>
            </div>
        );
    }

    return (
        <div className="max-w-6xl mx-auto h-[calc(100vh-160px)] flex flex-col">
            <div className="mb-6 flex justify-between items-end">
                <div>
                    <h1 className="text-3xl font-black text-[#1A1A1A] tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-[#0B3C91] to-[#00C2FF]">Messages</h1>
                    <p className="text-[#6B7280] font-medium">Chat with experts or our AI Travel Assistant</p>
                </div>
            </div>

            <div className="flex-1 bg-white rounded-[32px] border border-[#E5E7EB] shadow-2xl shadow-blue-900/5 flex overflow-hidden backdrop-blur-sm">
                {/* Sidebar */}
                <div className={`w-full md:w-80 border-r border-[#E5E7EB] flex flex-col bg-slate-50/30 ${selectedConversationId ? 'hidden md:flex' : 'flex'}`}>
                    <div className="p-6 border-b border-[#E5E7EB] bg-white/50 space-y-4">
                        <div className="relative group">
                            <Search className="absolute left-4 top-3.5 h-4 w-4 text-gray-400 group-focus-within:text-[#0B3C91] transition-colors" />
                            <Input
                                placeholder="Search conversations..."
                                className="pl-11 h-11 bg-white border-slate-200 rounded-2xl focus:ring-2 focus:ring-[#0B3C91]/20 focus:border-[#0B3C91] transition-all"
                                value={searchTerm}
                                onChange={(e) => setSearchTerm(e.target.value)}
                            />
                        </div>

                        {/* AI Assistant Virtual Button/Conversation */}
                        {isAiChatAccessible && (
                            <button
                                onClick={() => setSelectedConversationId('ai')}
                                className={`w-full p-4 rounded-2xl flex gap-4 transition-all duration-500 text-left relative overflow-hidden group ${isAiSelected
                                    ? 'bg-gradient-to-br from-[#0B3C91] via-[#0A2A66] to-[#011438] text-white shadow-xl shadow-blue-900/30 ring-2 ring-blue-400'
                                    : 'bg-white hover:bg-slate-50 text-slate-600 border border-slate-100 shadow-sm'}`}
                            >
                                {isAiSelected && (
                                    <div className="absolute top-[-50%] right-[-10%] w-32 h-32 bg-blue-400/20 rounded-full blur-2xl animate-pulse" />
                                )}
                                <div className={`h-12 w-12 rounded-xl flex items-center justify-center shrink-0 border transition-all ${isAiSelected
                                    ? 'bg-white/10 border-white/20 text-[#00C2FF]'
                                    : 'bg-blue-50 border-blue-100 text-[#0B3C91]'}`}>
                                    <Sparkles className={`h-6 w-6 ${isAiSelected ? 'animate-spin-slow' : ''}`} />
                                </div>
                                <div className="flex-1 min-w-0 relative z-10">
                                    <div className="flex justify-between items-center mb-0.5">
                                        <h4 className={`font-black tracking-tight ${isAiSelected ? 'text-white' : 'text-[#1A1A1A]'}`}>
                                            Pathway AI
                                        </h4>
                                        {aiAccess === 'locked' && <Lock className={`h-3 w-3 ${isAiSelected ? 'text-white/40' : 'text-slate-400'}`} />}
                                    </div>
                                    <p className={`text-[10px] font-bold uppercase tracking-wider ${isAiSelected ? 'text-blue-300' : 'text-[#0B3C91]'}`}>
                                        Specialized Assistant
                                    </p>
                                </div>
                                {isAiSelected && <div className="absolute left-0 top-0 bottom-0 w-1.5 bg-[#00C2FF]" />}
                            </button>
                        )}
                    </div>

                    <div className="flex-1 overflow-y-auto p-3 space-y-1">
                        <div className="px-3 py-2">
                            <h5 className="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-2">My Conversations</h5>
                        </div>
                        {loadingConversations ? (
                            <div className="p-4 space-y-4">
                                {[1, 2, 3].map(i => <div key={i} className="h-16 bg-gray-50 rounded-2xl animate-pulse" />)}
                            </div>
                        ) : filteredConversations.length > 0 ? (
                            filteredConversations.map((conv: any) => (
                                <button
                                    key={conv.id}
                                    onClick={() => setSelectedConversationId(conv.id)}
                                    className={`w-full p-4 rounded-2xl flex gap-4 transition-all duration-300 text-left group ${selectedConversationId === conv.id
                                        ? 'bg-gradient-to-r from-[#0B3C91] to-[#0066FF] text-white shadow-lg shadow-blue-900/20'
                                        : 'hover:bg-white hover:shadow-md text-slate-600'}`}
                                >
                                    <div className={`h-12 w-12 rounded-xl flex items-center justify-center font-bold shrink-0 border transition-all ${selectedConversationId === conv.id
                                        ? 'bg-white/20 border-white/30 text-white'
                                        : 'bg-white border-slate-100 text-[#0B3C91] shadow-sm'}`}>
                                        {conv.other_user?.name?.charAt(0) || '?'}
                                    </div>
                                    <div className="flex-1 min-w-0">
                                        <div className="flex justify-between items-start mb-1">
                                            <h4 className={`font-bold truncate ${selectedConversationId === conv.id ? 'text-white' : 'text-[#1A1A1A]'}`}>
                                                {conv.other_user?.name || 'User'}
                                            </h4>
                                            <span className={`text-[9px] uppercase font-black tracking-tighter ${selectedConversationId === conv.id ? 'text-blue-100' : 'text-slate-400'}`}>
                                                {formatDate(conv.updated_at)}
                                            </span>
                                        </div>
                                        <p className={`text-xs truncate font-medium ${selectedConversationId === conv.id ? 'text-blue-50' : 'text-slate-400'}`}>
                                            {conv.last_message?.body || 'Start a conversation'}
                                        </p>
                                    </div>
                                </button>
                            ))
                        ) : (
                            <div className="p-10 text-center">
                                <div className="h-16 w-16 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4 opacity-50">
                                    <MessageSquare className="h-8 w-8 text-slate-400" />
                                </div>
                                <p className="text-sm font-bold text-slate-400">Expert list empty</p>
                            </div>
                        )}
                    </div>
                </div>

                {/* Chat Area */}
                <div className={`flex-1 flex flex-col relative ${!selectedConversationId && !presetRecipientId ? 'hidden md:flex items-center justify-center bg-slate-50/50 outline-2 outline-dashed outline-slate-100 m-4 rounded-[24px]' : 'flex bg-white'}`}>

                    {/* Locked Overlay for AI or Messaging */}
                    {((isAiSelected && aiAccess === 'locked') || (!isAiSelected && selectedConversationId && msgAccess === 'locked')) && (
                        <div className="absolute inset-0 z-[60] backdrop-blur-md bg-white/60 flex items-center justify-center p-8">
                            <div className="bg-white/90 p-10 rounded-[40px] shadow-2xl border border-blue-100 text-center max-w-md transform transition-all scale-100 border-t-4 border-t-[#0B3C91]">
                                <div className="h-20 w-20 bg-blue-50 rounded-[28px] flex items-center justify-center mx-auto mb-6 rotate-6 shadow-inner">
                                    <Lock className="h-10 w-10 text-[#0B3C91]" />
                                </div>
                                <h4 className="text-3xl font-black text-[#1A1A1A] mb-3 tracking-tight">Premium Chat Access</h4>
                                <p className="text-[#6B7280] mb-8 leading-relaxed font-medium">
                                    {isAiSelected
                                        ? "Unlock our specialized AI Travel Assistant for instant visa advice, strategy comparison, and relocation planning."
                                        : "Upgrade to chat directly with verified immigration experts, lawyers, and documentation specialists."}
                                </p>
                                <Link to="/pricing">
                                    <Button className="bg-[#0B3C91] hover:bg-[#0A2A66] text-white w-full rounded-2xl py-8 font-black text-lg shadow-2xl shadow-blue-200 group flex items-center justify-center gap-3">
                                        <Zap className="h-5 w-5 fill-current text-blue-400" />
                                        Get Premium Access
                                        <ArrowLeft className="h-5 w-5 rotate-180 group-hover:translate-x-1 transition-transform" />
                                    </Button>
                                </Link>
                                <p className="mt-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Starting at just $19.99/mo</p>
                            </div>
                        </div>
                    )}

                    {selectedConversationId || presetRecipientId ? (
                        <>
                            {/* Chat Header */}
                            <div className="px-8 py-5 border-b border-[#E5E7EB] flex items-center justify-between bg-white/80 backdrop-blur-md sticky top-0 z-10 shadow-sm shadow-slate-100/50">
                                <div className="flex items-center gap-4">
                                    <Button
                                        variant="ghost"
                                        size="icon"
                                        className="md:hidden -ml-2 h-10 w-10 rounded-xl"
                                        onClick={() => setSelectedConversationId(null)}
                                    >
                                        <ArrowLeft className="h-5 w-5" />
                                    </Button>
                                    <div className="relative">
                                        <div className={`h-12 w-12 rounded-2xl text-white flex items-center justify-center font-bold shadow-lg shadow-blue-900/10 ${isAiSelected ? 'bg-gradient-to-br from-[#0B3C91] to-[#011438] ring-2 ring-blue-500/20' : 'bg-gradient-to-br from-[#0B3C91] to-[#00C2FF]'}`}>
                                            {isAiSelected ? <Sparkles className="h-6 w-6" /> : (selectedConv?.other_user?.name || presetRecipientName || '?').charAt(0)}
                                        </div>
                                        <div className={`absolute -bottom-1 -right-1 h-4 w-4 border-2 border-white rounded-full shadow-sm ${isAiSelected ? 'bg-blue-400 animate-pulse' : 'bg-green-500'}`} />
                                    </div>
                                    <div>
                                        <h3 className="font-black text-lg text-[#1A1A1A] leading-none tracking-tight">
                                            {isAiSelected ? 'GoPathway Travel Expert (AI)' : (selectedConv?.other_user?.name || presetRecipientName || 'Chat')}
                                        </h3>
                                        <div className="flex items-center gap-1.5 mt-1">
                                            <span className={`text-[10px] font-bold uppercase tracking-widest ${isAiSelected ? 'text-blue-500' : 'text-green-600'}`}>
                                                {isAiSelected ? 'System Active' : 'Online Now'}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div className="flex items-center gap-3">
                                    {!isAiSelected && (
                                        <Dialog open={isPaymentOpen} onOpenChange={setIsPaymentOpen}>
                                            <DialogTrigger asChild>
                                                <Button className="bg-[#10B981] hover:bg-[#059669] text-white font-bold h-9 px-4 rounded-lg hidden sm:flex">
                                                    <DollarSign className="h-4 w-4 mr-2" /> Pay Expert
                                                </Button>
                                            </DialogTrigger>
                                            <DialogContent className="sm:max-w-[425px] rounded-3xl">
                                                <DialogHeader>
                                                    <DialogTitle className="text-2xl font-black">Pay Expert</DialogTitle>
                                                    <DialogDescription>
                                                        Pay securely via Pathway. 100% buyer protection applied.
                                                    </DialogDescription>
                                                </DialogHeader>
                                                <div className="grid gap-4 py-4">
                                                    <div className="space-y-2">
                                                        <label className="text-sm font-bold">Amount (USD)</label>
                                                        <Input 
                                                            type="number" 
                                                            min="1" 
                                                            placeholder="0.00" 
                                                            value={paymentAmount}
                                                            onChange={(e) => setPaymentAmount(e.target.value)}
                                                            className="rounded-xl h-12"
                                                        />
                                                    </div>
                                                    <div className="space-y-2">
                                                        <label className="text-sm font-bold">Reason for Payment</label>
                                                        <Input 
                                                            placeholder="e.g. Document review, Consultation fee..."
                                                            value={paymentDescription}
                                                            onChange={(e) => setPaymentDescription(e.target.value)}
                                                            className="rounded-xl h-12"
                                                        />
                                                    </div>
                                                </div>
                                                <Button 
                                                    className="w-full bg-[#0B3C91] hover:bg-[#0B3C91]/90 font-bold h-12 rounded-xl"
                                                    onClick={handlePayExpert}
                                                    disabled={initPaymentMutation.isPending || !paymentAmount}
                                                >
                                                    {initPaymentMutation.isPending ? <Loader2 className="h-5 w-5 animate-spin" /> : 'Confirm & Proceed to Payment'}
                                                </Button>
                                            </DialogContent>
                                        </Dialog>
                                    )}
                                </div>
                            </div>

                            {/* Messages */}
                            <div className="flex-1 p-8 bg-gradient-to-b from-slate-50/20 to-white overflow-y-auto custom-scrollbar">
                                <div className="max-w-4xl mx-auto space-y-8">
                                    <div className="flex justify-center">
                                        <span className="px-4 py-1.5 bg-white border border-slate-100 rounded-full text-[10px] font-bold text-slate-400 uppercase tracking-widest shadow-sm flex items-center gap-2">
                                            <ShieldCheck className="h-3 w-3 text-[#0B3C91]" />
                                            Security: {isAiSelected ? 'Internal API Encryption' : 'End-to-end encrypted'}
                                        </span>
                                    </div>

                                    {isAiSelected ? (
                                        <>
                                            {aiMessages.length === 0 && !isAiCreating && (
                                                <div className="py-12 flex flex-col items-center text-center space-y-6 max-w-sm mx-auto">
                                                    <div className="h-20 w-20 bg-blue-50 rounded-3xl flex items-center justify-center shadow-inner">
                                                        <Bot className="h-10 w-10 text-[#0B3C91]" />
                                                    </div>
                                                    <div>
                                                        <h4 className="text-xl font-black text-slate-900 tracking-tight">Hello, I'm your AI Expert!</h4>
                                                        <p className="text-sm text-slate-500 mt-2 leading-relaxed">
                                                            Ask me anything about visa requirements, cost of living,
                                                            or document preparation for your destination.
                                                        </p>
                                                    </div>
                                                    <div className="flex flex-wrap justify-center gap-2">
                                                        {['UK Student Visa', 'Cost of living Canada', 'PR routes Spain'].map(tip => (
                                                            <button
                                                                key={tip}
                                                                onClick={() => { setNewMessage(tip); }}
                                                                className="text-[10px] font-bold px-4 py-2 bg-white border border-slate-200 rounded-full hover:border-[#0B3C91] hover:text-[#0B3C91] transition-all shadow-sm"
                                                            >
                                                                {tip}
                                                            </button>
                                                        ))}
                                                    </div>
                                                </div>
                                            )}
                                            {aiMessages.map((msg: any, i: number) => (
                                                <div key={i} className={`flex ${msg.role === 'user' ? 'justify-end' : 'justify-start'}`}>
                                                    <div className={`max-w-[85%] flex ${msg.role === 'user' ? 'flex-row-reverse' : 'flex-row'} gap-4`}>
                                                        <div className={`h-8 w-8 rounded-lg flex items-center justify-center shrink-0 shadow-sm ${msg.role === 'user' ? 'bg-[#0B3C91] text-white' : 'bg-white border border-slate-100 text-[#0B3C91]'}`}>
                                                            {msg.role === 'user' ? <User className="h-4 w-4" /> : <Bot className="h-4 w-4" />}
                                                        </div>
                                                        <div className={`p-5 rounded-[28px] text-sm leading-relaxed shadow-sm ${msg.role === 'user'
                                                            ? 'bg-gradient-to-br from-[#0B3C91] to-[#0A2A66] text-white rounded-tr-none'
                                                            : 'bg-white border border-slate-100 text-[#1A1A1A] rounded-tl-none whitespace-pre-wrap'
                                                            }`}>
                                                            {msg.content}
                                                        </div>
                                                    </div>
                                                </div>
                                            ))}
                                            {(isAiSending || isAiCreating) && (
                                                <div className="flex justify-start">
                                                    <div className="bg-white border border-slate-100 p-4 rounded-2xl rounded-tl-none flex items-center gap-3 shadow-sm">
                                                        <Loader2 className="h-4 w-4 animate-spin text-[#0B3C91]" />
                                                        <span className="text-xs text-slate-500 font-bold italic tracking-wide">Thinking...</span>
                                                    </div>
                                                </div>
                                            )}
                                        </>
                                    ) : selectedConversationId && Array.isArray(messages) ? (
                                        messages.map((msg: any) => {
                                            const isMe = msg.sender_id === user?.id;
                                            return (
                                                <div key={msg.id} className={`flex ${isMe ? 'justify-end' : 'justify-start'}`}>
                                                    <div className={`max-w-[80%] flex ${isMe ? 'flex-row-reverse' : 'flex-row'} gap-3 items-end`}>
                                                        {!isMe && (
                                                            <div className="h-8 w-8 rounded-lg bg-slate-100 border border-slate-200 flex items-center justify-center text-[10px] font-black shrink-0">
                                                                {(selectedConv?.other_user?.name || '?').charAt(0)}
                                                            </div>
                                                        )}
                                                        <div className={`p-4 rounded-3xl text-sm leading-relaxed shadow-sm ${isMe
                                                            ? 'bg-gradient-to-br from-[#0B3C91] to-[#0A2A66] text-white rounded-br-sm'
                                                            : 'bg-white text-[#1A1A1A] border border-slate-100 rounded-bl-sm shadow-blue-900/5'
                                                            }`}>
                                                            {msg.body}
                                                        </div>
                                                        <span className="text-[8px] font-black uppercase tracking-tighter text-slate-400 mb-1">
                                                            {formatTime(msg.created_at)}
                                                        </span>
                                                    </div>
                                                </div>
                                            );
                                        })
                                    ) : (
                                        <div className="py-20 text-center">
                                            <div className="h-24 w-24 bg-blue-50 rounded-[32px] flex items-center justify-center mx-auto mb-6 rotate-6 shadow-inner shadow-blue-100/50">
                                                <Sparkles className="h-10 w-10 text-[#0B3C91]" />
                                            </div>
                                            <h4 className="text-2xl font-black text-gray-900 tracking-tight">Expert Connection</h4>
                                            <p className="text-base text-gray-500 max-w-xs mx-auto mt-3 leading-relaxed">
                                                Send a message to <span className="text-[#0B3C91] font-bold">{presetRecipientName}</span> to discuss your journey.
                                            </p>
                                        </div>
                                    )}
                                    <div ref={messagesEndRef} />
                                </div>
                            </div>

                            {/* Input */}
                            <div className="p-8 bg-white border-t border-slate-100">
                                <div className="max-w-4xl mx-auto">
                                    <form onSubmit={handleSend} className="relative flex items-center">
                                        <Input
                                            placeholder={isAiSelected ? "Ask AI Assistant..." : "Type your message..."}
                                            className="h-16 pl-6 pr-16 bg-slate-50 border-none rounded-[24px] focus:ring-2 focus:ring-[#0B3C91]/5 focus:bg-white transition-all text-sm font-medium shadow-inner"
                                            value={newMessage}
                                            onChange={(e) => setNewMessage(e.target.value)}
                                            disabled={sendMessageMutation.isPending || isAiSending || isAiCreating}
                                        />
                                        <Button
                                            type="submit"
                                            className={`absolute right-2 h-12 w-12 rounded-2xl shadow-xl group transition-all ${isAiSelected ? 'bg-[#0B3C91] text-[#00C2FF]' : 'bg-[#0B3C91] text-white'}`}
                                            disabled={!newMessage.trim() || sendMessageMutation.isPending || isAiSending || isAiCreating}
                                        >
                                            {sendMessageMutation.isPending || isAiSending || isAiCreating ? (
                                                <Loader2 className="h-5 w-5 animate-spin" />
                                            ) : (
                                                <Send className="h-5 w-5 group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-transform" />
                                            )}
                                        </Button>
                                    </form>
                                    <p className="text-center text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-4">
                                        {isAiSelected ? 'GoPathway AI is highly accurate but check important facts.' : 'Priority Expert Support Active'}
                                    </p>
                                    {/(whatsapp|wa\.me|telegram|t\.me|ig|instagram|facebook|twitter|x\b|\+?[0-9]{10,})/i.test(newMessage) && !isAiSelected && (
                                        <motion.div 
                                            initial={{ opacity: 0, y: 10 }}
                                            animate={{ opacity: 1, y: 0 }}
                                            className="mt-3 p-3 bg-amber-50 border border-amber-200 rounded-xl flex items-start gap-3"
                                        >
                                            <AlertCircle className="h-5 w-5 text-amber-600 shrink-0 mt-0.5" />
                                            <p className="text-xs text-amber-900 font-medium leading-relaxed">
                                                <strong className="font-bold block text-amber-700 mb-0.5">Security Warning</strong>
                                                For your protection, please keep all communication and payments within GoPathway. Sharing external contact details or paying outside the platform violates our terms and removes buyer protection.
                                            </p>
                                        </motion.div>
                                    )}
                                </div>
                            </div>
                        </>
                    ) : (
                        <div className="text-center p-20 max-w-lg mx-auto">
                            <div className="h-32 w-32 rounded-[48px] bg-gradient-to-br from-blue-50 to-white flex items-center justify-center mx-auto mb-8 shadow-2xl shadow-blue-100/50 border border-blue-50">
                                <MessageSquare className="h-12 w-12 text-[#0B3C91] animate-bounce-slow" />
                            </div>
                            <h3 className="text-3xl font-black text-slate-900 tracking-tight mb-4">Your Intelligent Journey</h3>
                            <p className="text-slate-500 text-lg leading-relaxed">
                                Choose an expert or start a session with our **AI Assistant** to unlock your immigration roadmap.
                            </p>
                            <div className="mt-10 grid grid-cols-2 gap-4">
                                <div className="p-6 bg-slate-50 rounded-[24px] border border-slate-100 flex flex-col items-center">
                                    <Sparkles className="h-5 w-5 text-[#0B3C91] mb-2" />
                                    <h5 className="font-bold text-[#1A1A1A] text-xs uppercase mb-1">Instant AI</h5>
                                    <p className="text-[10px] text-slate-400">Strategy & Data</p>
                                </div>
                                <div className="p-6 bg-slate-50 rounded-[24px] border border-slate-100 flex flex-col items-center">
                                    <Zap className="h-5 w-5 text-[#0B3C91] mb-2" />
                                    <h5 className="font-bold text-[#1A1A1A] text-xs uppercase mb-1">Live Pros</h5>
                                    <p className="text-[10px] text-slate-400">Lawyers & Admin</p>
                                </div>
                            </div>
                        </div>
                    )}
                </div>
            </div>
        </div>
    );
}
