import re

with open('frontend/src/pages/admin/SchoolManagement.tsx', 'r') as f:
    content = f.read()

# Add BookOpen to lucide imports
content = re.sub(r"Globe2\s*\} from 'lucide-react'", "Globe2, BookOpen } from 'lucide-react'", content)

# Add program states
state_injection = """    // Programs Form State
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

    const { data: countriesRaw }"""

content = content.replace("    const { data: countriesRaw }", state_injection)

# Add program mutations
mutation_injection = """    const deleteMutation = useMutation({
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
    });"""

content = content.replace("""    const deleteMutation = useMutation({
        mutationFn: adminService.deleteSchool,
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['admin-schools'] });
            toast({ title: 'School deleted' });
        }
    });""", mutation_injection)


# Add program handlers
handlers_injection = """    const handleSubmit = (e: React.FormEvent) => {
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
"""
content = content.replace("""    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        mutation.mutate(formData);
    };""", handlers_injection)


# Add button for manage programs
btn_injection = """                                    <Button
                                        variant="outline"
                                        size="sm"
                                        className="gap-1 rounded-lg text-blue-600 border-blue-200 hover:bg-blue-50"
                                        onClick={() => openProgramsDialog(school)}
                                    >
                                        <BookOpen className="w-4 h-4" /> Manage Programs
                                    </Button>
                                    <Button"""
content = content.replace("                                    <Button\n                                        variant=\"outline\"\n                                        size=\"sm\"\n                                        className=\"gap-1 rounded-lg\"\n                                        onClick={() => handleEdit(school)}\n                                    >", btn_injection)


# Insert Modal at the end
modal_injection = """            </Dialog>

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
"""

content = content.replace("""            </Dialog>
        </div>
    );
}""", modal_injection)

with open('frontend/src/pages/admin/SchoolManagement.tsx', 'w') as f:
    f.write(content)
print("Updated successfully.")
