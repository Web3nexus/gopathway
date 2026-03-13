import React, { useState, useRef, useEffect } from 'react';
import { MessageSquare, X, Send, Sparkles, Loader2, Bot, User, Trash2 } from 'lucide-react';
import { Button } from '@/components/ui/button';
import { useAiChat } from '@/hooks/useAiChat';
import { motion, AnimatePresence } from 'framer-motion';

export const AiChatWidget: React.FC = () => {
    const [isOpen, setIsOpen] = useState(false);
    const [input, setInput] = useState('');
    const {
        messages,
        isSending,
        sendMessage,
        activeChatId,
        createChat,
        isCreating,
        chats
    } = useAiChat();

    const scrollRef = useRef<HTMLDivElement>(null);

    useEffect(() => {
        if (scrollRef.current) {
            scrollRef.current.scrollTop = scrollRef.current.scrollHeight;
        }
    }, [messages, isOpen]);

    const handleSend = () => {
        if (!input.trim()) return;

        if (!activeChatId) {
            createChat({ title: input.substring(0, 30) + '...' });
            // The hook will set the ID, but we need to wait or handle the split logic
            // For simplicity in this widget, we check activeChatId in the effect or 
            // trigger first message after creation.
        } else {
            sendMessage({ message: input });
            setInput('');
        }
    };

    // Auto-message after creation if input exists
    useEffect(() => {
        if (activeChatId && input && !isSending && messages.length === 0) {
            sendMessage({ message: input });
            setInput('');
        }
    }, [activeChatId]);

    return (
        <div className="fixed bottom-8 right-8 z-[100]">
            <AnimatePresence>
                {isOpen && (
                    <motion.div
                        initial={{ opacity: 0, y: 20, scale: 0.95 }}
                        animate={{ opacity: 1, y: 0, scale: 1 }}
                        exit={{ opacity: 0, y: 20, scale: 0.95 }}
                        className="mb-4 w-[400px] h-[600px] bg-white rounded-[32px] shadow-2xl border border-[#E5E7EB] flex flex-col overflow-hidden"
                    >
                        {/* Header */}
                        <div className="bg-[#0B3C91] p-6 text-white flex items-center justify-between relative overflow-hidden">
                            <div className="absolute top-[-50%] right-[-10%] w-[200px] h-[200px] bg-[#00C2FF]/20 rounded-full blur-[40px]" />
                            <div className="flex items-center gap-3 relative z-10">
                                <div className="h-10 w-10 rounded-xl bg-white/10 flex items-center justify-center">
                                    <Sparkles className="h-5 w-5 text-[#00C2FF]" />
                                </div>
                                <div>
                                    <h3 className="font-black text-lg">AI Travel Assistant</h3>
                                    <div className="flex items-center gap-1.5">
                                        <div className="h-1.5 w-1.5 rounded-full bg-green-400 animate-pulse" />
                                        <span className="text-[10px] uppercase font-bold tracking-widest text-blue-200">Online & Ready</span>
                                    </div>
                                </div>
                            </div>
                            <Button
                                variant="ghost"
                                size="icon"
                                onClick={() => setIsOpen(false)}
                                className="text-white hover:bg-white/10 relative z-10"
                            >
                                <X className="h-5 w-5" />
                            </Button>
                        </div>

                        {/* Messages Area */}
                        <div
                            ref={scrollRef}
                            className="flex-1 overflow-y-auto p-6 space-y-6 bg-slate-50/50"
                        >
                            {messages.length === 0 && !isCreating && (
                                <div className="h-full flex flex-col items-center justify-center text-center p-8 space-y-4">
                                    <div className="h-16 w-16 rounded-3xl bg-[#0B3C91]/5 flex items-center justify-center">
                                        <Bot className="h-8 w-8 text-[#0B3C91]" />
                                    </div>
                                    <div>
                                        <h4 className="font-bold text-[#1A1A1A]">How can I help you today?</h4>
                                        <p className="text-xs text-[#6B7280] mt-1 leading-relaxed">
                                            Ask me about visa requirements, cost of living,
                                            or pathway strategies for your relocation.
                                        </p>
                                    </div>
                                    <div className="flex flex-wrap justify-center gap-2 pt-2">
                                        {['UK Study Visa docs', 'Canada living costs', 'SOP tips'].map(tip => (
                                            <button
                                                key={tip}
                                                onClick={() => { setInput(tip); handleSend(); }}
                                                className="text-[10px] font-bold px-3 py-1.5 bg-white border border-[#E5E7EB] rounded-full hover:border-[#0B3C91] hover:text-[#0B3C91] transition-all"
                                            >
                                                {tip}
                                            </button>
                                        ))}
                                    </div>
                                </div>
                            )}

                            {messages.map((msg: any, i: number) => (
                                <div
                                    key={i}
                                    className={`flex ${msg.role === 'user' ? 'justify-end' : 'justify-start'}`}
                                >
                                    <div className={`max-w-[85%] flex gap-3 ${msg.role === 'user' ? 'flex-row-reverse' : 'flex-row'}`}>
                                        <div className={`h-8 w-8 rounded-lg flex items-center justify-center shrink-0 ${msg.role === 'user' ? 'bg-[#0B3C91] text-white' : 'bg-white border border-[#E5E7EB] text-[#0B3C91]'
                                            }`}>
                                            {msg.role === 'user' ? <User className="h-4 w-4" /> : <Bot className="h-4 w-4" />}
                                        </div>
                                        <div className={`p-4 rounded-2xl text-sm leading-relaxed ${msg.role === 'user'
                                                ? 'bg-[#0B3C91] text-white rounded-tr-none'
                                                : 'bg-white border border-[#E5E7EB] text-[#1A1A1A] rounded-tl-none shadow-sm'
                                            }`}>
                                            {msg.content}
                                        </div>
                                    </div>
                                </div>
                            ))}

                            {(isSending || isCreating) && (
                                <div className="flex justify-start">
                                    <div className="bg-white border border-[#E5E7EB] p-4 rounded-2xl rounded-tl-none flex items-center gap-2">
                                        <Loader2 className="h-4 w-4 animate-spin text-[#0B3C91]" />
                                        <span className="text-xs text-[#6B7280] font-medium italic">Thinking...</span>
                                    </div>
                                </div>
                            )}
                        </div>

                        {/* Input Area */}
                        <div className="p-6 bg-white border-t border-[#E5E7EB]">
                            <div className="relative">
                                <textarea
                                    value={input}
                                    onChange={(e) => setInput(e.target.value)}
                                    onKeyDown={(e) => {
                                        if (e.key === 'Enter' && !e.shiftKey) {
                                            e.preventDefault();
                                            handleSend();
                                        }
                                    }}
                                    placeholder="Type your question..."
                                    className="w-full bg-[#F9FAFB] border border-[#E5E7EB] rounded-2xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#0B3C91]/20 focus:border-[#0B3C91] transition-all resize-none pr-12 min-h-[56px]"
                                    rows={1}
                                />
                                <Button
                                    size="icon"
                                    onClick={handleSend}
                                    disabled={!input.trim() || isSending || isCreating}
                                    className="absolute right-2 top-2 h-10 w-10 rounded-xl bg-[#0B3C91] hover:bg-[#0B3C91]/90 text-white"
                                >
                                    <Send className="h-4 w-4" />
                                </Button>
                            </div>
                            <p className="text-[10px] text-center text-[#9CA3AF] mt-3 uppercase font-bold tracking-tighter">
                                GoPathway AI: Specialized Travel Expert
                            </p>
                        </div>
                    </motion.div>
                )}
            </AnimatePresence>

            <motion.button
                whileHover={{ scale: 1.05 }}
                whileTap={{ scale: 0.95 }}
                onClick={() => setIsOpen(!isOpen)}
                className={`h-16 w-16 rounded-full flex items-center justify-center shadow-2xl transition-all duration-300 ${isOpen ? 'bg-white text-[#0B3C91] border border-[#E5E7EB]' : 'bg-[#0B3C91] text-white'
                    }`}
            >
                {isOpen ? <X className="h-7 w-7" /> : <MessageSquare className="h-7 w-7" />}
                {!isOpen && (
                    <div className="absolute -top-1 -right-1 h-5 w-5 bg-[#00C2FF] rounded-full border-2 border-white flex items-center justify-center">
                        <Sparkles className="h-3 w-3 text-white" />
                    </div>
                )}
            </motion.button>
        </div>
    );
};
