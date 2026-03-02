import { Head, router } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Button } from '@/components/ui/button';
import { Bell, Users, Star, TrendingUp, CheckCheck } from 'lucide-react';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Notifications', href: '/notifications' },
];

interface Notification {
    id: string;
    type: 'new_lead' | 'new_testimonial' | 'milestone' | string;
    data: Record<string, unknown>;
    read_at: string | null;
    created_at: string;
}

function NotificationIcon({ type }: { type: string }) {
    if (type === 'new_lead') return <Users className="w-4 h-4 text-blue-500" />;
    if (type === 'new_testimonial') return <Star className="w-4 h-4 text-amber-500" />;
    if (type === 'milestone') return <TrendingUp className="w-4 h-4 text-green-500" />;
    return <Bell className="w-4 h-4 text-gray-400" />;
}

function notificationText(n: Notification): string {
    const d = n.data;
    if (n.type === 'new_lead') {
        return `New lead from ${d.visitor_name ?? 'someone'}${d.message ? `: "${d.message}"` : ''}`;
    }
    if (n.type === 'new_testimonial') {
        const stars = typeof d.rating === 'number' ? ` (${d.rating}★)` : '';
        return `New testimonial from ${d.reviewer_name ?? 'someone'}${stars}`;
    }
    if (n.type === 'milestone') {
        return `Your profile just hit ${d.milestone?.toLocaleString()} views!`;
    }
    return 'You have a new notification.';
}

function formatRelative(iso: string): string {
    const diff = Date.now() - new Date(iso).getTime();
    const mins = Math.floor(diff / 60000);
    if (mins < 1) return 'just now';
    if (mins < 60) return `${mins}m ago`;
    const hrs = Math.floor(mins / 60);
    if (hrs < 24) return `${hrs}h ago`;
    return `${Math.floor(hrs / 24)}d ago`;
}

export default function NotificationsIndex({ notifications }: { notifications: Notification[] }) {
    const unread = notifications.filter((n) => !n.read_at);

    const markRead = (id: string) => {
        router.patch(route('notifications.read', id));
    };

    const markAllRead = () => {
        router.patch(route('notifications.read-all'));
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Notifications" />

            <div className="p-6 max-w-2xl mx-auto space-y-4">
                <div className="flex items-center justify-between">
                    <div>
                        <h1 className="text-2xl font-bold text-gray-900 dark:text-white">Notifications</h1>
                        <p className="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                            {unread.length > 0 ? `${unread.length} unread` : 'All caught up'}
                        </p>
                    </div>
                    {unread.length > 0 && (
                        <Button type="button" variant="outline" onClick={markAllRead} className="flex items-center gap-2 text-sm">
                            <CheckCheck className="w-4 h-4" />
                            Mark all read
                        </Button>
                    )}
                </div>

                {notifications.length === 0 ? (
                    <div className="flex flex-col items-center justify-center py-20 text-center">
                        <Bell className="w-14 h-14 text-gray-200 dark:text-gray-700 mb-4" />
                        <h3 className="text-lg font-semibold text-gray-700 dark:text-gray-300">No notifications yet</h3>
                        <p className="text-sm text-gray-400 mt-1">You'll be notified when you get new leads or testimonials.</p>
                    </div>
                ) : (
                    <div className="space-y-2">
                        {notifications.map((n) => (
                            <div
                                key={n.id}
                                className={`flex items-start gap-4 p-4 rounded-xl border transition-colors ${
                                    n.read_at
                                        ? 'bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-700'
                                        : 'bg-blue-50 dark:bg-blue-900/10 border-blue-200 dark:border-blue-800'
                                }`}
                            >
                                <div className={`w-8 h-8 rounded-full flex items-center justify-center shrink-0 ${
                                    n.read_at
                                        ? 'bg-gray-100 dark:bg-gray-800'
                                        : 'bg-white dark:bg-gray-800 shadow-sm'
                                }`}>
                                    <NotificationIcon type={n.type} />
                                </div>

                                <div className="flex-1 min-w-0">
                                    <p className={`text-sm leading-snug ${n.read_at ? 'text-gray-600 dark:text-gray-400' : 'text-gray-900 dark:text-white font-medium'}`}>
                                        {notificationText(n)}
                                    </p>
                                    <p className="text-xs text-gray-400 mt-1">{formatRelative(n.created_at)}</p>
                                </div>

                                {!n.read_at && (
                                    <button
                                        type="button"
                                        onClick={() => markRead(n.id)}
                                        title="Mark as read"
                                        className="shrink-0 w-2 h-2 rounded-full bg-blue-500 mt-1.5 hover:bg-blue-700 transition-colors"
                                    />
                                )}
                            </div>
                        ))}
                    </div>
                )}
            </div>
        </AppLayout>
    );
}
