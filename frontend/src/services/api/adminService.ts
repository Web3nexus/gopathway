import api from '@/lib/api';

export const adminService = {
    // Countries
    getCountries: () => api.get('/api/v1/admin/countries').then(r => r.data.data),
    getCountry: (id: string) => api.get(`/api/v1/admin/countries/${id}`).then(r => r.data.data),
    createCountry: (data: any) => api.post('/api/v1/admin/countries', data).then(r => r.data.data),
    updateCountry: (id: string, data: any) => api.put(`/api/v1/admin/countries/${id}`, data).then(r => r.data.data),
    deleteCountry: (id: string) => api.delete(`/api/v1/admin/countries/${id}`).then(r => r.data.data),

    // Visa Types
    getVisaTypes: (countryId: string) => api.get(`/api/v1/admin/countries/${countryId}/visa-types`).then(r => r.data.data),
    createVisaType: (countryId: string, data: any) => api.post(`/api/v1/admin/countries/${countryId}/visa-types`, data).then(r => r.data.data),
    updateVisaType: (id: string, data: any) => api.put(`/api/v1/admin/visa-types/${id}`, data).then(r => r.data.data),
    deleteVisaType: (id: string) => api.delete(`/api/v1/admin/visa-types/${id}`).then(r => r.data.data),

    // Document Types
    getDocumentTypes: () => api.get('/api/v1/admin/document-types').then(r => r.data.data),
    createDocumentType: (data: any) => api.post('/api/v1/admin/document-types', data).then(r => r.data.data),
    updateDocumentType: (id: string, data: any) => api.put(`/api/v1/admin/document-types/${id}`, data).then(r => r.data.data),
    deleteDocumentType: (id: string) => api.delete(`/api/v1/admin/document-types/${id}`).then(r => r.data.data),

    // Timeline Templates
    getTimelineTemplates: () => api.get('/api/v1/admin/timeline-templates').then(r => r.data.data),
    createTimelineTemplate: (data: any) => api.post('/api/v1/admin/timeline-templates', data).then(r => r.data.data),
    updateTimelineTemplate: (id: string, data: any) => api.put(`/api/v1/admin/timeline-templates/${id}`, data).then(r => r.data.data),
    deleteTimelineTemplate: (id: string) => api.delete(`/api/v1/admin/timeline-templates/${id}`).then(r => r.data.data),

    // Cost Items
    getCostItems: (params?: { country_id?: string; visa_type_id?: string; is_template?: boolean }) =>
        api.get('/api/v1/admin/cost-items', { params }).then(r => r.data.data),
    createCostItem: (data: any) => api.post('/api/v1/admin/cost-items', data).then(r => r.data.data),
    updateCostItem: (id: string, data: any) => api.put(`/api/v1/admin/cost-items/${id}`, data).then(r => r.data.data),
    deleteCostItem: (id: string) => api.delete(`/api/v1/admin/cost-items/${id}`).then(r => r.data.data),

    // Dashboard
    getDashboardStats: () => api.get('/api/v1/admin/dashboard/stats').then(r => r.data),
    getSystemHealth: () => api.get('/api/v1/admin/system/health').then(r => r.data),

    // Users
    getUsers: (params?: { role?: string; search?: string }) =>
        api.get('/api/v1/admin/users', { params }).then(r => r.data.data),
    grantPremium: (userId: number, days?: number | 'lifetime') =>
        api.post(`/api/v1/admin/users/${userId}/grant-premium`, { days }).then(r => r.data),
    removePremium: (userId: number) =>
        api.post(`/api/v1/admin/users/${userId}/remove-premium`).then(r => r.data),
    impersonate: (userId: number) => api.post(`/api/v1/admin/users/${userId}/impersonate`).then(r => r.data),
    leaveImpersonation: () => api.post('/api/v1/admin/leave-impersonation').then(r => r.data),



    // Subscription Plans
    getSubscriptionPlans: () => api.get('/api/v1/admin/subscription-plans').then(r => r.data.data),
    createSubscriptionPlan: (data: any) => api.post('/api/v1/admin/subscription-plans', data).then(r => r.data.data),
    updateSubscriptionPlan: (id: number, data: any) => api.put(`/api/v1/admin/subscription-plans/${id}`, data).then(r => r.data.data),
    deleteSubscriptionPlan: (id: number) => api.delete(`/api/v1/admin/subscription-plans/${id}`).then(r => r.data.data),
    getSubscriptionHistory: (params?: { search?: string; page?: number }) => 
        api.get('/api/v1/admin/subscriptions/history', { params }).then(r => r.data.data),

    // Relocation Kits
    getRelocationKits: (params?: { country_id?: string }) => api.get('/api/v1/admin/relocation-kits', { params }).then(r => r.data.data),
    createRelocationKit: (data: any) => api.post('/api/v1/admin/relocation-kits', data).then(r => r.data.data),
    updateRelocationKit: (id: number, data: any) => api.put(`/api/v1/admin/relocation-kits/${id}`, data).then(r => r.data.data),
    deleteRelocationKit: (id: number) => api.delete(`/api/v1/admin/relocation-kits/${id}`).then(r => r.data.data),

    // Kit Items
    createRelocationKitItem: (kitId: number, data: any) => api.post(`/api/v1/admin/relocation-kits/${kitId}/items`, data).then(r => r.data.data),
    updateRelocationKitItem: (kitId: number, itemId: number, data: any) => api.put(`/api/v1/admin/relocation-kits/${kitId}/items/${itemId}`, data).then(r => r.data.data),
    deleteRelocationKitItem: (kitId: number, itemId: number) => api.delete(`/api/v1/admin/relocation-kits/${kitId}/items/${itemId}`).then(r => r.data.data),

    // Settlement Steps
    getSettlementSteps: (params?: { country_id?: string }) => api.get('/api/v1/admin/settlement-steps', { params }).then(r => r.data.data),
    createSettlementStep: (data: any) => api.post('/api/v1/admin/settlement-steps', data).then(r => r.data.data),
    updateSettlementStep: (id: number, data: any) => api.put(`/api/v1/admin/settlement-steps/${id}`, data).then(r => r.data.data),
    deleteSettlementStep: (id: number) => api.delete(`/api/v1/admin/settlement-steps/${id}`).then(r => r.data.data),

    // Schools
    getSchools: (params?: { country_id?: number }) => api.get('/api/v1/admin/schools', { params }).then(r => r.data.data),
    createSchool: (data: any) => api.post('/api/v1/admin/schools', data).then(r => r.data.data),
    updateSchool: (id: number, data: any) => api.put(`/api/v1/admin/schools/${id}`, data).then(r => r.data.data),
    deleteSchool: (id: number) => api.delete(`/api/v1/admin/schools/${id}`).then(r => r.data.data),

    // School Programs
    createSchoolProgram: (schoolId: number, data: any) => api.post(`/api/v1/admin/schools/${schoolId}/programs`, data).then(r => r.data.data),
    updateSchoolProgram: (schoolId: number, programId: number, data: any) => api.put(`/api/v1/admin/schools/${schoolId}/programs/${programId}`, data).then(r => r.data.data),
    deleteSchoolProgram: (schoolId: number, programId: number) => api.delete(`/api/v1/admin/schools/${schoolId}/programs/${programId}`).then(r => r.data.data),

    // Student Visa Requirements
    updateStudentVisaRequirement: (data: any) => api.post('/api/v1/admin/student-visa-requirements', data).then(r => r.data.data),

    // Job Platforms
    getJobPlatforms: (countryId: number) => api.get(`/api/v1/job-platforms/${countryId}`).then(r => r.data.data),
    // Note: If no specific admin job platform route exists, we can use public or create one.
    // Assuming admin routes follow the pattern in api.php
    createJobPlatform: (data: any) => api.post('/api/v1/admin/job-platforms', data).then(r => r.data.data),
    updateJobPlatform: (id: number, data: any) => api.put(`/api/v1/admin/job-platforms/${id}`, data).then(r => r.data.data),
    deleteJobPlatform: (id: number) => api.delete(`/api/v1/admin/job-platforms/${id}`).then(r => r.data.data),

    // Residency Rules
    getResidencyRules: (countryId: number) => api.get(`/api/v1/residency-rules/${countryId}`).then(r => r.data.data),
    updateResidencyRules: (countryId: number, data: any) => api.post(`/api/v1/admin/residency-rules/${countryId}`, data).then(r => r.data.data),

    // CV Templates
    createCvTemplate: (data: any) => api.post('/api/v1/admin/cv-templates', data).then(r => r.data.data),
    updateCvTemplate: (id: number, data: any) => api.put(`/api/v1/admin/cv-templates/${id}`, data).then(r => r.data.data),
    deleteCvTemplate: (id: number) => api.delete(`/api/v1/admin/cv-templates/${id}`).then(r => r.data.data),
    getExpertWithdrawals: () =>
        api.get('/api/v1/admin/expert-withdrawals').then(r => r.data),

    reviewExpertWithdrawal: (id: number, data: { status: string; admin_notes?: string }) =>
        api.post(`/api/v1/admin/expert-withdrawals/${id}/review`, data).then(r => r.data),

    // General Settings
    getSettings: () => api.get('/api/v1/admin/settings').then(r => r.data),
    updateSettings: (settings: { key: string; value: any }[]) =>
        api.post('/api/v1/admin/settings', { settings }).then(r => r.data),
    uploadSettings: (data: FormData) => api.post('/api/v1/admin/settings', data, { headers: { 'Content-Type': 'multipart/form-data' } }).then(r => r.data),
    revealSetting: (key: string, password: string) => api.post('/api/v1/admin/settings/reveal', { key, password }).then(r => r.data),

    // Email Management
    getEmailTemplates: () => api.get('/api/v1/admin/email-templates').then(r => r.data.data),
    updateEmailTemplate: (id: number, data: { subject: string; content: string }) => 
        api.put(`/api/v1/admin/email-templates/${id}`, data).then(r => r.data.data),
    testMailConnection: () => api.post('/api/v1/admin/mail-settings/test').then(r => r.data),

    // 2FA & Profile
    get2FASetup: () => api.get('/api/v1/admin/2fa/setup').then(r => r.data),
    enable2FA: (code: string) => api.post('/api/v1/admin/2fa/enable', { code }).then(r => r.data),
    disable2FA: (password: string) => api.post('/api/v1/admin/2fa/disable', { password }).then(r => r.data),
    verify2FA: (code: string) => api.post('/api/v1/admin/2fa/verify', { code }).then(r => r.data),
    updateAdminProfile: (data: { name: string; email: string }) => api.put('/api/v1/admin/profile', data).then(r => r.data),
    updatePassword: (data: any) => api.put('/api/v1/auth/password', data).then(r => r.data),

    // Finance Management
    getFinanceProviders: (page = 1) => api.get(`/api/v1/admin/finance-providers?page=${page}`).then(r => r.data),
    createFinanceProvider: (data: any) => api.post('/api/v1/admin/finance-providers', data).then(r => r.data),
    updateFinanceProvider: (id: number, data: any) => api.put(`/api/v1/admin/finance-providers/${id}`, data).then(r => r.data),
    deleteFinanceProvider: (id: number) => api.delete(`/api/v1/admin/finance-providers/${id}`).then(r => r.data),
    toggleFinanceProvider: (id: number) => api.patch(`/api/v1/admin/finance-providers/${id}/toggle`).then(r => r.data),
};
