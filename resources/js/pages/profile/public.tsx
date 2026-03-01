import React, { useState } from 'react';
import { Head, useForm } from '@inertiajs/react';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import {
    faGithub, faLinkedin, faWhatsapp, faXTwitter,
    faInstagram, faYoutube, faTiktok, faDribbble, faBehance, faMedium,
} from '@fortawesome/free-brands-svg-icons';
import { faEnvelope, faPhone, faGlobe, faLocationDot, faExternalLink } from '@fortawesome/free-solid-svg-icons';
import { GraduationCap, Award, ExternalLink, Copy, CalendarDays, Download, Share2, Star, Package, MessageSquare, CheckCircle2, LoaderCircle } from 'lucide-react';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';

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
    twitter?: string;
    instagram?: string;
    youtube?: string;
    tiktok?: string;
    dribbble?: string;
    behance?: string;
    medium?: string;
    location?: string;
    profile_image?: string;
    qr_code_url?: string;
    slug: string;
    skills?: string;
    template?: string;
    availability_status?: string;
    scheduling_url?: string;
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

interface Education {
    id: number;
    institution: string;
    degree: string;
    field_of_study?: string;
    start_year: number;
    end_year?: number;
    is_current: boolean;
    description?: string;
}

interface Certification {
    id: number;
    title: string;
    issuer: string;
    issue_date: string;
    expiry_date?: string;
    credential_url?: string;
    credential_id?: string;
}

interface Testimonial {
    id: number;
    reviewer_name: string;
    reviewer_title?: string;
    reviewer_company?: string;
    content: string;
    rating: number;
}

interface Service {
    id: number;
    title: string;
    description?: string;
    starting_price?: string | number | null;
    currency: string;
    cta_label?: string;
    cta_url?: string;
    sort_order: number;
}

// ── Theme config ────────────────────────────────────────────────────────────

type Theme = {
    outerBg: string;
    innerPad: string;
    card: string;
    banner: string;
    avatarBorder: string;
    name: string;
    title: string;
    bio: string;
    location: string;
    badge: string;
    skill: string;
    sectionBg: string;
    sectionTitle: string;
    subText: string;
    expTitle: string;
    expCompany: string;
    expDate: string;
    expLine: string;
    expDot: string;
    projCard: string;
    projName: string;
    statusMap: Record<string, string>;
    footerText: string;
};

const themes: Record<string, Theme> = {
    default: {
        outerBg:     'bg-gradient-to-br from-slate-50 to-blue-50',
        innerPad:    'py-10 px-4',
        card:        'bg-white border border-gray-100 shadow-sm',
        banner:      'h-28 bg-gradient-to-r from-blue-600 to-indigo-600',
        avatarBorder:'border-4 border-white shadow-md',
        name:        'text-2xl font-bold text-gray-900',
        title:       'text-blue-600 font-medium',
        bio:         'text-gray-600',
        location:    'text-gray-500',
        badge:       'border border-gray-200 text-gray-600 hover:border-blue-400 hover:text-blue-600',
        skill:       'bg-blue-50 text-blue-700',
        sectionBg:   'bg-white border border-gray-100 shadow-sm',
        sectionTitle:'text-gray-900',
        subText:     'text-gray-500',
        expTitle:    'text-gray-900',
        expCompany:  'text-blue-600',
        expDate:     'text-gray-400',
        expLine:     'border-blue-100',
        expDot:      'bg-blue-600',
        projCard:    'border border-gray-100 hover:border-blue-200 hover:shadow-sm',
        projName:    'text-gray-900',
        statusMap:   { planned: 'bg-yellow-100 text-yellow-700', ongoing: 'bg-blue-100 text-blue-700', completed: 'bg-green-100 text-green-700' },
        footerText:  'text-gray-400',
    },
    bold: {
        outerBg:     'bg-gray-950',
        innerPad:    'py-10 px-4',
        card:        'bg-gray-900 border border-gray-800',
        banner:      'h-36 bg-gradient-to-r from-violet-600 to-indigo-700',
        avatarBorder:'border-4 border-gray-900 shadow-lg',
        name:        'text-3xl font-black text-white',
        title:       'text-violet-400 font-medium',
        bio:         'text-gray-300',
        location:    'text-gray-400',
        badge:       'border border-gray-700 text-gray-300 hover:border-violet-500 hover:text-violet-300',
        skill:       'bg-violet-900/40 text-violet-300',
        sectionBg:   'bg-gray-900 border border-gray-800',
        sectionTitle:'text-white',
        subText:     'text-gray-400',
        expTitle:    'font-semibold text-white',
        expCompany:  'text-violet-400',
        expDate:     'text-gray-500',
        expLine:     'border-violet-900',
        expDot:      'bg-violet-500',
        projCard:    'border border-gray-800 hover:border-violet-700 bg-gray-800/50',
        projName:    'text-white',
        statusMap:   { planned: 'bg-yellow-900/30 text-yellow-300', ongoing: 'bg-violet-900/30 text-violet-300', completed: 'bg-green-900/30 text-green-300' },
        footerText:  'text-gray-600',
    },
    glass: {
        outerBg:     'bg-gradient-to-br from-blue-500 via-purple-600 to-pink-500',
        innerPad:    'py-10 px-4',
        card:        'bg-white/10 backdrop-blur-xl border border-white/20',
        banner:      'h-28 bg-white/20',
        avatarBorder:'border-4 border-white/40 shadow-lg',
        name:        'text-2xl font-bold text-white',
        title:       'text-white/90 font-medium',
        bio:         'text-white/80',
        location:    'text-white/70',
        badge:       'bg-white/15 border border-white/30 text-white hover:bg-white/25',
        skill:       'bg-white/20 text-white',
        sectionBg:   'bg-white/10 backdrop-blur-xl border border-white/20',
        sectionTitle:'text-white',
        subText:     'text-white/60',
        expTitle:    'font-semibold text-white',
        expCompany:  'text-white/80',
        expDate:     'text-white/50',
        expLine:     'border-white/20',
        expDot:      'bg-white',
        projCard:    'bg-white/10 border border-white/20 hover:bg-white/20',
        projName:    'text-white',
        statusMap:   { planned: 'bg-yellow-300/20 text-yellow-100', ongoing: 'bg-blue-300/20 text-blue-100', completed: 'bg-green-300/20 text-green-100' },
        footerText:  'text-white/30',
    },
};

// ── Helpers ──────────────────────────────────────────────────────────────────

function formatDate(dateStr?: string) {
    if (!dateStr) return '';
    return new Date(dateStr).toLocaleDateString('en-US', { month: 'short', year: 'numeric' });
}

const availabilityLabels: Record<string, { label: string; dot: string }> = {
    available:             { label: 'Available for Work',       dot: 'bg-green-500' },
    open_to_opportunities: { label: 'Open to Opportunities',    dot: 'bg-yellow-400' },
    not_available:         { label: 'Not Available',            dot: 'bg-gray-400' },
};

function StarDisplay({ rating }: { rating: number }) {
    return (
        <div className="flex gap-0.5">
            {[1, 2, 3, 4, 5].map((s) => (
                <Star
                    key={s}
                    className={`w-4 h-4 ${s <= rating ? 'fill-yellow-400 text-yellow-400' : 'text-gray-300'}`}
                />
            ))}
        </div>
    );
}

// ── Main component ────────────────────────────────────────────────────────────

export default function PublicProfile({
    profile,
    projects,
    experiences,
    educations,
    certifications,
    testimonials,
    services,
}: {
    profile: Profile;
    projects: Project[];
    experiences: Experience[];
    educations: Education[];
    certifications: Certification[];
    testimonials: Testimonial[];
    services: Service[];
}) {
    const t = themes[profile.template ?? 'default'] ?? themes.default;
    const skills = profile.skills ? profile.skills.split(',').map((s) => s.trim()).filter(Boolean) : [];
    const [copied, setCopied] = useState(false);
    const [leadOpen, setLeadOpen] = useState(false);
    const availability = profile.availability_status ? availabilityLabels[profile.availability_status] : null;

    // Lead form
    const { data: leadData, setData: setLeadData, post: postLead, processing: leadProcessing, errors: leadErrors, wasSuccessful: leadSent, reset: resetLead } = useForm({
        visitor_name:  '',
        visitor_email: '',
        visitor_phone: '',
        message:       '',
    });

    const submitLead = (e: React.FormEvent) => {
        e.preventDefault();
        postLead(route('lead.store', profile.slug), {
            onSuccess: () => resetLead(),
        });
    };

    const handleCopyLink = () => {
        navigator.clipboard.writeText(window.location.href).then(() => {
            setCopied(true);
            setTimeout(() => setCopied(false), 2000);
        });
    };

    const downloadQrPng = () => {
        if (!profile.qr_code_url) return;
        const img = new Image();
        img.crossOrigin = 'anonymous';
        img.src = profile.qr_code_url;
        img.onload = () => {
            const canvas = document.createElement('canvas');
            canvas.width  = img.naturalWidth  || 300;
            canvas.height = img.naturalHeight || 300;
            const ctx = canvas.getContext('2d');
            if (!ctx) return;
            ctx.drawImage(img, 0, 0);
            const a = document.createElement('a');
            a.href     = canvas.toDataURL('image/png');
            a.download = `${profile.slug}-qr.png`;
            a.click();
        };
    };

    const socialLinks = [
        { url: profile.linkedin,   icon: faLinkedin,  label: 'LinkedIn' },
        { url: profile.github,     icon: faGithub,    label: 'GitHub' },
        { url: profile.twitter,    icon: faXTwitter,  label: 'X' },
        { url: profile.instagram,  icon: faInstagram, label: 'Instagram' },
        { url: profile.youtube,    icon: faYoutube,   label: 'YouTube' },
        { url: profile.tiktok,     icon: faTiktok,    label: 'TikTok' },
        { url: profile.dribbble,   icon: faDribbble,  label: 'Dribbble' },
        { url: profile.behance,    icon: faBehance,   label: 'Behance' },
        { url: profile.medium,     icon: faMedium,    label: 'Medium' },
    ].filter((s) => s.url);

    return (
        <>
            <Head title={`${profile.display_name} — Digital Profile`} />

            <div className={`min-h-screen ${t.outerBg} ${t.innerPad}`}>
                <div className="max-w-3xl mx-auto space-y-6">

                    {/* ── Profile Card ── */}
                    <div className={`${t.card} rounded-2xl overflow-hidden`}>
                        <div className={t.banner} />

                        <div className="px-6 pb-6">
                            <div className="flex items-end justify-between -mt-14 mb-4">
                                {profile.profile_image ? (
                                    <img
                                        src={profile.profile_image}
                                        alt={profile.display_name}
                                        className={`w-24 h-24 rounded-full object-cover ${t.avatarBorder}`}
                                    />
                                ) : (
                                    <div className={`w-24 h-24 rounded-full bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center ${t.avatarBorder}`}>
                                        <span className="text-3xl font-bold text-white">
                                            {(profile.display_name ?? '').charAt(0).toUpperCase()}
                                        </span>
                                    </div>
                                )}

                                {profile.qr_code_url && (
                                    <div className="text-center">
                                        <img src={profile.qr_code_url} alt="QR Code" className="w-16 h-16" />
                                        <p className={`text-xs mt-1 ${t.subText}`}>Scan me</p>
                                    </div>
                                )}
                            </div>

                            {/* Name + availability */}
                            <div className="flex flex-wrap items-center gap-3">
                                <h1 className={t.name}>{profile.display_name}</h1>
                                {availability && (
                                    <span className="inline-flex items-center gap-1.5 text-xs font-medium px-2.5 py-1 rounded-full bg-black/5 dark:bg-white/10 text-gray-700 dark:text-gray-200">
                                        <span className={`w-2 h-2 rounded-full ${availability.dot}`} />
                                        {availability.label}
                                    </span>
                                )}
                            </div>

                            {profile.job_title && <p className={`mt-1 ${t.title}`}>{profile.job_title}</p>}

                            {profile.location && (
                                <p className={`text-sm mt-1 flex items-center gap-1.5 ${t.location}`}>
                                    <FontAwesomeIcon icon={faLocationDot} />
                                    {profile.location}
                                </p>
                            )}

                            {profile.short_bio && (
                                <p className={`mt-4 text-sm leading-relaxed ${t.bio}`}>{profile.short_bio}</p>
                            )}

                            {/* Contact badges */}
                            <div className="mt-5 flex flex-wrap gap-2">
                                {profile.email && (
                                    <a href={`mailto:${profile.email}`} className={`flex items-center gap-2 text-sm px-3 py-1.5 rounded-full transition-colors ${t.badge}`}>
                                        <FontAwesomeIcon icon={faEnvelope} className="text-xs" />
                                        {profile.email}
                                    </a>
                                )}
                                {profile.phone && (
                                    <a href={`tel:${profile.phone}`} className={`flex items-center gap-2 text-sm px-3 py-1.5 rounded-full transition-colors ${t.badge}`}>
                                        <FontAwesomeIcon icon={faPhone} className="text-xs" />
                                        {profile.phone}
                                    </a>
                                )}
                                {profile.whatsapp && (
                                    <a href={`https://wa.me/${profile.whatsapp.replace(/\D/g, '')}`} target="_blank" rel="noopener noreferrer" className={`flex items-center gap-2 text-sm px-3 py-1.5 rounded-full transition-colors ${t.badge}`}>
                                        <FontAwesomeIcon icon={faWhatsapp} className="text-xs" />
                                        WhatsApp
                                    </a>
                                )}
                                {profile.website && (
                                    <a href={profile.website} target="_blank" rel="noopener noreferrer" className={`flex items-center gap-2 text-sm px-3 py-1.5 rounded-full transition-colors ${t.badge}`}>
                                        <FontAwesomeIcon icon={faGlobe} className="text-xs" />
                                        Website
                                    </a>
                                )}
                                {socialLinks.map(({ url, icon, label }) => (
                                    <a key={label} href={url!} target="_blank" rel="noopener noreferrer" className={`flex items-center gap-2 text-sm px-3 py-1.5 rounded-full transition-colors ${t.badge}`}>
                                        <FontAwesomeIcon icon={icon} className="text-xs" />
                                        {label}
                                    </a>
                                ))}
                            </div>

                            {/* Book a Meeting */}
                            {profile.scheduling_url && (
                                <div className="mt-5">
                                    <a
                                        href={profile.scheduling_url}
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        className="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold transition-colors"
                                    >
                                        <CalendarDays className="w-4 h-4" />
                                        Book a Meeting
                                    </a>
                                </div>
                            )}
                        </div>
                    </div>

                    {/* ── Skills ── */}
                    {skills.length > 0 && (
                        <div className={`${t.sectionBg} rounded-2xl p-6`}>
                            <h2 className={`text-lg font-semibold mb-4 ${t.sectionTitle}`}>Skills</h2>
                            <div className="flex flex-wrap gap-2">
                                {skills.map((skill, i) => (
                                    <span key={i} className={`text-sm font-medium px-3 py-1 rounded-full ${t.skill}`}>
                                        {skill}
                                    </span>
                                ))}
                            </div>
                        </div>
                    )}

                    {/* ── Services ── */}
                    {services.length > 0 && (
                        <div className={`${t.sectionBg} rounded-2xl p-6`}>
                            <h2 className={`text-lg font-semibold mb-5 ${t.sectionTitle}`}>
                                <Package className="inline w-5 h-5 mr-2 opacity-70" />
                                Services
                            </h2>
                            <div className="grid gap-4 sm:grid-cols-2">
                                {services.map((svc) => (
                                    <div key={svc.id} className={`rounded-xl p-4 flex flex-col gap-3 ${t.projCard}`}>
                                        <h3 className={`font-semibold text-sm ${t.projName}`}>{svc.title}</h3>

                                        {svc.description && (
                                            <p className={`text-xs leading-relaxed line-clamp-3 ${t.subText}`}>
                                                {svc.description}
                                            </p>
                                        )}

                                        {svc.starting_price != null && svc.starting_price !== '' && (
                                            <span className={`text-xs font-medium px-2.5 py-1 rounded-full w-fit ${t.skill}`}>
                                                From {svc.currency} {parseFloat(String(svc.starting_price)).toLocaleString(undefined, { minimumFractionDigits: 0, maximumFractionDigits: 2 })}
                                            </span>
                                        )}

                                        {svc.cta_url && (
                                            <a
                                                href={svc.cta_url}
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                className="mt-auto text-xs text-center bg-blue-600 hover:bg-blue-700 text-white rounded-lg py-2 px-3 transition-colors"
                                            >
                                                {svc.cta_label || 'Get Started'}
                                            </a>
                                        )}
                                    </div>
                                ))}
                            </div>
                        </div>
                    )}

                    {/* ── Experience ── */}
                    {experiences.length > 0 && (
                        <div className={`${t.sectionBg} rounded-2xl p-6`}>
                            <h2 className={`text-lg font-semibold mb-5 ${t.sectionTitle}`}>Work Experience</h2>
                            <div className="space-y-5">
                                {experiences.map((exp) => (
                                    <div key={exp.id} className={`relative pl-6 border-l-2 ${t.expLine}`}>
                                        <div className={`absolute -left-[7px] top-1 w-3 h-3 rounded-full ${t.expDot}`} />
                                        <div className="flex items-start justify-between gap-2">
                                            <div>
                                                <h3 className={`font-semibold ${t.expTitle}`}>{exp.position}</h3>
                                                <p className={`text-sm font-medium ${t.expCompany}`}>{exp.company}</p>
                                                {exp.location && <p className={`text-xs ${t.subText}`}>{exp.location}</p>}
                                            </div>
                                            <div className="text-right shrink-0">
                                                <p className={`text-xs ${t.expDate}`}>
                                                    {formatDate(exp.start_date)} — {exp.is_current ? 'Present' : formatDate(exp.end_date)}
                                                </p>
                                                {exp.is_current && (
                                                    <span className="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full">Current</span>
                                                )}
                                            </div>
                                        </div>
                                        {exp.description && (
                                            <p className={`text-sm mt-2 leading-relaxed ${t.bio}`}>{exp.description}</p>
                                        )}
                                    </div>
                                ))}
                            </div>
                        </div>
                    )}

                    {/* ── Education ── */}
                    {educations.length > 0 && (
                        <div className={`${t.sectionBg} rounded-2xl p-6`}>
                            <h2 className={`text-lg font-semibold mb-5 ${t.sectionTitle}`}>Education</h2>
                            <div className="space-y-5">
                                {educations.map((edu) => (
                                    <div key={edu.id} className={`relative pl-6 border-l-2 ${t.expLine}`}>
                                        <div className={`absolute -left-[7px] top-1 w-3 h-3 rounded-full ${t.expDot}`} />
                                        <div className="flex items-start justify-between gap-2">
                                            <div>
                                                <h3 className={`font-semibold ${t.expTitle}`}>{edu.degree}</h3>
                                                {edu.field_of_study && (
                                                    <p className={`text-sm font-medium ${t.expCompany}`}>{edu.field_of_study}</p>
                                                )}
                                                <p className={`text-sm flex items-center gap-1 ${t.subText}`}>
                                                    <GraduationCap className="w-3.5 h-3.5" />
                                                    {edu.institution}
                                                </p>
                                            </div>
                                            <div className="text-right shrink-0">
                                                <p className={`text-xs ${t.expDate}`}>
                                                    {edu.start_year} — {edu.is_current ? 'Present' : (edu.end_year ?? '—')}
                                                </p>
                                                {edu.is_current && (
                                                    <span className="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full">Current</span>
                                                )}
                                            </div>
                                        </div>
                                        {edu.description && (
                                            <p className={`text-sm mt-2 leading-relaxed ${t.bio}`}>{edu.description}</p>
                                        )}
                                    </div>
                                ))}
                            </div>
                        </div>
                    )}

                    {/* ── Projects ── */}
                    {projects.length > 0 && (
                        <div className={`${t.sectionBg} rounded-2xl p-6`}>
                            <h2 className={`text-lg font-semibold mb-5 ${t.sectionTitle}`}>Projects</h2>
                            <div className="grid gap-4 sm:grid-cols-2">
                                {projects.map((project) => (
                                    <div key={project.id} className={`rounded-xl p-4 transition-all ${t.projCard}`}>
                                        <div className="flex items-start justify-between gap-2 mb-2">
                                            <h3 className={`font-semibold text-sm ${t.projName}`}>{project.name}</h3>
                                            <span className={`text-xs px-2 py-0.5 rounded-full shrink-0 ${t.statusMap[project.status] ?? 'bg-gray-100 text-gray-600'}`}>
                                                {project.status.charAt(0).toUpperCase() + project.status.slice(1)}
                                            </span>
                                        </div>
                                        {project.description && (
                                            <p className={`text-xs line-clamp-2 mb-2 ${t.subText}`}>{project.description}</p>
                                        )}
                                        {(project.start_date || project.end_date) && (
                                            <p className={`text-xs mb-2 ${t.expDate}`}>
                                                {formatDate(project.start_date)}{project.end_date && ` → ${formatDate(project.end_date)}`}
                                            </p>
                                        )}
                                        {project.project_url && (
                                            <a href={project.project_url} target="_blank" rel="noopener noreferrer" className="inline-flex items-center gap-1 text-xs text-blue-400 hover:underline font-medium">
                                                <FontAwesomeIcon icon={faExternalLink} className="w-3 h-3" />
                                                View Project
                                            </a>
                                        )}
                                    </div>
                                ))}
                            </div>
                        </div>
                    )}

                    {/* ── Certifications ── */}
                    {certifications.length > 0 && (
                        <div className={`${t.sectionBg} rounded-2xl p-6`}>
                            <h2 className={`text-lg font-semibold mb-5 ${t.sectionTitle}`}>Certifications</h2>
                            <div className="grid gap-3 sm:grid-cols-2">
                                {certifications.map((cert) => (
                                    <div key={cert.id} className={`rounded-xl p-4 transition-all ${t.projCard}`}>
                                        <div className="flex items-start gap-3">
                                            <div className="w-8 h-8 rounded-lg bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center shrink-0">
                                                <Award className="w-4 h-4 text-amber-600" />
                                            </div>
                                            <div className="min-w-0">
                                                <h3 className={`font-semibold text-sm leading-tight ${t.projName}`}>{cert.title}</h3>
                                                <p className={`text-xs mt-0.5 ${t.expCompany}`}>{cert.issuer}</p>
                                                <p className={`text-xs mt-0.5 ${t.expDate}`}>
                                                    {formatDate(cert.issue_date)}
                                                    {cert.expiry_date && ` · expires ${formatDate(cert.expiry_date)}`}
                                                </p>
                                                {cert.credential_url && (
                                                    <a href={cert.credential_url} target="_blank" rel="noopener noreferrer" className="inline-flex items-center gap-1 text-xs text-blue-400 hover:underline mt-1">
                                                        <ExternalLink className="w-3 h-3" />
                                                        Verify
                                                    </a>
                                                )}
                                            </div>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        </div>
                    )}

                    {/* ── Testimonials ── */}
                    {testimonials.length > 0 && (
                        <div className={`${t.sectionBg} rounded-2xl p-6`}>
                            <h2 className={`text-lg font-semibold mb-5 ${t.sectionTitle}`}>
                                <Star className="inline w-5 h-5 mr-2 opacity-70 fill-yellow-400 text-yellow-400" />
                                What people say
                            </h2>
                            <div className="grid gap-4 sm:grid-cols-2">
                                {testimonials.map((t_item) => (
                                    <div key={t_item.id} className={`rounded-xl p-4 flex flex-col gap-3 ${t.projCard}`}>
                                        <StarDisplay rating={t_item.rating} />
                                        <p className={`text-sm leading-relaxed line-clamp-4 ${t.bio}`}>
                                            "{t_item.content}"
                                        </p>
                                        <div>
                                            <p className={`font-semibold text-sm ${t.expTitle}`}>{t_item.reviewer_name}</p>
                                            {(t_item.reviewer_title || t_item.reviewer_company) && (
                                                <p className={`text-xs ${t.subText}`}>
                                                    {[t_item.reviewer_title, t_item.reviewer_company].filter(Boolean).join(' · ')}
                                                </p>
                                            )}
                                        </div>
                                    </div>
                                ))}
                            </div>
                        </div>
                    )}

                    {/* ── Lead Capture ── */}
                    <div className={`${t.sectionBg} rounded-2xl p-6`}>
                        <button
                            type="button"
                            onClick={() => setLeadOpen((prev) => !prev)}
                            className={`flex items-center gap-2 w-full text-left ${t.sectionTitle}`}
                        >
                            <MessageSquare className="w-5 h-5 opacity-70" />
                            <span className="text-lg font-semibold">Get in touch</span>
                            <span className={`ml-auto text-sm ${t.subText}`}>{leadOpen ? '▲' : '▼'}</span>
                        </button>

                        {leadOpen && (
                            <div className="mt-5">
                                {leadSent ? (
                                    <div className="flex flex-col items-center text-center py-6 gap-3">
                                        <CheckCircle2 className="w-10 h-10 text-green-500" />
                                        <p className={`text-sm ${t.bio}`}>
                                            Message sent! {profile.display_name} will be in touch.
                                        </p>
                                    </div>
                                ) : (
                                    <form onSubmit={submitLead} className="space-y-4">
                                        <div className="grid sm:grid-cols-2 gap-4">
                                            <div>
                                                <Label htmlFor="visitor_name" className={t.sectionTitle}>Your Name *</Label>
                                                <Input
                                                    id="visitor_name"
                                                    value={leadData.visitor_name}
                                                    onChange={(e) => setLeadData('visitor_name', e.target.value)}
                                                    placeholder="Jane Smith"
                                                    autoComplete="name"
                                                    className="bg-white text-gray-900 placeholder:text-gray-400 border-gray-300"
                                                />
                                                {leadErrors.visitor_name && <p className="text-xs text-red-500 mt-1">{leadErrors.visitor_name}</p>}
                                            </div>
                                            <div>
                                                <Label htmlFor="visitor_email" className={t.sectionTitle}>Email *</Label>
                                                <Input
                                                    id="visitor_email"
                                                    type="email"
                                                    value={leadData.visitor_email}
                                                    onChange={(e) => setLeadData('visitor_email', e.target.value)}
                                                    placeholder="jane@example.com"
                                                    autoComplete="email"
                                                    className="bg-white text-gray-900 placeholder:text-gray-400 border-gray-300"
                                                />
                                                {leadErrors.visitor_email && <p className="text-xs text-red-500 mt-1">{leadErrors.visitor_email}</p>}
                                            </div>
                                        </div>

                                        <div>
                                            <Label htmlFor="visitor_phone" className={t.sectionTitle}>Phone</Label>
                                            <Input
                                                id="visitor_phone"
                                                value={leadData.visitor_phone}
                                                onChange={(e) => setLeadData('visitor_phone', e.target.value)}
                                                placeholder="+1 555 000 0000"
                                                autoComplete="tel"
                                                className="bg-white text-gray-900 placeholder:text-gray-400 border-gray-300"
                                            />
                                        </div>

                                        <div>
                                            <Label htmlFor="lead_message" className={t.sectionTitle}>Message</Label>
                                            <Textarea
                                                id="lead_message"
                                                value={leadData.message}
                                                onChange={(e) => setLeadData('message', e.target.value)}
                                                placeholder="I'd love to discuss..."
                                                rows={4}
                                                className="bg-white text-gray-900 placeholder:text-gray-400 border-gray-300"
                                            />
                                        </div>

                                        <Button type="submit" disabled={leadProcessing} className="w-full">
                                            {leadProcessing && <LoaderCircle className="w-4 h-4 animate-spin mr-2" />}
                                            Send Message
                                        </Button>
                                    </form>
                                )}
                            </div>
                        )}
                    </div>

                    {/* ── Share Bar ── */}
                    <div className={`${t.sectionBg} rounded-2xl p-5`}>
                        <div className="flex items-center gap-2 mb-3">
                            <Share2 className={`w-4 h-4 ${t.subText}`} />
                            <p className={`text-sm font-medium ${t.sectionTitle}`}>Share this profile</p>
                        </div>
                        <div className="flex flex-wrap gap-2">
                            <button
                                type="button"
                                onClick={handleCopyLink}
                                className={`flex items-center gap-2 text-sm px-3 py-1.5 rounded-full transition-colors ${t.badge}`}
                            >
                                <Copy className="w-3.5 h-3.5" />
                                {copied ? 'Copied!' : 'Copy Link'}
                            </button>

                            <a
                                href={`https://wa.me/?text=${encodeURIComponent(profile.display_name + "'s digital profile: " + window.location.origin + '/p/' + profile.slug)}`}
                                target="_blank"
                                rel="noopener noreferrer"
                                className={`flex items-center gap-2 text-sm px-3 py-1.5 rounded-full transition-colors ${t.badge}`}
                            >
                                <FontAwesomeIcon icon={faWhatsapp} className="text-xs" />
                                WhatsApp
                            </a>

                            <a
                                href={`/p/${profile.slug}/vcard`}
                                className={`flex items-center gap-2 text-sm px-3 py-1.5 rounded-full transition-colors ${t.badge}`}
                            >
                                <Download className="w-3.5 h-3.5" />
                                Save Contact
                            </a>

                            {profile.qr_code_url && (
                                <button
                                    type="button"
                                    onClick={downloadQrPng}
                                    className={`flex items-center gap-2 text-sm px-3 py-1.5 rounded-full transition-colors ${t.badge}`}
                                >
                                    <Download className="w-3.5 h-3.5" />
                                    Download QR
                                </button>
                            )}
                        </div>
                    </div>

                    {/* ── Footer ── */}
                    <div className={`text-center text-xs py-4 ${t.footerText}`}>
                        <p>Digital Profile — Your professional identity in one link</p>
                    </div>

                </div>
            </div>
        </>
    );
}
