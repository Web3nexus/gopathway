import { useEffect, useState } from 'react';
import { useSearchParams, useNavigate, Link } from 'react-router-dom';
import { api } from '@/lib/api';
import { Button } from '@/components/ui/button';
import { CheckCircle2, XCircle, Loader2 } from 'lucide-react';
import { useToast } from '@/hooks/use-toast';
import { useQueryClient } from '@tanstack/react-query';

export default function VerifyEmail() {
    const [searchParams] = useSearchParams();
    const navigate = useNavigate();
    const { toast } = useToast();
    const queryClient = useQueryClient();
    const [status, setStatus] = useState<'loading' | 'success' | 'error'>('loading');
    const [message, setMessage] = useState('');

    useEffect(() => {
        const verifyUrl = searchParams.get('url');
        if (!verifyUrl) {
            setStatus('error');
            setMessage('Invalid verification link.');
            return;
        }

        const performVerification = async () => {
            try {
                // The URL in the param is the full backend signed route
                const response = await api.get(verifyUrl);
                setStatus('success');
                setMessage(response.data.message || 'Email verified successfully!');
                toast({ title: 'Success', description: 'Your email has been verified.' });
                
                // Invalidate frontend cache so the dashboard banner disappears
                queryClient.invalidateQueries({ queryKey: ['dashboard'] });
                queryClient.invalidateQueries({ queryKey: ['auth'] });
                
                // Redirect to dashboard after 3 seconds
                setTimeout(() => {
                    navigate('/dashboard');
                }, 3000);
            } catch (error: any) {
                setStatus('error');
                setMessage(error.response?.data?.message || 'Verification failed. The link may have expired.');
            }
        };

        performVerification();
    }, [searchParams, navigate, toast]);

    return (
        <div className="min-h-screen bg-slate-50 flex items-center justify-center p-6">
            <div className="max-w-md w-full bg-white rounded-3xl shadow-xl p-8 text-center border border-slate-100">
                {status === 'loading' && (
                    <div className="space-y-4">
                        <Loader2 className="h-12 w-12 text-blue-600 animate-spin mx-auto" />
                        <h2 className="text-2xl font-black text-slate-800">Verifying Email</h2>
                        <p className="text-slate-500">Please wait while we confirm your email address...</p>
                    </div>
                )}

                {status === 'success' && (
                    <div className="space-y-4">
                        <div className="h-16 w-16 bg-green-50 rounded-full flex items-center justify-center mx-auto">
                            <CheckCircle2 className="h-10 w-10 text-green-600" />
                        </div>
                        <h2 className="text-2xl font-black text-slate-800">Email Verified!</h2>
                        <p className="text-slate-500">{message}</p>
                        <p className="text-sm text-slate-400">Redirecting you to the dashboard...</p>
                        <Link to="/dashboard">
                            <Button className="mt-4 bg-[#0B3C91] hover:bg-[#0B3C91]/90 text-white font-bold rounded-xl h-11 px-8">
                                Go to Dashboard
                            </Button>
                        </Link>
                    </div>
                )}

                {status === 'error' && (
                    <div className="space-y-4">
                        <div className="h-16 w-16 bg-red-50 rounded-full flex items-center justify-center mx-auto">
                            <XCircle className="h-10 w-10 text-red-600" />
                        </div>
                        <h2 className="text-2xl font-black text-slate-800">Verification Failed</h2>
                        <p className="text-red-500/80 font-medium">{message}</p>
                        <div className="pt-4 flex flex-col gap-2">
                            <Link to="/dashboard">
                                <Button variant="outline" className="w-full border-slate-200 text-slate-600 font-bold rounded-xl h-11">
                                    Back to Dashboard
                                </Button>
                            </Link>
                            <Button 
                                variant="ghost" 
                                onClick={() => navigate(-1)}
                                className="text-slate-400 text-sm"
                            >
                                Try Again
                            </Button>
                        </div>
                    </div>
                )}
            </div>
        </div>
    );
}
