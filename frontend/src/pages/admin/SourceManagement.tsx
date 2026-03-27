import React, { useState, useEffect } from 'react';
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from '@/components/ui/card';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Badge } from '@/components/ui/badge';
import { Play, Trash2, Edit, Globe, Activity, Plus } from 'lucide-react';
import api from '@/lib/api';
import { useToast } from '@/hooks/use-toast';
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
} from "@/components/ui/dialog";
import { Label } from "@/components/ui/label";

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
    const [queueStats, setQueueStats] = useState({ pending: 0, failed: 0 });
    
    // Modal state
    const [isDialogOpen, setIsDialogOpen] = useState(false);
    const [editingSource, setEditingSource] = useState<ScholarshipSource | null>(null);
    const [formData, setFormData] = useState({
        name: '',
        base_url: '',
        crawl_type: 'scholarship',
        is_active: false,
    });
    const [isSubmitting, setIsSubmitting] = useState(false);

    const { toast } = useToast();

    useEffect(() => {
        fetchSources();
        fetchQueueStats();
        
        // Poll for queue stats every 10 seconds
        const interval = setInterval(fetchQueueStats, 10000);
        return () => clearInterval(interval);
    }, []);

    const fetchQueueStats = async () => {
        try {
            const response = await api.get('/api/v1/admin/scholarship-sources/queue-stats');
            setQueueStats(response.data);
        } catch (error) {
            console.error('Error fetching queue stats:', error);
        }
    };

    const handleProcessQueue = async () => {
        try {
            toast({ title: 'Processing', description: 'Working on pending jobs...' });
            await api.post('/api/v1/admin/scholarship-sources/process-queue');
            toast({ title: 'Success', description: 'Queue processed.' });
            fetchQueueStats();
            fetchSources();
        } catch (error) {
            toast({ title: 'Error', description: 'Failed to process queue', variant: 'destructive' });
        }
    };

    const handleAddClick = () => {
        setEditingSource(null);
        setFormData({ name: '', base_url: '', crawl_type: 'scholarship', is_active: true });
        setIsDialogOpen(true);
    };

    const handleEditClick = (source: ScholarshipSource) => {
        setEditingSource(source);
        setFormData({ name: source.name, base_url: source.base_url, crawl_type: source.crawl_type, is_active: source.is_active });
        setIsDialogOpen(true);
    };

    const handleSave = async (e: React.FormEvent) => {
        e.preventDefault();
        setIsSubmitting(true);
        try {
            if (editingSource) {
                await api.put(`/api/v1/admin/scholarship-sources/${editingSource.id}`, formData);
                toast({ title: 'Success', description: 'Source updated successfully' });
            } else {
                await api.post('/api/v1/admin/scholarship-sources', formData);
                toast({ title: 'Success', description: 'Source created successfully' });
            }
            setIsDialogOpen(false);
            fetchSources();
        } catch (error: any) {
            toast({ title: 'Error', description: error.response?.data?.message || 'Failed to save source', variant: 'destructive' });
        } finally {
            setIsSubmitting(false);
        }
    };

    const fetchSources = async () => {
        try {
            const response = await api.get('/api/v1/admin/scholarship-sources');
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
            await api.post(`/api/v1/admin/scholarship-sources/${id}/crawl`);
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
                <Button className="bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow-lg shadow-blue-200" onClick={handleAddClick}>
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
                                                    <Button size="icon" variant="ghost" className="text-slate-400 hover:text-slate-600" onClick={() => handleEditClick(s)}>
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
                                <div className="flex justify-between items-center text-sm border-b border-white/10 pb-2">
                                    <span className="text-blue-100">Pending Jobs in Queue</span>
                                    <span className="font-bold">{queueStats.pending}</span>
                                </div>
                                <div className="flex justify-between items-center text-sm border-b border-white/10 pb-4">
                                    <span className="text-blue-100">Failed Jobs</span>
                                    <span className="font-bold text-red-200">{queueStats.failed}</span>
                                </div>
                                <Button 
                                    onClick={handleProcessQueue} 
                                    className="w-full bg-white text-blue-700 hover:bg-slate-100 mt-2"
                                    disabled={queueStats.pending === 0}
                                >
                                    Process Queue Now
                                </Button>
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

            {/* Add/Edit Dialog */}
            <Dialog open={isDialogOpen} onOpenChange={setIsDialogOpen}>
                <DialogContent>
                    <form onSubmit={handleSave}>
                        <DialogHeader>
                            <DialogTitle>{editingSource ? 'Edit Source' : 'Add New Source'}</DialogTitle>
                            <DialogDescription>
                                Configure the web crawler rules and settings for this source.
                            </DialogDescription>
                        </DialogHeader>
                        
                        <div className="space-y-4 py-4">
                            <div className="space-y-2">
                                <Label>Source Name</Label>
                                <Input 
                                    required 
                                    value={formData.name} 
                                    onChange={e => setFormData({...formData, name: e.target.value})} 
                                    placeholder="e.g. Free Apply"
                                />
                            </div>
                            <div className="space-y-2">
                                <Label>Base URL</Label>
                                <Input 
                                    required 
                                    type="url"
                                    value={formData.base_url} 
                                    onChange={e => setFormData({...formData, base_url: e.target.value})} 
                                    placeholder="https://..."
                                />
                            </div>
                            <div className="space-y-2">
                                <Label>Type</Label>
                                <select 
                                    className="flex h-10 w-full rounded-md border border-slate-200 bg-white px-3 py-2 text-sm ring-offset-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-slate-400 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                                    value={formData.crawl_type}
                                    onChange={e => setFormData({...formData, crawl_type: e.target.value})}
                                >
                                    <option value="scholarship">Scholarship</option>
                                    <option value="school">School</option>
                                </select>
                            </div>
                            <div className="flex items-center space-x-2 pt-2">
                                <input 
                                    type="checkbox" 
                                    id="is_active" 
                                    checked={formData.is_active} 
                                    onChange={e => setFormData({...formData, is_active: e.target.checked})}
                                    className="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                                />
                                <Label htmlFor="is_active">Active (Scrape daily)</Label>
                            </div>
                        </div>

                        <DialogFooter>
                            <Button type="button" variant="outline" onClick={() => setIsDialogOpen(false)}>Cancel</Button>
                            <Button type="submit" disabled={isSubmitting}>
                                {isSubmitting ? 'Saving...' : 'Save Source'}
                            </Button>
                        </DialogFooter>
                    </form>
                </DialogContent>
            </Dialog>
        </div>
    );
}
