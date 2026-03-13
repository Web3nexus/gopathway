import { Outlet } from 'react-router-dom';
import { AdminSidebar } from '@/components/layout/AdminSidebar';
import { Header } from '@/components/layout/Header';
import { Toaster } from '@/components/ui/toaster';

export function AdminLayout() {
    return (
        <div className="h-screen flex w-full bg-slate-50 relative overflow-hidden">
            <AdminSidebar />
            <div className="flex-1 flex flex-col min-w-0 z-10 overflow-hidden">
                <Header />
                <main className="flex-1 overflow-y-auto p-4 md:p-8">
                    <Outlet />
                </main>
            </div>
            <Toaster />
        </div>
    );
}
