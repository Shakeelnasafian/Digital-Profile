import { Head, router } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Star } from 'lucide-react';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Testimonials', href: '/testimonials' },
];

interface Testimonial {
    id: number;
    reviewer_name: string;
    reviewer_title?: string;
    reviewer_company?: string;
    content: string;
    rating: number;
    is_approved: boolean;
    created_at: string;
}

function Stars({ rating }: { rating: number }) {
    return (
        <div className="flex gap-0.5">
            {[1, 2, 3, 4, 5].map((s) => (
                <Star
                    key={s}
                    className={`w-4 h-4 ${s <= rating ? 'fill-yellow-400 text-yellow-400' : 'text-gray-300 dark:text-gray-600'}`}
                />
            ))}
        </div>
    );
}

function TestimonialCard({ t, onApprove, onDelete }: { t: Testimonial; onApprove: () => void; onDelete: () => void }) {
    return (
        <div className="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl p-5 space-y-3">
            <div className="flex items-start justify-between gap-3">
                <div>
                    <p className="font-semibold text-gray-900 dark:text-white">{t.reviewer_name}</p>
                    {(t.reviewer_title || t.reviewer_company) && (
                        <p className="text-sm text-gray-500 dark:text-gray-400">
                            {[t.reviewer_title, t.reviewer_company].filter(Boolean).join(' · ')}
                        </p>
                    )}
                </div>
                <span className={`text-xs px-2 py-0.5 rounded-full font-medium shrink-0 ${
                    t.is_approved
                        ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400'
                        : 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400'
                }`}>
                    {t.is_approved ? 'Approved' : 'Pending'}
                </span>
            </div>

            <Stars rating={t.rating} />

            <p className="text-sm text-gray-600 dark:text-gray-400 leading-relaxed line-clamp-4">{t.content}</p>

            <div className="flex items-center justify-between pt-1">
                <span className="text-xs text-gray-400">{t.created_at}</span>
                <div className="flex gap-2">
                    {!t.is_approved && (
                        <button
                            type="button"
                            onClick={onApprove}
                            className="text-xs px-3 py-1 rounded-lg bg-green-600 text-white hover:bg-green-700 transition-colors"
                        >
                            Approve
                        </button>
                    )}
                    <button
                        type="button"
                        onClick={onDelete}
                        className="text-xs px-3 py-1 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 dark:bg-red-900/20 dark:text-red-400 dark:hover:bg-red-900/40 transition-colors"
                    >
                        Delete
                    </button>
                </div>
            </div>
        </div>
    );
}

export default function TestimonialsIndex({ testimonials }: { testimonials: Testimonial[] }) {
    const pending  = testimonials.filter((t) => !t.is_approved);
    const approved = testimonials.filter((t) => t.is_approved);

    const handleApprove = (id: number) => {
        router.patch(route('testimonials.approve', id));
    };

    const handleDelete = (id: number) => {
        if (!confirm('Delete this testimonial?')) return;
        router.delete(route('testimonials.destroy', id));
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Testimonials" />

            <div className="p-6 space-y-8">
                <div>
                    <h1 className="text-2xl font-bold text-gray-900 dark:text-white">Testimonials</h1>
                    <p className="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        Reviews submitted by visitors. Approve them to display on your public profile.
                    </p>
                </div>

                {testimonials.length === 0 ? (
                    <div className="flex flex-col items-center justify-center py-20 text-center">
                        <Star className="w-16 h-16 text-gray-300 dark:text-gray-600 mb-4" />
                        <h3 className="text-lg font-semibold text-gray-700 dark:text-gray-300">No testimonials yet</h3>
                        <p className="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            Share your profile's testimonial link to start collecting reviews.
                        </p>
                    </div>
                ) : (
                    <>
                        {pending.length > 0 && (
                            <section className="space-y-4">
                                <h2 className="text-sm font-semibold uppercase tracking-wider text-yellow-600 dark:text-yellow-400">
                                    Pending ({pending.length})
                                </h2>
                                <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                                    {pending.map((t) => (
                                        <TestimonialCard
                                            key={t.id}
                                            t={t}
                                            onApprove={() => handleApprove(t.id)}
                                            onDelete={() => handleDelete(t.id)}
                                        />
                                    ))}
                                </div>
                            </section>
                        )}

                        {approved.length > 0 && (
                            <section className="space-y-4">
                                <h2 className="text-sm font-semibold uppercase tracking-wider text-green-600 dark:text-green-400">
                                    Approved ({approved.length})
                                </h2>
                                <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                                    {approved.map((t) => (
                                        <TestimonialCard
                                            key={t.id}
                                            t={t}
                                            onApprove={() => handleApprove(t.id)}
                                            onDelete={() => handleDelete(t.id)}
                                        />
                                    ))}
                                </div>
                            </section>
                        )}
                    </>
                )}
            </div>
        </AppLayout>
    );
}
