import { Head } from '@inertiajs/react';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faGithub, faLinkedin, faTwitter, faInstagram } from '@fortawesome/free-brands-svg-icons';
import { faEnvelope, faPhone, faGlobe } from '@fortawesome/free-solid-svg-icons';

interface Profile {
    display_name: string;
    job_title?: string;
    short_bio?: string;
    email?: string;
    phone?: string;
    website?: string;
    linkedin?: string;
    github?: string;
    twitter?: string;
    instagram?: string;
    profile_image?: string;
    slug: string;
    availability_status?: string;
}

const availabilityDot: Record<string, string> = {
    available:             'bg-green-500',
    open_to_opportunities: 'bg-yellow-400',
    not_available:         'bg-gray-400',
};

export default function Embed({ profile }: { profile: Profile }) {
    const profileUrl = `/p/${profile.slug}`;
    const initials   = (profile.display_name ?? '').charAt(0).toUpperCase();

    const socials = [
        { icon: faLinkedin,  href: profile.linkedin,  title: 'LinkedIn' },
        { icon: faGithub,    href: profile.github,    title: 'GitHub' },
        { icon: faTwitter,   href: profile.twitter,   title: 'Twitter' },
        { icon: faInstagram, href: profile.instagram, title: 'Instagram' },
        { icon: faEnvelope,  href: profile.email ? `mailto:${profile.email}` : undefined, title: 'Email' },
        { icon: faPhone,     href: profile.phone ? `tel:${profile.phone}` : undefined, title: 'Phone' },
        { icon: faGlobe,     href: profile.website,   title: 'Website' },
    ].filter((s) => s.href);

    return (
        <>
            <Head title={`${profile.display_name} — Digital Card`} />
            <div style={{ fontFamily: 'system-ui, sans-serif', margin: 0, padding: 0, background: 'transparent' }}>
                <div style={{
                    display: 'flex',
                    alignItems: 'center',
                    gap: '14px',
                    padding: '14px 16px',
                    background: '#fff',
                    borderRadius: '14px',
                    boxShadow: '0 2px 12px rgba(0,0,0,0.10)',
                    border: '1px solid #e5e7eb',
                    maxWidth: '420px',
                    boxSizing: 'border-box',
                }}>
                    {/* Avatar */}
                    {profile.profile_image ? (
                        <img
                            src={profile.profile_image}
                            alt={profile.display_name}
                            style={{ width: 56, height: 56, borderRadius: '50%', objectFit: 'cover', flexShrink: 0 }}
                        />
                    ) : (
                        <div style={{
                            width: 56, height: 56, borderRadius: '50%', flexShrink: 0,
                            background: 'linear-gradient(135deg,#60a5fa,#818cf8)',
                            display: 'flex', alignItems: 'center', justifyContent: 'center',
                            fontSize: 22, fontWeight: 700, color: '#fff',
                        }}>
                            {initials}
                        </div>
                    )}

                    {/* Info */}
                    <div style={{ flex: 1, minWidth: 0 }}>
                        <div style={{ display: 'flex', alignItems: 'center', gap: 6, flexWrap: 'wrap' }}>
                            <span style={{ fontWeight: 700, fontSize: 15, color: '#111827', whiteSpace: 'nowrap' }}>
                                {profile.display_name}
                            </span>
                            {profile.availability_status && availabilityDot[profile.availability_status] && (
                                <span style={{
                                    width: 8, height: 8, borderRadius: '50%', flexShrink: 0,
                                    background: profile.availability_status === 'available' ? '#22c55e'
                                        : profile.availability_status === 'open_to_opportunities' ? '#facc15'
                                        : '#9ca3af',
                                }} />
                            )}
                        </div>
                        {profile.job_title && (
                            <p style={{ margin: '2px 0 0', fontSize: 12, color: '#2563eb', fontWeight: 500 }}>{profile.job_title}</p>
                        )}

                        {/* Socials */}
                        {socials.length > 0 && (
                            <div style={{ display: 'flex', gap: 8, marginTop: 6, flexWrap: 'wrap' }}>
                                {socials.slice(0, 5).map((s) => (
                                    <a
                                        key={s.title}
                                        href={s.href}
                                        title={s.title}
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        style={{ color: '#6b7280', fontSize: 13, textDecoration: 'none' }}
                                    >
                                        <FontAwesomeIcon icon={s.icon} />
                                    </a>
                                ))}
                            </div>
                        )}
                    </div>

                    {/* CTA */}
                    <a
                        href={profileUrl}
                        target="_blank"
                        rel="noopener noreferrer"
                        style={{
                            flexShrink: 0,
                            fontSize: 11,
                            fontWeight: 600,
                            color: '#fff',
                            background: '#2563eb',
                            borderRadius: 8,
                            padding: '6px 10px',
                            textDecoration: 'none',
                            whiteSpace: 'nowrap',
                        }}
                    >
                        View Profile
                    </a>
                </div>
            </div>
        </>
    );
}
