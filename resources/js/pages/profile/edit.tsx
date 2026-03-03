import React, { useCallback, useState } from 'react';
import { Head, useForm, router } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import { Textarea } from '@/components/ui/textarea';
import { LoaderCircle, CheckCircle2, XCircle, AlertTriangle, Calendar, Globe, Copy, ShieldCheck, ShieldAlert } from 'lucide-react';
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
    twitter?: string;
    instagram?: string;
    youtube?: string;
    tiktok?: string;
    dribbble?: string;
    behance?: string;
    medium?: string;
    location?: string;
    is_public?: boolean;
    slug: string;
    template?: string;
    availability_status?: string;
    scheduling_url?: string;
    custom_domain?: string | null;
    domain_verification_token?: string | null;
    domain_verified_at?: string | null;
}

const templates = [
    {
        id: 'default',
        name: 'Default',
        description: 'Clean & minimal',
        preview: 'bg-white border-2',
        banner: 'bg-gradient-to-r from-blue-500 to-indigo-600',
        accent: 'text-blue-600',
    },
    {
        id: 'bold',
        name: 'Bold',
        description: 'Dark & striking',
        preview: 'bg-gray-900 border-2',
        banner: 'bg-gradient-to-r from-violet-600 to-indigo-600',
        accent: 'text-violet-400',
    },
    {
        id: 'glass',
        name: 'Glass',
        description: 'Vibrant gradient',
        preview: 'bg-gradient-to-br from-blue-500 via-purple-600 to-pink-500 border-2',
        banner: 'bg-white/20',
        accent: 'text-white',
    },
];

const availabilityOptions = [
    { value: '', label: 'Not set' },
    { value: 'available', label: '🟢 Available for Work' },
    { value: 'open_to_opportunities', label: '🟡 Open to Opportunities' },
    { value: 'not_available', label: '⚫ Not Available' },
];

export default function Edit({ profile }: { profile: Profile }) {
    const [slugStatus, setSlugStatus] = useState<'idle' | 'checking' | 'available' | 'taken'>('idle');
    const [slugTimer, setSlugTimer] = useState<ReturnType<typeof setTimeout> | null>(null);

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
        twitter: profile.twitter ?? '',
        instagram: profile.instagram ?? '',
        youtube: profile.youtube ?? '',
        tiktok: profile.tiktok ?? '',
        dribbble: profile.dribbble ?? '',
        behance: profile.behance ?? '',
        medium: profile.medium ?? '',
        location: profile.location ?? '',
        template: profile.template ?? 'default',
        is_public: profile.is_public ?? true,
        availability_status: profile.availability_status ?? '',
        scheduling_url: profile.scheduling_url ?? '',
        custom_slug: profile.slug ?? '',
    });

    const handleSlugChange = useCallback((value: string) => {
        setData('custom_slug', value);

        if (slugTimer) clearTimeout(slugTimer);

        if (!value || value === profile.slug) {
            setSlugStatus('idle');
            return;
        }

        setSlugStatus('checking');
        const timer = setTimeout(async () => {
            try {
                const res = await fetch(
                    route('profile.check-slug', value) + '?profile_id=' + profile.id
                );
                const json = await res.json();
                setSlugStatus(json.available ? 'available' : 'taken');
            } catch {
                setSlugStatus('idle');
            }
        }, 500);

        setSlugTimer(timer);
    }, [slugTimer, profile.id, profile.slug]);

    const submit = (e: React.FormEvent) => {
        e.preventDefault();
        post(route('profile.update', profile.id), { forceFormData: true });
    };

    const [domainInput, setDomainInput] = useState('');
    const [domainProcessing, setDomainProcessing] = useState(false);
    const [copied, setCopied] = useState(false);

    const saveDomain = (e: React.FormEvent) => {
        e.preventDefault();
        setDomainProcessing(true);
        router.post(route('profile.custom-domain.save', profile.id), { custom_domain: domainInput }, {
            onFinish: () => setDomainProcessing(false),
            onSuccess: () => setDomainInput(''),
        });
    };

    const verifyDomain = () => {
        setDomainProcessing(true);
        router.post(route('profile.custom-domain.verify', profile.id), {}, {
            onFinish: () => setDomainProcessing(false),
        });
    };

    const removeDomain = () => {
        router.delete(route('profile.custom-domain.remove', profile.id));
    };

    const copyToken = () => {
        if (profile.domain_verification_token) {
            navigator.clipboard.writeText(profile.domain_verification_token);
            setCopied(true);
            setTimeout(() => setCopied(false), 2000);
        }
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Edit Digital Card" />

            <form onSubmit={submit} className="m-5 space-y-8">

                {/* ── Core Info ── */}
                <div className="p-6 space-y-6 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl">
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
                            title="Make profile public"
                            checked={data.is_public}
                            onChange={(e) => setData('is_public', e.target.checked)}
                            className="w-4 h-4 rounded border-gray-300"
                        />
                        <Label htmlFor="is_public" className="cursor-pointer">Make my profile public</Label>
                    </div>
                </div>

                {/* ── Profile URL (Custom Slug) ── */}
                <div className="p-6 space-y-4 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl">
                    <div>
                        <h3 className="text-base font-semibold text-gray-900 dark:text-white">Profile URL</h3>
                        <p className="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Customise your shareable profile link</p>
                    </div>

                    <div>
                        <Label htmlFor="custom_slug">Slug</Label>
                        <div className="flex items-center gap-0 mt-1">
                            <span className="px-3 py-2 text-sm text-gray-500 bg-gray-100 dark:bg-gray-800 border border-r-0 border-gray-300 dark:border-gray-600 rounded-l-md whitespace-nowrap">
                                /p/
                            </span>
                            <div className="relative flex-1">
                                <Input
                                    id="custom_slug"
                                    value={data.custom_slug}
                                    onChange={(e) => handleSlugChange(e.target.value.toLowerCase().replace(/[^a-z0-9-_]/g, ''))}
                                    placeholder="your-name"
                                    className="rounded-l-none"
                                    autoComplete="off"
                                />
                                {slugStatus !== 'idle' && (
                                    <div className="absolute right-3 top-1/2 -translate-y-1/2">
                                        {slugStatus === 'checking' && <LoaderCircle className="w-4 h-4 animate-spin text-gray-400" />}
                                        {slugStatus === 'available' && <CheckCircle2 className="w-4 h-4 text-green-500" />}
                                        {slugStatus === 'taken' && <XCircle className="w-4 h-4 text-red-500" />}
                                    </div>
                                )}
                            </div>
                        </div>
                        <div className="mt-1 flex items-center gap-1.5">
                            {slugStatus === 'available' && <p className="text-xs text-green-600">✓ Available</p>}
                            {slugStatus === 'taken' && <p className="text-xs text-red-500">✗ Already taken</p>}
                        </div>
                        {data.custom_slug !== profile.slug && (
                            <div className="mt-2 flex items-start gap-2 text-xs text-amber-700 dark:text-amber-400 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-700 rounded-lg p-2.5">
                                <AlertTriangle className="w-3.5 h-3.5 shrink-0 mt-0.5" />
                                Changing your slug will update your profile URL and regenerate your QR code. Old links will stop working.
                            </div>
                        )}
                        {errors.custom_slug && <p className="text-xs text-red-500 mt-1">{errors.custom_slug}</p>}
                    </div>
                </div>

                {/* ── Template Picker ── */}
                <div className="p-6 space-y-4 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl">
                    <div>
                        <h3 className="text-base font-semibold text-gray-900 dark:text-white">Profile Template</h3>
                        <p className="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Choose how your public profile looks</p>
                    </div>

                    <div className="grid grid-cols-3 gap-3">
                        {templates.map((tpl) => (
                            <button
                                key={tpl.id}
                                type="button"
                                onClick={() => setData('template', tpl.id)}
                                className={`relative rounded-xl overflow-hidden border-2 transition-all ${
                                    data.template === tpl.id
                                        ? 'border-blue-500 ring-2 ring-blue-500/30'
                                        : 'border-gray-200 dark:border-gray-700 hover:border-gray-300'
                                }`}
                            >
                                {/* Mini preview */}
                                <div className={`h-20 ${tpl.preview} flex flex-col`}>
                                    <div className={`h-6 w-full ${tpl.banner}`} />
                                    <div className="flex-1 flex flex-col items-center justify-center gap-1 p-1">
                                        <div className="w-6 h-6 rounded-full bg-gray-300 dark:bg-gray-600" />
                                        <div className={`text-xs font-semibold ${tpl.accent}`}>Aa</div>
                                    </div>
                                </div>
                                <div className="p-2 text-center">
                                    <p className="text-xs font-semibold text-gray-800 dark:text-white">{tpl.name}</p>
                                    <p className="text-xs text-gray-400">{tpl.description}</p>
                                </div>
                                {data.template === tpl.id && (
                                    <div className="absolute top-1.5 right-1.5 w-4 h-4 bg-blue-500 rounded-full flex items-center justify-center">
                                        <svg className="w-2.5 h-2.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={3} d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                )}
                            </button>
                        ))}
                    </div>
                </div>

                {/* ── Social Media Links ── */}
                <div className="p-6 space-y-4 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl">
                    <div>
                        <h3 className="text-base font-semibold text-gray-900 dark:text-white">Social Media</h3>
                        <p className="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Add your social profiles to display on your card</p>
                    </div>

                    <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {[
                            { key: 'twitter',   label: 'Twitter / X',  placeholder: 'https://x.com/username' },
                            { key: 'instagram', label: 'Instagram',    placeholder: 'https://instagram.com/username' },
                            { key: 'youtube',   label: 'YouTube',      placeholder: 'https://youtube.com/@channel' },
                            { key: 'tiktok',    label: 'TikTok',       placeholder: 'https://tiktok.com/@username' },
                            { key: 'dribbble',  label: 'Dribbble',     placeholder: 'https://dribbble.com/username' },
                            { key: 'behance',   label: 'Behance',      placeholder: 'https://behance.net/username' },
                            { key: 'medium',    label: 'Medium',       placeholder: 'https://medium.com/@username' },
                        ].map(({ key, label, placeholder }) => (
                            <div key={key}>
                                <Label htmlFor={key}>{label}</Label>
                                <Input
                                    id={key}
                                    type="url"
                                    value={(data as Record<string, unknown>)[key] as string}
                                    onChange={(e) => setData(key as never, e.target.value)}
                                    placeholder={placeholder}
                                    autoComplete="off"
                                />
                                {(errors as Record<string, string>)[key] && (
                                    <p className="text-xs text-red-500 mt-1">{(errors as Record<string, string>)[key]}</p>
                                )}
                            </div>
                        ))}
                    </div>
                </div>

                {/* ── Professional Status ── */}
                <div className="p-6 space-y-4 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl">
                    <div>
                        <h3 className="text-base font-semibold text-gray-900 dark:text-white">Professional Status</h3>
                        <p className="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Let visitors know your availability</p>
                    </div>

                    <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <Label htmlFor="availability_status">Availability Status</Label>
                            <select
                                id="availability_status"
                                title="Availability Status"
                                value={data.availability_status}
                                onChange={(e) => setData('availability_status', e.target.value)}
                                className="mt-1 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-3 py-2 text-sm text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                                {availabilityOptions.map((opt) => (
                                    <option key={opt.value} value={opt.value}>{opt.label}</option>
                                ))}
                            </select>
                            {errors.availability_status && <p className="text-xs text-red-500 mt-1">{errors.availability_status}</p>}
                        </div>

                        <div>
                            <Label htmlFor="scheduling_url">
                                <span className="flex items-center gap-1.5">
                                    <Calendar className="w-3.5 h-3.5" />
                                    Scheduling / Booking Link
                                </span>
                            </Label>
                            <Input
                                id="scheduling_url"
                                type="url"
                                value={data.scheduling_url}
                                onChange={(e) => setData('scheduling_url', e.target.value)}
                                placeholder="https://cal.com/yourname"
                                autoComplete="off"
                            />
                            <p className="text-xs text-gray-400 mt-1">Calendly, Cal.com, etc.</p>
                            {errors.scheduling_url && <p className="text-xs text-red-500 mt-1">{errors.scheduling_url}</p>}
                        </div>
                    </div>
                </div>

                {/* ── Save Button ── */}
                <Button type="submit" className="w-full" disabled={processing || slugStatus === 'taken'}>
                    {processing && <LoaderCircle className="h-4 w-4 animate-spin mr-2" />}
                    Save Changes
                </Button>

            </form>

            {/* ── Custom Domain ── */}
            <div className="m-5 p-6 space-y-5 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl">
                <div className="flex items-start gap-3">
                    <Globe className="w-5 h-5 text-gray-500 mt-0.5 shrink-0" />
                    <div>
                        <h3 className="text-base font-semibold text-gray-900 dark:text-white">Custom Domain</h3>
                        <p className="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                            Point your own domain to your public profile (e.g. <span className="font-mono">yourdomain.com</span>).
                        </p>
                    </div>
                </div>

                {/* State: no domain saved yet */}
                {!profile.custom_domain && (
                    <form onSubmit={saveDomain} className="flex gap-2">
                        <input
                            type="text"
                            value={domainInput}
                            onChange={(e) => setDomainInput(e.target.value)}
                            placeholder="yourdomain.com"
                            className="flex-1 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-3 py-2 text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        />
                        <Button type="submit" disabled={domainProcessing || !domainInput.trim()}>
                            {domainProcessing && <LoaderCircle className="h-4 w-4 animate-spin mr-1.5" />}
                            Save Domain
                        </Button>
                    </form>
                )}

                {/* State: domain saved, awaiting verification */}
                {profile.custom_domain && !profile.domain_verified_at && (
                    <div className="space-y-4">
                        <div className="flex items-center gap-2">
                            <ShieldAlert className="w-4 h-4 text-yellow-500 shrink-0" />
                            <span className="text-sm font-medium text-yellow-700 dark:text-yellow-400">
                                Pending verification — <span className="font-mono">{profile.custom_domain}</span>
                            </span>
                        </div>

                        <div className="bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4 space-y-3 text-sm">
                            <p className="font-medium text-gray-800 dark:text-gray-200">Add this DNS TXT record to your domain:</p>
                            <div className="grid grid-cols-[auto_1fr] gap-x-4 gap-y-1 font-mono text-xs text-gray-700 dark:text-gray-300">
                                <span className="text-gray-400 uppercase tracking-wide">Name</span>
                                <span className="break-all">_digital-profile.{profile.custom_domain}</span>
                                <span className="text-gray-400 uppercase tracking-wide">Type</span>
                                <span>TXT</span>
                                <span className="text-gray-400 uppercase tracking-wide">Value</span>
                                <div className="flex items-center gap-2 break-all">
                                    <span>{profile.domain_verification_token}</span>
                                    <button
                                        type="button"
                                        onClick={copyToken}
                                        className="shrink-0 text-blue-500 hover:text-blue-600"
                                        title="Copy token"
                                    >
                                        {copied ? <CheckCircle2 className="w-3.5 h-3.5" /> : <Copy className="w-3.5 h-3.5" />}
                                    </button>
                                </div>
                            </div>
                            <p className="text-xs text-gray-400">DNS changes can take up to 48 hours to propagate.</p>
                        </div>

                        <div className="flex gap-2">
                            <Button type="button" onClick={verifyDomain} disabled={domainProcessing}>
                                {domainProcessing && <LoaderCircle className="h-4 w-4 animate-spin mr-1.5" />}
                                Verify Domain
                            </Button>
                            <Button type="button" variant="outline" onClick={removeDomain}>
                                Remove
                            </Button>
                        </div>
                    </div>
                )}

                {/* State: verified */}
                {profile.custom_domain && profile.domain_verified_at && (
                    <div className="space-y-3">
                        <div className="flex items-center gap-2">
                            <ShieldCheck className="w-4 h-4 text-green-500 shrink-0" />
                            <span className="text-sm font-medium text-green-700 dark:text-green-400">
                                Verified — your profile is live at{' '}
                                <a
                                    href={`https://${profile.custom_domain}`}
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    className="underline font-mono"
                                >
                                    {profile.custom_domain}
                                </a>
                            </span>
                        </div>
                        <Button type="button" variant="outline" size="sm" onClick={removeDomain}>
                            <XCircle className="w-3.5 h-3.5 mr-1.5" />
                            Remove Custom Domain
                        </Button>
                    </div>
                )}
            </div>
        </AppLayout>
    );
}
