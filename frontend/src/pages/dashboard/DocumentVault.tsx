import React, { useRef } from 'react';
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import { documentService } from '@/services/api/documentService';
import { FileText, Upload, CheckCircle2, Clock, X, FolderOpen, Lock, Sparkles } from 'lucide-react';
import { Button } from '@/components/ui/button';
import { useToast } from '@/hooks/use-toast';
import { useFeatures } from '@/hooks/useFeatures';
import { Link } from 'react-router-dom';

export default function DocumentVault() {
    const { canAccessFeature, isLoading: featuresLoading } = useFeatures();
    const { toast } = useToast();
    const queryClient = useQueryClient();
    const fileInputRef = useRef<HTMLInputElement>(null);
    const [selectedTypeId, setSelectedTypeId] = React.useState<string>('');

    const { data: requiredTypes = [], isLoading: isLoadingTypes } = useQuery({
        queryKey: ['required-document-types'],
        queryFn: () => documentService.getRequiredDocumentTypes().then(res => res.data),
        enabled: canAccessFeature('document-vault')
    });

    const { data: documents = [], isLoading: isLoadingDocs } = useQuery({
        queryKey: ['documents'],
        queryFn: documentService.getDocuments,
        enabled: canAccessFeature('document-vault')
    });

    const uploadMutation = useMutation({
        mutationFn: ({ file, typeId }: { file: File, typeId: string }) => {
            const fd = new FormData();
            fd.append('file', file);
            fd.append('document_type_id', typeId);
            fd.append('name', file.name);
            return documentService.upload(fd);
        },
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['documents'] });
            toast({ title: '📄 Document uploaded successfully!' });
            setSelectedTypeId('');
        },
        onError: () => toast({ title: 'Upload failed. Check file size and type.', variant: 'destructive' }),
    });

    const handleFileChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const file = e.target.files?.[0];
        if (file && selectedTypeId) {
            uploadMutation.mutate({ file, typeId: selectedTypeId });
        } else if (file && !selectedTypeId) {
            toast({ title: 'Please select a document type first', variant: 'destructive' });
        }
        e.target.value = '';
    };

    const uploadedTypeIds = new Set(documents.map((d: any) => d.document_type?.id));
    const isLoading = isLoadingDocs || isLoadingTypes || featuresLoading;

    if (isLoading) return <div className="flex justify-center p-20"><span className="animate-spin text-[#0B3C91] hover:text-[#0B3C91]/90">⏳</span> Loading...</div>;

    if (!canAccessFeature('document-vault')) {
        return (
            <div className="max-w-4xl mx-auto my-12 relative overflow-hidden bg-white border border-blue-100 rounded-[32px] shadow-2xl p-12 text-center">
                <div className="h-20 w-20 bg-blue-50 rounded-3xl flex items-center justify-center mx-auto mb-8 rotate-3">
                    <Lock className="h-10 w-10 text-[#0B3C91]" />
                </div>
                <h2 className="text-4xl font-extrabold text-[#1A1A1A] mb-4 tracking-tight">Professional Document Vault</h2>
                <p className="text-lg text-[#6B7280] max-w-xl mx-auto mb-10 leading-relaxed">
                    Securely store, manage, and track all your immigration documents in one place with expert-approved checklists.
                </p>
                <div className="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <Link to="/pricing">
                        <Button className="bg-[#0B3C91] hover:bg-[#0A2A66] text-white px-10 py-7 rounded-2xl text-lg font-bold shadow-xl shadow-blue-100 group transition-all">
                            Upgrade to Premium
                            <Sparkles className="ml-2 h-5 w-5 group-hover:scale-110 transition-transform" />
                        </Button>
                    </Link>
                    <Link to="/dashboard">
                        <Button variant="ghost" className="text-[#6B7280] hover:text-[#1A1A1A] font-semibold text-lg px-8">
                            Return to Dashboard
                        </Button>
                    </Link>
                </div>
            </div>
        );
    }

    return (
        <div className="max-w-4xl mx-auto space-y-6">
            {/* Header */}
            <div className="flex items-center justify-between flex-wrap gap-4">
                <div>
                    <h1 className="text-2xl font-bold text-[#1A1A1A]">Document Vault</h1>
                    <p className="text-[#6B7280] mt-1">Securely store and track all required documents</p>
                </div>
                <div className="flex items-center gap-3">
                    <select
                        className="h-10 px-3 rounded-md border border-input bg-background text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring"
                        value={selectedTypeId}
                        onChange={(e) => setSelectedTypeId(e.target.value)}
                    >
                        <option value="">Select Document Type</option>
                        {requiredTypes.map((type: any) => (
                            <option key={type.id} value={type.id.toString()}>{type.name}</option>
                        ))}
                    </select>
                    <Button
                        className="bg-[#0B3C91] hover:bg-[#0B3C91]/90 text-white"
                        onClick={() => fileInputRef.current?.click()}
                        disabled={uploadMutation.isPending || !selectedTypeId}
                    >
                        {uploadMutation.isPending ? (
                            <span className="animate-spin mr-2">⏳</span>
                        ) : (
                            <Upload className="mr-2 h-4 w-4" />
                        )}
                        Upload
                    </Button>
                </div>
                <input
                    ref={fileInputRef}
                    type="file"
                    className="hidden"
                    accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                    onChange={handleFileChange}
                />
            </div>

            {/* Stats */}
            <div className="grid grid-cols-3 gap-4">
                <div className="bg-white rounded-2xl border border-[#E5E7EB] p-5 shadow-sm text-center">
                    <p className="text-3xl font-extrabold text-[#0B3C91]">{documents.length}</p>
                    <p className="text-xs font-semibold text-[#6B7280] mt-1 uppercase tracking-wider">Uploaded</p>
                </div>
                <div className="bg-white rounded-2xl border border-[#E5E7EB] p-5 shadow-sm text-center">
                    <p className="text-3xl font-extrabold text-amber-500">{requiredTypes.length - uploadedTypeIds.size}</p>
                    <p className="text-xs font-semibold text-[#6B7280] mt-1 uppercase tracking-wider">Remaining</p>
                </div>
                <div className="bg-white rounded-2xl border border-[#E5E7EB] p-5 shadow-sm text-center">
                    <p className="text-3xl font-extrabold text-green-500">
                        {requiredTypes.length > 0 ? Math.round((uploadedTypeIds.size / requiredTypes.length) * 100) : 0}%
                    </p>
                    <p className="text-xs font-semibold text-[#6B7280] mt-1 uppercase tracking-wider">Complete</p>
                </div>
            </div>

            <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {/* Checklist */}
                <div className="bg-white rounded-2xl border border-[#E5E7EB] shadow-sm overflow-hidden">
                    <div className="px-6 py-4 bg-[#F5F7FA] border-b border-[#E5E7EB]">
                        <h3 className="font-bold text-[#1A1A1A]">Required Documents</h3>
                        <p className="text-xs text-[#6B7280] mt-0.5">Standard checklist for visa applications</p>
                    </div>
                    <ul className="divide-y divide-[#E5E7EB]">
                        {requiredTypes.map((item: any) => {
                            const uploaded = uploadedTypeIds.has(item.id);
                            return (
                                <li key={item.id} className="flex items-center gap-3 px-6 py-3">
                                    {uploaded ? (
                                        <CheckCircle2 className="h-5 w-5 text-green-500 flex-shrink-0" />
                                    ) : (
                                        <Clock className="h-5 w-5 text-amber-400 flex-shrink-0" />
                                    )}
                                    <span className={`text-sm ${uploaded ? 'text-gray-400 line-through' : 'text-[#1A1A1A]'}`}>{item.name}</span>
                                </li>
                            );
                        })}
                    </ul>
                </div>

                {/* Uploaded Files */}
                <div className="bg-white rounded-2xl border border-[#E5E7EB] shadow-sm overflow-hidden">
                    <div className="px-6 py-4 bg-[#F5F7FA] border-b border-[#E5E7EB]">
                        <h3 className="font-bold text-[#1A1A1A]">Uploaded Files</h3>
                        <p className="text-xs text-[#6B7280] mt-0.5">All your securely stored documents</p>
                    </div>
                    {isLoading ? (
                        <div className="p-6 space-y-3 animate-pulse">
                            {[...Array(4)].map((_, i) => <div key={i} className="h-12 bg-gray-100 rounded-xl" />)}
                        </div>
                    ) : documents.length > 0 ? (
                        <ul className="divide-y divide-[#E5E7EB]">
                            {documents.map((doc: any) => (
                                <li key={doc.id} className="flex items-center gap-3 px-6 py-4">
                                    <div className="h-9 w-9 rounded-lg bg-blue-50 flex items-center justify-center flex-shrink-0">
                                        <FileText className="h-4 w-4 text-[#0B3C91]" />
                                    </div>
                                    <div className="flex-1 min-w-0">
                                        <p className="text-sm font-medium text-[#1A1A1A] truncate">{doc.name}</p>
                                        <p className="text-xs text-[#6B7280]">
                                            {doc.file_size ? `${Math.round(doc.file_size / 1024)} KB` : ''} · {new Date(doc.uploaded_at).toLocaleDateString()}
                                        </p>
                                    </div>
                                    <span className={`text-[11px] font-bold px-2 py-0.5 rounded-full flex-shrink-0 ${doc.status === 'uploaded' ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-500'}`}>
                                        {doc.status === 'uploaded' ? '✓ Uploaded' : doc.status}
                                    </span>
                                </li>
                            ))}
                        </ul>
                    ) : (
                        <div className="p-10 text-center">
                            <FolderOpen className="h-10 w-10 text-gray-300 mx-auto mb-3" />
                            <p className="text-[#6B7280] text-sm">No documents uploaded yet.</p>
                            <p className="text-[#6B7280] text-xs mt-1">Click 'Upload Document' above to get started.</p>
                        </div>
                    )}
                </div>
            </div>
        </div>
    );
}
