import { Head, useForm } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import { Textarea } from '@/components/ui/textarea';
import { Toggle } from '@/components/ui/toggle';
import { LoaderCircle } from 'lucide-react';
import { type BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Create Digital Profile', href: '/dashboard/profiles/create' },
];

export default function Create() {
    const { data, setData, post, processing, errors } = useForm({
        display_name: '',
        job_title: '',
        short_bio: '',
        profile_image: null,
        email: '',
        phone: '',
        whatsapp: '',
        website: '',
        linkedin: '',
        github: '',
        location: '',
        template: 'default',
        is_public: true,
    });

    const submit = (e: React.FormEvent) => {
        e.preventDefault();
        post(route('digital-profiles.store'), { forceFormData: true });
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Create Digital Profile" />

            <form onSubmit={submit} className="m-5 p-6 space-y-6 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl">

                <div className="grid grid-cols-2 gap-4 p-5 m-5 mb-0 pb-0">

                    {/* Display Name */}
                    <div>
                        <Label htmlFor="display_name">Display Name</Label>
                        <Input
                            id="display_name"
                            value={data.display_name}
                            onChange={(e) => setData('display_name', e.target.value)}
                            placeholder="John Doe"
                            autoComplete="off"
                        />
                        {errors.display_name && <p className="text-sm text-red-500 mt-1">{errors.display_name}</p>}
                    </div>

                    {/* Job Title */}
                    <div>
                        <Label htmlFor="job_title">Job Title</Label>
                        <Input
                            id="job_title"
                            value={data.job_title}
                            onChange={(e) => setData('job_title', e.target.value)}
                            placeholder="Software Engineer"
                            autoComplete="off"
                        />
                        {errors.job_title && <p className="text-sm text-red-500 mt-1">{errors.job_title}</p>}
                    </div>


                    {/* Email */}
                    <div>
                        <Label htmlFor="email">Email address</Label>
                        <Input
                            id="email"
                            type="email"
                            value={data.email}
                            onChange={(e) => setData('email', e.target.value)}
                            placeholder="email@example.com"
                            autoComplete="off"
                        />
                        {errors.email && <p className="text-sm text-red-500 mt-1">{errors.email}</p>}
                    </div>

                    {/* Phone */}
                    <div>
                        <Label htmlFor="phone">Phone</Label>
                        <Input
                            id="phone"
                            type="text"
                            value={data.phone}
                            onChange={(e) => setData('phone', e.target.value)}
                            placeholder="+971 55 123 4567"
                            autoComplete="off"
                        />
                        {errors.phone && <p className="text-sm text-red-500 mt-1">{errors.phone}</p>}
                    </div>

                    {/* WhatsApp */}
                    <div>
                        <Label htmlFor="whatsapp">WhatsApp</Label>
                        <Input
                            id="whatsapp"
                            type="text"
                            value={data.whatsapp}
                            onChange={(e) => setData('whatsapp', e.target.value)}
                            placeholder="+971 55 000 0000"
                            autoComplete="off"
                        />
                        {errors.whatsapp && <p className="text-sm text-red-500 mt-1">{errors.whatsapp}</p>}
                    </div>

                    {/* Website */}
                    <div>
                        <Label htmlFor="website">Website</Label>
                        <Input
                            id="website"
                            type="url"
                            value={data.website}
                            onChange={(e) => setData('website', e.target.value)}
                            placeholder="www.example.com"
                            autoComplete="off"
                        />
                        {errors.website && <p className="text-sm text-red-500 mt-1">{errors.website}</p>}
                    </div>

                    {/* LinkedIn */}
                    <div>
                        <Label htmlFor="linkedin">LinkedIn</Label>
                        <Input
                            id="linkedin"
                            type="url"
                            value={data.linkedin}
                            onChange={(e) => setData('linkedin', e.target.value)}
                            placeholder="linkedin.com/in/yourname"
                        />
                        {errors.linkedin && <p className="text-sm text-red-500 mt-1">{errors.linkedin}</p>}
                    </div>

                    {/* GitHub */}
                    <div>
                        <Label htmlFor="github">GitHub</Label>
                        <Input
                            id="github"
                            type="url"
                            value={data.github}
                            onChange={(e) => setData('github', e.target.value)}
                            placeholder="github.com/username"
                        />
                        {errors.github && <p className="text-sm text-red-500 mt-1">{errors.github}</p>}
                    </div>

                    {/* Location */}
                    <div>
                        <Label htmlFor="location">Location</Label>
                        <Input
                            id="location"
                            type="text"
                            value={data.location}
                            onChange={(e) => setData('location', e.target.value)}
                            placeholder="Dubai, UAE"
                        />
                        {errors.location && <p className="text-sm text-red-500 mt-1">{errors.location}</p>}
                    </div>

                    {/* Profile Image */}
                    <div>
                        <Label htmlFor="profile_image">Profile Image</Label>
                        <Input
                            id="profile_image"
                            type="file"
                            onChange={(e) => setData('profile_image', e.target.files?.[0] || null)}
                        />
                        {errors.profile_image && <p className="text-sm text-red-500 mt-1">{errors.profile_image}</p>}
                    </div>

                </div>

                <div className='grid grid-cols-1 gap-4 p-5 m-5 mt-0 pb-0'>

                    {/* Short Bio */}
                    <div>
                        <Label htmlFor="short_bio">Short Bio</Label>
                        <Textarea
                            id="short_bio"
                            value={data.short_bio}
                            onChange={(e) => setData('short_bio', e.target.value)}
                            placeholder="A brief introduction about yourself..."
                        />
                        {errors.short_bio && <p className="text-sm text-red-500 mt-1">{errors.short_bio}</p>}
                    </div>

                    {/* Is Public */}
                    <div>
                        <Label htmlFor="profile_image" className='block'>Make profile public</Label>
                        <Toggle
                            pressed={data.is_public}
                            onPressedChange={(val) => setData('is_public', val)}
                            variant="outline"
                            className='block'
                        >
                            {data.is_public ? 'Public' : 'Private'}
                        </Toggle>

                    </div>

                    {/* Submit */}
                    <div className="pt-4">
                        <Button className="w-full" disabled={processing}>
                            {processing && <LoaderCircle className="h-4 w-4 animate-spin mr-2" />}
                            Create Profile
                        </Button>
                    </div>
                </div>
            </form>
        </AppLayout>
    );
}
