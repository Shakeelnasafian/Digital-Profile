import { Head, useForm } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import { Textarea } from '@/components/ui/textarea';
import { LoaderCircle } from 'lucide-react';
import { type BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Edit Digital Card', href: '#' },
];

interface Profile {
    id: number;
    display_name: string;
    job_title?: string;
    short_bio?: string;
    skills?: string;
    email?: string;
    phone?: string;
    whatsapp?: string;
    website?: string;
    linkedin?: string;
    github?: string;
    location?: string;
    is_public?: boolean;
    slug: string;
}

export default function Edit({ profile }: { profile: Profile }) {
    const { data, setData, post, processing, errors } = useForm({
        _method: 'PATCH',
        display_name: profile.display_name ?? '',
        job_title: profile.job_title ?? '',
        short_bio: profile.short_bio ?? '',
        skills: profile.skills ?? '',
        profile_image: null as File | null,
        email: profile.email ?? '',
        phone: profile.phone ?? '',
        whatsapp: profile.whatsapp ?? '',
        website: profile.website ?? '',
        linkedin: profile.linkedin ?? '',
        github: profile.github ?? '',
        location: profile.location ?? '',
        template: 'default',
        is_public: profile.is_public ?? true,
    });

    const submit = (e: React.FormEvent) => {
        e.preventDefault();
        post(route('profile.update', profile.id), { forceFormData: true });
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Edit Digital Card" />

            <form onSubmit={submit} className="m-5 p-6 space-y-6 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl">
                <div>
                    <h2 className="text-xl font-bold text-gray-900 dark:text-white">Edit Your Digital Card</h2>
                    <p className="text-sm text-gray-500 dark:text-gray-400 mt-1">Update your professional profile information.</p>
                </div>

                <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <Label htmlFor="display_name">Display Name *</Label>
                        <Input
                            id="display_name"
                            value={data.display_name}
                            onChange={(e) => setData('display_name', e.target.value)}
                            placeholder="John Doe"
                            autoComplete="off"
                        />
                        {errors.display_name && <p className="text-xs text-red-500 mt-1">{errors.display_name}</p>}
                    </div>

                    <div>
                        <Label htmlFor="job_title">Job Title</Label>
                        <Input
                            id="job_title"
                            value={data.job_title}
                            onChange={(e) => setData('job_title', e.target.value)}
                            placeholder="Software Engineer"
                            autoComplete="off"
                        />
                        {errors.job_title && <p className="text-xs text-red-500 mt-1">{errors.job_title}</p>}
                    </div>

                    <div>
                        <Label htmlFor="email">Email Address *</Label>
                        <Input
                            id="email"
                            type="email"
                            value={data.email}
                            onChange={(e) => setData('email', e.target.value)}
                            placeholder="you@example.com"
                            autoComplete="off"
                        />
                        {errors.email && <p className="text-xs text-red-500 mt-1">{errors.email}</p>}
                    </div>

                    <div>
                        <Label htmlFor="location">Location</Label>
                        <Input
                            id="location"
                            value={data.location}
                            onChange={(e) => setData('location', e.target.value)}
                            placeholder="Dubai, UAE"
                        />
                        {errors.location && <p className="text-xs text-red-500 mt-1">{errors.location}</p>}
                    </div>

                    <div>
                        <Label htmlFor="phone">Phone</Label>
                        <Input
                            id="phone"
                            value={data.phone}
                            onChange={(e) => setData('phone', e.target.value)}
                            placeholder="+971 55 123 4567"
                            autoComplete="off"
                        />
                        {errors.phone && <p className="text-xs text-red-500 mt-1">{errors.phone}</p>}
                    </div>

                    <div>
                        <Label htmlFor="whatsapp">WhatsApp</Label>
                        <Input
                            id="whatsapp"
                            value={data.whatsapp}
                            onChange={(e) => setData('whatsapp', e.target.value)}
                            placeholder="+971 55 000 0000"
                            autoComplete="off"
                        />
                        {errors.whatsapp && <p className="text-xs text-red-500 mt-1">{errors.whatsapp}</p>}
                    </div>

                    <div>
                        <Label htmlFor="website">Website</Label>
                        <Input
                            id="website"
                            type="url"
                            value={data.website}
                            onChange={(e) => setData('website', e.target.value)}
                            placeholder="https://yoursite.com"
                            autoComplete="off"
                        />
                        {errors.website && <p className="text-xs text-red-500 mt-1">{errors.website}</p>}
                    </div>

                    <div>
                        <Label htmlFor="linkedin">LinkedIn</Label>
                        <Input
                            id="linkedin"
                            type="url"
                            value={data.linkedin}
                            onChange={(e) => setData('linkedin', e.target.value)}
                            placeholder="https://linkedin.com/in/yourname"
                        />
                        {errors.linkedin && <p className="text-xs text-red-500 mt-1">{errors.linkedin}</p>}
                    </div>

                    <div>
                        <Label htmlFor="github">GitHub</Label>
                        <Input
                            id="github"
                            type="url"
                            value={data.github}
                            onChange={(e) => setData('github', e.target.value)}
                            placeholder="https://github.com/username"
                        />
                        {errors.github && <p className="text-xs text-red-500 mt-1">{errors.github}</p>}
                    </div>

                    <div>
                        <Label htmlFor="profile_image">Update Profile Photo</Label>
                        <Input
                            id="profile_image"
                            type="file"
                            accept="image/*"
                            onChange={(e) => setData('profile_image', e.target.files?.[0] ?? null)}
                        />
                        <p className="text-xs text-gray-400 mt-1">Leave empty to keep current photo</p>
                        {errors.profile_image && <p className="text-xs text-red-500 mt-1">{errors.profile_image}</p>}
                    </div>
                </div>

                <div>
                    <Label htmlFor="short_bio">Short Bio</Label>
                    <Textarea
                        id="short_bio"
                        value={data.short_bio}
                        onChange={(e) => setData('short_bio', e.target.value)}
                        placeholder="A brief intro about yourself..."
                        rows={3}
                    />
                    {errors.short_bio && <p className="text-xs text-red-500 mt-1">{errors.short_bio}</p>}
                </div>

                <div>
                    <Label htmlFor="skills">Skills</Label>
                    <Input
                        id="skills"
                        value={data.skills}
                        onChange={(e) => setData('skills', e.target.value)}
                        placeholder="React, Laravel, TypeScript, Docker (comma-separated)"
                        autoComplete="off"
                    />
                    <p className="text-xs text-gray-400 mt-1">Enter skills separated by commas</p>
                    {errors.skills && <p className="text-xs text-red-500 mt-1">{errors.skills}</p>}
                </div>

                <div className="flex items-center gap-3">
                    <input
                        id="is_public"
                        type="checkbox"
                        checked={data.is_public}
                        onChange={(e) => setData('is_public', e.target.checked)}
                        className="w-4 h-4 rounded border-gray-300"
                    />
                    <Label htmlFor="is_public" className="cursor-pointer">
                        Make my profile public
                    </Label>
                </div>

                <Button type="submit" className="w-full" disabled={processing}>
                    {processing && <LoaderCircle className="h-4 w-4 animate-spin mr-2" />}
                    Save Changes
                </Button>
            </form>
        </AppLayout>
    );
}
