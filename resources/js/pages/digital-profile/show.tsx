import React from "react";
import { usePage, Head } from "@inertiajs/react";
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faInstagram, faTwitter, faGithub, faLinkedin, faWhatsapp } from '@fortawesome/free-brands-svg-icons';
import { faEnvelope, faPhone, faGlobe, faLocationDot } from '@fortawesome/free-solid-svg-icons';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'View Digital Profile', href: '/dashboard/profiles/create' },
];

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
    account_type?: string;
    slug: string;
    is_public?: boolean;
    template?: string;
}

export default function Show() {
    const { props } = usePage<{ profile: Profile }>();
    const profile = props.profile;

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Create Digital Profile" />
            <div className="w-lg mx-auto bg-[#f5f1ea] rounded-xl shadow-md overflow-hidden">
                <div className="p-6 text-center w-full">
                    {profile.profile_image && (
                        <img
                            src={profile.profile_image}
                            className="w-24 h-24 mx-auto rounded-full border-4 border-[#d4c2b0]"
                            alt={profile.display_name}
                        />
                    )}

                    <h2 className="text-xl font-semibold text-[#5a4332] mt-2">
                        {profile.display_name}
                    </h2>

                    {profile.job_title && (
                        <p className="text-sm text-[#5a4332] mb-1">{profile.job_title}</p>
                    )}

                    {profile.short_bio && (
                        <p className="text-sm text-[#715c4a] mb-3">{profile.short_bio}</p>
                    )}

                    <div className="space-y-1 text-sm text-[#5a4332] mt-2">
                       
                        {profile.location && <p><FontAwesomeIcon icon={faLocationDot} className="mr-2" />{profile.location}</p>}
                    </div>
                    <div>
                        {profile.qr_code_url && (
                        <img
                            src={profile.qr_code_url}
                            className="w-24 h-24 mx-auto rounded-full border-4 border-[#d4c2b0]"
                            alt={profile.qr_code_url}
                        />
                    )}

                    </div>

                    <div className="flex justify-center gap-4 my-4 text-[#5a4332]">
                        {profile.linkedin && (
                            <a href={profile.linkedin} target="_blank">
                                <FontAwesomeIcon icon={faLinkedin} className="w-5 h-5" />
                            </a>
                        )}
                        {profile.github && (
                            <a href={profile.github} target="_blank">
                                <FontAwesomeIcon icon={faGithub} className="w-5 h-5" />
                            </a>
                        )}
                        {profile.email && (
                            <a href={`mailto:${profile.email}`}>
                                <FontAwesomeIcon icon={faEnvelope} className="w-5 h-5" />
                            </a>
                        )}
                        {profile.phone && (
                            <a href={`tel:${profile.phone}`}>
                                <FontAwesomeIcon icon={faPhone} className="w-5 h-5" />
                            </a>
                        )}
                    </div>

                    {profile.qr_code_url && (
                        <img
                            src={profile.qr_code_url}
                            alt="QR Code"
                            className="w-24 h-24 mx-auto mt-2"
                        />
                    )}

                    <a
                        href={`/profile/${profile.slug}`}
                        className="mt-4 inline-block bg-[#9e7c5a] text-white px-6 py-2 rounded-full text-sm font-medium"
                    >
                        View Public Profile
                    </a>
                </div>
            </div>
        </AppLayout>
    );
}
