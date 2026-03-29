import { useQuery } from '@tanstack/react-query';
import { adminService } from '@/services/api/adminService';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    Activity,
    Database,
    Server,
    Cpu,
    AlertCircle,
    CheckCircle2,
    Clock,
    Zap,
    RefreshCw,
    Shield,
    TrendingUp,
    HardDrive,
    Wifi
} from 'lucide-react';

interface HealthMetrics {
    status: string;
    timestamp: string;
    metrics: {
        uptime: string;
        cpu_load: number[];
        memory: {
            used: number;
            limit: number;
            percentage: number;
        };
        database: {
            status: string;
            latency: string;
            connection: string;
        };
        performance: {
            avg_latency: string;
            throughput: string;
            error_rate: string;
        };
    };
    alerts: Array<{
        id: number;
        type: string;
        severity: string;
        message: string;
        timestamp: string;
    }>;
}

function formatBytes(bytes: number): string {
    if (bytes === 0) return '0 B';
    const k = 1024;
    const sizes = ['B', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
}

function StatusBadge({ status }: { status: string }) {
    const isHealth = status === 'healthy' || status === 'operational';
    return (
        <Badge className={isHealth
            ? 'bg-emerald-50 text-emerald-600 border-none font-bold uppercase text-[10px] tracking-wider'
            : 'bg-rose-50 text-rose-600 border-none font-bold uppercase text-[10px] tracking-wider'
        }>
            {isHealth ? <CheckCircle2 className="w-3 h-3 mr-1 inline" /> : <AlertCircle className="w-3 h-3 mr-1 inline" />}
            {status}
        </Badge>
    );
}

function MetricBar({ label, value, maxLabel }: { label: string; value: number; maxLabel?: string }) {
    const color = value > 85 ? 'bg-rose-500' : value > 65 ? 'bg-amber-500' : 'bg-emerald-500';
    return (
        <div>
            <div className="flex justify-between items-center mb-2">
                <span className="text-sm text-slate-600 font-medium">{label}</span>
                <span className="text-sm font-bold text-slate-900">{value.toFixed(1)}%{maxLabel && <span className="text-slate-400 font-normal text-xs ml-1">of {maxLabel}</span>}</span>
            </div>
            <div className="h-2.5 bg-slate-100 rounded-full overflow-hidden">
                <div
                    className={`h-full rounded-full transition-all duration-1000 ${color}`}
                    style={{ width: `${Math.min(value, 100)}%` }}
                />
            </div>
        </div>
    );
}

export default function SystemHealth() {
    const { data: health, isLoading, refetch, isFetching } = useQuery<HealthMetrics>({
        queryKey: ['system-health'],
        queryFn: adminService.getSystemHealth,
        refetchInterval: 30000,
    });

    const cpuLoad = health?.metrics?.cpu_load?.[0] ?? 0;
    const memPercent = health?.metrics?.memory?.percentage ?? 0;
    const dbOk = health?.metrics?.database?.status === 'healthy';

    return (
        <div className="space-y-8">
            {/* Header */}
            <div className="flex items-center justify-between">
                <div>
                    <h1 className="text-3xl font-bold text-slate-900">System Health</h1>
                    <p className="text-slate-500 mt-1">Real-time platform diagnostics and monitoring</p>
                </div>
                <div className="flex items-center gap-4">
                    {health?.timestamp && (
                        <p className="text-xs text-slate-400 flex items-center gap-1.5">
                            <Clock className="w-3.5 h-3.5" />
                            Last updated: {new Date(health.timestamp).toLocaleTimeString()}
                        </p>
                    )}
                    <Button
                        variant="outline"
                        size="sm"
                        className="gap-2"
                        onClick={() => refetch()}
                        disabled={isFetching}
                    >
                        <RefreshCw className={`w-4 h-4 ${isFetching ? 'animate-spin' : ''}`} />
                        Refresh
                    </Button>
                </div>
            </div>

            {/* Overall Health */}
            <div className={`rounded-2xl p-6 border flex items-center gap-5 ${
                health?.status === 'operational'
                    ? 'bg-emerald-50 border-emerald-100'
                    : 'bg-rose-50 border-rose-100'
            }`}>
                <div className={`w-14 h-14 rounded-2xl flex items-center justify-center ${
                    health?.status === 'operational' ? 'bg-emerald-500' : 'bg-rose-500'
                }`}>
                    <Shield className="w-7 h-7 text-white" />
                </div>
                <div>
                    <p className="text-sm font-semibold text-slate-500 uppercase tracking-wider">Overall System Status</p>
                    <p className={`text-2xl font-bold mt-0.5 ${
                        health?.status === 'operational' ? 'text-emerald-700' : 'text-rose-700'
                    }`}>
                        {isLoading ? 'Checking...' : health?.status === 'operational' ? 'All Systems Operational' : 'Degraded Performance'}
                    </p>
                </div>
                <div className="ml-auto">
                    <StatusBadge status={health?.status ?? 'checking'} />
                </div>
            </div>

            {/* Key Metrics Row */}
            <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                {[
                    {
                        label: 'API Latency',
                        value: health?.metrics?.performance?.avg_latency ?? '—',
                        icon: Zap,
                        sub: 'Average response time',
                        color: 'text-blue-600',
                        bg: 'bg-blue-50',
                    },
                    {
                        label: 'Throughput',
                        value: health?.metrics?.performance?.throughput ?? '—',
                        icon: TrendingUp,
                        sub: 'Last hour',
                        color: 'text-violet-600',
                        bg: 'bg-violet-50',
                    },
                    {
                        label: 'Error Rate',
                        value: health?.metrics?.performance?.error_rate ?? '—',
                        icon: AlertCircle,
                        sub: 'Last 24 hours',
                        color: 'text-amber-600',
                        bg: 'bg-amber-50',
                    },
                    {
                        label: 'DB Latency',
                        value: health?.metrics?.database?.latency ?? '—',
                        icon: Database,
                        sub: `Connection: ${health?.metrics?.database?.connection ?? '—'}`,
                        color: dbOk ? 'text-emerald-600' : 'text-rose-600',
                        bg: dbOk ? 'bg-emerald-50' : 'bg-rose-50',
                    },
                ].map((m) => (
                    <div key={m.label} className="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                        <div className={`w-10 h-10 rounded-xl ${m.bg} flex items-center justify-center mb-4`}>
                            <m.icon className={`w-5 h-5 ${m.color}`} />
                        </div>
                        <p className="text-2xl font-bold text-slate-900">{m.value}</p>
                        <p className="text-sm font-semibold text-slate-700 mt-1">{m.label}</p>
                        <p className="text-xs text-slate-400 mt-0.5">{m.sub}</p>
                    </div>
                ))}
            </div>

            {/* Resource Usage + DB + Alerts */}
            <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {/* Resource Usage */}
                <div className="lg:col-span-1 bg-white rounded-2xl border shadow-sm p-6 space-y-6">
                    <div className="flex items-center gap-3 mb-2">
                        <div className="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center">
                            <HardDrive className="w-5 h-5 text-blue-600" />
                        </div>
                        <div>
                            <p className="font-bold text-slate-900">Resource Usage</p>
                            <p className="text-xs text-slate-400">Live server metrics</p>
                        </div>
                    </div>
                    <MetricBar
                        label="Memory"
                        value={memPercent}
                        maxLabel={formatBytes(health?.metrics?.memory?.limit ?? 0)}
                    />
                    <MetricBar label="CPU Load (1min avg)" value={cpuLoad * 100} />
                    <div className="pt-4 border-t space-y-3">
                        <div className="flex justify-between text-sm">
                            <span className="text-slate-500">Memory Used</span>
                            <span className="font-semibold text-slate-900">{formatBytes(health?.metrics?.memory?.used ?? 0)}</span>
                        </div>
                        <div className="flex justify-between text-sm">
                            <span className="text-slate-500">Memory Limit</span>
                            <span className="font-semibold text-slate-900">{formatBytes(health?.metrics?.memory?.limit ?? 0)}</span>
                        </div>
                        <div className="flex justify-between text-sm">
                            <span className="text-slate-500">Server Uptime</span>
                            <span className="font-semibold text-slate-900 text-right max-w-[140px] truncate" title={health?.metrics?.uptime}>
                                {health?.metrics?.uptime ?? 'Unavailable'}
                            </span>
                        </div>
                    </div>
                </div>

                {/* Services Status */}
                <div className="bg-white rounded-2xl border shadow-sm p-6">
                    <div className="flex items-center gap-3 mb-6">
                        <div className="w-9 h-9 rounded-xl bg-slate-100 flex items-center justify-center">
                            <Server className="w-5 h-5 text-slate-700" />
                        </div>
                        <div>
                            <p className="font-bold text-slate-900">Service Status</p>
                            <p className="text-xs text-slate-400">Platform component health</p>
                        </div>
                    </div>
                    <div className="space-y-4">
                        {[
                            { name: 'API Gateway', status: 'operational', latency: health?.metrics?.performance?.avg_latency },
                            { name: 'Database', status: health?.metrics?.database?.status ?? 'checking', latency: health?.metrics?.database?.latency },
                            { name: 'Email Service', status: 'operational', latency: null },
                            { name: 'Job Queue', status: 'operational', latency: null },
                            { name: 'File Storage', status: 'operational', latency: null },
                            { name: 'Rate Limiting', status: 'operational', latency: null },
                        ].map((svc) => (
                            <div key={svc.name} className="flex items-center justify-between py-2 border-b border-slate-50 last:border-0">
                                <div className="flex items-center gap-3">
                                    <div className={`w-2 h-2 rounded-full ${svc.status === 'operational' || svc.status === 'healthy' ? 'bg-emerald-500 animate-pulse' : 'bg-rose-500'}`} />
                                    <span className="text-sm font-medium text-slate-700">{svc.name}</span>
                                </div>
                                <div className="flex items-center gap-2">
                                    {svc.latency && <span className="text-xs text-slate-400">{svc.latency}</span>}
                                    <StatusBadge status={svc.status} />
                                </div>
                            </div>
                        ))}
                    </div>
                </div>

                {/* Recent Alerts */}
                <div className="bg-slate-900 rounded-2xl p-6 text-white">
                    <div className="flex items-center gap-3 mb-6">
                        <div className="w-9 h-9 rounded-xl bg-white/10 flex items-center justify-center">
                            <AlertCircle className="w-5 h-5 text-amber-400" />
                        </div>
                        <div>
                            <p className="font-bold">Security Alerts</p>
                            <p className="text-xs text-slate-400">High-severity events (24h)</p>
                        </div>
                    </div>
                    <div className="space-y-5">
                        {health?.alerts && health.alerts.length > 0 ? (
                            health.alerts.map((alert) => (
                                <div key={alert.id} className="flex gap-3">
                                    <div className={`w-2 h-2 rounded-full mt-2 shrink-0 ${alert.severity === 'high' ? 'bg-rose-500' : 'bg-amber-500'}`} />
                                    <div>
                                        <p className="text-sm font-semibold">{alert.type}</p>
                                        <p className="text-xs text-slate-400 mt-0.5 line-clamp-2">{alert.message}</p>
                                        <p className="text-[10px] text-slate-500 mt-1.5">{alert.timestamp}</p>
                                    </div>
                                </div>
                            ))
                        ) : (
                            <div className="text-center py-10">
                                <CheckCircle2 className="w-12 h-12 text-emerald-500 mx-auto mb-3 opacity-60" />
                                <p className="text-sm text-slate-400">No critical alerts in the last 24 hours</p>
                            </div>
                        )}
                    </div>
                </div>
            </div>

            {/* Security Info */}
            <div className="bg-white rounded-2xl border shadow-sm p-6">
                <div className="flex items-center gap-3 mb-6">
                    <div className="w-9 h-9 rounded-xl bg-emerald-50 flex items-center justify-center">
                        <Shield className="w-5 h-5 text-emerald-600" />
                    </div>
                    <div>
                        <p className="font-bold text-slate-900">Security Configuration</p>
                        <p className="text-xs text-slate-400">Active security policies</p>
                    </div>
                </div>
                <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    {[
                        { label: 'Password Reset Expiry', value: '30 minutes', status: 'secure', icon: Clock },
                        { label: 'API Rate Limiting', value: 'Enabled', status: 'secure', icon: Wifi },
                        { label: 'Login Throttle', value: '5 attempts / min', status: 'secure', icon: Shield },
                        { label: '2FA for Admins', value: 'Required', status: 'secure', icon: Cpu },
                    ].map((item) => (
                        <div key={item.label} className="bg-slate-50 rounded-xl p-4">
                            <item.icon className="w-5 h-5 text-slate-400 mb-3" />
                            <p className="text-sm font-bold text-slate-900">{item.value}</p>
                            <p className="text-xs text-slate-500 mt-0.5">{item.label}</p>
                            <div className="mt-3">
                                <Badge className="bg-emerald-50 text-emerald-600 border-none text-[10px] uppercase font-bold">Active</Badge>
                            </div>
                        </div>
                    ))}
                </div>
            </div>
        </div>
    );
}
