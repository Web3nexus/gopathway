import { useQuery } from '@tanstack/react-query';
import { useParams, Link } from 'react-router-dom';
import { motion } from 'framer-motion';
import { Button } from '@/components/ui/button';
import { ArrowLeft, Calendar, User, Clock, Share2, Facebook, Twitter, Link as LinkIcon, Sparkles } from 'lucide-react';
import axios from 'axios';
import { Helmet } from 'react-helmet-async';

const fetchBlogPost = async (slug: string) => {
    const { data } = await axios.get(`/api/v1/blog/${slug}`);
    return data;
};

export default function BlogPostView() {
    const { slug } = useParams<{ slug: string }>();
    const { data: post, isLoading, error } = useQuery({
        queryKey: ['blogPost', slug],
        queryFn: () => fetchBlogPost(slug!),
        enabled: !!slug
    });

    if (isLoading) {
        return (
            <div className="min-h-screen pt-32 flex flex-col items-center">
                <div className="w-16 h-16 border-4 border-blue-600 border-t-transparent rounded-full animate-spin" />
            </div>
        );
    }

    if (!post) {
        return (
            <div className="min-h-screen pt-32 text-center text-slate-900">
                <h2 className="text-3xl font-black">Post not found</h2>
                <Link to="/blog">
                    <Button mt-4>Back to Blog</Button>
                </Link>
            </div>
        );
    }

    return (
        <div className="min-h-screen bg-white">
            <Helmet>
                <title>{post.meta_title || post.title} | GoPathway Blog</title>
                <meta name="description" content={post.meta_description || post.excerpt} />
            </Helmet>

            {/* Article Hero */}
            <section className="relative pt-24 pb-16 lg:pt-32 lg:pb-24 overflow-hidden border-b border-slate-100">
                <div className="absolute inset-0 glossy-mesh -z-10 opacity-50" />
                <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                    <motion.div
                        initial={{ opacity: 0, y: 20 }}
                        animate={{ opacity: 1, y: 0 }}
                        className="flex flex-col items-center text-center"
                    >
                        <Link to="/blog">
                            <Button variant="ghost" className="mb-8 font-bold text-blue-600 hover:text-blue-700 hover:bg-blue-50 rounded-full pl-2">
                                <ArrowLeft className="mr-2 w-4 h-4" /> Back to Insights
                            </Button>
                        </Link>

                        <div className="flex items-center gap-4 mb-6">
                            <span className="px-4 py-1 bg-blue-600/10 text-blue-700 text-[10px] font-black uppercase tracking-[0.2em] rounded-full border border-blue-200/50">
                                Migration Strategy
                            </span>
                        </div>

                        <h1 className="text-4xl lg:text-6xl font-black text-slate-900 leading-tight mb-8 tracking-tight">
                            {post.title}
                        </h1>

                        <div className="flex flex-wrap items-center justify-center gap-6 text-slate-500 font-bold uppercase tracking-widest text-xs">
                            <div className="flex items-center gap-2">
                                <div className="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-black text-[10px]">
                                    {post.author?.name?.charAt(0) || 'A'}
                                </div>
                                <span className="text-slate-900">{post.author?.name || 'Admin'}</span>
                            </div>
                            <span className="flex items-center gap-2">
                                <Calendar className="w-4 h-4" />
                                {new Date(post.published_at).toLocaleDateString()}
                            </span>
                            <span className="flex items-center gap-2">
                                <Clock className="w-4 h-4" />
                                5 MIN READ
                            </span>
                        </div>
                    </motion.div>
                </div>
            </section>

            {/* Content Section */}
            <article className="py-20 lg:py-24 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div className="grid grid-cols-1 lg:grid-cols-12 gap-16">
                    {/* Share Sidebar */}
                    <div className="hidden lg:block lg:col-span-1 sticky top-32 h-fit">
                        <div className="flex flex-col gap-4">
                            <button className="w-12 h-12 rounded-2xl border border-slate-100 flex items-center justify-center text-slate-400 hover:text-blue-600 hover:border-blue-100 transition-all hover:shadow-lg">
                                <Twitter className="w-5 h-5" />
                            </button>
                            <button className="w-12 h-12 rounded-2xl border border-slate-100 flex items-center justify-center text-slate-400 hover:text-blue-600 hover:border-blue-100 transition-all hover:shadow-lg">
                                <Facebook className="w-5 h-5" />
                            </button>
                            <button className="w-12 h-12 rounded-2xl border border-slate-100 flex items-center justify-center text-slate-400 hover:text-blue-600 hover:border-blue-100 transition-all hover:shadow-lg">
                                <LinkIcon className="w-5 h-5" />
                            </button>
                        </div>
                    </div>

                    {/* Main Content */}
                    <div className="lg:col-span-8 lg:col-start-2">
                        {post.featured_image && (
                            <motion.div
                                initial={{ opacity: 0, scale: 0.95 }}
                                animate={{ opacity: 1, scale: 1 }}
                                transition={{ delay: 0.2 }}
                                className="mb-12 rounded-[40px] overflow-hidden shadow-2xl border border-slate-100"
                            >
                                <img
                                    src={post.featured_image}
                                    alt={post.title}
                                    className="w-full h-auto"
                                />
                            </motion.div>
                        )}

                        <div className="prose prose-lg prose-slate max-w-none prose-headings:font-black prose-headings:tracking-tight prose-a:text-blue-600 prose-img:rounded-[32px] prose-strong:text-slate-900">
                            <div dangerouslySetInnerHTML={{ __html: post.content }} />
                        </div>

                        {/* Article Footer */}
                        <div className="mt-20 pt-10 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-8">
                            <div className="flex items-center gap-4">
                                <div className="w-12 h-12 rounded-2xl bg-blue-600/10 flex items-center justify-center text-blue-600 font-black">
                                    {post.author?.name?.charAt(0) || 'A'}
                                </div>
                                <div>
                                    <p className="text-sm font-black text-slate-900 uppercase tracking-widest leading-none mb-1">Written by</p>
                                    <p className="text-lg font-bold text-slate-600">{post.author?.name || 'Admin'}</p>
                                </div>
                            </div>

                            <div className="flex gap-4">
                                <Button className="bg-slate-900 text-white rounded-2xl px-8 font-bold h-12">
                                    Join The Conversation
                                </Button>
                                <button className="w-12 h-12 rounded-2xl border border-slate-100 flex items-center justify-center text-slate-400 hover:text-blue-600 hover:border-blue-100 transition-all">
                                    <Share2 className="w-5 h-5" />
                                </button>
                            </div>
                        </div>
                    </div>

                    {/* More to Read / Sidebar */}
                    <div className="lg:col-span-3">
                        <div className="sticky top-32 space-y-12">
                            <div className="p-8 bg-blue-600 rounded-[32px] text-white shadow-xl shadow-blue-200">
                                <Sparkles className="w-10 h-10 mb-6 text-cyan-300" />
                                <h4 className="text-2xl font-black mb-4 leading-tight">Ready to move?</h4>
                                <p className="text-blue-100 font-medium mb-8 leading-relaxed">
                                    Get your personalized readiness score and start tracking your journey.
                                </p>
                                <Link to="/register">
                                    <Button className="w-full bg-white text-blue-600 hover:bg-slate-50 font-black h-12 rounded-xl">
                                        Get Started Free
                                    </Button>
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </article>
        </div>
    );
}
