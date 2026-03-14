import React, { useState } from 'react';
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import { FileText, Plus, Pencil, Trash2, CheckCircle2, XCircle, Search, Eye, Image as ImageIcon, Send } from 'lucide-react';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { useToast } from '@/hooks/use-toast';
import {
    Dialog,
    DialogContent,
    DialogHeader,
    DialogTitle,
    DialogFooter,
} from "@/components/ui/dialog";
import { Label } from '@/components/ui/label';
import { Switch } from '@/components/ui/switch';
import { Textarea } from '@/components/ui/textarea';
import axios from 'axios';

const fetchAdminPosts = async () => {
    const { data } = await axios.get('/api/v1/admin/blog');
    return data;
};

const createPost = async (data: any) => {
    const { data: response } = await axios.post('/api/v1/admin/blog', data);
    return response;
};

const updatePost = async ({ id, data }: { id: number, data: any }) => {
    const { data: response } = await axios.put(`/api/v1/admin/blog/${id}`, data);
    return response;
};

const deletePost = async (id: number) => {
    await axios.delete(`/api/v1/admin/blog/${id}`);
};

export default function BlogManagement() {
    const { toast } = useToast();
    const queryClient = useQueryClient();
    const [searchTerm, setSearchTerm] = useState('');
    const [isDialogOpen, setIsDialogOpen] = useState(false);
    const [editingPost, setEditingPost] = useState<any>(null);
    const [formData, setFormData] = useState({
        title: '',
        content: '',
        excerpt: '',
        featured_image: '',
        is_published: false,
        meta_title: '',
        meta_description: ''
    });

    const { data: response, isLoading } = useQuery({
        queryKey: ['admin-blog-posts'],
        queryFn: fetchAdminPosts
    });

    const posts = Array.isArray(response?.data) ? response.data : [];

    const mutation = useMutation({
        mutationFn: (data: any) => {
            if (editingPost) {
                return updatePost({ id: editingPost.id, data });
            }
            return createPost(data);
        },
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['admin-blog-posts'] });
            toast({ title: editingPost ? 'Post updated' : 'Post created' });
            setIsDialogOpen(false);
            setEditingPost(null);
            resetForm();
        }
    });

    const deleteMutation = useMutation({
        mutationFn: deletePost,
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['admin-blog-posts'] });
            toast({ title: 'Post deleted' });
        }
    });

    const resetForm = () => {
        setFormData({
            title: '',
            content: '',
            excerpt: '',
            featured_image: '',
            is_published: false,
            meta_title: '',
            meta_description: ''
        });
    };

    const handleEdit = (post: any) => {
        setEditingPost(post);
        setFormData({
            title: post.title,
            content: post.content,
            excerpt: post.excerpt || '',
            featured_image: post.featured_image || '',
            is_published: post.is_published,
            meta_title: post.meta_title || '',
            meta_description: post.meta_description || ''
        });
        setIsDialogOpen(true);
    };

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        mutation.mutate(formData);
    };

    const filteredPosts = posts.filter((p: any) =>
        p.title.toLowerCase().includes(searchTerm.toLowerCase())
    );

    return (
        <div className="space-y-6">
            <div className="flex items-center justify-between">
                <div>
                    <h1 className="text-2xl font-bold text-slate-900">Blog Management</h1>
                    <p className="text-slate-500 text-sm">Manage SEO articles and news updates</p>
                </div>
                <Button onClick={() => { setEditingPost(null); resetForm(); setIsDialogOpen(true); }} className="bg-blue-600 hover:bg-blue-700">
                    <Plus className="w-4 h-4 mr-2" />
                    New Article
                </Button>
            </div>

            <div className="bg-white rounded-2xl border shadow-sm overflow-hidden">
                <div className="p-4 border-b bg-slate-50/50 flex items-center gap-4">
                    <div className="relative flex-1 max-w-sm">
                        <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" />
                        <Input
                            placeholder="Search articles..."
                            className="pl-9 bg-white"
                            value={searchTerm}
                            onChange={(e) => setSearchTerm(e.target.value)}
                        />
                    </div>
                </div>

                <div className="overflow-x-auto">
                    <table className="w-full text-left border-collapse">
                        <thead>
                            <tr className="bg-slate-50/50 border-b text-xs uppercase tracking-wider text-slate-500 font-semibold">
                                <th className="px-6 py-4">Article</th>
                                <th className="px-6 py-4">Author</th>
                                <th className="px-6 py-4">Status</th>
                                <th className="px-6 py-4">Published Date</th>
                                <th className="px-6 py-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-slate-100">
                            {isLoading ? (
                                [...Array(3)].map((_, i) => (
                                    <tr key={i} className="animate-pulse">
                                        <td colSpan={5} className="px-6 py-4"><div className="h-10 bg-slate-100 rounded w-full" /></td>
                                    </tr>
                                ))
                            ) : filteredPosts.map((post: any) => (
                                <tr key={post.id} className="hover:bg-slate-50 transition-colors">
                                    <td className="px-6 py-4">
                                        <div className="flex items-center gap-3">
                                            {post.featured_image ? (
                                                <img src={post.featured_image} className="w-12 h-12 object-cover rounded-xl" alt="" />
                                            ) : (
                                                <div className="w-12 h-12 bg-slate-100 rounded-xl flex items-center justify-center">
                                                    <FileText className="w-5 h-5 text-slate-400" />
                                                </div>
                                            )}
                                            <div>
                                                <p className="font-bold text-slate-900">{post.title}</p>
                                                <p className="text-xs text-slate-500 truncate max-w-[300px]">{post.slug}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td className="px-6 py-4">
                                        <div className="flex items-center gap-2">
                                            <div className="w-6 h-6 rounded-full bg-blue-100 flex items-center justify-center text-[10px] font-bold text-blue-600">
                                                {post.author?.name?.charAt(0) || 'A'}
                                            </div>
                                            <span className="text-sm text-slate-600">{post.author?.name || 'Admin'}</span>
                                        </div>
                                    </td>
                                    <td className="px-6 py-4">
                                        {post.is_published ? (
                                            <span className="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-green-50 text-green-700 text-xs font-bold border border-green-200">
                                                <CheckCircle2 className="w-3.5 h-3.5" /> Published
                                            </span>
                                        ) : (
                                            <span className="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-100 text-slate-500 text-xs font-bold border border-slate-200">
                                                <XCircle className="w-3.5 h-3.5" /> Draft
                                            </span>
                                        )}
                                    </td>
                                    <td className="px-6 py-4 text-sm text-slate-500">
                                        {post.published_at ? new Date(post.published_at).toLocaleDateString() : 'N/A'}
                                    </td>
                                    <td className="px-6 py-4 text-right">
                                        <div className="flex items-center justify-end gap-2">
                                            <Button variant="ghost" size="icon" asChild>
                                                <a href={`/blog/${post.slug}`} target="_blank" rel="noopener noreferrer">
                                                    <Eye className="w-4 h-4 text-slate-400 hover:text-blue-600" />
                                                </a>
                                            </Button>
                                            <Button variant="ghost" size="icon" onClick={() => handleEdit(post)}>
                                                <Pencil className="w-4 h-4 text-slate-400 hover:text-blue-600" />
                                            </Button>
                                            <Button variant="ghost" size="icon" onClick={() => {
                                                if (confirm('Are you sure?')) deleteMutation.mutate(post.id);
                                            }}>
                                                <Trash2 className="w-4 h-4 text-slate-400 hover:text-red-600" />
                                            </Button>
                                        </div>
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>
            </div>

            {/* Editor Dialog */}
            <Dialog open={isDialogOpen} onOpenChange={setIsDialogOpen}>
                <DialogContent className="sm:max-w-[800px] max-h-[90vh] overflow-y-auto">
                    <DialogHeader>
                        <DialogTitle>{editingPost ? 'Edit Article' : 'Launch New Article'}</DialogTitle>
                    </DialogHeader>
                    <form onSubmit={handleSubmit} className="space-y-6 py-4">
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div className="space-y-2">
                                <Label htmlFor="title">Headline</Label>
                                <Input
                                    id="title"
                                    value={formData.title}
                                    onChange={e => setFormData({ ...formData, title: e.target.value })}
                                    placeholder="Catchy and relevant..."
                                    required
                                />
                            </div>
                            <div className="space-y-2">
                                <Label htmlFor="image">Featured Image URL</Label>
                                <Input
                                    id="image"
                                    value={formData.featured_image}
                                    onChange={e => setFormData({ ...formData, featured_image: e.target.value })}
                                    placeholder="https://images.unsplash..."
                                />
                            </div>
                        </div>

                        <div className="space-y-2">
                            <Label htmlFor="content">Article Content (HTML supported)</Label>
                            <Textarea
                                id="content"
                                className="min-h-[300px] font-mono text-sm"
                                value={formData.content}
                                onChange={e => setFormData({ ...formData, content: e.target.value })}
                                placeholder="Write your masterpiece here..."
                                required
                            />
                        </div>

                        <div className="space-y-2">
                            <Label htmlFor="excerpt">Short Snippet / Excerpt</Label>
                            <Textarea
                                id="excerpt"
                                value={formData.excerpt}
                                onChange={e => setFormData({ ...formData, excerpt: e.target.value })}
                                placeholder="Shown in list views..."
                            />
                        </div>

                        <div className="bg-slate-50 p-6 rounded-3xl border border-slate-100 space-y-6">
                            <h4 className="font-black text-slate-900 uppercase tracking-widest text-xs">SEO Configuration</h4>
                            <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div className="space-y-2">
                                    <Label htmlFor="meta_title">Meta Title</Label>
                                    <Input
                                        id="meta_title"
                                        value={formData.meta_title}
                                        onChange={e => setFormData({ ...formData, meta_title: e.target.value })}
                                        placeholder="SEO Title..."
                                    />
                                </div>
                                <div className="space-y-2">
                                    <Label htmlFor="meta_desc">Meta Description</Label>
                                    <Input
                                        id="meta_desc"
                                        value={formData.meta_description}
                                        onChange={e => setFormData({ ...formData, meta_description: e.target.value })}
                                        placeholder="SEO Description..."
                                    />
                                </div>
                            </div>
                        </div>

                        <div className="flex items-center justify-between p-6 bg-blue-50 rounded-3xl border border-blue-100">
                            <div className="space-y-0.5">
                                <Label className="text-base font-black text-blue-900">Publish to Network</Label>
                                <p className="text-sm text-blue-600 font-medium">Make this article visible to all users immediately</p>
                            </div>
                            <Switch checked={formData.is_published} onCheckedChange={val => setFormData({ ...formData, is_published: val })} />
                        </div>

                        <DialogFooter>
                            <Button type="button" variant="ghost" onClick={() => setIsDialogOpen(false)} className="rounded-xl">Cancel</Button>
                            <Button type="submit" disabled={mutation.isPending} className="bg-blue-600 hover:bg-blue-700 rounded-xl px-10 h-12">
                                {mutation.isPending ? 'Processing...' : (editingPost ? 'Update Post' : 'Publish Article')}
                                <Send className="ml-2 w-4 h-4" />
                            </Button>
                        </DialogFooter>
                    </form>
                </DialogContent>
            </Dialog>
        </div>
    );
}
