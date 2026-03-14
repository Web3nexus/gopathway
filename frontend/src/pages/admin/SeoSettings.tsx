import React, { useState, useEffect } from 'react';
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import { adminService } from '@/services/api/adminService';
import { useToast } from '@/hooks/use-toast';
import { Globe, Image as ImageIcon, Loader2, Save, Upload, ShieldCheck } from 'lucide-react';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';
import { Label } from '@/components/ui/label';
import { ImageCropper } from '@/components/ui/ImageCropper';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { SensitiveInput } from '@/components/ui/SensitiveInput';
import { Copy } from 'lucide-react';
import ReactQuill from 'react-quill-new';
import 'react-quill-new/dist/quill.snow.css';

export default function SeoSettings() {
    const { toast } = useToast();
    const queryClient = useQueryClient();

    const [metaTitle, setMetaTitle] = useState('');
    const [metaDesc, setMetaDesc] = useState('');
    const [gaId, setGaId] = useState('');
    
    // Compliance
    const [cookieConsentEnabled, setCookieConsentEnabled] = useState(false);
    const [cookieConsentMessage, setCookieConsentMessage] = useState('');
    const [privacyPolicyUrl, setPrivacyPolicyUrl] = useState('');
    const [termsServiceUrl, setTermsServiceUrl] = useState('');
    const [privacyPolicyContent, setPrivacyPolicyContent] = useState('');
    const [termsServiceContent, setTermsServiceContent] = useState('');
    const [documentationContent, setDocumentationContent] = useState('');

    // Security
    const [turnstileSiteKey, setTurnstileSiteKey] = useState('');
    const [turnstileSecretKey, setTurnstileSecretKey] = useState('');
    
    const [logoUrl, setLogoUrl] = useState('');
    const [faviconUrl, setFaviconUrl] = useState('');
    const [ogImageUrl, setOgImageUrl] = useState('');

    const [isCropperOpen, setIsCropperOpen] = useState(false);
    const [cropImageSrc, setCropImageSrc] = useState('');
    const [cropType, setCropType] = useState<'logo' | 'favicon' | 'ogImage' | null>(null);

    const { data, isLoading } = useQuery({
        queryKey: ['admin-settings'],
        queryFn: adminService.getSettings,
    });

    useEffect(() => {
        if (data?.data) {
            const getSettingValue = (key: string) => {
                for (const group in data.data) {
                    const setting = data.data[group].find((s: any) => s.key === key);
                    if (setting) return setting.value;
                }
                return '';
            };

            setMetaTitle(getSettingValue('site_meta_title') || '');
            setMetaDesc(getSettingValue('site_meta_description') || '');
            setGaId(getSettingValue('google_analytics_id') || '');
            
            setCookieConsentEnabled(getSettingValue('cookie_consent_enabled') === '1' || getSettingValue('cookie_consent_enabled') === true);
            setCookieConsentMessage(getSettingValue('cookie_consent_message') || '');
            setPrivacyPolicyUrl(getSettingValue('privacy_policy_url') || '');
            setTermsServiceUrl(getSettingValue('terms_service_url') || '');

            setPrivacyPolicyContent(getSettingValue('privacy_policy_content') || '');
            setTermsServiceContent(getSettingValue('terms_service_content') || '');
            setDocumentationContent(getSettingValue('documentation_content') || '');

            setTurnstileSiteKey(getSettingValue('turnstile_site_key') || '');
            setTurnstileSecretKey(getSettingValue('turnstile_secret_key') || '');

            setLogoUrl(getSettingValue('site_logo') || '');
            setFaviconUrl(getSettingValue('site_favicon') || '');
            setOgImageUrl(getSettingValue('site_og_image') || '');
        }
    }, [data]);

    const updateSettingsMutation = useMutation({
        mutationFn: (formData: FormData) => {
            // Because SettingController expects an array of settings in normal json, but we changed it to support multipart
            // WAIT: The updated SettingController expects `settings[0][key]` format for multipart.
            return adminService.uploadSettings(formData);
        },
        onSuccess: () => {
            toast({ title: 'SEO settings saved successfully!' });
            queryClient.invalidateQueries({ queryKey: ['admin-settings'] });
        },
        onError: () => {
            toast({ title: 'Failed to save settings', variant: 'destructive' });
        }
    });

    const handleTextSave = () => {
        const formData = new FormData();
        formData.append('settings[0][key]', 'site_meta_title');
        formData.append('settings[0][value]', metaTitle);
        formData.append('settings[1][key]', 'site_meta_description');
        formData.append('settings[1][value]', metaDesc);
        formData.append('settings[2][key]', 'google_analytics_id');
        formData.append('settings[2][value]', gaId);
        
        formData.append('settings[3][key]', 'cookie_consent_enabled');
        formData.append('settings[3][value]', cookieConsentEnabled ? '1' : '0');
        formData.append('settings[4][key]', 'cookie_consent_message');
        formData.append('settings[4][value]', cookieConsentMessage);
        formData.append('settings[5][key]', 'privacy_policy_url');
        formData.append('settings[5][value]', privacyPolicyUrl);
        formData.append('settings[6][key]', 'terms_service_url');
        formData.append('settings[6][value]', termsServiceUrl);
        
        formData.append('settings[7][key]', 'turnstile_site_key');
        formData.append('settings[7][value]', turnstileSiteKey);
        formData.append('settings[8][key]', 'turnstile_secret_key');
        formData.append('settings[8][value]', turnstileSecretKey);
        
        formData.append('settings[9][key]', 'privacy_policy_content');
        formData.append('settings[9][value]', privacyPolicyContent);
        formData.append('settings[10][key]', 'terms_service_content');
        formData.append('settings[10][value]', termsServiceContent);
        formData.append('settings[11][key]', 'documentation_content');
        formData.append('settings[11][value]', documentationContent);

        updateSettingsMutation.mutate(formData);
    };

    const onFileSelect = (e: React.ChangeEvent<HTMLInputElement>, type: 'logo' | 'favicon' | 'ogImage') => {
        if (e.target.files && e.target.files.length > 0) {
            const reader = new FileReader();
            reader.onload = () => {
                setCropImageSrc(reader.result as string);
                setCropType(type);
                setIsCropperOpen(true);
            };
            reader.readAsDataURL(e.target.files[0]);
        }
    };

    const handleCropComplete = (croppedFile: File) => {
        if (!cropType) return;

        const formData = new FormData();
        const settingsKey = cropType === 'logo' ? 'site_logo' : cropType === 'favicon' ? 'site_favicon' : 'site_og_image';
        
        formData.append('settings[0][key]', settingsKey);
        formData.append('settings[0][value]', croppedFile);

        updateSettingsMutation.mutate(formData, {
            onSuccess: () => {
                // Read file to display locally right away
                const objUrl = URL.createObjectURL(croppedFile);
                if (cropType === 'logo') setLogoUrl(objUrl);
                if (cropType === 'favicon') setFaviconUrl(objUrl);
                if (cropType === 'ogImage') setOgImageUrl(objUrl);
                
                toast({ title: `${cropType} updated successfully!` });
            }
        });
    };

    const getAspectRatio = () => {
        if (cropType === 'favicon') return 1; // 1:1
        if (cropType === 'ogImage') return 1.91; // 1200x630
        return undefined; // Free crop for logo
    };

    if (isLoading) return <div className="flex justify-center p-20"><Loader2 className="h-8 w-8 animate-spin text-[#0B3C91]" /></div>;

    const renderAssetUpload = (title: string, desc: string, type: 'logo' | 'favicon' | 'ogImage', previewUrl: string, id: string) => (
        <Card className="rounded-3xl border-[#E5E7EB] shadow-sm overflow-hidden flex flex-col">
            <CardHeader className="bg-slate-50/50 border-b border-slate-100 flex-none px-6 py-4">
                <CardTitle className="text-base font-bold text-[#1A1A1A]">{title}</CardTitle>
                <CardDescription className="text-xs">{desc}</CardDescription>
            </CardHeader>
            <CardContent className="p-6 flex flex-col items-center justify-center flex-grow space-y-4">
                <div className={`relative group w-full ${type === 'favicon' ? 'aspect-square max-w-[120px]' : type === 'ogImage' ? 'aspect-[1.91/1]' : 'aspect-video'} rounded-2xl bg-white border border-slate-200 border-dashed flex items-center justify-center overflow-hidden transition-all hover:border-[#0B3C91]/50 shadow-inner`}>
                    {previewUrl ? (
                        <img src={previewUrl} alt={title} className="max-w-full max-h-full object-contain p-2" />
                    ) : (
                        <div className="flex flex-col items-center gap-2 text-slate-400">
                            <ImageIcon className="w-8 h-8 opacity-20" />
                            <span className="text-[10px] uppercase font-black tracking-widest opacity-40">No Image</span>
                        </div>
                    )}
                </div>
                
                <div className="w-full">
                    <input
                        type="file"
                        id={id}
                        className="hidden"
                        accept="image/*"
                        onChange={(e) => onFileSelect(e, type)}
                    />
                    <Label htmlFor={id} className="cursor-pointer w-full">
                        <div className="w-full flex items-center justify-center rounded-xl text-sm font-bold transition-all bg-white border border-[#E5E7EB] hover:border-[#0B3C91] hover:text-[#0B3C91] h-11 px-4 shadow-sm active:scale-95">
                            <Upload className="w-4 h-4 mr-2" />
                            Manage Asset
                        </div>
                    </Label>
                </div>
            </CardContent>
        </Card>
    );

    return (
        <div className="max-w-full mx-auto p-4 md:p-8 space-y-8 pb-32">
            <div className="flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div>
                    <h1 className="text-4xl font-black text-[#1A1A1A] tracking-tight">SEO & Branding</h1>
                    <p className="text-[#6B7280] text-lg mt-1">Configure your global identity, search presence, and legal compliance.</p>
                </div>
                <div className="flex gap-3">
                    <Button 
                        onClick={handleTextSave} 
                        disabled={updateSettingsMutation.isPending}
                        className="bg-[#0B3C91] hover:bg-[#0B3C91]/90 text-white rounded-xl h-12 px-8 font-bold flex items-center gap-2 shadow-lg shadow-blue-900/10"
                    >
                        {updateSettingsMutation.isPending ? <Loader2 className="w-4 h-4 mr-2 animate-spin" /> : <Save className="w-4 h-4 mr-2" />}
                        Save All Changes
                    </Button>
                </div>
            </div>

            {/* Branding Section - Now at the Top and Full Width */}
            <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {renderAssetUpload("Site Logo", "Navbar & emails.", "logo", logoUrl, "upload-logo")}
                {renderAssetUpload("Favicon", "Browser tab (1:1).", "favicon", faviconUrl, "upload-favicon")}
                {renderAssetUpload("Social Banner", "OG Image (1.91:1).", "ogImage", ogImageUrl, "upload-og")}
            </div>

            <div className="grid grid-cols-1 xl:grid-cols-12 gap-8">
                {/* Left Column - SEO & Compliance */}
                <div className="xl:col-span-4 space-y-8">
                    <Card className="rounded-3xl border-[#E5E7EB] shadow-sm overflow-hidden">
                        <CardHeader className="bg-slate-50/50 border-b border-slate-100">
                            <CardTitle className="text-xl flex items-center gap-2">
                                <Globe className="w-5 h-5 text-[#0B3C91]" /> Search Visibility
                            </CardTitle>
                        </CardHeader>
                        <CardContent className="p-6 space-y-5">
                            <div className="space-y-2">
                                <Label className="text-sm font-bold">Meta Title</Label>
                                <Input 
                                    className="rounded-xl h-11 border-slate-200"
                                    value={metaTitle} 
                                    onChange={(e) => setMetaTitle(e.target.value)} 
                                    placeholder="GoPathway - Immigration Made Easy" 
                                />
                                <div className="flex justify-between text-[10px] uppercase font-bold tracking-wider">
                                    <span className={metaTitle.length > 60 ? "text-red-500" : "text-slate-400"}>Recommended: 50-60</span>
                                    <span className="text-slate-500">{metaTitle.length} chars</span>
                                </div>
                            </div>
                            <div className="space-y-2">
                                <Label className="text-sm font-bold">Meta Description</Label>
                                <Textarea 
                                    className="rounded-xl border-slate-200 min-h-[120px] resize-none"
                                    value={metaDesc} 
                                    onChange={(e) => setMetaDesc(e.target.value)} 
                                    placeholder="A concise summary of your platform for search engines..."
                                />
                                <div className="flex justify-between text-[10px] uppercase font-bold tracking-wider">
                                    <span className={metaDesc.length > 160 ? "text-red-500" : "text-slate-400"}>Max: 160</span>
                                    <span className="text-slate-500">{metaDesc.length} chars</span>
                                </div>
                            </div>

                            <div className="pt-4 border-t border-slate-100">
                                <Label className="text-sm font-bold block mb-2">Google Analytics (GA4)</Label>
                                <Input 
                                    className="rounded-xl h-11 border-slate-200 font-mono"
                                    value={gaId} 
                                    onChange={(e) => setGaId(e.target.value)} 
                                    placeholder="G-XXXXXXXXXX" 
                                />
                                <p className="text-[11px] text-slate-400 mt-2">Required for traffic tracking and reporting.</p>
                            </div>
                        </CardContent>
                    </Card>

                    <Card className="rounded-3xl border-[#E5E7EB] shadow-sm overflow-hidden">
                        <CardHeader className="bg-slate-50/50 border-b border-slate-100">
                            <CardTitle className="text-xl flex items-center gap-2">
                                <ShieldCheck className="w-5 h-5 text-[#0B3C91]" /> Compliance & Security
                            </CardTitle>
                        </CardHeader>
                        <CardContent className="p-6 space-y-6">
                            <div className="flex items-center justify-between p-4 bg-blue-50/50 rounded-2xl border border-blue-100">
                                <div>
                                    <Label className="font-bold block">Cookie Banner</Label>
                                    <p className="text-xs text-blue-600/80">Show GDRP consent banner.</p>
                                </div>
                                <input 
                                    type="checkbox" 
                                    className="h-6 w-6 rounded-lg border-slate-300 text-[#0B3C91] focus:ring-[#0B3C91]"
                                    checked={cookieConsentEnabled}
                                    onChange={(e) => setCookieConsentEnabled(e.target.checked)}
                                />
                            </div>
                            
                            <div className="space-y-2">
                                <Label className="text-sm font-bold">Banner Message</Label>
                                <Textarea 
                                    className="rounded-xl border-slate-200 h-20"
                                    value={cookieConsentMessage} 
                                    onChange={(e) => setCookieConsentMessage(e.target.value)} 
                                    placeholder="We use cookies to enhance your experience..."
                                />
                            </div>

                            <div className="space-y-4 pt-4 border-t border-slate-100">
                                <div className="space-y-2">
                                    <Label className="text-sm font-bold">Privacy Policy URL</Label>
                                    <Input value={privacyPolicyUrl} onChange={(e) => setPrivacyPolicyUrl(e.target.value)} placeholder="/privacy-policy" className="rounded-xl" />
                                </div>
                                <div className="space-y-2">
                                    <Label className="text-sm font-bold">Terms of Service URL</Label>
                                    <Input value={termsServiceUrl} onChange={(e) => setTermsServiceUrl(e.target.value)} placeholder="/terms-of-service" className="rounded-xl" />
                                </div>
                            </div>

                            <div className="space-y-4 pt-4 border-t border-slate-100">
                                <h3 className="text-sm font-bold flex items-center gap-2 text-orange-600">
                                    <ShieldCheck className="w-4 h-4" /> BOT Protection
                                </h3>
                                <div className="space-y-4">
                                    <div className="space-y-2">
                                        <Label className="text-[11px] uppercase font-bold text-slate-500">Cloudflare Site Key</Label>
                                        <Input value={turnstileSiteKey} onChange={(e) => setTurnstileSiteKey(e.target.value)} placeholder="1x000..." className="rounded-xl font-mono text-xs" />
                                    </div>
                                    <div className="space-y-2">
                                        <Label className="text-[11px] uppercase font-bold text-slate-500">Cloudflare Secret Key</Label>
                                        <SensitiveInput settingKey="turnstile_secret_key" value={turnstileSecretKey} onChange={(val) => setTurnstileSecretKey(val)} className="rounded-xl font-mono text-xs" />
                                    </div>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </div>

                {/* Right Column - Rich Text Editors */}
                <div className="xl:col-span-8 space-y-8">
                    <Card className="rounded-3xl border-[#E5E7EB] shadow-sm overflow-hidden">
                        <CardHeader className="bg-slate-50/50 border-b border-slate-100">
                            <CardTitle className="text-xl">Legal & Documentation Pages</CardTitle>
                            <CardDescription>Use the rich text editor below to manage internal page contents.</CardDescription>
                        </CardHeader>
                        <CardContent className="p-0">
                            <div className="divide-y divide-slate-100">
                                <div className="p-8 space-y-4">
                                    <div className="flex items-center justify-between mb-2">
                                        <Label className="text-lg font-black text-[#1A1A1A]">Privacy Policy</Label>
                                        <span className="px-3 py-1 bg-green-100 text-green-700 text-[10px] font-black uppercase rounded-full">Rich Text Enabled</span>
                                    </div>
                                    <div className="bg-white rounded-2xl overflow-hidden border border-slate-200 quill-editor-wrapper shadow-inner">
                                        <ReactQuill theme="snow" value={privacyPolicyContent} onChange={setPrivacyPolicyContent} modules={{ toolbar: [[{ 'header': [1, 2, 3, false] }], ['bold', 'italic', 'underline', 'strike'], [{'list': 'ordered'}, {'list': 'bullet'}], ['link', 'clean']] }} className="h-[350px] mb-12" />
                                    </div>
                                </div>
                                
                                <div className="p-8 space-y-4">
                                    <div className="flex items-center justify-between mb-2">
                                        <Label className="text-lg font-black text-[#1A1A1A]">Terms of Service</Label>
                                        <span className="px-3 py-1 bg-green-100 text-green-700 text-[10px] font-black uppercase rounded-full">Rich Text Enabled</span>
                                    </div>
                                    <div className="bg-white rounded-2xl overflow-hidden border border-slate-200 quill-editor-wrapper shadow-inner">
                                        <ReactQuill theme="snow" value={termsServiceContent} onChange={setTermsServiceContent} modules={{ toolbar: [[{ 'header': [1, 2, 3, false] }], ['bold', 'italic', 'underline', 'strike'], [{'list': 'ordered'}, {'list': 'bullet'}], ['link', 'clean']] }} className="h-[350px] mb-12" />
                                    </div>
                                </div>

                                <div className="p-8 space-y-4">
                                    <div className="flex items-center justify-between mb-2">
                                        <Label className="text-lg font-black text-[#1A1A1A]">System Documentation</Label>
                                        <span className="px-3 py-1 bg-blue-100 text-blue-700 text-[10px] font-black uppercase rounded-full">Admin Guide</span>
                                    </div>
                                    <div className="bg-white rounded-2xl overflow-hidden border border-slate-200 quill-editor-wrapper shadow-inner">
                                        <ReactQuill theme="snow" value={documentationContent} onChange={setDocumentationContent} modules={{ toolbar: [[{ 'header': [1, 2, 3, false] }], ['bold', 'italic', 'underline', 'strike'], [{'list': 'ordered'}, {'list': 'bullet'}], ['link', 'clean']] }} className="h-[450px] mb-12" />
                                    </div>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>

            <ImageCropper
                open={isCropperOpen}
                onOpenChange={setIsCropperOpen}
                imageSrc={cropImageSrc}
                aspectRatio={getAspectRatio()}
                onCropComplete={handleCropComplete}
            />
        </div>
    );
}

            <ImageCropper
                open={isCropperOpen}
                onOpenChange={setIsCropperOpen}
                imageSrc={cropImageSrc}
                aspectRatio={getAspectRatio()}
                onCropComplete={handleCropComplete}
            />
        </div>
    );
}
