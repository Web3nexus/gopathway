import React, { useState, useEffect } from 'react';
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import { adminService } from '@/services/api/adminService';
import { useToast } from '@/hooks/use-toast';
import { Globe, Image as ImageIcon, Loader2, Save, Upload } from 'lucide-react';
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
                            <Button className="w-full bg-[#0B3C91] hover:bg-[#0B3C91]/90" onClick={handleTextSave} disabled={updateSettingsMutation.isPending}>
                                {updateSettingsMutation.isPending ? <Loader2 className="w-4 h-4 mr-2 animate-spin" /> : <Save className="w-4 h-4 mr-2" />}
                                Save Text Settings
                            </Button>
                        </CardContent>
                    </Card>
                </div>

                <div className="space-y-6">
                    {renderAssetUpload("Site Logo", "The primary logo shown in the navbar.", "logo", logoUrl, "upload-logo")}
                    {renderAssetUpload("Favicon", "The small 1:1 icon in the browser tab (must be square).", "favicon", faviconUrl, "upload-favicon")}
                    {renderAssetUpload("Open Graph Image", "The banner image shown when sharing links on social media (1.91:1 ratio recommended).", "ogImage", ogImageUrl, "upload-og")}
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
