import React, { useState, useEffect } from 'react';
import { adminService } from '@/services/api/adminService';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import { useToast } from '@/hooks/use-toast';
import { Loader2, Mail, Save, Send, Info, Key, Server, Settings, Edit2 } from 'lucide-react';
import { SensitiveInput } from '@/components/ui/SensitiveInput';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';

interface EmailTemplate {
    id: number;
    key: string;
    subject: string;
    content: string;
    variables: Record<string, string>;
}

export default function EmailManagement() {
    const { toast } = useToast();
    const [isLoading, setIsLoading] = useState(true);
    const [isSaving, setIsSaving] = useState(false);
    const [isTesting, setIsTesting] = useState(false);
    const [settings, setSettings] = useState<any[]>([]);
    const [templates, setTemplates] = useState<EmailTemplate[]>([]);
    const [editingTemplate, setEditingTemplate] = useState<EmailTemplate | null>(null);
    const [formData, setFormData] = useState<Record<string, any>>({});

    useEffect(() => {
        fetchData();
    }, []);

    const fetchData = async () => {
        setIsLoading(true);
        try {
            const [settingsRes, templatesRes] = await Promise.all([
                adminService.getSettings(),
                adminService.getEmailTemplates()
            ]);
            
            // Filter only Email group settings
            const emailSettings = settingsRes.data.filter((s: any) => s.group === 'Email');
            setSettings(emailSettings);
            setTemplates(templatesRes);

            // Initialize form data
            const initialData: Record<string, any> = {};
            emailSettings.forEach((s: any) => {
                initialData[s.key] = s.value;
            });
            setFormData(initialData);

        } catch (error) {
            toast({ title: 'Error', description: 'Failed to load email settings.', variant: 'destructive' });
        } finally {
            setIsLoading(false);
        }
    };

    const handleSaveSettings = async () => {
        setIsSaving(true);
        try {
            const payload = Object.keys(formData).map(key => ({
                key,
                value: formData[key]
            }));
            await adminService.updateSettings(payload);
            toast({ title: 'Success', description: 'SMTP settings updated successfully.' });
        } catch (error) {
            toast({ title: 'Error', description: 'Failed to update settings.', variant: 'destructive' });
        } finally {
            setIsSaving(false);
        }
    };

    const handleTestConnection = async () => {
        setIsTesting(true);
        try {
            const res = await adminService.testMailConnection();
            toast({ title: 'Success', description: res.message });
        } catch (error: any) {
            toast({ 
                title: 'Connection Failed', 
                description: error.response?.data?.message || 'Failed to send test email.', 
                variant: 'destructive' 
            });
        } finally {
            setIsTesting(false);
        }
    };

    const handleUpdateTemplate = async () => {
        if (!editingTemplate) return;
        setIsSaving(true);
        try {
            await adminService.updateEmailTemplate(editingTemplate.id, {
                subject: editingTemplate.subject,
                content: editingTemplate.content
            });
            toast({ title: 'Success', description: 'Template updated successfully.' });
            setEditingTemplate(null);
            fetchData();
        } catch (error) {
            toast({ title: 'Error', description: 'Failed to update template.', variant: 'destructive' });
        } finally {
            setIsSaving(false);
        }
    };

    if (isLoading) {
        return (
            <div className="flex h-[400px] items-center justify-center">
                <Loader2 className="h-8 w-8 animate-spin text-[#0B3C91]" />
            </div>
        );
    }

    return (
        <div className="space-y-6 max-w-6xl mx-auto py-8 px-4">
            <div className="flex flex-col gap-1">
                <h1 className="text-2xl font-bold text-[#1A1A1A]">Email Management</h1>
                <p className="text-sm text-[#6B7280]">Configure SMTP settings and manage system email templates.</p>
            </div>

            <Tabs defaultValue="smtp" className="w-full">
                <TabsList className="bg-white p-1 h-12 rounded-xl border border-[#E5E7EB]">
                    <TabsTrigger value="smtp" className="rounded-lg h-full px-8 data-[state=active]:bg-[#0B3C91] data-[state=active]:text-white">
                        <Server className="w-4 h-4 mr-2" />
                        SMTP Settings
                    </TabsTrigger>
                    <TabsTrigger value="templates" className="rounded-lg h-full px-8 data-[state=active]:bg-[#0B3C91] data-[state=active]:text-white">
                        <Mail className="w-4 h-4 mr-2" />
                        Email Templates
                    </TabsTrigger>
                </TabsList>

                <TabsContent value="smtp" className="mt-6">
                    <Card className="border-none shadow-sm shadow-[#0B3C91]/5">
                        <CardHeader className="border-b border-slate-50">
                            <div className="flex justify-between items-center">
                                <div>
                                    <CardTitle className="text-lg font-bold">Mail Server Configuration</CardTitle>
                                    <CardDescription>Enter your SMTP server details to enable outgoing emails.</CardDescription>
                                </div>
                                <Button 
                                    onClick={handleTestConnection} 
                                    disabled={isTesting}
                                    variant="outline"
                                    className="border-[#0B3C91] text-[#0B3C91] hover:bg-[#0B3C91] hover:text-white"
                                >
                                    {isTesting ? <Loader2 className="mr-2 h-4 w-4 animate-spin" /> : <Send className="mr-2 h-4 w-4" />}
                                    Send Test Email
                                </Button>
                            </div>
                        </CardHeader>
                        <CardContent className="pt-6">
                            <div className="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                                {settings.map((s) => (
                                    <div key={s.key} className="space-y-2">
                                        <div className="flex items-center gap-2">
                                            <Label className="text-xs font-bold uppercase tracking-wider text-[#6B7280]">
                                                {s.label}
                                            </Label>
                                            <Info className="w-3 h-3 text-[#9CA3AF]" title={s.description} />
                                        </div>
                                        {s.type === 'encrypted_string' ? (
                                            <SensitiveInput
                                                settingKey={s.key}
                                                value={formData[s.key] || ''}
                                                onChange={(val) => setFormData(p => ({ ...p, [s.key]: val }))}
                                            />
                                        ) : (
                                            <Input
                                                value={formData[s.key] || ''}
                                                onChange={(e) => setFormData(p => ({ ...p, [s.key]: e.target.value }))}
                                                placeholder={`Enter ${s.label}`}
                                                className="rounded-xl border-[#E5E7EB] focus:ring-[#0B3C91] h-11"
                                            />
                                        )}
                                    </div>
                                ))}
                            </div>
                            <div className="mt-8 pt-8 border-t border-slate-50 flex justify-end">
                                <Button 
                                    onClick={handleSaveSettings} 
                                    className="bg-[#0B3C91] hover:bg-[#0B3C91]/90 rounded-xl px-8"
                                    disabled={isSaving}
                                >
                                    {isSaving ? <Loader2 className="mr-2 h-4 w-4 animate-spin" /> : <Save className="mr-2 h-4 w-4" />}
                                    Save Changes
                                </Button>
                            </div>
                        </CardContent>
                    </Card>
                </TabsContent>

                <TabsContent value="templates" className="mt-6">
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {templates.map((template) => (
                            <Card key={template.id} className="border-none shadow-sm shadow-[#0B3C91]/5 overflow-hidden group">
                                <CardHeader className="bg-slate-50/50 space-y-1">
                                    <div className="flex justify-between items-start">
                                        <div className="p-2 bg-white rounded-lg border border-slate-100 mb-2">
                                            <Mail className="w-5 h-5 text-[#0B3C91]" />
                                        </div>
                                        <Button
                                            variant="ghost"
                                            size="sm"
                                            className="opacity-0 group-hover:opacity-100 transition-opacity"
                                            onClick={() => setEditingTemplate(template)}
                                        >
                                            <Edit2 className="w-4 h-4 mr-2" />
                                            Edit
                                        </Button>
                                    </div>
                                    <CardTitle className="text-base font-bold">{template.key.replace(/_/g, ' ').toUpperCase()}</CardTitle>
                                    <CardDescription className="line-clamp-1">{template.subject}</CardDescription>
                                </CardHeader>
                                <CardContent className="pt-4">
                                    <div className="text-xs text-[#6B7280] mb-3 font-medium">AVAILABLE VARIABLES:</div>
                                    <div className="flex flex-wrap gap-2">
                                        {Object.keys(template.variables || {}).map(v => (
                                            <code key={v} className="bg-slate-100 px-2 py-0.5 rounded text-[10px] text-[#0B3C91] font-mono">
                                                {"{{" + v + "}}"}
                                            </code>
                                        ))}
                                    </div>
                                </CardContent>
                            </Card>
                        ))}
                    </div>
                </TabsContent>
            </Tabs>

            {/* Edit Template Modal */}
            <Dialog open={!!editingTemplate} onOpenChange={(open) => !open && setEditingTemplate(null)}>
                <DialogContent className="max-w-2xl max-h-[90vh] overflow-y-auto">
                    <DialogHeader>
                        <DialogTitle>Edit Email Template</DialogTitle>
                        <DialogDescription>
                            Customize the content of the {editingTemplate?.key} notification.
                        </DialogDescription>
                    </DialogHeader>
                    {editingTemplate && (
                        <div className="space-y-6 py-4">
                            <div className="space-y-2">
                                <Label>Subject Line</Label>
                                <Input
                                    value={editingTemplate.subject}
                                    onChange={(e) => setEditingTemplate(p => p ? { ...p, subject: e.target.value } : null)}
                                    className="rounded-xl border-[#E5E7EB]"
                                />
                            </div>
                            <div className="space-y-2">
                                <Label>Content (Markdown supported)</Label>
                                <Textarea
                                    value={editingTemplate.content}
                                    onChange={(e) => setEditingTemplate(p => p ? { ...p, content: e.target.value } : null)}
                                    className="min-h-[300px] rounded-xl border-[#E5E7EB] font-mono text-sm leading-relaxed"
                                />
                            </div>
                            <div className="bg-blue-50/50 p-4 rounded-xl border border-blue-100/50">
                                <div className="flex items-center gap-2 mb-2">
                                    <Info className="w-4 h-4 text-[#0B3C91]" />
                                    <span className="text-xs font-bold text-[#0B3C91] uppercase tracking-wider">Reference Variables</span>
                                </div>
                                <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    {Object.entries(editingTemplate.variables || {}).map(([v, desc]) => (
                                        <div key={v} className="space-y-1">
                                            <code className="text-[11px] font-bold text-[#0B3C91]">{"{{" + v + "}}"}</code>
                                            <p className="text-[10px] text-[#6B7280]">{desc}</p>
                                        </div>
                                    ))}
                                </div>
                            </div>
                        </div>
                    )}
                    <DialogFooter>
                        <Button variant="outline" onClick={() => setEditingTemplate(null)}>Cancel</Button>
                        <Button
                            onClick={handleUpdateTemplate}
                            disabled={isSaving}
                            className="bg-[#0B3C91] hover:bg-[#0B3C91]/90"
                        >
                            {isSaving && <Loader2 className="mr-2 h-4 w-4 animate-spin" />}
                            Update Template
                        </Button>
                    </DialogFooter>
                </DialogContent>
            </Dialog>
        </div>
    );
}
