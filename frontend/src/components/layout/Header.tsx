import { useState, useRef, useEffect } from 'react';
import { Link } from 'react-router-dom';
import { Bell, Search, User, LogOut, Check, CheckCheck, Crown } from 'lucide-react';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { useAuth } from '@/hooks/useAuth';
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import { notificationService } from '@/services/api/notificationService';
import { adminService } from '@/services/api/adminService';
import { ShieldAlert, LogOut as LeaveIcon } from 'lucide-react';

export function Header() {
    const { user, logout, isLoggingOut, isImpersonating } = useAuth();
    const queryClient = useQueryClient();
    const [isOpen, setIsOpen] = useState(false);
    const panelRef = useRef<HTMLDivElement>(null);

    const { data: notifData } = useQuery({
        queryKey: ['notifications'],
        queryFn: notificationService.getNotifications,
        refetchInterval: 30000, // Refetch every 30 seconds
    });

    const markReadMutation = useMutation({
        mutationFn: notificationService.markRead,
        onSuccess: () => queryClient.invalidateQueries({ queryKey: ['notifications'] }),
    });

    const markAllReadMutation = useMutation({
        mutationFn: notificationService.markAllRead,
        onSuccess: () => queryClient.invalidateQueries({ queryKey: ['notifications'] }),
    });

    const notifications = notifData?.data || [];
    const unreadCount = notifData?.unread_count || 0;

    const leaveImpersonationMutation = useMutation({
        mutationFn: adminService.leaveImpersonation,
        onSuccess: () => {
            window.location.href = '/admin/users';
        },
        onError: (err: any) => {
            console.error('Exit failed:', err);
            queryClient.invalidateQueries({ queryKey: ['user'] });
        }
    });

    // Close panel on outside click
    useEffect(() => {
        const handler = (e: MouseEvent) => {
            if (panelRef.current && !panelRef.current.contains(e.target as Node)) {
                setIsOpen(false);
            }
        };
        document.addEventListener('mousedown', handler);
        return () => document.removeEventListener('mousedown', handler);
    }, []);

    return (
        <>
            {isImpersonating && (
                <div className="bg-amber-500 text-white px-6 py-2 flex items-center justify-between z-[60] sticky top-0 animate-in fade-in slide-in-from-top duration-300">
                    <div className="flex items-center gap-2 text-sm font-bold">
                        <ShieldAlert className="w-4 h-4" />
                        <span>You are currently impersonating <span className="underline">{user?.name}</span></span>
                    </div>
                    <Button
                        variant="secondary"
                        size="sm"
                        onClick={() => leaveImpersonationMutation.mutate()}
                        disabled={leaveImpersonationMutation.isPending}
                        className="bg-white text-amber-600 hover:bg-amber-50 h-7 text-xs font-bold rounded-full gap-1.5"
                    >
                        {leaveImpersonationMutation.isPending ? 'Exiting...' : (
                            <>
                                <LeaveIcon className="w-3 h-3" />
                                Exit Impersonation
                            </>
                        )}
                    </Button>
                </div>
            )}
            <header className="h-16 border-b bg-white backdrop-blur-md dark:bg-black flex items-center justify-between px-6 z-[50] sticky top-0">
                <div className="flex-1 flex items-center">
                    <div className="relative w-full max-w-md hidden md:flex">
                        <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground" />
                        <Input
                            placeholder="Search countries, visas, resources..."
                            className="pl-9 bg-black/5 dark:bg-white/5 border-transparent focus-visible:ring-primary/50 rounded-full h-9"
                        />
                    </div>
                </div>

                <div className="flex items-center gap-4">
                    {/* Notification Bell */}
                    <div className="relative" ref={panelRef}>
                        <Button
                            variant="ghost"
                            size="icon"
                            className="relative hover:bg-black/5 dark:hover:bg-white/5 rounded-full"
                            onClick={() => setIsOpen(!isOpen)}
                        >
                            <Bell className="w-5 h-5 text-muted-foreground" />
                            {unreadCount > 0 && (
                                <span className="absolute -top-0.5 -right-0.5 min-w-[18px] h-[18px] bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center px-1 border-2 border-white dark:border-black">
                                    {unreadCount > 9 ? '9+' : unreadCount}
                                </span>
                            )}
                        </Button>

                        {/* Notification Dropdown */}
                        {isOpen && (
                            <div className="absolute right-0 top-12 w-80 sm:w-96 bg-white dark:bg-slate-900 rounded-2xl border shadow-2xl z-[100] overflow-hidden animate-in fade-in slide-in-from-top-2 duration-200 ring-1 ring-black/5">
                                <div className="flex items-center justify-between px-4 py-3 border-b bg-slate-50/80 dark:bg-slate-800/50">
                                    <h3 className="font-bold text-sm text-slate-900 dark:text-white">Notifications</h3>
                                    {unreadCount > 0 && (
                                        <button
                                            onClick={() => markAllReadMutation.mutate()}
                                            className="text-xs font-semibold text-blue-600 hover:text-blue-700 flex items-center gap-1"
                                        >
                                            <CheckCheck className="w-3.5 h-3.5" /> Mark all read
                                        </button>
                                    )}
                                </div>
                                <div className="max-h-80 overflow-y-auto divide-y divide-slate-100 dark:divide-slate-800">
                                    {!Array.isArray(notifications) || notifications.length === 0 ? (
                                        <div className="p-6 text-center">
                                            <Bell className="w-8 h-8 text-slate-200 mx-auto mb-2" />
                                            <p className="text-sm text-slate-400">No notifications yet.</p>
                                        </div>
                                    ) : (
                                        notifications.map((notif: any) => (
                                            <div
                                                key={notif.id}
                                                className={`flex gap-3 px-4 py-3 cursor-pointer transition-colors ${!notif.is_read ? 'bg-blue-50/50 dark:bg-blue-900/10 hover:bg-blue-50 dark:hover:bg-blue-900/20' : 'hover:bg-slate-50 dark:hover:bg-slate-800/50'}`}
                                                onClick={() => {
                                                    if (!notif.is_read) markReadMutation.mutate(notif.id);
                                                }}
                                            >
                                                <div className="flex-shrink-0 mt-1">
                                                    <div className={`w-2 h-2 rounded-full ${!notif.is_read ? 'bg-blue-500' : 'bg-slate-200'}`} />
                                                </div>
                                                <div className="flex-1 min-w-0">
                                                    <p className={`text-sm ${!notif.is_read ? 'font-semibold text-slate-900 dark:text-white' : 'text-slate-500'}`}>
                                                        {notif.title}
                                                    </p>
                                                    {notif.message && (
                                                        <p className="text-xs text-slate-400 mt-0.5 line-clamp-2">{notif.message}</p>
                                                    )}
                                                    <p className="text-[10px] text-slate-300 mt-1">
                                                        {new Date(notif.created_at).toLocaleDateString(undefined, { month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' })}
                                                    </p>
                                                </div>
                                                {!notif.is_read && (
                                                    <button
                                                        onClick={(e) => { e.stopPropagation(); markReadMutation.mutate(notif.id); }}
                                                        className="flex-shrink-0 text-slate-300 hover:text-blue-500 transition-colors"
                                                        title="Mark as read"
                                                    >
                                                        <Check className="w-4 h-4" />
                                                    </button>
                                                )}
                                            </div>
                                        ))
                                    )}
                                </div>
                            </div>
                        )}
                    </div>

                    <div className="w-px h-6 bg-border mx-1"></div>
                    <Link 
                        to={user?.roles?.some((role: any) => role.name === 'admin') ? '/admin/profile' : '/dashboard/settings'} 
                        className="flex items-center gap-2 hover:bg-black/5 dark:hover:bg-white/5 p-1 rounded-full transition-colors group"
                    >
                        <div className="w-8 h-8 rounded-full bg-primary/20 flex items-center justify-center border border-primary/20 group-hover:bg-primary/30 transition-colors">
                            <User className="w-4 h-4 text-primary" />
                        </div>
                        <div className="hidden sm:block text-left mr-2">
                            <div className="flex items-center gap-1.5 mb-0.5">
                                <p className="text-sm font-bold leading-none">{user?.name || 'Loading...'}</p>
                                {user?.is_premium && (
                                    <span className="bg-amber-100 text-amber-600 px-1.5 py-0.5 rounded text-[9px] font-black flex items-center gap-0.5 uppercase tracking-tighter ring-1 ring-amber-200">
                                        <Crown className="h-2.5 w-2.5" /> Premium
                                    </span>
                                )}
                            </div>
                            <p className="text-xs text-muted-foreground leading-none capitalize font-medium">{user?.roles?.[0]?.name || 'User'}</p>
                        </div>
                    </Link>

                    <Button
                        variant="ghost"
                        size="icon"
                        title="Logout"
                        onClick={() => logout()}
                        disabled={isLoggingOut}
                        className="text-muted-foreground hover:text-destructive hover:bg-destructive/10"
                    >
                        <LogOut className="w-4 h-4" />
                    </Button>
                </div>
            </header>
        </>
    );
}
