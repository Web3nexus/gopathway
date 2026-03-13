import api from '@/lib/api';

export const countryService = {
    getCountries: () => api.get('/api/v1/countries').then(r => r.data.data),
    getCountry: (id: number) => api.get(`/api/v1/countries/${id}`).then(r => r.data.data),
    getVisaTypes: (countryId: number) => api.get(`/api/v1/countries/${countryId}/visa-types`).then(r => r.data.data),
    getScores: () => api.get('/api/v1/countries/scores').then(r => r.data.data),
    compareCountries: (ids: number[]) => api.get('/api/v1/countries/compare', { params: { ids } }).then(r => r.data.data),
};
