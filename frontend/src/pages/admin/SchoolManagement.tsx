import { useState } from 'react';
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import api from '@/lib/api';
import { adminService } from '@/services/api/adminService';
import { Building2, Plus, Trash2, Edit2, Loader2, Globe2, BookOpen } from 'lucide-react';
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

export default function SchoolManagement() {
    const { toast } = useToast();
    const queryClient = useQueryClient();
    const [selectedCountry, setSelectedCountry] = useState<number | null>(null);
    const [isDialogOpen, setIsDialogOpen] = useState(false);
    const [editingSchool, setEditingSchool] = useState<any>(null);
    const [formData, setFormData] = useState({
        name: '',
        country_id: 0,
        location: '',
        type: 'public',
        website: '',
        application_portal: '',
        description: ''
    });

    // Programs Form State
    const [isProgramsDialogOpen, setIsProgramsDialogOpen] = useState(false);
    const [selectedSchoolForPrograms, setSelectedSchoolForPrograms] = useState<any>(null);
    const [isEditingProgram, setIsEditingProgram] = useState<any>(null);
    const [programFormData, setProgramFormData] = useState({
        name: '',
        degree_type: 'bachelor',
        field_of_study: '',
        duration_years: 1,
        tuition_per_year: 0,
        currency: 'USD',
    });

    const { data: countriesRaw } = useQuery({
        queryKey: ['admin-countries'],
        queryFn: () => api.get('/api/v1/countries').then(res => res.data.data)
    });
    const countriesData = Array.isArray(countriesRaw) ? countriesRaw : [];

    const { data: schoolsRaw, isLoading } = useQuery({
        queryKey: ['admin-schools', selectedCountry],
        queryFn: () => adminService.getSchools({ country_id: selectedCountry || undefined })
    });
    const schoolsData = Array.isArray(schoolsRaw) ? schoolsRaw : [];

    const mutation = useMutation({
        mutationFn: (data: any) => {
            if (editingSchool) {
                return adminService.updateSchool(editingSchool.id, data);
            }
            return adminService.createSchool(data);
        },
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['admin-schools'] });
            toast({ title: editingSchool ? 'School updated' : 'School created' });
            setIsDialogOpen(false);
            resetForm();
        },
        onError: (error: any) => {
            toast({
                title: 'Error saving school',
                description: error.response?.data?.message || 'Something went wrong',
                variant: 'destructive'
            });
        }
    });

    const deleteMutation = useMutation({
        mutationFn: adminService.deleteSchool,
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['admin-schools'] });
            toast({ title: 'School deleted' });
        }
    });

    // Program Mutations
    const programMutation = useMutation({
        mutationFn: (data: any) => {
            if (isEditingProgram) {
                return adminService.updateSchoolProgram(selectedSchoolForPrograms.id, isEditingProgram.id, data);
            }
            return adminService.createSchoolProgram(selectedSchoolForPrograms.id, data);
        },
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['admin-schools'] });
            toast({ title: isEditingProgram ? 'Program updated' : 'Program created' });
            resetProgramForm();
        },
        onError: (error: any) => {
            toast({ title: 'Error saving program', description: error.response?.data?.message || 'Something went wrong', variant: 'destructive' });
        }
    });

    const deleteProgramMutation = useMutation({
        mutationFn: (programId: number) => adminService.deleteSchoolProgram(selectedSchoolForPrograms.id, programId),
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['admin-schools'] });
            toast({ title: 'Program deleted' });
        }
    });

    const resetForm = () => {
        setEditingSchool(null);
        setFormData({
            name: '',
            country_id: selectedCountry || (countriesData?.[0]?.id || 0),
            location: '',
            type: 'public',
            website: '',
            application_portal: '',
            description: ''
        });
    };

    const handleEdit = (school: any) => {
        setEditingSchool(school);
        setFormData({
            name: school.name,
            country_id: school.country_id,
            location: school.location || '',
            type: school.type,
            website: school.website || '',
            application_portal: school.application_portal || '',
            description: school.description || ''
        });
        setIsDialogOpen(true);
    };

    const handleDelete = (id: number) => {
        if (confirm('Are you sure you want to delete this school?')) {
            deleteMutation.mutate(id);
        }
    };

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        mutation.mutate(formData);
    };

    // Program Handlers
    const resetProgramForm = () => {
        setIsEditingProgram(null);
        setProgramFormData({
            name: '', degree_type: 'bachelor', field_of_study: '',
            duration_years: 1, tuition_per_year: 0, currency: 'USD',
        });
    };

    const openProgramsDialog = (school: any) => {
        setSelectedSchoolForPrograms(school);
        resetProgramForm();
        setIsProgramsDialogOpen(true);
    };

    const handleEditProgram = (program: any) => {
        setIsEditingProgram(program);
        setProgramFormData({
            name: program.name || '',
            degree_type: program.degree_type || 'bachelor',
            field_of_study: program.field_of_study || '',
            duration_years: program.duration_years || 1,
            tuition_per_year: program.tuition_per_year || 0,
            currency: program.currency || 'USD',
        });
    };

    const handleDeleteProgram = (programId: number) => {
        if (confirm('Are you confirm you want to delete this program?')) {
            deleteProgramMutation.mutate(programId);
        }
    };

    const handleProgramSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        programMutation.mutate(programFormData);
    };

    const currentSchoolData = schoolsData.find((s: any) => s.id === selectedSchoolForPrograms?.id) || selectedSchoolForPrograms;


    return (
        <div className="space-y-6">
            <div className="flex justify-between items-center">
                <div>
                    <h1 className="text-2xl font-bold tracking-tight">School Management</h1>
                    <p className="text-muted-foreground mt-1">Manage universities, colleges, and study programs per country.</p>
                </div>
                <Button
                    onClick={() => { resetForm(); setIsDialogOpen(true); }}
                    className="bg-blue-600 hover:bg-blue-700 gap-2"
                >
                    <Plus className="w-4 h-4" /> Add School
                </Button>
            </div>

            <div className="bg-white rounded-xl shadow-sm border p-4">
                <div className="flex items-center gap-4">
                    <Globe2 className="w-5 h-5 text-slate-400" />
                    <select
                        className="flex-1 bg-slate-50 border-none rounded-lg py-2 px-4 focus:ring-0 text-sm font-medium text-slate-700"
                        value={selectedCountry || ''}
                        onChange={(e) => setSelectedCountry(e.target.value ? Number(e.target.value) : null)}
                    >
                        <option value="">All Countries</option>
                        {countriesData?.map((c: any) => (
                            <option key={c.id} value={c.id}>{c.name}</option>
                        ))}
                    </select>
                </div>
            </div>

            {isLoading ? (
                <div className="flex justify-center py-20"><Loader2 className="w-8 h-8 animate-spin text-blue-600" /></div>
            ) : schoolsData?.length === 0 ? (
                <div className="bg-slate-50 border rounded-2xl p-12 text-center text-slate-500">
                    No schools found. Select a country or add a new school.
                </div>
            ) : (
                <div className="grid grid-cols-1 gap-4">
                    {schoolsData?.map((school: any) => (
                        <div key={school.id} className="bg-white rounded-xl border shadow-sm p-5 hover:border-blue-200 transition-colors">
                            <div className="flex justify-between items-start">
                                <div className="flex items-start gap-4">
                                    <div className="w-12 h-12 rounded-lg bg-blue-50 flex items-center justify-center shrink-0">
                                        <Building2 className="w-6 h-6 text-blue-600" />
                                    </div>
                                    <div>
                                        <div className="flex items-center gap-2">
                                            <h3 className="font-bold text-lg text-slate-900">{school.name}</h3>
                                            <span className="text-xs bg-slate-100 text-slate-600 px-2 py-0.5 rounded uppercase font-bold">{school.country?.code}</span>
                                        </div>
                                        <div className="flex gap-4 mt-1 text-sm text-slate-500">
                                            <span>{school.location || 'Location missing'}</span>
                                            <span className="capitalize">{school.type}</span>
                                            <span className="font-semibold">{school.programs?.length || 0} programs</span>
                                        </div>
                                    </div>
                                </div>
                                <div className="flex items-center gap-2">
                                    <Button
                                        variant="outline"
                                        size="sm"
                                        className="gap-1 rounded-lg text-blue-600 border-blue-200 hover:bg-blue-50"
                                        onClick={() => openProgramsDialog(school)}
                                    >
                                        <BookOpen className="w-4 h-4" /> Manage Programs
                                    </Button>
                                    <Button
                                        variant="outline"
                                        size="sm"
                                        className="gap-1 rounded-lg"
                                        onClick={() => handleEdit(school)}
                                    >
                                        <Edit2 className="w-4 h-4" /> Edit
                                    </Button>
                                    <Button
                                        variant="ghost"
                                        size="sm"
                                        className="text-red-600 hover:text-red-700 hover:bg-red-50 rounded-lg"
                                        onClick={() => handleDelete(school.id)}
                                    >
                                        <Trash2 className="w-4 h-4" />
                                    </Button>
                                </div>
                            </div>
                        </div>
                    ))}
                </div>
            )}

            <Dialog open={isDialogOpen} onOpenChange={setIsDialogOpen}>
                <DialogContent className="max-w-2xl">
                    <DialogHeader>
                        <DialogTitle>{editingSchool ? 'Edit School' : 'Add New School'}</DialogTitle>
                    </DialogHeader>
                    <form onSubmit={handleSubmit} className="space-y-4 py-4">
                        <div className="grid grid-cols-2 gap-4">
                            <div className="space-y-2">
                                <Label htmlFor="name">School Name</Label>
                                <Input
                                    id="name"
                                    value={formData.name}
                                    onChange={(e) => setFormData({ ...formData, name: e.target.value })}
                                    placeholder="University of Lagos"
                                    required
                                />
                            </div>
                            <div className="space-y-2">
                                <Label htmlFor="country">Country</Label>
                                <select
                                    id="country"
                                    className="w-full bg-slate-50 border rounded-lg py-2 px-3 text-sm focus:ring-2 focus:ring-blue-500"
                                    value={formData.country_id}
                                    onChange={(e) => setFormData({ ...formData, country_id: Number(e.target.value) })}
                                    required
                                >
                                    <option value="">Select Country</option>
                                    {countriesData?.map((c: any) => (
                                        <option key={c.id} value={c.id}>{c.name}</option>
                                    ))}
                                </select>
                            </div>
                            <div className="space-y-2">
                                <Label htmlFor="location">Location (City)</Label>
                                <Input
                                    id="location"
                                    value={formData.location}
                                    onChange={(e) => setFormData({ ...formData, location: e.target.value })}
                                    placeholder="Akoka, Lagos"
                                />
                            </div>
                            <div className="space-y-2">
                                <Label htmlFor="type">School Type</Label>
                                <select
                                    id="type"
                                    className="w-full bg-slate-50 border rounded-lg py-2 px-3 text-sm focus:ring-2 focus:ring-blue-500"
                                    value={formData.type}
                                    onChange={(e) => setFormData({ ...formData, type: e.target.value })}
                                    required
                                >
                                    <option value="public">Public University</option>
                                    <option value="private">Private University</option>
                                    <option value="college">College</option>
                                    <option value="technical">Technical School</option>
                                </select>
                            </div>
                            <div className="space-y-2">
                                <Label htmlFor="website">Official Website</Label>
                                <Input
                                    id="website"
                                    type="url"
                                    value={formData.website}
                                    onChange={(e) => setFormData({ ...formData, website: e.target.value })}
                                    placeholder="https://..."
                                />
                            </div>
                            <div className="space-y-2">
                                <Label htmlFor="portal">Application Portal</Label>
                                <Input
                                    id="portal"
                                    type="url"
                                    value={formData.application_portal}
                                    onChange={(e) => setFormData({ ...formData, application_portal: e.target.value })}
                                    placeholder="https://..."
                                />
                            </div>
                        </div>
                        <div className="space-y-2">
                            <Label htmlFor="description">About School</Label>
                            <Textarea
                                id="description"
                                value={formData.description}
                                onChange={(e) => setFormData({ ...formData, description: e.target.value })}
                                placeholder="Describe the institution..."
                                rows={3}
                            />
                        </div>
                        <DialogFooter className="pt-4">
                            <Button type="button" variant="ghost" onClick={() => setIsDialogOpen(false)}>Cancel</Button>
                            <Button type="submit" disabled={mutation.isPending}>
                                {mutation.isPending ? 'Saving...' : 'Save School'}
                            </Button>
                        </DialogFooter>
                    </form>
                </DialogContent>
            </Dialog>

            <Dialog open={isProgramsDialogOpen} onOpenChange={setIsProgramsDialogOpen}>
                <DialogContent className="max-w-4xl h-[85vh] flex flex-col p-0">
                    <DialogHeader className="p-6 pb-2 border-b shrink-0">
                        <DialogTitle className="text-xl">
                            {currentSchoolData?.name} - Course Programs
                        </DialogTitle>
                    </DialogHeader>

                    <div className="flex-1 overflow-hidden flex flex-col md:flex-row bg-slate-50/50">
                        <div className="flex-1 overflow-y-auto p-6 border-r">
                            <h3 className="font-semibold mb-4 text-slate-700">Existing Programs ({currentSchoolData?.programs?.length || 0})</h3>
                            {!currentSchoolData?.programs?.length ? (
                                <div className="text-center p-8 bg-white border border-dashed rounded-xl text-slate-400">
                                    No programs added yet. Add a bachelor, master, or language course.
                                </div>
                            ) : (
                                <div className="space-y-3">
                                    {currentSchoolData.programs.map((prog: any) => (
                                        <div key={prog.id} className="bg-white border rounded-xl p-4 shadow-sm group">
                                            <div className="flex justify-between items-start">
                                                <div>
                                                    <h4 className="font-bold text-slate-900">{prog.name}</h4>
                                                    <div className="flex items-center gap-2 mt-1 max-w-sm flex-wrap">
                                                        <span className="text-xs font-semibold bg-blue-50 text-blue-700 px-2 py-0.5 rounded capitalize">
                                                            {prog.degree_type}
                                                        </span>
                                                        <span className="text-xs text-slate-500">{prog.duration_years} Years</span>
                                                        <span className="text-xs text-slate-500">{prog.tuition_per_year} {prog.currency} / yr</span>
                                                    </div>
                                                </div>
                                                <div className="flex items-center gap-1 opacity-100 md:opacity-0 group-hover:opacity-100 transition-opacity">
                                                    <Button variant="ghost" size="icon" className="h-8 w-8 text-slate-500 hover:text-blue-600 hover:bg-blue-50" onClick={() => handleEditProgram(prog)}>
                                                        <Edit2 className="w-4 h-4" />
                                                    </Button>
                                                    <Button variant="ghost" size="icon" className="h-8 w-8 text-slate-500 hover:text-red-600 hover:bg-red-50" onClick={() => handleDeleteProgram(prog.id)}>
                                                        <Trash2 className="w-4 h-4" />
                                                    </Button>
                                                </div>
                                            </div>
                                        </div>
                                    ))}
                                </div>
                            )}
                        </div>

                        <div className="w-full md:w-[400px] flex-shrink-0 bg-white p-6 overflow-y-auto">
                            <div className="flex items-center justify-between mb-4">
                                <h3 className="font-semibold">{isEditingProgram ? 'Edit Program' : 'Add New Program/Course'}</h3>
                                {isEditingProgram && (
                                    <Button variant="ghost" size="sm" onClick={resetProgramForm} className="text-xs text-blue-600">Cancel Edit</Button>
                                )}
                            </div>

                            <form onSubmit={handleProgramSubmit} className="space-y-4">
                                <div className="space-y-2">
                                    <Label>Program/Course Name</Label>
                                    <Input placeholder="e.g. BSc Computer Science" required value={programFormData.name} onChange={(e) => setProgramFormData({...programFormData, name: e.target.value})} />
                                </div>
                                <div className="space-y-2">
                                    <Label>Degree Type</Label>
                                    <select className="w-full bg-slate-50 border rounded-lg py-2 px-3 text-sm focus:ring-2 focus:ring-blue-500" required value={programFormData.degree_type} onChange={(e) => setProgramFormData({...programFormData, degree_type: e.target.value})}>
                                        <option value="certificate">Certificate / Language</option>
                                        <option value="diploma">Diploma</option>
                                        <option value="associate">Associate Degree</option>
                                        <option value="bachelor">Bachelor Degree</option>
                                        <option value="master">Master Degree</option>
                                        <option value="phd">PhD / Doctorate</option>
                                    </select>
                                </div>
                                <div className="space-y-2">
                                    <Label>Field of Study <span className="text-slate-400 font-normal">(Optional)</span></Label>
                                    <Input placeholder="e.g. Technology, Business" value={programFormData.field_of_study} onChange={(e) => setProgramFormData({...programFormData, field_of_study: e.target.value})} />
                                </div>
                                <div className="grid grid-cols-2 gap-4">
                                    <div className="space-y-2">
                                        <Label>Duration (Years)</Label>
                                        <Input type="number" step="0.1" min="0" required value={programFormData.duration_years} onChange={(e) => setProgramFormData({...programFormData, duration_years: parseFloat(e.target.value) || 0})} />
                                    </div>
                                    <div className="space-y-2">
                                        <Label>Tuition Currency</Label>
                                        <Input placeholder="USD, EUR" maxLength={3} required value={programFormData.currency} onChange={(e) => setProgramFormData({...programFormData, currency: e.target.value.toUpperCase()})} />
                                    </div>
                                </div>
                                <div className="space-y-2">
                                    <Label>Tuition per Year (Numeric)</Label>
                                    <Input type="number" min="0" required value={programFormData.tuition_per_year} onChange={(e) => setProgramFormData({...programFormData, tuition_per_year: parseFloat(e.target.value) || 0})} />
                                </div>
                                <Button type="submit" className="w-full mt-4" disabled={programMutation.isPending}>
                                    {programMutation.isPending ? 'Saving...' : (isEditingProgram ? 'Update Program' : 'Save Program')}
                                </Button>
                            </form>
                        </div>
                    </div>
                </DialogContent>
            </Dialog>
        </div>
    );
}

