import React, { useState } from 'react';
import { Head, router, useForm } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { LoaderCircle, UserPlus, Trash2, Crown, ExternalLink } from 'lucide-react';

interface TeamMember {
    id: number;
    name: string;
    email: string;
    role: 'owner' | 'member';
    profile?: {
        slug: string;
        display_name: string;
        job_title?: string;
        profile_image?: string;
    };
}

interface Team {
    id: number;
    name: string;
    slug: string;
    description?: string;
    website?: string;
    logo_url?: string;
}

export default function ManageTeam({ team, members }: { team: Team; members: TeamMember[] }) {
    const breadcrumbs: BreadcrumbItem[] = [
        { title: 'Dashboard', href: '/dashboard' },
        { title: 'Teams', href: '/teams' },
        { title: team.name, href: '#' },
    ];

    const [removingId, setRemovingId] = useState<number | null>(null);
    const { data, setData, post, processing, errors, reset } = useForm({ email: '' });

    const handleInvite = (e: React.FormEvent) => {
        e.preventDefault();
        post(route('teams.add-member', team.slug), {
            onSuccess: () => reset(),
        });
    };

    const handleRemove = (userId: number) => {
        setRemovingId(userId);
        router.delete(route('teams.remove-member', { slug: team.slug, userId }), {
            onFinish: () => setRemovingId(null),
        });
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={`Manage — ${team.name}`} />

            <div className="p-6 max-w-2xl mx-auto space-y-6">
                {/* Team Header */}
                <div className="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-2xl p-6 flex items-center gap-4">
                    {team.logo_url ? (
                        <img src={team.logo_url} alt={team.name} className="w-16 h-16 rounded-xl object-cover shrink-0" />
                    ) : (
                        <div className="w-16 h-16 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shrink-0">
                            <span className="text-2xl font-bold text-white">{team.name.charAt(0).toUpperCase()}</span>
                        </div>
                    )}
                    <div className="flex-1 min-w-0">
                        <h1 className="text-xl font-bold text-gray-900 dark:text-white">{team.name}</h1>
                        {team.description && <p className="text-sm text-gray-500 dark:text-gray-400 mt-0.5">{team.description}</p>}
                        {team.website && (
                            <a href={team.website} target="_blank" rel="noopener noreferrer" className="text-xs text-blue-600 hover:underline flex items-center gap-1 mt-1">
                                <ExternalLink className="w-3 h-3" />
                                {team.website.replace(/^https?:\/\//, '')}
                            </a>
                        )}
                    </div>
                    <a href={`/t/${team.slug}`} target="_blank" rel="noopener noreferrer">
                        <Button type="button" variant="outline" className="flex items-center gap-2 text-sm shrink-0">
                            <ExternalLink className="w-3.5 h-3.5" />
                            Public Page
                        </Button>
                    </a>
                </div>

                {/* Add Member */}
                <div className="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-2xl p-6">
                    <h2 className="font-semibold text-gray-900 dark:text-white mb-4">Add Member</h2>
                    <form onSubmit={handleInvite} className="flex gap-3">
                        <div className="flex-1">
                            <Input
                                type="email"
                                value={data.email}
                                onChange={(e) => setData('email', e.target.value)}
                                placeholder="colleague@example.com"
                                autoComplete="off"
                            />
                            {errors.email && <p className="text-xs text-red-500 mt-1">{errors.email}</p>}
                        </div>
                        <Button type="submit" disabled={processing} className="flex items-center gap-2 shrink-0">
                            {processing ? <LoaderCircle className="w-4 h-4 animate-spin" /> : <UserPlus className="w-4 h-4" />}
                            Add
                        </Button>
                    </form>
                    <p className="text-xs text-gray-400 mt-2">The user must already have an account in this platform.</p>
                </div>

                {/* Members List */}
                <div className="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-2xl p-6">
                    <h2 className="font-semibold text-gray-900 dark:text-white mb-4">
                        Members ({members.length})
                    </h2>

                    <div className="space-y-3">
                        {members.map((member) => (
                            <div key={member.id} className="flex items-center gap-3 p-3 rounded-xl border border-gray-100 dark:border-gray-800">
                                {member.profile?.profile_image ? (
                                    <img src={member.profile.profile_image} alt={member.name} className="w-10 h-10 rounded-full object-cover shrink-0" />
                                ) : (
                                    <div className="w-10 h-10 rounded-full bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center shrink-0">
                                        <span className="text-white font-semibold text-sm">{member.name.charAt(0).toUpperCase()}</span>
                                    </div>
                                )}
                                <div className="flex-1 min-w-0">
                                    <div className="flex items-center gap-1.5">
                                        <p className="font-medium text-gray-900 dark:text-white text-sm truncate">
                                            {member.profile?.display_name ?? member.name}
                                        </p>
                                        {member.role === 'owner' && (
                                            <Crown className="w-3.5 h-3.5 text-amber-500 shrink-0" title="Owner" />
                                        )}
                                    </div>
                                    <p className="text-xs text-gray-500 dark:text-gray-400">{member.email}</p>
                                    {member.profile?.job_title && (
                                        <p className="text-xs text-blue-600">{member.profile.job_title}</p>
                                    )}
                                </div>
                                <div className="flex gap-2 shrink-0">
                                    {member.profile && (
                                        <a href={`/p/${member.profile.slug}`} target="_blank" rel="noopener noreferrer">
                                            <Button type="button" variant="outline" className="p-1.5 h-auto" title="View profile">
                                                <ExternalLink className="w-3.5 h-3.5" />
                                            </Button>
                                        </a>
                                    )}
                                    {member.role !== 'owner' && (
                                        <Button
                                            type="button"
                                            variant="outline"
                                            disabled={removingId === member.id}
                                            onClick={() => handleRemove(member.id)}
                                            className="p-1.5 h-auto text-red-500 border-red-200 hover:bg-red-50"
                                            title="Remove member"
                                        >
                                            {removingId === member.id
                                                ? <LoaderCircle className="w-3.5 h-3.5 animate-spin" />
                                                : <Trash2 className="w-3.5 h-3.5" />
                                            }
                                        </Button>
                                    )}
                                </div>
                            </div>
                        ))}
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
