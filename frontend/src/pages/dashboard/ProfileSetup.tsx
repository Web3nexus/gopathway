import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { useProfile, useUpdateProfile } from '@/hooks/useProfile';
import { useCountries } from '@/hooks/useCountries';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Loader2, ArrowRight, ArrowLeft } from 'lucide-react';
import { useToast } from '@/hooks/use-toast';

export default function ProfileSetup() {
    const navigate = useNavigate();
    const { toast } = useToast();
    const { data: profile, isLoading: isLoadingProfile } = useProfile();
    const updateMutation = useUpdateProfile();
    const { data: countries } = useCountries();

    const [step, setStep] = useState(1);
    const [formData, setFormData] = useState({
        age: profile?.age || '',
        education_level: profile?.education_level || '',
        preferred_country_id: profile?.preferred_country_id?.toString() || '',
        work_experience_years: profile?.work_experience_years || '',
        funds_range: profile?.funds_range || '',
        ielts_status: profile?.ielts_status || '',
    });

    const handleNext = () => setStep(s => Math.min(s + 1, 3));
    const handlePrev = () => setStep(s => Math.max(s - 1, 1));

    const handleChange = (field: string, value: string) => {
        setFormData(prev => ({ ...prev, [field]: value }));
    };

    const handleComplete = () => {
        // Basic validation formatting
        const payload = {
            ...formData,
            age: formData.age ? parseInt(formData.age as string) : null,
            work_experience_years: formData.work_experience_years ? parseInt(formData.work_experience_years as string) : null,
            preferred_country_id: formData.preferred_country_id ? parseInt(formData.preferred_country_id as string) : null,
        };

        updateMutation.mutate(payload, {
            onSuccess: () => {
                toast({ title: "Profile updated successfully!" });
                navigate('/dashboard');
            },
            onError: (err: any) => {
                toast({
                    title: "Update failed",
                    description: err.response?.data?.message || "Please check your inputs.",
                    variant: "destructive"
                });
            }
        });
    };

    if (isLoadingProfile) {
        return <div className="p-8 flex justify-center"><Loader2 className="h-8 w-8 animate-spin text-[#0B3C91]" /></div>;
    }

    return (
        <div className="max-w-2xl mx-auto py-10">
            <div className="mb-8 text-center">
                <h1 className="text-3xl font-bold text-[#1A1A1A]">Complete Your Profile</h1>
                <p className="text-[#6B7280] mt-2">Let's build your relocation profile. This helps us calculate your readiness score and find the best visa options.</p>
            </div>

            {/* Progress Bar */}
            <div className="flex gap-2 mb-8">
                {[1, 2, 3].map((i) => (
                    <div key={i} className={`h-2 flex-1 rounded-full bg-transition duration-300 ${step >= i ? 'bg-[#00C2FF]' : 'bg-gray-200'}`} />
                ))}
            </div>

            <div className="bg-white p-8 rounded-2xl border border-[#E5E7EB] shadow-sm">

                {step === 1 && (
                    <div className="space-y-6 animate-in fade-in slide-in-from-right-4 duration-300">
                        <h2 className="text-xl font-bold text-[#1A1A1A] mb-4">Step 1: Personal Profile</h2>

                        <div className="space-y-2">
                            <Label htmlFor="age">Age</Label>
                            <Input
                                id="age" type="number" placeholder="e.g. 28"
                                value={formData.age} onChange={e => handleChange('age', e.target.value)}
                            />
                        </div>

                        <div className="space-y-2">
                            <Label>Highest Education Level</Label>
                            <Select value={formData.education_level} onValueChange={v => handleChange('education_level', v)}>
                                <SelectTrigger><SelectValue placeholder="Select education level" /></SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="high_school">High School</SelectItem>
                                    <SelectItem value="bachelors">Bachelor's Degree</SelectItem>
                                    <SelectItem value="masters">Master's Degree</SelectItem>
                                    <SelectItem value="phd">Ph.D.</SelectItem>
                                    <SelectItem value="other">Other</SelectItem>
                                </SelectContent>
                            </Select>
                        </div>

                        <div className="space-y-2">
                            <Label htmlFor="experience">Work Experience (Years)</Label>
                            <Input
                                id="experience" type="number" placeholder="e.g. 4"
                                value={formData.work_experience_years} onChange={e => handleChange('work_experience_years', e.target.value)}
                            />
                        </div>
                    </div>
                )}

                {step === 2 && (
                    <div className="space-y-6 animate-in fade-in slide-in-from-right-4 duration-300">
                        <h2 className="text-xl font-bold text-[#1A1A1A] mb-4">Step 2: Migration Goals</h2>

                        <div className="space-y-2">
                            <Label>Where do you want to move?</Label>
                            <Select value={formData.preferred_country_id} onValueChange={v => handleChange('preferred_country_id', v)}>
                                <SelectTrigger><SelectValue placeholder="Select a destination country" /></SelectTrigger>
                                <SelectContent>
                                    {countries?.map((c: any) => (
                                        <SelectItem key={c.id} value={c.id.toString()}>{c.name}</SelectItem>
                                    ))}
                                </SelectContent>
                            </Select>
                            <p className="text-xs text-[#6B7280]">You can change this or add more preferences later.</p>
                        </div>
                    </div>
                )}

                {step === 3 && (
                    <div className="space-y-6 animate-in fade-in slide-in-from-right-4 duration-300">
                        <h2 className="text-xl font-bold text-[#1A1A1A] mb-4">Step 3: Finances & Language</h2>

                        <div className="space-y-2">
                            <Label>Available Funds (Proof of Funds)</Label>
                            <Select value={formData.funds_range} onValueChange={v => handleChange('funds_range', v)}>
                                <SelectTrigger><SelectValue placeholder="Select your budget range" /></SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="under_5k">Under £5,000</SelectItem>
                                    <SelectItem value="5k_10k">£5,000 - £10,000</SelectItem>
                                    <SelectItem value="10k_20k">£10,000 - £20,000</SelectItem>
                                    <SelectItem value="20k_50k">£20,000 - £50,000</SelectItem>
                                    <SelectItem value="over_50k">Over £50,000</SelectItem>
                                </SelectContent>
                            </Select>
                        </div>

                        <div className="space-y-2">
                            <Label>Language Proficiency (IELTS / Equivalent)</Label>
                            <Select value={formData.ielts_status} onValueChange={v => handleChange('ielts_status', v)}>
                                <SelectTrigger><SelectValue placeholder="Select your current status" /></SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="not_taken">Not Taken Yet</SelectItem>
                                    <SelectItem value="scheduled">Scheduled / Preparing</SelectItem>
                                    <SelectItem value="band_5">Band 5.0 - 5.5 (Basic)</SelectItem>
                                    <SelectItem value="band_6">Band 6.0 - 6.5 (Competent)</SelectItem>
                                    <SelectItem value="band_7">Band 7.0 - 7.5 (Good)</SelectItem>
                                    <SelectItem value="band_8_plus">Band 8.0+ (Excellent)</SelectItem>
                                </SelectContent>
                            </Select>
                        </div>
                    </div>
                )}

                <div className="flex justify-between mt-10 pt-6 border-t border-[#E5E7EB]">
                    <Button variant="outline" onClick={handlePrev} disabled={step === 1} type="button">
                        <ArrowLeft className="mr-2 h-4 w-4" /> Back
                    </Button>

                    {step < 3 ? (
                        <Button className="bg-[#0B3C91] hover:bg-[#0B3C91]/90" onClick={handleNext} type="button">
                            Next Step <ArrowRight className="ml-2 h-4 w-4" />
                        </Button>
                    ) : (
                        <Button
                            className="bg-[#00C2FF] hover:bg-[#00C2FF]/90 text-[#0B3C91] font-bold"
                            onClick={handleComplete}
                            disabled={updateMutation.isPending}
                        >
                            {updateMutation.isPending ? <Loader2 className="mr-2 h-4 w-4 animate-spin" /> : null}
                            Complete Profile
                        </Button>
                    )}
                </div>
            </div>
        </div>
    );
}
