import { useQuery } from '@tanstack/react-query';
import { publicService } from '@/services/api/publicService';
import { motion } from 'framer-motion';
import { Activity, Database, Mail, Globe, CheckCircle2, AlertTriangle, XCircle, RefreshCcw } from 'lucide-react';
import { Button } from '@/components/ui/button';

export default function Status() {
    const { data, isLoading, isError, refetch, isRefetching } = useQuery({
        queryKey: ['system-health'],
        queryFn: publicService.getHealth,
        refetchInterval: 30000, // Refetch every 30 seconds
    });

    const status = data?.status || 'unknown';
    const checks = data?.checks || {};

    const getStatusIcon = (s: string) => {
        switch (s) {
            case 'up': return <CheckCircle2 className="w-6 h-6 text-green-500" />;
            case 'degraded': return <AlertTriangle className="w-6 h-6 text-amber-500" />;
            case 'down': return <XCircle className="w-6 h-6 text-red-500" />;
            default: return <RefreshCcw className="w-6 h-6 text-slate-400 animate-spin" />;
        }
    };

    const getOverallStatus = () => {
        if (isLoading) return { label: 'Checking Systems...', color: 'text-slate-400', bg: 'bg-slate-100' };
        if (isError) return { label: 'Connection Error', color: 'text-red-500', bg: 'bg-red-50' };
        
        switch (status) {
            case 'operational': return { label: 'All Systems Operational', color: 'text-green-600', bg: 'bg-green-50' };
            case 'degraded': return { label: 'Partial System Outage', color: 'text-amber-600', bg: 'bg-amber-50' };
            case 'down': return { label: 'Major System Outage', color: 'text-red-600', bg: 'bg-red-50' };
            default: return { label: 'Status Unknown', color: 'text-slate-400', bg: 'bg-slate-50' };
        }
    };

    const overall = getOverallStatus();

    return (
        <div className="min-h-screen bg-[#F5F7FA] py-20 px-4">
            <div className="max-w-3xl mx-auto">
                <div className="flex flex-col items-center mb-12">
                    <motion.div
                        initial={{ scale: 0.9, opacity: 0 }}
                        animate={{ scale: 1, opacity: 1 }}
                        className="p-4 bg-white rounded-3xl shadow-xl shadow-blue-900/5 border border-white mb-6"
                    >
                        <Activity className="w-12 h-12 text-[#0B3C91]" />
                    </motion.div>
                    <h1 className="text-3xl font-black text-[#1A1A1A] tracking-tight mb-2">System Status</h1>
                    <p className="text-[#6B7280] text-center max-w-md">
                        Real-time status of GoPathway platform services and infrastructure.
                    </p>
                </div>

                <motion.div
                    initial={{ y: 20, opacity: 0 }}
                    animate={{ y: 0, opacity: 1 }}
                    transition={{ delay: 0.2 }}
                    className={`rounded-[32px] p-8 mb-8 border flex flex-col sm:flex-row items-center justify-between gap-6 transition-colors duration-500 ${overall.bg} ${status === 'operational' ? 'border-green-100' : status === 'down' ? 'border-red-100' : 'border-slate-100'}`}
                >
                    <div className="flex items-center gap-4">
                        <div className="relative">
                            <div className={`w-4 h-4 rounded-full ${overall.color.replace('text', 'bg')}`} />
                            <motion.div
                                animate={{ scale: [1, 1.8, 1], opacity: [0.5, 0, 0.5] }}
                                transition={{ duration: 2, repeat: Infinity }}
                                className={`absolute inset-0 rounded-full ${overall.color.replace('text', 'bg')}`}
                            />
                        </div>
                        <span className={`text-xl font-black ${overall.color}`}>
                            {overall.label}
                        </span>
                    </div>
                    
                    <Button
                        variant="outline"
                        size="sm"
                        onClick={() => refetch()}
                        disabled={isRefetching}
                        className="rounded-xl border-white/50 bg-white/50 backdrop-blur-sm hover:bg-white"
                    >
                        <RefreshCcw className={`w-4 h-4 mr-2 ${isRefetching ? 'animate-spin' : ''}`} />
                        Refresh Status
                    </Button>
                </motion.div>

                <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <StatusCard 
                        icon={<Globe className="w-5 h-5" />}
                        label="API Services"
                        status={isError ? 'down' : isLoading ? 'loading' : checks.api || 'up'}
                        description="Core platform engine and endpoints"
                        delay={0.3}
                    />
                    <StatusCard 
                        icon={<Database className="w-5 h-5" />}
                        label="Storage Hub"
                        status={isError ? 'down' : isLoading ? 'loading' : checks.database || 'up'}
                        description="Database and document preservation"
                        delay={0.4}
                    />
                    <StatusCard 
                        icon={<Mail className="w-5 h-5" />}
                        label="Communication"
                        status={isError ? 'down' : isLoading ? 'loading' : checks.mail || 'up'}
                        description="SMTP and notification delivery"
                        delay={0.5}
                    />
                    <div className="p-6 bg-white rounded-3xl border border-[#E5E7EB] shadow-sm flex flex-col justify-center">
                        <p className="text-xs font-bold text-[#9CA3AF] uppercase tracking-widest mb-1">Last Checked</p>
                        <p className="text-sm font-mono text-[#4B5563]">
                            {data?.checks?.timestamp ? new Date(data.checks.timestamp).toLocaleTimeString() : '--:--:--'}
                        </p>
                    </div>
                </div>

                <div className="mt-12 pt-8 border-t border-[#E5E7EB] text-center">
                    <p className="text-sm text-[#6B7280]">
                        Experiencing an issue not listed here? <a href="/support" className="text-[#0B3C91] font-bold hover:underline">Contact the control tower</a>
                    </p>
                </div>
            </div>
        </div>
    );
}

function StatusCard({ icon, label, status, description, delay }: { icon: any, label: string, status: string, description: string, delay: number }) {
    const getStatusText = (s: string) => {
        if (s === 'loading') return 'Checking...';
        if (s === 'up') return 'Operational';
        if (s === 'degraded') return 'Performance Issues';
        if (s === 'down') return 'Service Outage';
        return 'Status Unknown';
    };

    const getStatusColor = (s: string) => {
        if (s === 'up') return 'text-green-500';
        if (s === 'degraded') return 'text-amber-500';
        if (s === 'down') return 'text-red-500';
        return 'text-slate-400';
    };

    return (
        <motion.div
            initial={{ y: 10, opacity: 0 }}
            animate={{ y: 0, opacity: 1 }}
            transition={{ delay }}
            className="p-6 bg-white rounded-3xl border border-[#E5E7EB] shadow-sm hover:shadow-md transition-shadow group"
        >
            <div className="flex items-start justify-between mb-2">
                <div className="p-2 bg-slate-50 rounded-xl group-hover:bg-blue-50 transition-colors text-[#0B3C91]">
                    {icon}
                </div>
                <div className="flex items-center gap-2">
                   <div className={`w-2 h-2 rounded-full ${status === 'up' ? 'bg-green-500' : status === 'down' ? 'bg-red-500' : 'bg-amber-500'}`} />
                   <span className={`text-[10px] uppercase font-bold tracking-wider ${getStatusColor(status)}`}>
                        {getStatusText(status)}
                   </span>
                </div>
            </div>
            <h3 className="font-bold text-[#1A1A1A] mb-1">{label}</h3>
            <p className="text-xs text-[#6B7280] leading-relaxed">{description}</p>
        </motion.div>
    );
}
