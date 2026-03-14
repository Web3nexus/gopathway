import { useState, useEffect } from 'react';
import { useQuery } from '@tanstack/react-query';
import { publicService } from '@/services/api/publicService';
import { Button } from '@/components/ui/button';
import { X, ShieldCheck } from 'lucide-react';
import { Link } from 'react-router-dom';

export function CookieConsent() {
    const [isVisible, setIsVisible] = useState(false);
    
    const { data: settingsData } = useQuery({
        queryKey: ['public-settings'],
        queryFn: publicService.getSettings,
        staleTime: 1000 * 60 * 60,
    });

    const settings = settingsData?.data || {};
    const isEnabled = settings.cookie_consent_enabled === '1' || settings.cookie_consent_enabled === true;
    const message = settings.cookie_consent_message || 'We use cookies to enhance your experience.';
    const privacyUrl = settings.privacy_policy_url || '/privacy-policy';

    useEffect(() => {
        const consent = localStorage.getItem('cookie-consent');
        if (isEnabled && !consent) {
            // Delay showing the banner for better UX
            const timer = setTimeout(() => setIsVisible(true), 1500);
            return () => clearTimeout(timer);
        }
    }, [isEnabled]);

    const handleAccept = () => {
        localStorage.setItem('cookie-consent', 'accepted');
        setIsVisible(false);
    };

    const handleDecline = () => {
        localStorage.setItem('cookie-consent', 'declined');
        setIsVisible(false);
    };

    if (!isVisible) return null;

    return (
        <div className="fixed bottom-6 left-6 right-6 md:left-auto md:right-8 md:max-w-md z-[100] animate-in slide-in-from-bottom-10 fade-in duration-500">
            <div className="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-2xl rounded-2xl p-6 relative overflow-hidden group">
                {/* Background Pattern */}
                <div className="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-primary/5 rounded-full blur-2xl group-hover:bg-primary/10 transition-colors duration-500" />
                
                <div className="flex items-start gap-4 relative">
                    <div className="h-10 w-10 shrink-0 rounded-full bg-primary/10 flex items-center justify-center text-primary">
                        <ShieldCheck className="w-5 h-5" />
                    </div>
                    
                    <div className="flex-1 space-y-3">
                        <div className="flex items-center justify-between">
                            <h3 className="font-semibold text-slate-900 dark:text-white">Cookie Consent</h3>
                            <button 
                                onClick={() => setIsVisible(false)}
                                className="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors"
                            >
                                <X className="w-4 h-4" />
                            </button>
                        </div>
                        
                        <p className="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                            {message}{' '}
                            <Link to={privacyUrl} className="text-primary hover:underline font-medium">
                                Privacy Policy
                            </Link>
                        </p>
                        
                        <div className="flex items-center gap-3 pt-2">
                            <Button 
                                onClick={handleAccept}
                                size="sm" 
                                className="flex-1 bg-primary hover:bg-primary/90 text-white shadow-lg shadow-primary/20"
                            >
                                Accept All
                            </Button>
                            <Button 
                                onClick={handleDecline}
                                variant="outline" 
                                size="sm"
                                className="flex-1 border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-800"
                            >
                                Reject
                            </Button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}
