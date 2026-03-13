import api from '@/lib/api';

export const riskAnalysisService = {
    getReport: (pathwayId: number) =>
        api.get(`/api/v1/pathways/${pathwayId}/risk-analysis`).then(r => r.data.data),
    calculate: (pathwayId: number) =>
        api.post(`/api/v1/pathways/${pathwayId}/risk-analysis/calculate`).then(r => r.data.data),
};
