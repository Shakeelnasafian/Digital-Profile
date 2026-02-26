import React from 'react';
import { Head, Link, router } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faGithub, faLinkedin, faWhatsapp } from '@fortawesome/free-brands-svg-icons';
import { faEnvelope, faPhone, faGlobe, faLocationDot } from '@fortawesome/free-solid-svg-icons';
import { Button } from '@/components/ui/button';
import { Pencil, Eye, QrCode, Trash2, ExternalLink } from 'lucide-react';

interface Profile {
    id: number;
    display_name: string;
    job_title?: string;
    short_bio?: string;
    email?: string;
    phone?: string;
    whatsapp?: string;
    website?: string;
    linkedin?: string;
    github?: string;
    location?: string;
    profile_image?: string;
    qr_code_url?: string;
    slug: string;
    is_public?: boolean;
    skills?: string;
    profile_views?: number;
}

interface Project {
    id: number;
    name: string;
    description?: string;
    project_url?: string;
    status: string;
}

interface Experience {
    id: number;
    company: string;
    position: string;
    start_date: string;
    end_date?: string;
    is_current: boolean;
}

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'My Profile', href: '#' },
];

const statusColors: Record<string, string> = {
    planned: 'bg-yellow-100 text-yellow-700',
    ongoing: 'bg-blue-100 text-blue-700',
    completed: 'bg-green-100 text-green-700',
};

function formatDate(dateStr?: string) {
    if (!dateStr) return '';
    return new Date(dateStr).toLocaleDateString('en-US', { month: 'short', year: 'numeric' });
}

export default function Show({
    profile,
    projects,
    experiences,
}: {
    profile: Profile;
    projects: Project[];
    experiences: Experience[];
}) {
    const publicUrl = `/p/${profile.slug}`;
    const skills = profile.skills
        ? profile.skills.split(',').map((s) => s.trim()).filter(Boolean)
        : [];

    const handleDelete = () => {
        if (!confirm('Are you sure you want to delete your digital card? This cannot be undone.')) return;
        router.delete(route('profile.destroy', profile.id));
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="My Digital Card" />

            <div className="p-6 max-w-4xl mx-auto space-y-6">
                {/* Action bar */}
                <div className="flex items-center justify-between flex-wrap gap-3">
                    <div>
                        <h1 className="text-2xl font-bold text-gray-900 dark:text-white">My Digital Card</h1>
                        <p className="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                            {profile.profile_views ?? 0} profile views
                            {profile.is_public ? (
                                <span className="ml-2 text-xs bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 px-2 py-0.5 rounded-full">Public</span>
                            ) : (
                                <span className="ml-2 text-xs bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400 px-2 py-0.5 rounded-full">Private</span>
                            )}
                        </p>
                    </div>
                    <div className="flex gap-2 flex-wrap">
                        <a href={publicUrl} target="_blank" rel="noopener noreferrer">
                            <Button variant="outline" className="flex items-center gap-2">
                                <ExternalLink className="w-4 h-4" />
                                View Public Page
                            </Button>
                        </a>
                        <Link href={route('profile.edit', profile.id)}>
                            <Button className="flex items-center gap-2">
                                <Pencil className="w-4 h-4" />
                                Edit Profile
                            </Button>
                        </Link>
                        <Button variant="outline" onClick={handleDelete} className="text-red-600 border-red-200 hover:bg-red-50 flex items-center gap-2">
                            <Trash2 className="w-4 h-4" />
                            Delete
                        </Button>
                    </div>
                </div>

                {/* Profile Card */}
                <div className="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden">
                    <div className="h-24 bg-gradient-to-r from-blue-600 to-indigo-600" />
                    <div className="px-6 pb-6">
                        <div className="flex items-end justify-between -mt-12 mb-4">
                            {profile.profile_image ? (
                                <img
                                    src={profile.profile_image}
                                    alt={profile.display_name}
                                    className="w-20 h-20 rounded-full border-4 border-white dark:border-gray-900 shadow-md object-cover"
                                />
                            ) : (
                                <div className="w-20 h-20 rounded-full border-4 border-white dark:border-gray-900 shadow-md bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center">
                                    <span className="text-2xl font-bold text-white">
                                        {profile.display_name.charAt(0).toUpperCase()}
                                    </span>
                                </div>
                            )}
                            {profile.qr_code_url && (
                                <div className="text-center">
                                    <img src={profile.qr_code_url} alt="QR Code" className="w-16 h-16" />
                                    <p className="text-xs text-gray-400 mt-1">Your QR</p>
                                </div>
                            )}
                        </div>

                        <h2 className="text-xl font-bold text-gray-900 dark:text-white">{profile.display_name}</h2>
                        {profile.job_title && <p className="text-blue-600 font-medium">{profile.job_title}</p>}
                        {profile.location && (
                            <p className="text-sm text-gray-500 flex items-center gap-1.5 mt-1">
                                <FontAwesomeIcon icon={faLocationDot} className="text-gray-400" />
                                {profile.location}
                            </p>
                        )}
                        {profile.short_bio && (
                            <p className="text-sm text-gray-600 dark:text-gray-400 mt-3 leading-relaxed">{profile.short_bio}</p>
                        )}

                        <div className="flex flex-wrap gap-3 mt-4">
                            {profile.email && (
                                <a href={`mailto:${profile.email}`} className="text-gray-500 dark:text-gray-400 hover:text-blue-600 transition-colors">
                                    <FontAwesomeIcon icon={faEnvelope} className="w-5 h-5" />
                                </a>
                            )}
                            {profile.phone && (
                                <a href={`tel:${profile.phone}`} className="text-gray-500 dark:text-gray-400 hover:text-blue-600 transition-colors">
                                    <FontAwesomeIcon icon={faPhone} className="w-5 h-5" />
                                </a>
                            )}
                            {profile.whatsapp && (
                                <a href={`https://wa.me/${profile.whatsapp.replace(/\D/g, '')}`} target="_blank" rel="noopener noreferrer" className="text-gray-500 dark:text-gray-400 hover:text-green-600 transition-colors">
                                    <FontAwesomeIcon icon={faWhatsapp} className="w-5 h-5" />
                                </a>
                            )}
                            {profile.linkedin && (
                                <a href={profile.linkedin} target="_blank" rel="noopener noreferrer" className="text-gray-500 dark:text-gray-400 hover:text-blue-600 transition-colors">
                                    <FontAwesomeIcon icon={faLinkedin} className="w-5 h-5" />
                                </a>
                            )}
                            {profile.github && (
                                <a href={profile.github} target="_blank" rel="noopener noreferrer" className="text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
                                    <FontAwesomeIcon icon={faGithub} className="w-5 h-5" />
                                </a>
                            )}
                            {profile.website && (
                                <a href={profile.website} target="_blank" rel="noopener noreferrer" className="text-gray-500 dark:text-gray-400 hover:text-blue-600 transition-colors">
                                    <FontAwesomeIcon icon={faGlobe} className="w-5 h-5" />
                                </a>
                            )}
                        </div>
                    </div>
                </div>

                {/* Skills */}
                {skills.length > 0 && (
                    <div className="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-2xl p-6">
                        <h3 className="font-semibold text-gray-900 dark:text-white mb-3">Skills</h3>
                        <div className="flex flex-wrap gap-2">
                            {skills.map((skill, i) => (
                                <span key={i} className="bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-400 text-sm px-3 py-1 rounded-full">
                                    {skill}
                                </span>
                            ))}
                        </div>
                    </div>
                )}

                {/* Experience */}
                {experiences.length > 0 && (
                    <div className="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-2xl p-6">
                        <div className="flex items-center justify-between mb-4">
                            <h3 className="font-semibold text-gray-900 dark:text-white">Work Experience</h3>
                            <Link href="/experience" className="text-sm text-blue-600 hover:underline">Manage</Link>
                        </div>
                        <div className="space-y-4">
                            {experiences.slice(0, 3).map((exp) => (
                                <div key={exp.id} className="flex items-start gap-3">
                                    <div className="w-2 h-2 rounded-full bg-blue-600 mt-2 shrink-0" />
                                    <div>
                                        <p className="font-medium text-gray-900 dark:text-white text-sm">{exp.position}</p>
                                        <p className="text-xs text-blue-600">{exp.company}</p>
                                        <p className="text-xs text-gray-400">
                                            {formatDate(exp.start_date)} — {exp.is_current ? 'Present' : formatDate(exp.end_date)}
                                        </p>
                                    </div>
                                </div>
                            ))}
                            {experiences.length > 3 && (
                                <Link href="/experience" className="text-xs text-blue-600 hover:underline">
                                    +{experiences.length - 3} more
                                </Link>
                            )}
                        </div>
                    </div>
                )}

                {/* Projects */}
                {projects.length > 0 && (
                    <div className="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-2xl p-6">
                        <div className="flex items-center justify-between mb-4">
                            <h3 className="font-semibold text-gray-900 dark:text-white">Projects</h3>
                            <Link href="/projects" className="text-sm text-blue-600 hover:underline">Manage</Link>
                        </div>
                        <div className="grid gap-3 sm:grid-cols-2">
                            {projects.slice(0, 4).map((project) => (
                                <div key={project.id} className="border border-gray-100 dark:border-gray-800 rounded-xl p-3">
                                    <div className="flex items-center justify-between gap-2">
                                        <p className="font-medium text-gray-900 dark:text-white text-sm truncate">{project.name}</p>
                                        <span className={`text-xs px-2 py-0.5 rounded-full shrink-0 ${statusColors[project.status] ?? 'bg-gray-100 text-gray-600'}`}>
                                            {project.status.charAt(0).toUpperCase() + project.status.slice(1)}
                                        </span>
                                    </div>
                                    {project.description && (
                                        <p className="text-xs text-gray-500 dark:text-gray-400 mt-1 line-clamp-1">{project.description}</p>
                                    )}
                                </div>
                            ))}
                        </div>
                        {projects.length > 4 && (
                            <Link href="/projects" className="text-xs text-blue-600 hover:underline mt-2 block">
                                +{projects.length - 4} more projects
                            </Link>
                        )}
                    </div>
                )}

                {/* Share section */}
                <div className="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border border-blue-100 dark:border-blue-800 rounded-2xl p-6 text-center">
                    <h3 className="font-semibold text-gray-900 dark:text-white mb-2">Share Your Profile</h3>
                    <p className="text-sm text-gray-500 dark:text-gray-400 mb-4">Your public profile URL:</p>
                    <div className="flex items-center gap-2 max-w-md mx-auto">
                        <code className="flex-1 text-xs bg-white dark:bg-gray-900 border border-blue-200 dark:border-blue-700 rounded-lg px-3 py-2 text-gray-700 dark:text-gray-300 truncate">
                            {window.location.origin}{publicUrl}
                        </code>
                        <Button
                            size="sm"
                            onClick={() => navigator.clipboard.writeText(window.location.origin + publicUrl)}
                        >
                            Copy
                        </Button>
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
