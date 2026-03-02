import React from 'react';
import { Head, useForm } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { LoaderCircle } from 'lucide-react';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Teams', href: '/teams' },
    { title: 'Create Team', href: '#' },
];

export default function CreateTeam() {
    const { data, setData, post, processing, errors } = useForm({
        name:        '',
        description: '',
        website:     '',
        logo:        null as File | null,
    });

    const submit = (e: React.FormEvent) => {
        e.preventDefault();
        post(route('teams.store'), { forceFormData: true });
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Create Team" />

            <div className="p-6 max-w-xl mx-auto">
                <div className="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-6">
                    <h1 className="text-xl font-bold text-gray-900 dark:text-white mb-1">Create a Team</h1>
                    <p className="text-sm text-gray-500 dark:text-gray-400 mb-6">Group profiles together under one workspace.</p>

                    <form onSubmit={submit} className="space-y-5">
                        <div>
                            <Label htmlFor="name">Team Name *</Label>
                            <Input
                                id="name"
                                value={data.name}
                                onChange={(e) => setData('name', e.target.value)}
                                placeholder="Acme Corp"
                                autoComplete="off"
                            />
                            {errors.name && <p className="text-xs text-red-500 mt-1">{errors.name}</p>}
                        </div>

                        <div>
                            <Label htmlFor="description">Description</Label>
                            <Textarea
                                id="description"
                                value={data.description}
                                onChange={(e) => setData('description', e.target.value)}
                                placeholder="What does your team do?"
                                rows={3}
                            />
                        </div>

                        <div>
                            <Label htmlFor="website">Website</Label>
                            <Input
                                id="website"
                                type="url"
                                value={data.website}
                                onChange={(e) => setData('website', e.target.value)}
                                placeholder="https://yourteam.com"
                            />
                            {errors.website && <p className="text-xs text-red-500 mt-1">{errors.website}</p>}
                        </div>

                        <div>
                            <Label htmlFor="logo">Team Logo</Label>
                            <Input
                                id="logo"
                                type="file"
                                accept="image/*"
                                onChange={(e) => setData('logo', e.target.files?.[0] ?? null)}
                            />
                            <p className="text-xs text-gray-400 mt-1">Optional — JPG, PNG, up to 2MB</p>
                            {errors.logo && <p className="text-xs text-red-500 mt-1">{errors.logo}</p>}
                        </div>

                        <Button type="submit" disabled={processing} className="w-full">
                            {processing && <LoaderCircle className="w-4 h-4 animate-spin mr-2" />}
                            Create Team
                        </Button>
                    </form>
                </div>
            </div>
        </AppLayout>
    );
}
