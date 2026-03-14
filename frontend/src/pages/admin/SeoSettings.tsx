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
        return undefined; // Free crop or typical logo ratio
    };

    if (isLoading) return <div className="flex justify-center p-20"><Loader2 className="h-8 w-8 animate-spin text-[#0B3C91]" /></div>;

    const renderAssetUpload = (title: string, desc: string, type: 'logo' | 'favicon' | 'ogImage', previewUrl: string, id: string) => (
        <Card>
            <CardHeader>
                <CardTitle className="text-lg">{title}</CardTitle>
                <CardDescription>{desc}</CardDescription>
            </CardHeader>
            <CardContent>
                <div className="flex items-center gap-6">
                    <div className="w-32 h-32 rounded-xl bg-slate-50 border border-slate-200 border-dashed flex items-center justify-center overflow-hidden">
                        {previewUrl ? (
                            <img src={previewUrl} alt={title} className="max-w-full max-h-full object-contain" />
                        ) : (
                            <ImageIcon className="w-8 h-8 text-slate-300" />
                        )}
                    </div>
                    <div>
                        <input
                            type="file"
                            id={id}
                            className="hidden"
                            accept="image/*"
                            onChange={(e) => onFileSelect(e, type)}
                        />
                        <Label htmlFor={id} className="cursor-pointer">
                            <div className="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-[#0B3C91] hover:bg-[#0B3C91]/90 text-white h-10 px-4 py-2">
                                <Upload className="w-4 h-4 mr-2" />
                                Upload & Crop
                            </div>
                        </Label>
                    </div>
                </div>
            </CardContent>
        </Card>
    );

    return (
        <div className="max-w-5xl mx-auto p-6 space-y-8 pb-32">
            <div>
                <h1 className="text-3xl font-black text-[#1A1A1A] tracking-tight">SEO & Branding Settings</h1>
                <p className="text-[#6B7280]">Manage global meta tags, site logo, and social sharing banners.</p>
            </div>

            <div className="grid md:grid-cols-2 gap-8">
                <div className="space-y-6">
                    <Card>
                        <CardHeader>
                            <CardTitle className="text-xl flex items-center gap-2">
                                <Globe className="w-5 h-5 text-[#0B3C91]" /> Global Meta Tags
                            </CardTitle>
                            <CardDescription>
                                These tags are used by search engines to understand your site, and appear when linking your site anywhere online.
                            </CardDescription>
                        </CardHeader>
                        <CardContent className="space-y-4">
                            <div className="space-y-2">
                                <Label>Site Meta Title (Recommended: 50-60 characters)</Label>
                                <Input 
                                    value={metaTitle} 
                                    onChange={(e) => setMetaTitle(e.target.value)} 
                                    placeholder="GoPathway - Immigration Made Easy" 
                                />
                                <div className="text-xs text-right text-slate-400">{metaTitle.length} chars</div>
                            </div>
                            <div className="space-y-2">
                                <Label>Site Meta Description (Recommended: max 160 characters)</Label>
                                <Textarea 
                                    value={metaDesc} 
                                    onChange={(e) => setMetaDesc(e.target.value)} 
                                    placeholder="A concise summary of your platform..."
                                    className="h-24"
                                />
                                <div className="text-xs text-right text-slate-400">{metaDesc.length} chars</div>
                            </div>

                            <div className="space-y-2 pt-2 border-t">
                                <Label>Google Analytics Measurement ID (GA4)</Label>
                                <Input 
                                    value={gaId} 
                                    onChange={(e) => setGaId(e.target.value)} 
                                    placeholder="G-XXXXXXXXXX" 
                                />
                                <p className="text-xs text-slate-400">Leave empty to disable tracking.</p>
                            </div>

                            <Button className="w-full bg-[#0B3C91] hover:bg-[#0B3C91]/90" onClick={handleTextSave} disabled={updateSettingsMutation.isPending}>
                                {updateSettingsMutation.isPending ? <Loader2 className="w-4 h-4 mr-2 animate-spin" /> : <Save className="w-4 h-4 mr-2" />}
                                Save Text Settings
                            </Button>
                        </CardContent>
                    </Card>
                </div>

                <div className="space-y-6">
                    <Card>
                        <CardHeader>
                            <CardTitle className="text-xl flex items-center gap-2">
                                <ShieldCheck className="w-5 h-5 text-[#0B3C91]" /> Compliance & Legal
                            </CardTitle>
                            <CardDescription>
                                Manage cookie consent and legal document links.
                            </CardDescription>
                        </CardHeader>
                        <CardContent className="space-y-4">
                            <div className="flex items-center justify-between">
                                <Label htmlFor="cookie-enable">Enable Cookie Consent Banner</Label>
                                <input 
                                    type="checkbox" 
                                    id="cookie-enable"
                                    checked={cookieConsentEnabled}
                                    onChange={(e) => setCookieConsentEnabled(e.target.checked)}
                                    className="h-5 w-5 rounded border-gray-300 text-primary focus:ring-primary"
                                />
                            </div>
                            
                            <div className="space-y-2">
                                <Label>Cookie Consent Message</Label>
                                <Textarea 
                                    value={cookieConsentMessage} 
                                    onChange={(e) => setCookieConsentMessage(e.target.value)} 
                                    placeholder="We use cookies to enhance your experience..."
                                    className="h-20"
                                />
                            </div>

                            <div className="grid grid-cols-2 gap-4">
                                <div className="space-y-2">
                                    <Label>Privacy Policy URL</Label>
                                    <Input 
                                        value={privacyPolicyUrl} 
                                        onChange={(e) => setPrivacyPolicyUrl(e.target.value)} 
                                        placeholder="/privacy-policy" 
                                    />
                                </div>
                                <div className="space-y-2">
                                    <Label>Terms of Service URL</Label>
                                    <Input 
                                        value={termsServiceUrl} 
                                        onChange={(e) => setTermsServiceUrl(e.target.value)} 
                                        placeholder="/terms-of-service" 
                                    />
                                </div>
                            </div>

                            <div className="space-y-4 pt-4 border-t">
                                <h3 className="text-sm font-bold flex items-center gap-2">
                                    <ShieldCheck className="w-4 h-4 text-orange-500" /> Cloudflare Turnstile
                                </h3>
                                <div className="space-y-4">
                                    <div className="space-y-2">
                                        <Label>Turnstile Site Key</Label>
                                        <Input 
                                            value={turnstileSiteKey} 
                                            onChange={(e) => setTurnstileSiteKey(e.target.value)} 
                                            placeholder="1x00000000000000000000AA" 
                                        />
                                    </div>
                                    <div className="space-y-2">
                                        <Label>Turnstile Secret Key</Label>
                                        <Input 
                                            type="password"
                                            value={turnstileSecretKey} 
                                            onChange={(e) => setTurnstileSecretKey(e.target.value)} 
                                            placeholder="••••••••••••••••••••••••••••" 
                                        />
                                    </div>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    {renderAssetUpload("Site Logo", "The primary logo shown in the navbar.", "logo", logoUrl, "upload-logo")}
                    {renderAssetUpload("Favicon", "The small 1:1 icon in the browser tab (must be square).", "favicon", faviconUrl, "upload-favicon")}
                    {renderAssetUpload("Open Graph Image", "The banner image shown when sharing links on social media (1.91:1 ratio recommended).", "ogImage", ogImageUrl, "upload-og")}

                    <Card>
                        <CardHeader>
                            <CardTitle className="text-xl">Page Content</CardTitle>
                            <CardDescription>Manage the long-form content for legal and documentation pages (HTML supported).</CardDescription>
                        </CardHeader>
                        <CardContent className="space-y-6">
                            <div className="space-y-2">
                                <Label>Privacy Policy Content</Label>
                                <Textarea value={privacyPolicyContent} onChange={e => setPrivacyPolicyContent(e.target.value)} className="min-h-[200px]" />
                            </div>
                            <div className="space-y-2">
                                <Label>Terms of Service Content</Label>
                                <Textarea value={termsServiceContent} onChange={e => setTermsServiceContent(e.target.value)} className="min-h-[200px]" />
                            </div>
                            <div className="space-y-2">
                                <Label>Documentation & Features Content</Label>
                                <Textarea value={documentationContent} onChange={e => setDocumentationContent(e.target.value)} className="min-h-[200px]" />
                            </div>
                            <Button className="w-full bg-[#0B3C91] hover:bg-[#0B3C91]/90" onClick={handleTextSave} disabled={updateSettingsMutation.isPending}>
                                {updateSettingsMutation.isPending ? <Loader2 className="w-4 h-4 mr-2 animate-spin" /> : <Save className="w-4 h-4 mr-2" />}
                                Save Page Contents
                            </Button>
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
