import { Outlet } from 'react-router-dom';
import { Sidebar } from '@/components/layout/Sidebar';
import { Header } from '@/components/layout/Header';
import { Toaster } from '@/components/ui/toaster';
import { useAuth } from '@/hooks/useAuth';

export function DashboardLayout() {
    const { user } = useAuth();

    return (
        <div className="h-screen flex w-full bg-background relative overflow-hidden">
            <Sidebar />
            <div className="flex-1 flex flex-col min-w-0 overflow-hidden relative">
                <Header />
                <main className="flex-1 overflow-y-auto bg-slate-50/50 p-4 md:p-8 custom-scrollbar relative">
                    <Outlet />
                </main>
            </div>

            <Toaster />
        </div>
    );
}

export default DashboardLayout;
