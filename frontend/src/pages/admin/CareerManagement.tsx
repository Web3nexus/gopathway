import { useState, useEffect } from 'react';
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import api from '@/lib/api';
import { adminService } from '@/services/api/adminService';
import { Globe2, Loader2, Plus, Briefcase, FileText, Map, Trash2, Edit2, Save } from 'lucide-react';
import { Button } from '@/components/ui/button';
import { useToast } from '@/hooks/use-toast';
import {
    Dialog,
    DialogContent,
    DialogHeader,
    DialogTitle,
    DialogFooter,
} from "@/components/ui/dialog";
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';

export default function CareerManagement() {
    const { toast } = useToast();
    const queryClient = useQueryClient();
    const [selectedCountry, setSelectedCountry] = useState<number | null>(null);

    // Dialog States
    const [isPlatformDialogOpen, setIsPlatformDialogOpen] = useState(false);
    const [isRulesDialogOpen, setIsRulesDialogOpen] = useState(false);
    const [isTemplateDialogOpen, setIsTemplateDialogOpen] = useState(false);

    // Form States
    const [editingPlatform, setEditingPlatform] = useState<any>(null);
    const [platformForm, setPlatformForm] = useState({
        name: '',
        website_url: '',
        category: 'General',
        tips: [] as string[]
    });

    const [rulesForm, setRulesForm] = useState({
        temporary_reqs: '',
        permanent_reqs: '',
        citizenship_reqs: '',
        notes: ''
    });

    const [editingTemplate, setEditingTemplate] = useState<any>(null);
    const [templateForm, setTemplateForm] = useState({
        name: '',
        cv_format_rules: '',
        structure_json: '',
        is_active: true
    });

    const { data: countriesData } = useQuery({
        queryKey: ['admin-countries'],
        queryFn: () => api.get('/api/v1/countries').then(res => res.data.data)
    });

    const { data: platforms, isLoading: loadingPlatforms } = useQuery({
        queryKey: ['admin-job-platforms', selectedCountry],
        queryFn: () => adminService.getJobPlatforms(selectedCountry!),
        enabled: !!selectedCountry
    });

    const { data: rules, isLoading: loadingRules } = useQuery({
        queryKey: ['admin-residency-rules', selectedCountry],
        queryFn: () => adminService.getResidencyRules(selectedCountry!),
        enabled: !!selectedCountry
    });

    const { data: templates, isLoading: loadingTemplates } = useQuery({
        queryKey: ['admin-cv-templates', selectedCountry],
        queryFn: () => api.get(`/api/v1/cv-templates/${selectedCountry}`).then(res => res.data.data),
        enabled: !!selectedCountry
    });

    // Mutations
    const platformMutation = useMutation({
        mutationFn: (data: any) => {
            if (editingPlatform) return adminService.updateJobPlatform(editingPlatform.id, data);
            return adminService.createJobPlatform({ ...data, country_id: selectedCountry });
        },
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['admin-job-platforms'] });
            toast({ title: editingPlatform ? 'Platform updated' : 'Platform added' });
            setIsPlatformDialogOpen(false);
        }
    });

    const deletePlatformMutation = useMutation({
        mutationFn: adminService.deleteJobPlatform,
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['admin-job-platforms'] });
            toast({ title: 'Platform deleted' });
        }
    });

    const rulesMutation = useMutation({
        mutationFn: (data: any) => adminService.updateResidencyRules(selectedCountry!, data),
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['admin-residency-rules'] });
            toast({ title: 'Residency rules updated' });
            setIsRulesDialogOpen(false);
        }
    });

    const templateMutation = useMutation({
        mutationFn: (data: any) => {
            if (editingTemplate) return adminService.updateCvTemplate(editingTemplate.id, data);
            return adminService.createCvTemplate({ ...data, country_id: selectedCountry });
        },
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['admin-cv-templates'] });
            toast({ title: editingTemplate ? 'Template updated' : 'Template added' });
            setIsTemplateDialogOpen(false);
        }
    });

    const deleteTemplateMutation = useMutation({
        mutationFn: adminService.deleteCvTemplate,
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['admin-cv-templates'] });
            toast({ title: 'Template deleted' });
        }
    });

    // Handlers
    const handleEditRules = () => {
        setRulesForm({
            temporary_reqs: JSON.stringify(rules?.temporary_reqs || {}, null, 2),
            permanent_reqs: JSON.stringify(rules?.permanent_reqs || {}, null, 2),
            citizenship_reqs: JSON.stringify(rules?.citizenship_reqs || {}, null, 2),
            notes: rules?.notes || ''
        });
        setIsRulesDialogOpen(true);
    };

    const handleSaveRules = (e: React.FormEvent) => {
        e.preventDefault();
        try {
            const data = {
                temporary_reqs: JSON.parse(rulesForm.temporary_reqs),
                permanent_reqs: JSON.parse(rulesForm.permanent_reqs),
                citizenship_reqs: JSON.parse(rulesForm.citizenship_reqs),
                notes: rulesForm.notes
            };
            rulesMutation.mutate(data);
        } catch (e) {
            toast({ title: 'Invalid JSON', description: 'Please check your JSON formatting.', variant: 'destructive' });
        }
    };

    const handleOpenPlatform = (platform?: any) => {
        setEditingPlatform(platform || null);
        setPlatformForm({
            name: platform?.name || '',
            website_url: platform?.website_url || '',
            category: platform?.category || 'General',
            tips: platform?.tips || []
        });
        setIsPlatformDialogOpen(true);
    };

    const handleOpenTemplate = (template?: any) => {
        setEditingTemplate(template || null);
        setTemplateForm({
            name: template?.name || '',
            cv_format_rules: JSON.stringify(template?.cv_format_rules || {}, null, 2),
            structure_json: JSON.stringify(template?.structure_json || [], null, 2),
            is_active: template ? template.is_active : true
        });
        setIsTemplateDialogOpen(true);
    };

    return (
        <div className="space-y-6">
            <div className="flex justify-between items-center">
                <div>
                    <h1 className="text-2xl font-bold tracking-tight">Residency & Career Management</h1>
                    <p className="text-muted-foreground mt-1">Configure post-arrival job platforms, CV rules, and residency trackers per country.</p>
                </div>
            </div>

            <div className="bg-white rounded-xl shadow-sm border p-4">
                <div className="flex items-center gap-4">
                    <Globe2 className="w-5 h-5 text-slate-400" />
                    <select
                        className="flex-1 bg-slate-50 border-none rounded-lg py-2 px-4 focus:ring-0 text-sm font-medium text-slate-700"
                        value={selectedCountry || ''}
                        onChange={(e) => setSelectedCountry(e.target.value ? Number(e.target.value) : null)}
                    >
                        <option value="">Select a Country to Configure</option>
                        {countriesData?.map((c: any) => (
                            <option key={c.id} value={c.id}>{c.name}</option>
                        ))}
                    </select>
                </div>
            </div>

            {!selectedCountry ? (
                <div className="bg-slate-50 border rounded-2xl p-16 text-center text-slate-500">
                    <Globe2 className="w-12 h-12 text-slate-300 mx-auto mb-4" />
                    <p className="font-medium text-lg">Select a country above to manage its post-arrival settings.</p>
                </div>
            ) : (
                <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">

                    {/* Residency Rules */}
                    <div className="bg-white rounded-xl border shadow-sm flex flex-col">
                        <div className="p-5 border-b flex justify-between items-center bg-slate-50/50 rounded-t-xl">
                            <h2 className="font-bold flex items-center gap-2"><Map className="w-5 h-5 text-emerald-600" /> Residency Timeline Rules</h2>
                            <Button variant="outline" size="sm" onClick={handleEditRules}>Edit Rules</Button>
                        </div>
                        <div className="p-5 flex-1 max-h-[400px] overflow-y-auto">
                            {loadingRules ? (
                                <div className="flex justify-center py-8"><Loader2 className="w-6 h-6 animate-spin text-slate-400" /></div>
                            ) : rules ? (
                                <div className="space-y-4 text-sm">
                                    <div>
                                        <p className="font-bold text-slate-700 mb-1 uppercase text-xs tracking-wider">Temporary Residency</p>
                                        <div className="bg-slate-50 p-3 rounded border font-mono text-[10px] whitespace-pre-wrap">
                                            {JSON.stringify(rules.temporary_reqs || {}, null, 2)}
                                        </div>
                                    </div>
                                    <div>
                                        <p className="font-bold text-slate-700 mb-1 uppercase text-xs tracking-wider">Permanent Residency</p>
                                        <div className="bg-slate-50 p-3 rounded border font-mono text-[10px] whitespace-pre-wrap">
                                            {JSON.stringify(rules.permanent_reqs || {}, null, 2)}
                                        </div>
                                    </div>
                                    <div>
                                        <p className="font-bold text-slate-700 mb-1 uppercase text-xs tracking-wider">Citizenship</p>
                                        <div className="bg-slate-50 p-3 rounded border font-mono text-[10px] whitespace-pre-wrap">
                                            {JSON.stringify(rules.citizenship_reqs || {}, null, 2)}
                                        </div>
                                    </div>
                                </div>
                            ) : (
                                <div className="text-center py-8 text-slate-500">No residency rules configured.</div>
                            )}
                        </div>
                    </div>

                    {/* Job Platforms */}
                    <div className="bg-white rounded-xl border shadow-sm flex flex-col">
                        <div className="p-5 border-b flex justify-between items-center bg-slate-50/50 rounded-t-xl">
                            <h2 className="font-bold flex items-center gap-2"><Briefcase className="w-5 h-5 text-blue-600" /> Local Job Platforms</h2>
                            <Button size="sm" className="gap-1" onClick={() => handleOpenPlatform()}><Plus className="w-4 h-4" /> Add Platform</Button>
                        </div>
                        <div className="p-5 flex-1 max-h-[400px] overflow-y-auto">
                            {loadingPlatforms ? (
                                <div className="flex justify-center py-8"><Loader2 className="w-6 h-6 animate-spin text-slate-400" /></div>
                            ) : platforms?.length === 0 ? (
                                <div className="text-center py-8 text-slate-500">No job platforms added.</div>
                            ) : (
                                <div className="space-y-3">
                                    {platforms?.map((p: any) => (
                                        <div key={p.id} className="flex items-center justify-between p-3 bg-slate-50 rounded-lg border group">
                                            <div>
                                                <p className="font-bold text-slate-800 text-sm">{p.name}</p>
                                                <a href={p.website_url} target="_blank" rel="noreferrer" className="text-xs text-blue-600 hover:underline">{p.website_url}</a>
                                            </div>
                                            <div className="flex items-center gap-2">
                                                <span className="text-[10px] font-bold uppercase tracking-wider bg-white px-2 py-1 rounded border shadow-sm text-slate-500">{p.category || 'General'}</span>
                                                <Button variant="ghost" size="icon" className="h-8 w-8 opacity-0 group-hover:opacity-100" onClick={() => handleOpenPlatform(p)}>
                                                    <Edit2 className="w-3 h-3" />
                                                </Button>
                                                <Button variant="ghost" size="icon" className="h-8 w-8 text-red-500 opacity-0 group-hover:opacity-100" onClick={() => confirm('Delete?') && deletePlatformMutation.mutate(p.id)}>
                                                    <Trash2 className="w-3 h-3" />
                                                </Button>
                                            </div>
                                        </div>
                                    ))}
                                </div>
                            )}
                        </div>
                    </div>

                    {/* CV Templates */}
                    <div className="lg:col-span-2 bg-white rounded-xl border shadow-sm flex flex-col">
                        <div className="p-5 border-b flex justify-between items-center bg-slate-50/50 rounded-t-xl">
                            <h2 className="font-bold flex items-center gap-2"><FileText className="w-5 h-5 text-purple-600" /> Country CV Templates</h2>
                            <Button size="sm" className="gap-1 bg-purple-600 hover:bg-purple-700 text-white" onClick={() => handleOpenTemplate()}>
                                <Plus className="w-4 h-4" /> Add Template
                            </Button>
                        </div>
                        <div className="p-5 flex-1">
                            {loadingTemplates ? (
                                <div className="flex justify-center py-8"><Loader2 className="w-6 h-6 animate-spin text-slate-400" /></div>
                            ) : templates?.length === 0 ? (
                                <div className="text-center py-8 text-slate-500">No CV templates added for this country.</div>
                            ) : (
                                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    {templates?.map((t: any) => (
                                        <div key={t.id} className="p-4 rounded-xl border bg-slate-50 flex flex-col justify-between">
                                            <div>
                                                <div className="flex justify-between items-start mb-2">
                                                    <h3 className="font-bold text-slate-900">{t.name}</h3>
                                                    {t.is_active ? (
                                                        <span className="text-[9px] bg-emerald-100 text-emerald-700 px-1.5 py-0.5 rounded font-bold uppercase tracking-wider">Active</span>
                                                    ) : (
                                                        <span className="text-[9px] bg-slate-200 text-slate-500 px-1.5 py-0.5 rounded font-bold uppercase tracking-wider">Draft</span>
                                                    )}
                                                </div>
                                                <p className="text-xs text-slate-500 line-clamp-2">{Object.keys(t.cv_format_rules || {}).length} rules configured</p>
                                            </div>
                                            <div className="flex justify-end gap-2 mt-4 pt-3 border-t">
                                                <Button variant="ghost" size="sm" onClick={() => handleOpenTemplate(t)}><Edit2 className="w-4 h-4 mr-1" /> Edit</Button>
                                                <Button variant="ghost" size="sm" className="text-red-500" onClick={() => confirm('Delete?') && deleteTemplateMutation.mutate(t.id)}><Trash2 className="w-4 h-4" /></Button>
                                            </div>
                                        </div>
                                    ))}
                                </div>
                            )}
                        </div>
                    </div>

                </div>
            )}

            {/* Residency Rules Dialog */}
            <Dialog open={isRulesDialogOpen} onOpenChange={setIsRulesDialogOpen}>
                <DialogContent className="max-w-4xl max-h-[90vh] overflow-y-auto">
                    <DialogHeader>
                        <DialogTitle>Edit Residency Rules - JSON Editor</DialogTitle>
                    </DialogHeader>
                    <form onSubmit={handleSaveRules} className="space-y-4 py-4">
                        <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div className="space-y-2">
                                <Label>Temporary Req (JSON)</Label>
                                <Textarea className="font-mono text-[10px]" rows={15} value={rulesForm.temporary_reqs} onChange={e => setRulesForm({ ...rulesForm, temporary_reqs: e.target.value })} />
                            </div>
                            <div className="space-y-2">
                                <Label>Permanent Req (JSON)</Label>
                                <Textarea className="font-mono text-[10px]" rows={15} value={rulesForm.permanent_reqs} onChange={e => setRulesForm({ ...rulesForm, permanent_reqs: e.target.value })} />
                            </div>
                            <div className="space-y-2">
                                <Label>Citizenship Req (JSON)</Label>
                                <Textarea className="font-mono text-[10px]" rows={15} value={rulesForm.citizenship_reqs} onChange={e => setRulesForm({ ...rulesForm, citizenship_reqs: e.target.value })} />
                            </div>
                        </div>
                        <div className="space-y-2">
                            <Label>Expert Notes</Label>
                            <Textarea value={rulesForm.notes} onChange={e => setRulesForm({ ...rulesForm, notes: e.target.value })} />
                        </div>
                        <DialogFooter>
                            <Button type="button" variant="ghost" onClick={() => setIsRulesDialogOpen(false)}>Cancel</Button>
                            <Button type="submit" disabled={rulesMutation.isPending}><Save className="w-4 h-4 mr-2" /> Save Rules</Button>
                        </DialogFooter>
                    </form>
                </DialogContent>
            </Dialog>

            {/* Job Platform Dialog */}
            <Dialog open={isPlatformDialogOpen} onOpenChange={setIsPlatformDialogOpen}>
                <DialogContent>
                    <DialogHeader>
                        <DialogTitle>{editingPlatform ? 'Edit Job Platform' : 'Add Job Platform'}</DialogTitle>
                    </DialogHeader>
                    <form onSubmit={(e) => { e.preventDefault(); platformMutation.mutate(platformForm); }} className="space-y-4 py-4">
                        <div className="space-y-2">
                            <Label>Platform Name</Label>
                            <Input value={platformForm.name} onChange={e => setPlatformForm({ ...platformForm, name: e.target.value })} required placeholder="e.g. LinkedIn" />
                        </div>
                        <div className="space-y-2">
                            <Label>Website URL</Label>
                            <Input type="url" value={platformForm.website_url} onChange={e => setPlatformForm({ ...platformForm, website_url: e.target.value })} required placeholder="https://..." />
                        </div>
                        <div className="space-y-2">
                            <Label>Category</Label>
                            <Input value={platformForm.category} onChange={e => setPlatformForm({ ...platformForm, category: e.target.value })} placeholder="e.g. General, Tech, Health" />
                        </div>
                        <DialogFooter>
                            <Button type="button" variant="ghost" onClick={() => setIsPlatformDialogOpen(false)}>Cancel</Button>
                            <Button type="submit" disabled={platformMutation.isPending}>Save Platform</Button>
                        </DialogFooter>
                    </form>
                </DialogContent>
            </Dialog>

            {/* CV Template Dialog */}
            <Dialog open={isTemplateDialogOpen} onOpenChange={setIsTemplateDialogOpen}>
                <DialogContent className="max-w-2xl">
                    <DialogHeader>
                        <DialogTitle>{editingTemplate ? 'Edit CV Template' : 'Add CV Template'}</DialogTitle>
                    </DialogHeader>
                    <form onSubmit={(e) => {
                        e.preventDefault();
                        try {
                            templateMutation.mutate({
                                ...templateForm,
                                cv_format_rules: JSON.parse(templateForm.cv_format_rules),
                                structure_json: JSON.parse(templateForm.structure_json)
                            });
                        } catch (err) {
                            toast({ title: 'Invalid JSON', variant: 'destructive' });
                        }
                    }} className="space-y-4 py-4">
                        <div className="space-y-2">
                            <Label>Template Name</Label>
                            <Input value={templateForm.name} onChange={e => setTemplateForm({ ...templateForm, name: e.target.value })} required placeholder="e.g. British Standard CV" />
                        </div>
                        <div className="grid grid-cols-2 gap-4">
                            <div className="space-y-2">
                                <Label>Format Rules (JSON - photo, page_limit etc)</Label>
                                <Textarea className="font-mono text-xs" rows={10} value={templateForm.cv_format_rules} onChange={e => setTemplateForm({ ...templateForm, cv_format_rules: e.target.value })} />
                            </div>
                            <div className="space-y-2">
                                <Label>Structure (JSON - Sections list)</Label>
                                <Textarea className="font-mono text-xs" rows={10} value={templateForm.structure_json} onChange={e => setTemplateForm({ ...templateForm, structure_json: e.target.value })} />
                            </div>
                        </div>
                        <DialogFooter>
                            <Button type="button" variant="ghost" onClick={() => setIsTemplateDialogOpen(false)}>Cancel</Button>
                            <Button type="submit" disabled={templateMutation.isPending}>Save Template</Button>
                        </DialogFooter>
                    </form>
                </DialogContent>
            </Dialog>

        </div>
    );
}
