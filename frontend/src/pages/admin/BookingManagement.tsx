import { useQuery } from '@tanstack/react-query';
import api from '@/lib/api';
import { Calendar, User, Clock, CheckCircle2, XCircle, Info } from 'lucide-react';
import { Button } from '@/components/ui/button';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from "@/components/ui/table";
import { Badge } from "@/components/ui/badge";

export default function BookingManagement() {
    const { data, isLoading } = useQuery({
        queryKey: ['admin-bookings'],
        queryFn: () => api.get('/api/v1/admin/bookings').then(res => res.data),
    });
    const bookings = Array.isArray(data) ? data : [];

    if (isLoading) {
        return <div className="p-8 text-center text-slate-500">Loading bookings...</div>;
    }

    return (
        <div className="p-8 max-w-7xl mx-auto space-y-8">
            <div>
                <h1 className="text-3xl font-black text-slate-900 mb-2">Booking Management</h1>
                <p className="text-slate-500">Monitor and manage all professional consultations and translations across the platform.</p>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div className="bg-white p-6 rounded-3xl border shadow-sm">
                    <p className="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Total Bookings</p>
                    <p className="text-3xl font-black text-blue-600">{bookings?.length || 0}</p>
                </div>
                <div className="bg-white p-6 rounded-3xl border shadow-sm">
                    <p className="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Pending Requests</p>
                    <p className="text-3xl font-black text-amber-500">
                        {bookings?.filter?.((b: any) => b.status === 'pending')?.length || 0}
                    </p>
                </div>
                <div className="bg-white p-6 rounded-3xl border shadow-sm">
                    <p className="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Completed</p>
                    <p className="text-3xl font-black text-green-500">
                        {bookings?.filter?.((b: any) => b.status === 'completed')?.length || 0}
                    </p>
                </div>
            </div>

            <div className="bg-white rounded-3xl border shadow-sm overflow-hidden">
                <Table>
                    <TableHeader>
                        <TableRow className="bg-slate-50/50">
                            <TableHead className="px-8 py-4">User</TableHead>
                            <TableHead className="px-8 py-4">Professional</TableHead>
                            <TableHead className="px-8 py-4">type</TableHead>
                            <TableHead className="px-8 py-4">Status</TableHead>
                            <TableHead className="px-8 py-4">Scheduled For</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        {bookings?.length > 0 ? (
                            bookings.map((booking: any) => (
                                <TableRow key={booking.id}>
                                    <TableCell className="px-8 py-4">
                                        <div className="font-bold">{booking.user?.name}</div>
                                        <div className="text-xs text-slate-500">{booking.user?.email}</div>
                                    </TableCell>
                                    <TableCell className="px-8 py-4">
                                        <div className="font-bold">{booking.professional?.name}</div>
                                        <div className="text-xs text-slate-500 capitalize">{booking.professional?.roles?.[0]?.name}</div>
                                    </TableCell>
                                    <TableCell className="px-8 py-4 capitalize font-medium">{booking.type}</TableCell>
                                    <TableCell className="px-8 py-4">
                                        <Badge variant={
                                            booking.status === 'completed' ? 'default' :
                                                booking.status === 'confirmed' ? 'outline' :
                                                    booking.status === 'cancelled' ? 'destructive' : 'secondary'
                                        } className="font-bold uppercase tracking-tight text-[10px]">
                                            {booking.status}
                                        </Badge>
                                    </TableCell>
                                    <TableCell className="px-8 py-4 text-slate-500">
                                        {booking.scheduled_at ? new Date(booking.scheduled_at).toLocaleString() : 'Not set'}
                                    </TableCell>
                                </TableRow>
                            ))
                        ) : (
                            <TableRow>
                                <TableCell colSpan={5} className="px-8 py-12 text-center text-slate-400">
                                    <Info className="h-8 w-8 mx-auto mb-2 opacity-20" />
                                    No bookings found in the system.
                                </TableCell>
                            </TableRow>
                        )}
                    </TableBody>
                </Table>
            </div>
        </div>
    );
}
