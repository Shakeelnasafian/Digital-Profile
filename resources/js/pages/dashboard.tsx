import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/react';
import { Eye, FolderOpen, Briefcase, UserCircle2, ArrowRight, QrCode } from 'lucide-react';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
];

interface Stats {
    profile_views: number;
    project_count: number;
    experience_count: number;
    has_profile: boolean;
    profile_slug: string | null;
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

export default function Dashboard({ stats }: { stats: Stats }) {
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
