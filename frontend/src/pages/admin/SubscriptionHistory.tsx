import { useState } from 'react';
import { useQuery } from '@tanstack/react-query';
import { adminService } from '@/services/api/adminService';
import { 
    Search, 
    CreditCard, 
    Calendar, 
    User, 
    ArrowUpRight, 
    Loader2,
    Filter,
    Download
} from 'lucide-react';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';

export default function SubscriptionHistory() {
    const [searchTerm, setSearchTerm] = useState('');
    const [page, setPage] = useState(1);

    const { data: historyData, isLoading } = useQuery({
        queryKey: ['admin-subscription-history', searchTerm, page],
        queryFn: () => adminService.getSubscriptionHistory({ search: searchTerm, page }),
    });

    const logs = historyData?.data || [];
    const meta = historyData; // Pagination meta is usually in the root or 'meta' field

    return (
        <div className="space-y-6">
            <div className="flex items-center justify-between">
                <div>
                    <h1 className="text-2xl font-bold tracking-tight">Subscription History</h1>
                    <p className="text-muted-foreground">Monitor all payments and subscription logs across the platform.</p>
                </div>
                <Button variant="outline" className="gap-2">
                    <Download className="w-4 h-4" /> Export CSV
                </Button>
            </div>

            <Card>
                <CardHeader className="pb-3">
                    <div className="flex items-center justify-between">
                        <CardTitle className="text-lg font-semibold flex items-center gap-2">
                            <CreditCard className="w-5 h-5 text-blue-500" /> All Transactions
                        </CardTitle>
                        <div className="flex items-center gap-2">
                            <div className="relative w-64">
                                <Search className="absolute left-2.5 top-2.5 h-4 w-4 text-muted-foreground" />
                                <Input
                                    placeholder="Search by user or ref..."
                                    className="pl-8 h-9"
                                    value={searchTerm}
                                    onChange={(e) => setSearchTerm(e.target.value)}
                                />
                            </div>
                            <Button variant="outline" size="sm" className="gap-2 h-9">
                                <Filter className="w-4 h-4" /> Filter
                            </Button>
                        </div>
                    </div>
                </CardHeader>
                <CardContent>
                    <div className="rounded-md border border-slate-800">
                        <Table>
                            <TableHeader className="bg-slate-900/50">
                                <TableRow className="hover:bg-transparent border-slate-800">
                                    <TableHead className="text-slate-400">User</TableHead>
                                    <TableHead className="text-slate-400">Plan</TableHead>
                                    <TableHead className="text-slate-400">Amount</TableHead>
                                    <TableHead className="text-slate-400">Reference</TableHead>
                                    <TableHead className="text-slate-400">Status</TableHead>
                                    <TableHead className="text-slate-400">Date</TableHead>
                                    <TableHead className="text-right text-slate-400">Action</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                {isLoading ? (
                                    <TableRow>
                                        <TableCell colSpan={7} className="h-24 text-center">
                                            <Loader2 className="w-6 h-6 animate-spin mx-auto text-primary" />
                                        </TableCell>
                                    </TableRow>
                                ) : logs.length === 0 ? (
                                    <TableRow>
                                        <TableCell colSpan={7} className="h-24 text-center text-muted-foreground">
                                            No transactions found.
                                        </TableCell>
                                    </TableRow>
                                ) : (
                                    logs.map((log: any) => (
                                        <TableRow key={log.id} className="hover:bg-slate-800/30 border-slate-800 transition-colors">
                                            <TableCell>
                                                <div className="flex flex-col">
                                                    <span className="font-medium text-slate-200">{log.user?.name || 'Unknown User'}</span>
                                                    <span className="text-xs text-slate-500">{log.user?.email}</span>
                                                </div>
                                            </TableCell>
                                            <TableCell>
                                                <span className="px-2 py-0.5 rounded-full bg-blue-500/10 text-blue-400 text-[10px] font-bold uppercase tracking-wider border border-blue-500/20">
                                                    {log.plan_name || 'N/A'}
                                                </span>
                                            </TableCell>
                                            <TableCell>
                                                <div className="flex items-center gap-1 font-bold text-slate-200">
                                                    <span>{log.currency}</span>
                                                    <span>{parseFloat(log.amount).toLocaleString()}</span>
                                                </div>
                                            </TableCell>
                                            <TableCell>
                                                <code className="text-[10px] bg-slate-800 px-1.5 py-0.5 rounded text-slate-400">
                                                    {log.reference}
                                                </code>
                                            </TableCell>
                                            <TableCell>
                                                <span className={`px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider border ${
                                                    log.status === 'success' 
                                                    ? 'bg-green-500/10 text-green-400 border-green-500/20' 
                                                    : 'bg-red-500/10 text-red-400 border-red-500/20'
                                                }`}>
                                                    {log.status}
                                                </span>
                                            </TableCell>
                                            <TableCell>
                                                <div className="flex flex-col text-xs text-slate-400">
                                                    <span className="flex items-center gap-1">
                                                        <Calendar className="w-3 h-3" />
                                                        {new Date(log.created_at).toLocaleDateString()}
                                                    </span>
                                                    <span>{new Date(log.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}</span>
                                                </div>
                                            </TableCell>
                                            <TableCell className="text-right">
                                                <Button variant="ghost" size="icon" className="h-8 w-8 hover:bg-slate-700">
                                                    <ArrowUpRight className="w-4 h-4 text-slate-500" />
                                                </Button>
                                            </TableCell>
                                        </TableRow>
                                    ))
                                )}
                            </TableBody>
                        </Table>
                    </div>

                    {/* Simple Pagination */}
                    {meta?.total > meta?.per_page && (
                        <div className="flex items-center justify-between mt-4 text-sm text-slate-400">
                            <div>
                                Showing {meta.from} to {meta.to} of {meta.total} results
                            </div>
                            <div className="flex items-center gap-2">
                                <Button 
                                    variant="outline" 
                                    size="sm" 
                                    disabled={page === 1}
                                    onClick={() => setPage(p => p - 1)}
                                    className="h-8 border-slate-800 hover:bg-slate-800"
                                >
                                    Previous
                                </Button>
                                <Button 
                                    variant="outline" 
                                    size="sm"
                                    disabled={page === meta.last_page}
                                    onClick={() => setPage(p => p + 1)}
                                    className="h-8 border-slate-800 hover:bg-slate-800"
                                >
                                    Next
                                </Button>
                            </div>
                        </div>
                    )}
                </CardContent>
            </Card>
        </div>
    );
}
