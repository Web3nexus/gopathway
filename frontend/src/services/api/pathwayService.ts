import api from '@/lib/api';

export const pathwayService = {
    getPathways: () => api.get('/api/v1/pathways').then(r => r.data.data),
    getPathway: () => api.get('/api/v1/pathway').then(r => r.data),
    selectPathway: (data: { country_id: number; visa_type_id: number }) =>
        api.post('/api/v1/pathway/select', data).then(r => r.data.data),
    updateSavings: (data: { current_savings: number; monthly_target: number; target_date?: string | null }) =>
        api.put('/api/v1/pathway/savings', data).then(r => r.data),
    getTimeline: () => api.get('/api/v1/timeline').then(r => r.data.data),
    completeStep: (stepId: number) =>
        api.post(`/api/v1/timeline/${stepId}/complete`).then(r => r.data.data),
    deactivatePathway: () => api.post('/api/v1/pathway/deactivate').then(r => r.data),
};
