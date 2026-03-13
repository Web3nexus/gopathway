import api from '@/lib/api';

export interface SopDraft {
    id: number;
    user_id: number;
    visa_type_id: number;
    answers: Record<string, any>;
    generated_text: string | null;
    status: 'drafting' | 'generated' | 'exported';
}

export const sopService = {
    start: (): Promise<{ data: SopDraft }> => 
        api.post('/api/v1/sop/start').then(r => r.data),
    
    save: (id: number, answers: Record<string, any>): Promise<{ data: SopDraft }> => 
        api.put(`/api/v1/sop/${id}/save`, { answers }).then(r => r.data),
    
    generate: (id: number): Promise<{ data: SopDraft }> => 
        api.post(`/api/v1/sop/${id}/generate`).then(r => r.data),
        
    review: (draft: string, country: string, visa_type: string): Promise<any> =>
        api.post('/api/v1/sop/review', { draft, country, visa_type }).then(r => r.data),
};
