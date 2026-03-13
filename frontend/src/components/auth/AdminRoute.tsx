import { Navigate, Outlet } from 'react-router-dom';
import { useAuth } from '@/hooks/useAuth';
import { Loader2 } from 'lucide-react';

export function AdminRoute() {
    const { user, isLoading } = useAuth();

    if (isLoading) {
        return (
            <div className="min-h-screen flex items-center justify-center bg-background">
                <Loader2 className="h-8 w-8 animate-spin text-primary" />
            </div>
        );
    }

    const isAdmin = user?.roles?.some((role: any) => role.name === 'admin');

    if (!user || !isAdmin) {
        return <Navigate to="/dashboard" replace />;
    }

    return <Outlet />;
}
