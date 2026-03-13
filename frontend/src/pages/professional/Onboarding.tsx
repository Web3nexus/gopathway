import { useState } from 'react';
import { useMutation, useQueryClient } from '@tanstack/react-query';
import { professionalService } from '@/services/api/professionalService';
import { useToast } from '@/hooks/use-toast';
import { useNavigate } from 'react-router-dom';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue
} from '@/components/ui/select';
import { Card, CardHeader, CardTitle, CardDescription, CardContent, CardFooter } from '@/components/ui/card';
import { ShieldCheck, Upload, Briefcase, Languages, FileText } from 'lucide-react';

export default function ProfessionalOnboarding() {
    const { toast } = useToast();
    const navigate = useNavigate();
    const queryClient = useQueryClient();
    const [step, setStep] = useState(1);
    const [formData, setFormData] = useState({
        type: '',
        bio: '',
        years_of_experience: '',
        specialization: '',
        languages: '',
    });
    const [file, setFile] = useState<File | null>(null);

    const mutation = useMutation({
        mutationFn: professionalService.apply,
        onSuccess: () => {
            toast({
                title: "Application Submitted",
                description: "Your credentials are being reviewed by our team.",
            });
            queryClient.invalidateQueries({ queryKey: ['professional-status'] });
            navigate('/professional/dashboard');
        },
        onError: (error: any) => {
            toast({
                title: "Application Failed",
                description: error.response?.data?.message || "Something went wrong.",
                variant: "destructive",
            });
        },
    });

    const handleSubmit = () => {
        if (!formData.type || !formData.bio || !file) {
            toast({
                title: "Missing Information",
                description: "Please fill in all required fields and upload your document.",
                variant: "destructive",
            });
            return;
        }

        const data = new FormData();
        data.append('type', formData.type);
        data.append('bio', formData.bio);
        data.append('years_of_experience', formData.years_of_experience);

        // Handle JSON fields
        const specs = formData.specialization.split(',').map(s => s.trim()).filter(Boolean);
        specs.forEach((s, i) => data.append(`specialization[${i}]`, s));

        const langs = formData.languages.split(',').map(l => l.trim()).filter(Boolean);
        langs.forEach((l, i) => data.append(`languages[${i}]`, l));

        data.append('document', file);

        mutation.mutate(data);
    };

    return (
        <div className="max-w-3xl mx-auto py-10 px-4">
            <div className="text-center mb-10">
                <div className="inline-flex items-center justify-center p-3 bg-blue-50 rounded-2xl mb-4">
                    <ShieldCheck className="h-8 w-8 text-[#0B3C91]" />
                </div>
                <h1 className="text-3xl font-extrabold text-[#1A1A1A] tracking-tight">Professional Onboarding</h1>
                <p className="text-[#6B7280] mt-2">Join our network of verified relocation experts</p>
            </div>

            <Card className="border-[#E5E7EB] shadow-sm overflow-hidden">
                <CardHeader className="bg-[#F5F7FA] border-b border-[#E5E7EB]">
                    <div className="flex items-center justify-between">
                        <div>
                            <CardTitle className="text-lg">Step {step} of 2</CardTitle>
                            <CardDescription>
                                {step === 1 ? 'Tell us about your background' : 'Verification documents'}
                            </CardDescription>
                        </div>
                        <div className="flex gap-1">
                            <div className={`h-2 w-8 rounded-full ${step >= 1 ? 'bg-[#0B3C91]' : 'bg-[#E5E7EB]'}`} />
                            <div className={`h-2 w-8 rounded-full ${step >= 2 ? 'bg-[#0B3C91]' : 'bg-[#E5E7EB]'}`} />
                        </div>
                    </div>
                </CardHeader>

                <CardContent className="pt-6 space-y-6">
                    {step === 1 ? (
                        <>
                            <div className="space-y-4">
                                <Label>Role Type</Label>
                                <Select
                                    value={formData.type}
                                    onValueChange={(v) => setFormData({ ...formData, type: v })}
                                >
                                    <SelectTrigger className="h-12">
                                        <SelectValue placeholder="Select your profession" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="lawyer">Immigration Lawyer / Expert</SelectItem>
                                        <SelectItem value="translator">Certified Translator</SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>

                            <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div className="space-y-2">
                                    <Label htmlFor="experience">Years of Experience</Label>
                                    <div className="relative">
                                        <Briefcase className="absolute left-3 top-3 h-4 w-4 text-gray-400" />
                                        <Input
                                            id="experience"
                                            className="pl-10 h-10"
                                            type="number"
                                            placeholder="e.g. 5"
                                            value={formData.years_of_experience}
                                            onChange={(e) => setFormData({ ...formData, years_of_experience: e.target.value })}
                                        />
                                    </div>
                                </div>
                                <div className="space-y-2">
                                    <Label htmlFor="languages">{formData.type === 'translator' ? 'Language Pairs' : 'Consultation Languages'}</Label>
                                    <div className="relative">
                                        <Languages className="absolute left-3 top-3 h-4 w-4 text-gray-400" />
                                        <Input
                                            id="languages"
                                            className="pl-10 h-10"
                                            placeholder="e.g. English, French, Arabic"
                                            value={formData.languages}
                                            onChange={(e) => setFormData({ ...formData, languages: e.target.value })}
                                        />
                                    </div>
                                </div>
                            </div>

                            <div className="space-y-2">
                                <Label htmlFor="specialization">Specialization (comma separated)</Label>
                                <Input
                                    id="specialization"
                                    placeholder="e.g. Student Visas, Digital Nomads, PR Applications"
                                    value={formData.specialization}
                                    onChange={(e) => setFormData({ ...formData, specialization: e.target.value })}
                                />
                            </div>

                            <div className="space-y-2">
                                <Label htmlFor="bio">Professional Bio</Label>
                                <Textarea
                                    id="bio"
                                    placeholder="Briefly describe your expertise and how you help clients..."
                                    className="min-h-[120px]"
                                    value={formData.bio}
                                    onChange={(e) => setFormData({ ...formData, bio: e.target.value })}
                                />
                            </div>
                        </>
                    ) : (
                        <div className="space-y-6">
                            <div className="p-10 border-2 border-dashed border-[#E5E7EB] rounded-2xl flex flex-col items-center justify-center bg-[#F9FAFB] hover:bg-gray-50 transition-colors cursor-pointer relative">
                                <Input
                                    type="file"
                                    className="absolute inset-0 opacity-0 cursor-pointer"
                                    onChange={(e) => setFile(e.target.files?.[0] || null)}
                                    accept=".pdf,.jpg,.png"
                                />
                                <div className="h-14 w-14 rounded-full bg-white shadow-sm flex items-center justify-center mb-4">
                                    <Upload className="h-6 w-6 text-[#0B3C91]" />
                                </div>
                                {file ? (
                                    <div className="text-center">
                                        <p className="font-semibold text-[#1A1A1A]">{file.name}</p>
                                        <p className="text-xs text-[#6B7280]">{(file.size / 1024 / 1024).toFixed(2)} MB</p>
                                    </div>
                                ) : (
                                    <div className="text-center">
                                        <p className="font-semibold text-[#1A1A1A]">Upload Credentials</p>
                                        <p className="text-sm text-[#6B7280]">PDF, JPG, or PNG (Max 5MB)</p>
                                        <p className="text-xs text-amber-600 mt-2 flex items-center gap-1 justify-center">
                                            <ShieldCheck className="h-3 w-3" /> Lawyer License or Translator Certificate
                                        </p>
                                    </div>
                                )}
                            </div>

                            <div className="bg-blue-50 p-4 rounded-xl flex gap-3">
                                <FileText className="h-5 w-5 text-[#0B3C91] shrink-0" />
                                <p className="text-xs text-[#0B3C91] leading-relaxed">
                                    Your documents will be stored securely and only accessible by our verification team.
                                    Verification typically takes 24-48 business hours.
                                </p>
                            </div>
                        </div>
                    )}
                </CardContent>

                <CardFooter className="bg-[#F9FAFB] border-t border-[#E5E7EB] p-6 flex justify-between">
                    <Button
                        variant="ghost"
                        onClick={() => step > 1 && setStep(step - 1)}
                        disabled={step === 1}
                    >
                        Back
                    </Button>
                    {step === 1 ? (
                        <Button
                            className="bg-[#0B3C91] hover:bg-[#0B3C91]/90 text-white font-bold"
                            onClick={() => setStep(2)}
                            disabled={!formData.type || !formData.bio || !formData.years_of_experience}
                        >
                            Next Step
                        </Button>
                    ) : (
                        <Button
                            className="bg-[#00C2FF] hover:bg-[#00C2FF]/90 text-[#0B3C91] font-extrabold px-8"
                            onClick={handleSubmit}
                            disabled={mutation.isPending}
                        >
                            {mutation.isPending ? 'Submitting...' : 'Submit Application'}
                        </Button>
                    )}
                </CardFooter>
            </Card>
        </div>
    );
}
