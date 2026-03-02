import { Head } from '@inertiajs/react';
import { ExternalLink, Globe } from 'lucide-react';

interface TeamMemberProfile {
    slug: string;
    display_name: string;
    job_title?: string;
    profile_image?: string;
    location?: string;
    availability_status?: string;
}

interface TeamMember {
    id: number;
    name: string;
    role: 'owner' | 'member';
    profile: TeamMemberProfile;
}

interface Team {
    name: string;
    slug: string;
    description?: string;
    website?: string;
    logo_url?: string;
}

const availabilityDot: Record<string, string> = {
    available:             '#22c55e',
    open_to_opportunities: '#facc15',
    not_available:         '#9ca3af',
};

export default function TeamShow({ team, members }: { team: Team; members: TeamMember[] }) {
    return (
        <>
            <Head title={`${team.name} — Team`} />

            <div className="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 dark:from-gray-950 dark:to-gray-900 py-12 px-4">
                <div className="max-w-3xl mx-auto space-y-8">

                    {/* Team Header */}
                    <div className="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 p-8 text-center">
                        {team.logo_url ? (
                            <img src={team.logo_url} alt={team.name} className="w-20 h-20 rounded-2xl object-cover mx-auto mb-4 shadow" />
                        ) : (
                            <div className="w-20 h-20 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center mx-auto mb-4 shadow">
                                <span className="text-3xl font-bold text-white">{team.name.charAt(0).toUpperCase()}</span>
                            </div>
                        )}
                        <h1 className="text-2xl font-bold text-gray-900 dark:text-white">{team.name}</h1>
                        {team.description && (
                            <p className="text-gray-500 dark:text-gray-400 mt-2 max-w-md mx-auto text-sm leading-relaxed">{team.description}</p>
                        )}
                        {team.website && (
                            <a
                                href={team.website}
                                target="_blank"
                                rel="noopener noreferrer"
                                className="inline-flex items-center gap-1.5 mt-3 text-sm text-blue-600 hover:underline"
                            >
                                <Globe className="w-3.5 h-3.5" />
                                {team.website.replace(/^https?:\/\//, '')}
                            </a>
                        )}
                    </div>

                    {/* Members */}
                    {members.length > 0 && (
                        <div>
                            <h2 className="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-4">
                                {members.length} Member{members.length !== 1 ? 's' : ''}
                            </h2>
                            <div className="grid gap-4 sm:grid-cols-2">
                                {members.map((member) => (
                                    <a
                                        key={member.id}
                                        href={`/p/${member.profile.slug}`}
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        className="bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800 p-5 flex items-center gap-4 hover:shadow-md transition-shadow group"
                                    >
                                        {member.profile.profile_image ? (
                                            <img
                                                src={member.profile.profile_image}
                                                alt={member.profile.display_name}
                                                className="w-14 h-14 rounded-full object-cover shrink-0"
                                            />
                                        ) : (
                                            <div className="w-14 h-14 rounded-full bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center shrink-0">
                                                <span className="text-xl font-bold text-white">
                                                    {member.profile.display_name.charAt(0).toUpperCase()}
                                                </span>
                                            </div>
                                        )}
                                        <div className="flex-1 min-w-0">
                                            <div className="flex items-center gap-2">
                                                <p className="font-semibold text-gray-900 dark:text-white text-sm truncate">
                                                    {member.profile.display_name}
                                                </p>
                                                {member.profile.availability_status && availabilityDot[member.profile.availability_status] && (
                                                    <span
                                                        className="w-2 h-2 rounded-full shrink-0"
                                                        style={{ background: availabilityDot[member.profile.availability_status] }}
                                                    />
                                                )}
                                            </div>
                                            {member.profile.job_title && (
                                                <p className="text-xs text-blue-600 mt-0.5">{member.profile.job_title}</p>
                                            )}
                                            {member.profile.location && (
                                                <p className="text-xs text-gray-400 mt-0.5">{member.profile.location}</p>
                                            )}
                                        </div>
                                        <ExternalLink className="w-4 h-4 text-gray-300 dark:text-gray-600 group-hover:text-blue-500 transition-colors shrink-0" />
                                    </a>
                                ))}
                            </div>
                        </div>
                    )}

                    <p className="text-center text-xs text-gray-400">
                        Powered by <a href="/" className="hover:underline">Digital Profile</a>
                    </p>
                </div>
            </div>
        </>
    );
}
