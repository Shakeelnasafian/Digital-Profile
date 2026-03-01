import { useState } from 'react';
import { Head, useForm, usePage } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { Star, LoaderCircle, CheckCircle2 } from 'lucide-react';

interface Props {
    profile_name: string;
    slug: string;
}

function StarPicker({ value, onChange }: { value: number; onChange: (v: number) => void }) {
    const [hovered, setHovered] = useState(0);

    return (
        <div className="flex gap-1">
            {[1, 2, 3, 4, 5].map((s) => (
                <button
                    key={s}
                    type="button"
                    onMouseEnter={() => setHovered(s)}
                    onMouseLeave={() => setHovered(0)}
                    onClick={() => onChange(s)}
                    className="focus:outline-none"
                    aria-label={`${s} star${s > 1 ? 's' : ''}`}
                >
                    <Star
                        className={`w-8 h-8 transition-colors ${
                            s <= (hovered || value)
                                ? 'fill-yellow-400 text-yellow-400'
                                : 'text-gray-300 dark:text-gray-600'
                        }`}
                    />
                </button>
            ))}
        </div>
    );
}

export default function TestimonialSubmit({ profile_name, slug }: Props) {
    const { props } = usePage<{ flash?: { success?: string } }>();
    const flash = props.flash;
    const submitted = !!flash?.success;

    const { data, setData, post, processing, errors } = useForm({
        reviewer_name:    '',
        reviewer_title:   '',
        reviewer_company: '',
        content:          '',
        rating:           5,
    });

    const submit = (e: React.FormEvent) => {
        e.preventDefault();
        post(route('testimonial.store', slug));
    };

    return (
        <>
            <Head title={`Leave a testimonial for ${profile_name}`} />

            <div className="min-h-screen flex items-center justify-center bg-gray-50 dark:bg-gray-950 p-4">
                <div className="w-full max-w-lg bg-white dark:bg-gray-900 rounded-2xl shadow-xl p-8">
                    {submitted ? (
                        <div className="flex flex-col items-center text-center py-8 space-y-4">
                            <CheckCircle2 className="w-16 h-16 text-green-500" />
                            <h2 className="text-2xl font-bold text-gray-900 dark:text-white">Thank you!</h2>
                            <p className="text-gray-500 dark:text-gray-400">
                                Your testimonial is pending review and will appear on {profile_name}'s profile once approved.
                            </p>
                        </div>
                    ) : (
                        <>
                            <div className="mb-6 text-center">
                                <h1 className="text-2xl font-bold text-gray-900 dark:text-white">Leave a Testimonial</h1>
                                <p className="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                    for <span className="font-medium text-indigo-600 dark:text-indigo-400">{profile_name}</span>
                                </p>
                            </div>

                            <form onSubmit={submit} className="space-y-4">
                                <div>
                                    <Label htmlFor="reviewer_name">Your Name *</Label>
                                    <Input
                                        id="reviewer_name"
                                        value={data.reviewer_name}
                                        onChange={(e) => setData('reviewer_name', e.target.value)}
                                        placeholder="Jane Smith"
                                        autoComplete="name"
                                    />
                                    {errors.reviewer_name && <p className="text-xs text-red-500 mt-1">{errors.reviewer_name}</p>}
                                </div>

                                <div className="grid grid-cols-2 gap-4">
                                    <div>
                                        <Label htmlFor="reviewer_title">Your Title</Label>
                                        <Input
                                            id="reviewer_title"
                                            value={data.reviewer_title}
                                            onChange={(e) => setData('reviewer_title', e.target.value)}
                                            placeholder="CEO"
                                            autoComplete="off"
                                        />
                                    </div>
                                    <div>
                                        <Label htmlFor="reviewer_company">Company</Label>
                                        <Input
                                            id="reviewer_company"
                                            value={data.reviewer_company}
                                            onChange={(e) => setData('reviewer_company', e.target.value)}
                                            placeholder="Acme Corp"
                                            autoComplete="organization"
                                        />
                                    </div>
                                </div>

                                <div>
                                    <Label className="block mb-2">Rating *</Label>
                                    <StarPicker value={data.rating} onChange={(v) => setData('rating', v)} />
                                    {errors.rating && <p className="text-xs text-red-500 mt-1">{errors.rating}</p>}
                                </div>

                                <div>
                                    <Label htmlFor="content">Testimonial *</Label>
                                    <Textarea
                                        id="content"
                                        value={data.content}
                                        onChange={(e) => setData('content', e.target.value)}
                                        placeholder="Share your experience working with this person..."
                                        rows={5}
                                    />
                                    {errors.content && <p className="text-xs text-red-500 mt-1">{errors.content}</p>}
                                </div>

                                <Button type="submit" disabled={processing} className="w-full">
                                    {processing && <LoaderCircle className="w-4 h-4 animate-spin mr-2" />}
                                    Submit Testimonial
                                </Button>
                            </form>
                        </>
                    )}
                </div>
            </div>
        </>
    );
}
