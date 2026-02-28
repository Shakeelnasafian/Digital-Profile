import React from 'react';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/react';
import { Eye, FolderOpen, Briefcase, UserCircle2, ArrowRight, QrCode, CheckCircle2, Circle, Smartphone, Monitor, Tablet } from 'lucide-react';
import ReactApexChart from 'react-apexcharts';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
];

interface Stats {
    profile_views: number;
    project_count: number;
    experience_count: number;
    has_profile: boolean;
    profile_slug: string | null;
    profile_id?: number;
    views_last_30_days: { date: string; count: number }[];
    device_breakdown: { mobile: number; desktop: number; tablet: number };
    top_referrers: { referrer: string; count: number }[];
    completion_score: number;
    completion_checklist: { label: string; href: string; done: boolean }[];
}

function StatCard({
    icon,
    label,
    value,
    color,
}: {
    icon: React.ReactNode;
    label: string;
    value: number | string;
    color: string;
}) {
    return (
        <div className="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-2xl p-5 flex items-center gap-4">
            <div className={`w-12 h-12 rounded-xl flex items-center justify-center ${color}`}>
                {icon}
            </div>
            <div>
                <p className="text-sm text-gray-500 dark:text-gray-400">{label}</p>
                <p className="text-2xl font-bold text-gray-900 dark:text-white">{value}</p>
            </div>
        </div>
    );
}

function CompletionRing({ score }: { score: number }) {
    const deg = Math.round(score * 3.6);
    return (
        <div
            className="w-24 h-24 rounded-full flex items-center justify-center"
            style={{
                background: `conic-gradient(#2563eb ${deg}deg, #e5e7eb ${deg}deg)`,
            }}
        >
            <div className="w-[72px] h-[72px] rounded-full bg-white dark:bg-gray-900 flex items-center justify-center">
                <span className="text-xl font-bold text-gray-900 dark:text-white">{score}%</span>
            </div>
        </div>
    );
}

const REFERRER_LABELS: Record<string, string> = {
    linkedin: 'LinkedIn',
    whatsapp: 'WhatsApp',
    twitter: 'Twitter / X',
    instagram: 'Instagram',
    qr: 'QR Code',
    direct: 'Direct',
    other: 'Other',
};

export default function Dashboard({ stats }: { stats: Stats }) {
    const hasAnalytics = stats.views_last_30_days.length > 0 || stats.profile_views > 0;

    const viewSeries = [{ name: 'Views', data: stats.views_last_30_days.map((v) => v.count) }];
    const viewCategories = stats.views_last_30_days.map((v) => v.date);

    const viewChartOptions: ApexCharts.ApexOptions = {
        chart: { type: 'area', toolbar: { show: false }, background: 'transparent', sparkline: { enabled: false } },
        dataLabels: { enabled: false },
        stroke: { curve: 'smooth', width: 2 },
        fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.35, opacityTo: 0.05, stops: [0, 100] } },
        xaxis: {
            categories: viewCategories,
            labels: {
                formatter: (val: string) => {
                    if (!val) return '';
                    const d = new Date(val);
                    return d.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
                },
                rotate: -45,
                style: { fontSize: '11px' },
            },
            axisBorder: { show: false },
            axisTicks: { show: false },
            tickAmount: 6,
        },
        yaxis: { labels: { style: { fontSize: '11px' } }, min: 0, forceNiceScale: true },
        grid: { borderColor: '#f3f4f6', strokeDashArray: 4 },
        tooltip: { x: { format: 'dd MMM yyyy' } },
        colors: ['#2563eb'],
    };

    const { mobile, desktop, tablet } = stats.device_breakdown;
    const deviceTotal = mobile + desktop + tablet || 1;
    const donutSeries = [mobile, desktop, tablet];
    const donutOptions: ApexCharts.ApexOptions = {
        chart: { type: 'donut', background: 'transparent' },
        labels: ['Mobile', 'Desktop', 'Tablet'],
        colors: ['#2563eb', '#7c3aed', '#06b6d4'],
        legend: { position: 'bottom', fontSize: '12px' },
        dataLabels: { enabled: false },
        plotOptions: { pie: { donut: { size: '65%' } } },
        tooltip: { y: { formatter: (val: number) => `${val} visits` } },
    };

    const totalReferrers = stats.top_referrers.reduce((s, r) => s + r.count, 0) || 1;

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Dashboard" />

            <div className="p-6 space-y-8">
                {/* Welcome */}
                <div>
                    <h1 className="text-2xl font-bold text-gray-900 dark:text-white">Dashboard</h1>
                    <p className="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        Overview of your digital profile activity
                    </p>
                </div>

                {/* Stats Grid */}
                <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    <StatCard
                        icon={<Eye className="w-6 h-6 text-blue-600" />}
                        label="Profile Views"
                        value={stats.profile_views}
                        color="bg-blue-50 dark:bg-blue-900/20"
                    />
                    <StatCard
                        icon={<FolderOpen className="w-6 h-6 text-purple-600" />}
                        label="Projects"
                        value={stats.project_count}
                        color="bg-purple-50 dark:bg-purple-900/20"
                    />
                    <StatCard
                        icon={<Briefcase className="w-6 h-6 text-green-600" />}
                        label="Experience Entries"
                        value={stats.experience_count}
                        color="bg-green-50 dark:bg-green-900/20"
                    />
                </div>

                {/* Profile Completion + Analytics row */}
                {stats.has_profile && (
                    <div className="grid gap-6 lg:grid-cols-3">
                        {/* Completion Widget */}
                        <div className="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-2xl p-6">
                            <h2 className="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Profile Completion</h2>
                            <div className="flex items-center gap-5 mb-5">
                                <CompletionRing score={stats.completion_score} />
                                <div>
                                    <p className="text-2xl font-bold text-gray-900 dark:text-white">{stats.completion_score}%</p>
                                    <p className="text-xs text-gray-400 dark:text-gray-500 mt-0.5">complete</p>
                                </div>
                            </div>
                            <ul className="space-y-2 max-h-48 overflow-y-auto pr-1">
                                {stats.completion_checklist.map((item, i) => (
                                    <li key={i} className="flex items-start gap-2">
                                        {item.done ? (
                                            <CheckCircle2 className="w-4 h-4 text-green-500 shrink-0 mt-0.5" />
                                        ) : (
                                            <Circle className="w-4 h-4 text-gray-300 dark:text-gray-600 shrink-0 mt-0.5" />
                                        )}
                                        {item.done ? (
                                            <span className="text-xs text-gray-400 line-through">{item.label}</span>
                                        ) : (
                                            <Link href={item.href} className="text-xs text-blue-600 dark:text-blue-400 hover:underline">
                                                {item.label}
                                            </Link>
                                        )}
                                    </li>
                                ))}
                            </ul>
                        </div>

                        {/* Views Chart */}
                        <div className="lg:col-span-2 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-2xl p-6">
                            <h2 className="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">
                                Views — last 30 days
                            </h2>
                            {hasAnalytics ? (
                                <ReactApexChart
                                    type="area"
                                    height={180}
                                    options={viewChartOptions}
                                    series={viewSeries}
                                />
                            ) : (
                                <div className="h-44 flex items-center justify-center text-sm text-gray-400 dark:text-gray-500">
                                    No view data yet — share your profile to start tracking.
                                </div>
                            )}
                        </div>
                    </div>
                )}

                {/* Device + Referrers row */}
                {stats.has_profile && (
                    <div className="grid gap-6 lg:grid-cols-2">
                        {/* Device Breakdown */}
                        <div className="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-2xl p-6">
                            <h2 className="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Device Breakdown</h2>
                            {deviceTotal > 1 ? (
                                <>
                                    <ReactApexChart
                                        type="donut"
                                        height={220}
                                        options={donutOptions}
                                        series={donutSeries}
                                    />
                                    <div className="grid grid-cols-3 gap-2 mt-3 text-center">
                                        <div>
                                            <Smartphone className="w-4 h-4 text-blue-600 mx-auto mb-1" />
                                            <p className="text-xs text-gray-500">Mobile</p>
                                            <p className="text-sm font-semibold text-gray-800 dark:text-white">
                                                {Math.round((mobile / deviceTotal) * 100)}%
                                            </p>
                                        </div>
                                        <div>
                                            <Monitor className="w-4 h-4 text-violet-600 mx-auto mb-1" />
                                            <p className="text-xs text-gray-500">Desktop</p>
                                            <p className="text-sm font-semibold text-gray-800 dark:text-white">
                                                {Math.round((desktop / deviceTotal) * 100)}%
                                            </p>
                                        </div>
                                        <div>
                                            <Tablet className="w-4 h-4 text-cyan-600 mx-auto mb-1" />
                                            <p className="text-xs text-gray-500">Tablet</p>
                                            <p className="text-sm font-semibold text-gray-800 dark:text-white">
                                                {Math.round((tablet / deviceTotal) * 100)}%
                                            </p>
                                        </div>
                                    </div>
                                </>
                            ) : (
                                <div className="h-44 flex items-center justify-center text-sm text-gray-400 dark:text-gray-500">
                                    No device data yet.
                                </div>
                            )}
                        </div>

                        {/* Top Referrers */}
                        <div className="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-2xl p-6">
                            <h2 className="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Top Traffic Sources</h2>
                            {stats.top_referrers.length > 0 ? (
                                <ul className="space-y-3">
                                    {stats.top_referrers.map((ref, i) => (
                                        <li key={i} className="flex items-center gap-3">
                                            <span className="text-xs text-gray-400 w-4 text-right">{i + 1}</span>
                                            <div className="flex-1">
                                                <div className="flex items-center justify-between mb-1">
                                                    <span className="text-sm text-gray-700 dark:text-gray-300">
                                                        {REFERRER_LABELS[ref.referrer] ?? ref.referrer}
                                                    </span>
                                                    <span className="text-xs font-medium text-gray-500">{ref.count}</span>
                                                </div>
                                                <div className="h-1.5 bg-gray-100 dark:bg-gray-800 rounded-full overflow-hidden">
                                                    <div
                                                        className="h-full bg-blue-600 rounded-full"
                                                        style={{ width: `${Math.round((ref.count / totalReferrers) * 100)}%` }}
                                                    />
                                                </div>
                                            </div>
                                        </li>
                                    ))}
                                </ul>
                            ) : (
                                <div className="h-44 flex items-center justify-center text-sm text-gray-400 dark:text-gray-500">
                                    No referrer data yet.
                                </div>
                            )}
                        </div>
                    </div>
                )}

                {/* Quick Actions */}
                <div>
                    <h2 className="text-lg font-semibold text-gray-900 dark:text-white mb-4">Quick Actions</h2>
                    <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">

                        {/* Profile card */}
                        {stats.has_profile ? (
                            <Link
                                href={route('profile.show', stats.profile_slug!)}
                                className="group bg-gradient-to-br from-blue-600 to-indigo-600 rounded-2xl p-6 text-white hover:shadow-lg transition-shadow"
                            >
                                <QrCode className="w-8 h-8 mb-3 opacity-90" />
                                <h3 className="font-semibold text-lg">My Digital Card</h3>
                                <p className="text-blue-100 text-sm mt-1">View and share your profile</p>
                                <div className="flex items-center gap-1 mt-4 text-sm font-medium">
                                    View Card <ArrowRight className="w-4 h-4 group-hover:translate-x-1 transition-transform" />
                                </div>
                            </Link>
                        ) : (
                            <Link
                                href={route('profile.create')}
                                className="group bg-gradient-to-br from-blue-600 to-indigo-600 rounded-2xl p-6 text-white hover:shadow-lg transition-shadow"
                            >
                                <UserCircle2 className="w-8 h-8 mb-3 opacity-90" />
                                <h3 className="font-semibold text-lg">Create Your Card</h3>
                                <p className="text-blue-100 text-sm mt-1">Set up your digital profile</p>
                                <div className="flex items-center gap-1 mt-4 text-sm font-medium">
                                    Get Started <ArrowRight className="w-4 h-4 group-hover:translate-x-1 transition-transform" />
                                </div>
                            </Link>
                        )}

                        {/* Projects */}
                        <Link
                            href={route('projects.index')}
                            className="group bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-2xl p-6 hover:shadow-md hover:border-purple-300 dark:hover:border-purple-600 transition-all"
                        >
                            <FolderOpen className="w-8 h-8 text-purple-600 mb-3" />
                            <h3 className="font-semibold text-lg text-gray-900 dark:text-white">Projects</h3>
                            <p className="text-gray-500 dark:text-gray-400 text-sm mt-1">
                                {stats.project_count > 0
                                    ? `You have ${stats.project_count} project${stats.project_count === 1 ? '' : 's'}`
                                    : 'Showcase your portfolio work'}
                            </p>
                            <div className="flex items-center gap-1 mt-4 text-sm font-medium text-purple-600 dark:text-purple-400">
                                Manage Projects <ArrowRight className="w-4 h-4 group-hover:translate-x-1 transition-transform" />
                            </div>
                        </Link>

                        {/* Experience */}
                        <Link
                            href={route('experience.index')}
                            className="group bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-2xl p-6 hover:shadow-md hover:border-green-300 dark:hover:border-green-600 transition-all"
                        >
                            <Briefcase className="w-8 h-8 text-green-600 mb-3" />
                            <h3 className="font-semibold text-lg text-gray-900 dark:text-white">Experience</h3>
                            <p className="text-gray-500 dark:text-gray-400 text-sm mt-1">
                                {stats.experience_count > 0
                                    ? `${stats.experience_count} experience entr${stats.experience_count === 1 ? 'y' : 'ies'}`
                                    : 'Add your work history'}
                            </p>
                            <div className="flex items-center gap-1 mt-4 text-sm font-medium text-green-600 dark:text-green-400">
                                Manage Experience <ArrowRight className="w-4 h-4 group-hover:translate-x-1 transition-transform" />
                            </div>
                        </Link>
                    </div>
                </div>

                {/* No profile prompt */}
                {!stats.has_profile && (
                    <div className="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-700 rounded-2xl p-6 flex items-start gap-4">
                        <UserCircle2 className="w-8 h-8 text-amber-600 shrink-0 mt-0.5" />
                        <div>
                            <h3 className="font-semibold text-amber-900 dark:text-amber-300">You haven't created a digital card yet</h3>
                            <p className="text-sm text-amber-700 dark:text-amber-400 mt-1">
                                Create your digital card to get a unique QR code and shareable profile link.
                            </p>
                            <Link
                                href={route('profile.create')}
                                className="inline-flex items-center gap-2 mt-3 text-sm font-medium text-amber-700 dark:text-amber-300 hover:underline"
                            >
                                Create Digital Card <ArrowRight className="w-4 h-4" />
                            </Link>
                        </div>
                    </div>
                )}
            </div>
        </AppLayout>
    );
}
