import api from '@/lib/api';

export const documentService = {
    getDocuments: () => api.get('/api/v1/documents').then(r => r.data.data),
    upload: (formData: FormData) => api.post('/api/v1/documents/upload', formData, {
        headers: { 'Content-Type': 'multipart/form-data' }
    }).then(r => r.data),
    getRequiredDocumentTypes: () => api.get('/api/v1/documents/required-types').then(r => r.data),
};
