import { useQuery } from '@tanstack/react-query';
import { motion } from 'framer-motion';
import { Link } from 'react-router-dom';
import { Button } from '@/components/ui/button';
import { ArrowRight, Calendar, User, Clock } from 'lucide-react';
import axios from 'axios';

const fetchBlogPosts = async () => {
    const { data } = await axios.get('/api/v1/blog');
    return data;
};

export default function BlogList() {
    const { data, isLoading, error } = useQuery({
        queryKey: ['blogPosts'],
        queryFn: fetchBlogPosts
    });

    const posts = data?.data || [];

    return (
        <div className="min-h-screen bg-slate-50 pt-24 pb-20">
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div className="text-center mb-16">
                    <motion.h1
                        initial={{ opacity: 0, y: -20 }}
                        animate={{ opacity: 1, y: 0 }}
                        className="text-4xl lg:text-6xl font-black text-slate-900 mb-6 tracking-tight"
                    >
                        GoPathway <span className="text-blue-600">Insights</span>
                    </motion.h1>
                    <motion.p
                        initial={{ opacity: 0, y: 20 }}
                        animate={{ opacity: 1, y: 0 }}
                        transition={{ delay: 0.1 }}
                        className="text-xl text-slate-600 max-w-2xl mx-auto font-medium"
                    >
                        Expert advice, success stories, and the latest intelligence on global migration.
                    </motion.p>
                </div>

                {isLoading ? (
                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        {[...Array(6)].map((_, i) => (
                            <div key={i} className="bg-white rounded-[32px] h-96 animate-pulse" />
                        ))}
                    </div>
                ) : posts.length > 0 ? (
                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        {posts.map((post: any, idx: number) => (
                            <motion.div
                                key={post.id}
                                initial={{ opacity: 0, scale: 0.95 }}
                                whileInView={{ opacity: 1, scale: 1 }}
                                viewport={{ once: true }}
                                transition={{ delay: idx * 0.05 }}
                                className="group"
                            >
                                <Link to={`/blog/${post.slug}`}>
                                    <div className="bg-white rounded-[32px] overflow-hidden border border-slate-100 shadow-sm transition-all duration-500 hover:shadow-2xl hover:-translate-y-2 flex flex-col h-full">
                                        <div className="relative h-56 overflow-hidden">
                                            <img
                                                src={post.featured_image || 'https://images.unsplash.com/photo-1434030216411-0b793f4b4173?auto=format&fit=crop&q=80&w=1000'}
                                                alt={post.title}
                                                className="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                                            />
                                            <div className="absolute top-4 left-4">
                                                <span className="bg-white/90 backdrop-blur-md text-blue-600 text-[10px] font-black uppercase tracking-widest px-3 py-1 rounded-full border border-blue-100 shadow-sm">
                                                    Migration Strategy
                                                </span>
                                            </div>
                                        </div>
                                        <div className="p-8 flex flex-col flex-grow">
                                            <div className="flex items-center gap-4 text-slate-400 text-xs font-bold uppercase tracking-widest mb-4">
                                                <span className="flex items-center gap-1">
                                                    <Calendar className="w-3.5 h-3.5" />
                                                    {new Date(post.published_at).toLocaleDateString()}
                                                </span>
                                                <span className="flex items-center gap-1">
                                                    <Clock className="w-3.5 h-3.5" />
                                                    5 min read
                                                </span>
                                            </div>
                                            <h3 className="text-2xl font-black text-slate-900 mb-4 tracking-tight group-hover:text-blue-600 transition-colors line-clamp-2">
                                                {post.title}
                                            </h3>
                                            <p className="text-slate-600 font-medium mb-6 line-clamp-3">
                                                {post.excerpt || post.content.substring(0, 150) + '...'}
                                            </p>
                                            <div className="mt-auto pt-6 border-t border-slate-50 flex items-center justify-between">
                                                <div className="flex items-center gap-3">
                                                    <div className="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-black text-xs">
                                                        {post.author?.name?.charAt(0) || 'A'}
                                                    </div>
                                                    <span className="text-sm font-bold text-slate-900">{post.author?.name || 'Admin'}</span>
                                                </div>
                                                <Button variant="ghost" className="p-0 hover:bg-transparent text-blue-600 font-black text-sm group-hover:translate-x-1 transition-transform">
                                                    Read More <ArrowRight className="ml-2 w-4 h-4" />
                                                </Button>
                                            </div>
                                        </div>
                                    </div>
                                </Link>
                            </motion.div>
                        ))}
                    </div>
                ) : (
                    <div className="text-center py-20 bg-white rounded-[40px] border border-dashed border-slate-200">
                        <motion.div initial={{ scale: 0.9, opacity: 0 }} animate={{ scale: 1, opacity: 1 }}>
                            <h3 className="text-2xl font-black text-slate-900 mb-2 tracking-tight">No posts yet</h3>
                            <p className="text-slate-500 font-medium">Check back soon for expert insights and news.</p>
                        </motion.div>
                    </div>
                )}
            </div>
        </div>
    );
}
