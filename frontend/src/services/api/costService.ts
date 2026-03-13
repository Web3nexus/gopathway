import api from '@/lib/api';

export const costService = {
    getTemplates: (params?: { country_id?: number; visa_type_id?: number }) => 
        api.get('/api/v1/costs/templates', { params }).then(r => {
            // Handle both raw array and {data: []} formats
            return Array.isArray(r.data) ? r.data : (r.data.data || []);
        }),
};
