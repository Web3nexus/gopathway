import React, { useState, useEffect } from 'react';
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from '@/components/ui/card';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Badge } from '@/components/ui/badge';
import { Plus, Play, Trash2, Edit, Globe, Activity } from 'lucide-react';
import axios from 'axios';
import { useToast } from '@/hooks/use-toast';

interface ScholarshipSource {
    id: number;
    name: string;
    base_url: string;
    crawl_type: string;
    is_active: boolean;
    last_run_at?: string;
}

export default function SourceManagement() {
    const [sources, setSources] = useState<ScholarshipSource[]>([]);
    const [loading, setLoading] = useState(true);
    const { toast } = useToast();

    useEffect(() => {
        fetchSources();
    }, []);

    const fetchSources = async () => {
        try {
            const response = await axios.get('/api/v1/admin/scholarship-sources');
            setSources(response.data);
        } catch (error) {
            console.error('Error fetching sources:', error);
            toast({ title: 'Error', description: 'Failed to load sources', variant: 'destructive' });
        } finally {
            setLoading(false);
        }
    };

    const handleCrawl = async (id: number) => {
        try {
            await axios.post(`/api/v1/admin/scholarship-sources/${id}/crawl`);
            toast({ title: 'Success', description: 'Scraping job dispatched.' });
        } catch (error) {
            toast({ title: 'Error', description: 'Failed to start scraping', variant: 'destructive' });
        }
    };

    return (
        <div className="p-6 space-y-6">
            <div className="flex justify-between items-center">
                <div>
                    <h1 className="text-2xl font-bold">Scholarship Sources</h1>
                    <p className="text-slate-500">Manage external websites for automated discovery.</p>
                </div>
                <Button className="bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow-lg shadow-blue-200">
                    <Plus className="w-4 h-4 mr-2" /> Add New Source
                </Button>
            </div>

            <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <Card className="lg:col-span-2 border-none shadow-sm">
                    <CardHeader>
                        <CardTitle>Configured Sources</CardTitle>
                        <CardDescription>Websites scheduled for daily scraping.</CardDescription>
                    </CardHeader>
                    <CardContent className="p-0">
                        <Table>
                            <TableHeader>
                                <TableRow className="hover:bg-transparent border-slate-100">
                                    <TableHead className="pl-6 py-4">Source Name</TableHead>
                                    <TableHead>Type</TableHead>
                                    <TableHead>Last Run</TableHead>
                                    <TableHead>Status</TableHead>
                                    <TableHead className="text-right pr-6">Actions</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                {loading ? (
                                    [1, 2].map(i => <TableRow key={i}><TableCell colSpan={5} className="h-16 bg-slate-50/50 animate-pulse" /></TableRow>)
                                ) : (
                                    sources.map((s) => (
                                        <TableRow key={s.id} className="hover:bg-slate-50/50 border-slate-50">
                                            <TableCell className="pl-6 font-medium">
                                                <div className="flex flex-col">
                                                    <span>{s.name}</span>
                                                    <span className="text-xs text-slate-400 font-normal truncate max-w-[200px]">{s.base_url}</span>
                                                </div>
                                            </TableCell>
                                            <TableCell>
                                                <Badge variant="outline" className="capitalize">{s.crawl_type}</Badge>
                                            </TableCell>
                                            <TableCell className="text-slate-500 text-sm">
                                                {s.last_run_at ? new Date(s.last_run_at).toLocaleString() : 'Never'}
                                            </TableCell>
                                            <TableCell>
                                                {s.is_active ? (
                                                    <Badge className="bg-green-50 text-green-600 border-none font-bold">Active</Badge>
                                                ) : (
                                                    <Badge className="bg-slate-100 text-slate-400 border-none">Inactive</Badge>
                                                )}
                                            </TableCell>
                                            <TableCell className="text-right pr-6">
                                                <div className="flex justify-end gap-2">
                                                    <Button size="icon" variant="ghost" className="text-blue-600 hover:bg-blue-50" onClick={() => handleCrawl(s.id)}>
                                                        <Play className="w-4 h-4" />
                                                    </Button>
                                                    <Button size="icon" variant="ghost" className="text-slate-400 hover:text-slate-600">
                                                        <Edit className="w-4 h-4" />
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

                <div className="space-y-6">
                    <Card className="border-none shadow-sm bg-gradient-to-br from-blue-600 to-indigo-700 text-white">
                        <CardHeader>
                            <CardTitle className="text-lg flex items-center gap-2">
                                <Activity className="w-5 h-5 text-blue-200" />
                                Crawler Status
                            </CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div className="space-y-4">
                                <div className="flex justify-between items-center text-sm border-b border-white/10 pb-2">
                                    <span className="text-blue-100">Next Scheduled Run</span>
                                    <span className="font-bold">Tomorrow, 00:00 AM</span>
                                </div>
                                <div className="flex justify-between items-center text-sm border-b border-white/10 pb-2">
                                    <span className="text-blue-100">Active Sources</span>
                                    <span className="font-bold">{sources.filter(s => s.is_active).length}</span>
                                </div>
                                <div className="flex justify-between items-center text-sm">
                                    <span className="text-blue-100">AI Tokens Used (Today)</span>
                                    <span className="font-bold">1.2k</span>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <Card className="border-none shadow-sm">
                        <CardHeader>
                            <CardTitle className="text-base">Quick Help</CardTitle>
                        </CardHeader>
                        <CardContent className="text-xs text-slate-500 leading-relaxed">
                            <p className="mb-2">Sources use CSS selectors for parsing. If a website changes structure, the AI parser will automatically attempt to fallback and extract the required fields.</p>
                            <p>Make sure to respect robots.txt and set appropriate rate limits in the scraping rules.</p>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </div>
    );
}
