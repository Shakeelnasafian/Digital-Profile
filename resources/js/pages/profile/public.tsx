import React from 'react';
import { Head } from '@inertiajs/react';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faGithub, faLinkedin, faWhatsapp } from '@fortawesome/free-brands-svg-icons';
import { faEnvelope, faPhone, faGlobe, faLocationDot, faExternalLink } from '@fortawesome/free-solid-svg-icons';

interface Profile {
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
    skills?: string;
    profile_views?: number;
}

interface Project {
    id: number;
    name: string;
    description?: string;
    project_url?: string;
    start_date?: string;
    end_date?: string;
    status: string;
}

interface Experience {
    id: number;
    company: string;
    position: string;
    location?: string;
    start_date: string;
    end_date?: string;
    is_current: boolean;
    description?: string;
}

const statusColors: Record<string, string> = {
    planned: 'bg-yellow-100 text-yellow-700',
    ongoing: 'bg-blue-100 text-blue-700',
    completed: 'bg-green-100 text-green-700',
};

function formatDate(dateStr?: string) {
    if (!dateStr) return '';
    return new Date(dateStr).toLocaleDateString('en-US', { month: 'short', year: 'numeric' });
}

export default function PublicProfile({
    profile,
    projects,
    experiences,
}: {
    profile: Profile;
    projects: Project[];
    experiences: Experience[];
}) {
    const skills = profile.skills
        ? profile.skills.split(',').map((s) => s.trim()).filter(Boolean)
        : [];

    return (
        <>
            <Head title={`${profile.display_name} — Digital Profile`} />

            <div className="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 py-10 px-4">
                <div className="max-w-3xl mx-auto space-y-6">

                    {/* Profile Card */}
                    <div className="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        {/* Banner */}
                        <div className="h-28 bg-gradient-to-r from-blue-600 to-indigo-600" />

                        <div className="px-6 pb-6">
                            {/* Avatar */}
                            <div className="flex items-end justify-between -mt-14 mb-4">
                                {profile.profile_image ? (
                                    <img
                                        src={profile.profile_image}
                                        alt={profile.display_name}
                                        className="w-24 h-24 rounded-full border-4 border-white shadow-md object-cover"
                                    />
                                ) : (
                                    <div className="w-24 h-24 rounded-full border-4 border-white shadow-md bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center">
                                        <span className="text-3xl font-bold text-white">
                                            {profile.display_name.charAt(0).toUpperCase()}
                                        </span>
                                    </div>
                                )}

                                {/* QR Code */}
                                {profile.qr_code_url && (
                                    <div className="text-center">
                                        <img src={profile.qr_code_url} alt="QR Code" className="w-16 h-16" />
                                        <p className="text-xs text-gray-400 mt-1">Scan me</p>
                                    </div>
                                )}
                            </div>

                            {/* Name + Title */}
                            <h1 className="text-2xl font-bold text-gray-900">{profile.display_name}</h1>
                            {profile.job_title && (
                                <p className="text-blue-600 font-medium mt-0.5">{profile.job_title}</p>
                            )}

                            {/* Location */}
                            {profile.location && (
                                <p className="text-sm text-gray-500 mt-1 flex items-center gap-1.5">
                                    <FontAwesomeIcon icon={faLocationDot} className="text-gray-400" />
                                    {profile.location}
                                </p>
                            )}

                            {/* Bio */}
                            {profile.short_bio && (
                                <p className="mt-4 text-gray-600 leading-relaxed text-sm">{profile.short_bio}</p>
                            )}

                            {/* Contact icons */}
                            <div className="mt-5 flex flex-wrap gap-3">
                                {profile.email && (
                                    <a
                                        href={`mailto:${profile.email}`}
                                        className="flex items-center gap-2 text-sm px-3 py-1.5 rounded-full border border-gray-200 text-gray-600 hover:border-blue-400 hover:text-blue-600 transition-colors"
                                    >
                                        <FontAwesomeIcon icon={faEnvelope} />
                                        {profile.email}
                                    </a>
                                )}
                                {profile.phone && (
                                    <a
                                        href={`tel:${profile.phone}`}
                                        className="flex items-center gap-2 text-sm px-3 py-1.5 rounded-full border border-gray-200 text-gray-600 hover:border-blue-400 hover:text-blue-600 transition-colors"
                                    >
                                        <FontAwesomeIcon icon={faPhone} />
                                        {profile.phone}
                                    </a>
                                )}
                                {profile.whatsapp && (
                                    <a
                                        href={`https://wa.me/${profile.whatsapp.replace(/\D/g, '')}`}
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        className="flex items-center gap-2 text-sm px-3 py-1.5 rounded-full border border-gray-200 text-gray-600 hover:border-green-400 hover:text-green-600 transition-colors"
                                    >
                                        <FontAwesomeIcon icon={faWhatsapp} />
                                        WhatsApp
                                    </a>
                                )}
                                {profile.website && (
                                    <a
                                        href={profile.website}
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        className="flex items-center gap-2 text-sm px-3 py-1.5 rounded-full border border-gray-200 text-gray-600 hover:border-blue-400 hover:text-blue-600 transition-colors"
                                    >
                                        <FontAwesomeIcon icon={faGlobe} />
                                        Website
                                    </a>
                                )}
                                {profile.linkedin && (
                                    <a
                                        href={profile.linkedin}
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        className="flex items-center gap-2 text-sm px-3 py-1.5 rounded-full border border-gray-200 text-gray-600 hover:border-blue-400 hover:text-blue-600 transition-colors"
                                    >
                                        <FontAwesomeIcon icon={faLinkedin} />
                                        LinkedIn
                                    </a>
                                )}
                                {profile.github && (
                                    <a
                                        href={profile.github}
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        className="flex items-center gap-2 text-sm px-3 py-1.5 rounded-full border border-gray-200 text-gray-600 hover:border-gray-600 hover:text-gray-900 transition-colors"
                                    >
                                        <FontAwesomeIcon icon={faGithub} />
                                        GitHub
                                    </a>
                                )}
                            </div>
                        </div>
                    </div>

                    {/* Skills */}
                    {skills.length > 0 && (
                        <div className="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                            <h2 className="text-lg font-semibold text-gray-900 mb-4">Skills</h2>
                            <div className="flex flex-wrap gap-2">
                                {skills.map((skill, i) => (
                                    <span
                                        key={i}
                                        className="bg-blue-50 text-blue-700 text-sm font-medium px-3 py-1 rounded-full"
                                    >
                                        {skill}
                                    </span>
                                ))}
                            </div>
                        </div>
                    )}

                    {/* Experience */}
                    {experiences.length > 0 && (
                        <div className="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                            <h2 className="text-lg font-semibold text-gray-900 mb-5">Work Experience</h2>
                            <div className="space-y-5">
                                {experiences.map((exp, idx) => (
                                    <div key={exp.id} className="relative pl-6 border-l-2 border-blue-100">
                                        <div className="absolute -left-[7px] top-1 w-3 h-3 rounded-full bg-blue-600" />
                                        <div className="flex items-start justify-between gap-2">
                                            <div>
                                                <h3 className="font-semibold text-gray-900">{exp.position}</h3>
                                                <p className="text-sm text-blue-600 font-medium">{exp.company}</p>
                                                {exp.location && (
                                                    <p className="text-xs text-gray-500">{exp.location}</p>
                                                )}
                                            </div>
                                            <div className="text-right shrink-0">
                                                <p className="text-xs text-gray-400">
                                                    {formatDate(exp.start_date)} — {exp.is_current ? 'Present' : formatDate(exp.end_date)}
                                                </p>
                                                {exp.is_current && (
                                                    <span className="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full">
                                                        Current
                                                    </span>
                                                )}
                                            </div>
                                        </div>
                                        {exp.description && (
                                            <p className="text-sm text-gray-600 mt-2 leading-relaxed">{exp.description}</p>
                                        )}
                                    </div>
                                ))}
                            </div>
                        </div>
                    )}

                    {/* Projects */}
                    {projects.length > 0 && (
                        <div className="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                            <h2 className="text-lg font-semibold text-gray-900 mb-5">Projects</h2>
                            <div className="grid gap-4 sm:grid-cols-2">
                                {projects.map((project) => (
                                    <div
                                        key={project.id}
                                        className="border border-gray-100 rounded-xl p-4 hover:border-blue-200 hover:shadow-sm transition-all"
                                    >
                                        <div className="flex items-start justify-between gap-2 mb-2">
                                            <h3 className="font-semibold text-gray-900 text-sm">{project.name}</h3>
                                            <span className={`text-xs px-2 py-0.5 rounded-full shrink-0 ${statusColors[project.status] ?? 'bg-gray-100 text-gray-600'}`}>
                                                {project.status.charAt(0).toUpperCase() + project.status.slice(1)}
                                            </span>
                                        </div>

                                        {project.description && (
                                            <p className="text-xs text-gray-500 line-clamp-2 mb-3">{project.description}</p>
                                        )}

                                        {(project.start_date || project.end_date) && (
                                            <p className="text-xs text-gray-400 mb-2">
                                                {formatDate(project.start_date)}
                                                {project.end_date && ` → ${formatDate(project.end_date)}`}
                                            </p>
                                        )}

                                        {project.project_url && (
                                            <a
                                                href={project.project_url}
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                className="inline-flex items-center gap-1 text-xs text-blue-600 hover:underline font-medium"
                                            >
                                                <FontAwesomeIcon icon={faExternalLink} className="w-3 h-3" />
                                                View Project
                                            </a>
                                        )}
                                    </div>
                                ))}
                            </div>
                        </div>
                    )}

                    {/* Footer */}
                    <div className="text-center text-xs text-gray-400 py-4">
                        <p>Digital Profile — Powered by ProfileCard</p>
                    </div>
                </div>
            </div>
        </>
    );
}
