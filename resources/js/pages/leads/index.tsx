import { Head, router } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Button } from '@/components/ui/button';
import { Users, Download } from 'lucide-react';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Leads', href: '/leads' },
];

interface Lead {
    id: number;
    visitor_name: string;
    visitor_email: string;
    visitor_phone?: string;
    message?: string;
    created_at: string;
}

function exportCsv(leads: Lead[]) {
    const headers = ['Name', 'Email', 'Phone', 'Message', 'Date'];
    const rows = leads.map((l) => [
        `"${l.visitor_name}"`,
        `"${l.visitor_email}"`,
        `"${l.visitor_phone ?? ''}"`,
        `"${(l.message ?? '').replace(/"/g, '""')}"`,
        `"${l.created_at}"`,
    ]);
    const csv = [headers.join(','), ...rows.map((r) => r.join(','))].join('\n');
    const a = document.createElement('a');
    a.href = 'data:text/csv;charset=utf-8,' + encodeURIComponent(csv);
    a.download = 'leads.csv';
    a.click();
}

export default function LeadsIndex({ leads }: { leads: Lead[] }) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Leads" />

            <div className="p-6 space-y-6">
                <div className="flex items-center justify-between">
                    <div>
                        <h1 className="text-2xl font-bold text-gray-900 dark:text-white">Leads</h1>
                        <p className="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            Visitors who reached out from your public profile
                        </p>
                    </div>
                    {leads.length > 0 && (
                        <Button
                            type="button"
                            variant="outline"
                            onClick={() => exportCsv(leads)}
                            className="flex items-center gap-2"
                        >
                            <Download className="w-4 h-4" />
                            Export CSV
                        </Button>
                    )}
                </div>

                {leads.length === 0 ? (
                    <div className="flex flex-col items-center justify-center py-20 text-center">
                        <Users className="w-16 h-16 text-gray-300 dark:text-gray-600 mb-4" />
                        <h3 className="text-lg font-semibold text-gray-700 dark:text-gray-300">No leads yet</h3>
                        <p className="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            When visitors submit the contact form on your profile, they'll appear here.
                        </p>
                    </div>
                ) : (
                    <div className="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden">
                        <table className="w-full text-sm">
                            <thead>
                                <tr className="border-b border-gray-100 dark:border-gray-800 bg-gray-50 dark:bg-gray-800/50">
                                    <th className="text-left px-5 py-3 font-medium text-gray-600 dark:text-gray-400">Name</th>
                                    <th className="text-left px-5 py-3 font-medium text-gray-600 dark:text-gray-400">Email</th>
                                    <th className="text-left px-5 py-3 font-medium text-gray-600 dark:text-gray-400 hidden md:table-cell">Phone</th>
                                    <th className="text-left px-5 py-3 font-medium text-gray-600 dark:text-gray-400 hidden lg:table-cell">Message</th>
                                    <th className="text-left px-5 py-3 font-medium text-gray-600 dark:text-gray-400 hidden sm:table-cell">Date</th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-gray-100 dark:divide-gray-800">
                                {leads.map((lead) => (
                                    <tr key={lead.id} className="hover:bg-gray-50 dark:hover:bg-gray-800/30 transition-colors">
                                        <td className="px-5 py-3 font-medium text-gray-900 dark:text-white">
                                            {lead.visitor_name}
                                        </td>
                                        <td className="px-5 py-3 text-gray-600 dark:text-gray-400">
                                            <a href={`mailto:${lead.visitor_email}`} className="hover:underline text-indigo-600 dark:text-indigo-400">
                                                {lead.visitor_email}
                                            </a>
                                        </td>
                                        <td className="px-5 py-3 text-gray-600 dark:text-gray-400 hidden md:table-cell">
                                            {lead.visitor_phone ?? '—'}
                                        </td>
                                        <td className="px-5 py-3 text-gray-500 dark:text-gray-400 hidden lg:table-cell max-w-xs">
                                            <span className="line-clamp-2">{lead.message ?? '—'}</span>
                                        </td>
                                        <td className="px-5 py-3 text-gray-400 dark:text-gray-500 hidden sm:table-cell whitespace-nowrap">
                                            {lead.created_at}
                                        </td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </div>
                )}
            </div>
        </AppLayout>
    );
}
