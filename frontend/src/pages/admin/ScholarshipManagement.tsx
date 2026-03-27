import React, { useState, useEffect } from 'react';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { CheckCircle, XCircle, Edit, Trash2, Eye } from 'lucide-react';
import axios from 'axios';
import { useToast } from '@/hooks/use-toast';

interface Scholarship {
    id: number;
    title: string;
    provider: string;
    country?: { name: string };
    status: string;
    created_at: string;
}

export default function ScholarshipManagement() {
    const [scholarships, setScholarships] = useState<Scholarship[]>([]);
    const [loading, setLoading] = useState(true);
    const [stats, setStats] = useState({ total: 0, pending: 0, approved: 0, rejected: 0 });
    const { toast } = useToast();

    useEffect(() => {
        fetchData();
    }, []);

    const fetchData = async () => {
        try {
            const [listRes, statsRes] = await Promise.all([
                axios.get('/api/v1/admin/scholarships'),
                axios.get('/api/v1/admin/scholarships/stats')
            ]);
            setScholarships(listRes.data.data);
            setStats(statsRes.data);
        } catch (error) {
            console.error('Error fetching data:', error);
            toast({ title: 'Error', description: 'Failed to load scholarships', variant: 'destructive' });
        } finally {
            setLoading(false);
        }
    };

    const handleStatus = async (id: number, status: string) => {
        try {
            await axios.put(`/api/v1/admin/scholarships/${id}`, { status });
            toast({ title: 'Success', description: `Scholarship ${status} successfully.` });
            fetchData();
        } catch (error) {
            toast({ title: 'Error', description: 'Failed to update scholarship', variant: 'destructive' });
        }
    };

    const getStatusBadge = (status: string) => {
        switch (status) {
            case 'approved': return <Badge className="bg-green-100 text-green-700 border-none">Approved</Badge>;
            case 'rejected': return <Badge className="bg-red-100 text-red-700 border-none">Rejected</Badge>;
            default: return <Badge className="bg-yellow-100 text-yellow-700 border-none">Pending</Badge>;
        }
    };

    return (
        <div className="p-6 space-y-6">
            <div className="flex justify-between items-center">
                <h1 className="text-2xl font-bold">Scholarship Management</h1>
                <div className="flex gap-4">
                    <Card className="px-4 py-2 border-none shadow-sm bg-blue-50">
                        <p className="text-xs text-blue-600 font-bold uppercase">Pending</p>
                        <p className="text-xl font-bold text-blue-900">{stats.pending}</p>
                    </Card>
                    <Card className="px-4 py-2 border-none shadow-sm bg-green-50">
                        <p className="text-xs text-green-600 font-bold uppercase">Approved</p>
                        <p className="text-xl font-bold text-green-900">{stats.approved}</p>
                    </Card>
                </div>
            </div>

            <Card className="border-none shadow-sm">
                <CardContent className="p-0">
                    <Table>
                        <TableHeader>
                            <TableRow className="hover:bg-transparent border-slate-100">
                                <TableHead className="py-4">Scholarship</TableHead>
                                <TableHead>Provider</TableHead>
                                <TableHead>Country</TableHead>
                                <TableHead>Status</TableHead>
                                <TableHead>Date Found</TableHead>
                                <TableHead className="text-right">Actions</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            {loading ? (
                                [1, 2, 3].map(i => <TableRow key={i}><TableCell colSpan={6} className="h-16 bg-slate-50/50 animate-pulse" /></TableRow>)
                            ) : (
                                scholarships.map((s) => (
                                    <TableRow key={s.id} className="hover:bg-slate-50/50 border-slate-50">
                                        <TableCell className="font-medium max-w-[250px] truncate">{s.title}</TableCell>
                                        <TableCell>{s.provider}</TableCell>
                                        <TableCell>{s.country?.name || 'N/A'}</TableCell>
                                        <TableCell>{getStatusBadge(s.status)}</TableCell>
                                        <TableCell className="text-slate-500 text-sm">{new Date(s.created_at).toLocaleDateString()}</TableCell>
                                        <TableCell className="text-right">
                                            <div className="flex justify-end gap-2">
                                                {s.status === 'pending' && (
                                                    <>
                                                        <Button size="icon" variant="ghost" className="text-green-600 hover:text-green-700 hover:bg-green-50" onClick={() => handleStatus(s.id, 'approved')}>
                                                            <CheckCircle className="w-4 h-4" />
                                                        </Button>
                                                        <Button size="icon" variant="ghost" className="text-red-600 hover:text-red-700 hover:bg-red-50" onClick={() => handleStatus(s.id, 'rejected')}>
                                                            <XCircle className="w-4 h-4" />
                                                        </Button>
                                                    </>
                                                )}
                                                <Button size="icon" variant="ghost" className="text-slate-400 hover:text-slate-600">
                                                    <Eye className="w-4 h-4" />
                                                </Button>
                                            </div>
                                        </TableCell>
                                    </TableRow>
                                ))
                            )}
                        </TableBody>
                    </Table>
                </CardContent>
            </Card>
        </div>
    );
}
