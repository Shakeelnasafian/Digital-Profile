import React from 'react';
import { Head, Link } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Button } from '@/components/ui/button';
import { Plus, User } from 'lucide-react';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Digital Card', href: '/profile' },
];

interface Profile {
    id: number;
    display_name: string;
    job_title?: string;
    slug: string;
    is_public: boolean;
    profile_views: number;
    profile_image?: string;
}

export default function ProfileIndex({ profiles }: { profiles: Profile[] }) {
    if (profiles.length === 0) {
        return (
            <AppLayout breadcrumbs={breadcrumbs}>
                <Head title="Digital Card" />
                <div className="p-6 flex flex-col items-center justify-center py-20 text-center">
                    <User className="w-16 h-16 text-gray-300 dark:text-gray-600 mb-4" />
                    <h2 className="text-xl font-semibold text-gray-700 dark:text-gray-300">No digital card yet</h2>
                    <p className="text-sm text-gray-500 dark:text-gray-400 mt-2 mb-6">
                        Create your digital profile card to get started.
                    </p>
                    <Link href={route('profile.create')}>
                        <Button className="flex items-center gap-2">
                            <Plus className="w-4 h-4" />
                            Create Digital Card
                        </Button>
                    </Link>
                </div>
            </AppLayout>
        );
    }

    const profile = profiles[0];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Digital Card" />
            <div className="p-6">
                <Link href={route('profile.show', profile.slug)} className="block">
                    <div className="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-2xl p-6 hover:shadow-md transition-shadow max-w-md">
                        <div className="flex items-center gap-4">
                            {profile.profile_image ? (
                                <img src={profile.profile_image} alt={profile.display_name} className="w-14 h-14 rounded-full object-cover" />
                            ) : (
                                <div className="w-14 h-14 rounded-full bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center">
                                    <span className="text-xl font-bold text-white">{profile.display_name.charAt(0)}</span>
                                </div>
                            )}
                            <div>
                                <h3 className="font-semibold text-gray-900 dark:text-white">{profile.display_name}</h3>
                                {profile.job_title && <p className="text-sm text-gray-500">{profile.job_title}</p>}
                                <p className="text-xs text-gray-400 mt-1">{profile.profile_views} views</p>
                            </div>
                        </div>
                    </div>
                </Link>
            </div>
        </AppLayout>
    );
}
