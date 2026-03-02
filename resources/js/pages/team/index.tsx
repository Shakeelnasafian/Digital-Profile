import { Head, Link, router } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Button } from '@/components/ui/button';
import { Plus, Users, ExternalLink, Settings, Crown } from 'lucide-react';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Teams', href: '/teams' },
];

interface Team {
    id: number;
    name: string;
    slug: string;
    logo_url?: string;
    role: 'owner' | 'member';
    members: number;
}

export default function TeamsIndex({ teams }: { teams: Team[] }) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Teams" />

            <div className="p-6 space-y-6">
                <div className="flex items-center justify-between">
                    <div>
                        <h1 className="text-2xl font-bold text-gray-900 dark:text-white">Teams</h1>
                        <p className="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage your team workspaces</p>
                    </div>
                    <Link href={route('teams.create')}>
                        <Button type="button" className="flex items-center gap-2">
                            <Plus className="w-4 h-4" />
                            Create Team
                        </Button>
                    </Link>
                </div>

                {teams.length === 0 ? (
                    <div className="flex flex-col items-center justify-center py-20 text-center">
                        <Users className="w-16 h-16 text-gray-300 dark:text-gray-600 mb-4" />
                        <h3 className="text-lg font-semibold text-gray-700 dark:text-gray-300">No teams yet</h3>
                        <p className="text-sm text-gray-500 dark:text-gray-400 mt-1 mb-6">Create a team to group profiles together.</p>
                        <Link href={route('teams.create')}>
                            <Button type="button">
                                <Plus className="w-4 h-4 mr-2" />
                                Create Your First Team
                            </Button>
                        </Link>
                    </div>
                ) : (
                    <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        {teams.map((team) => (
                            <div key={team.id} className="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl p-5 flex flex-col gap-3">
                                <div className="flex items-center gap-3">
                                    {team.logo_url ? (
                                        <img src={team.logo_url} alt={team.name} className="w-10 h-10 rounded-lg object-cover" />
                                    ) : (
                                        <div className="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center">
                                            <span className="text-white font-bold text-sm">{team.name.charAt(0).toUpperCase()}</span>
                                        </div>
                                    )}
                                    <div className="flex-1 min-w-0">
                                        <h3 className="font-semibold text-gray-900 dark:text-white truncate">{team.name}</h3>
                                        <p className="text-xs text-gray-500 dark:text-gray-400">{team.members} member{team.members !== 1 ? 's' : ''}</p>
                                    </div>
                                    {team.role === 'owner' && (
                                        <Crown className="w-4 h-4 text-amber-500 shrink-0" title="You are the owner" />
                                    )}
                                </div>

                                <div className="flex gap-2 mt-auto pt-2 border-t border-gray-100 dark:border-gray-800">
                                    <a href={`/t/${team.slug}`} target="_blank" rel="noopener noreferrer" className="flex-1">
                                        <Button type="button" variant="outline" className="w-full flex items-center gap-1 text-xs">
                                            <ExternalLink className="w-3 h-3" />
                                            View Page
                                        </Button>
                                    </a>
                                    {team.role === 'owner' && (
                                        <Link href={route('teams.manage', team.slug)} className="flex-1">
                                            <Button type="button" variant="outline" className="w-full flex items-center gap-1 text-xs">
                                                <Settings className="w-3 h-3" />
                                                Manage
                                            </Button>
                                        </Link>
                                    )}
                                </div>
                            </div>
                        ))}
                    </div>
                )}
            </div>
        </AppLayout>
    );
}
