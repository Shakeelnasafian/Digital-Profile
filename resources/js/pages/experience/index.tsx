import React, { useState } from 'react';
import { Head, useForm, router } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { LoaderCircle, Plus, Pencil, Trash2, Briefcase } from 'lucide-react';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Experience', href: '/experience' },
];

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

const emptyForm = {
    company: '',
    position: '',
    location: '',
    start_date: '',
    end_date: '',
    is_current: false,
    description: '',
};

function formatDate(dateStr?: string) {
    if (!dateStr) return '';
    const d = new Date(dateStr);
    return d.toLocaleDateString('en-US', { month: 'short', year: 'numeric' });
}

export default function ExperienceIndex({ experiences }: { experiences: Experience[] }) {
    const [showModal, setShowModal] = useState(false);
    const [editingExp, setEditingExp] = useState<Experience | null>(null);
    const [deletingId, setDeletingId] = useState<number | null>(null);

    const { data, setData, post, patch, processing, errors, reset } = useForm(emptyForm);

    const openCreate = () => {
        reset();
        setEditingExp(null);
        setShowModal(true);
    };

    const openEdit = (exp: Experience) => {
        setEditingExp(exp);
        setData({
            company: exp.company,
            position: exp.position,
            location: exp.location ?? '',
            start_date: exp.start_date ? exp.start_date.split('T')[0] : '',
            end_date: exp.end_date ? exp.end_date.split('T')[0] : '',
            is_current: exp.is_current,
            description: exp.description ?? '',
        });
        setShowModal(true);
    };

    const closeModal = () => {
        setShowModal(false);
        setEditingExp(null);
        reset();
    };

    const submit = (e: React.FormEvent) => {
        e.preventDefault();
        if (editingExp) {
            patch(route('experience.update', editingExp.id), {
                onSuccess: closeModal,
            });
        } else {
            post(route('experience.store'), {
                onSuccess: closeModal,
            });
        }
    };

    const handleDelete = (id: number) => {
        if (!confirm('Delete this experience?')) return;
        setDeletingId(id);
        router.delete(route('experience.destroy', id), {
            onFinish: () => setDeletingId(null),
        });
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Experience" />

            <div className="p-6 space-y-6">
                {/* Header */}
                <div className="flex items-center justify-between">
                    <div>
                        <h1 className="text-2xl font-bold text-gray-900 dark:text-white">Work Experience</h1>
                        <p className="text-sm text-gray-500 dark:text-gray-400 mt-1">Your professional history and career journey</p>
                    </div>
                    <Button onClick={openCreate} className="flex items-center gap-2">
                        <Plus className="w-4 h-4" />
                        Add Experience
                    </Button>
                </div>

                {/* Timeline */}
                {experiences.length === 0 ? (
                    <div className="flex flex-col items-center justify-center py-20 text-center">
                        <Briefcase className="w-16 h-16 text-gray-300 dark:text-gray-600 mb-4" />
                        <h3 className="text-lg font-semibold text-gray-700 dark:text-gray-300">No experience added yet</h3>
                        <p className="text-sm text-gray-500 dark:text-gray-400 mt-1 mb-6">Add your work history to build a complete professional profile.</p>
                        <Button onClick={openCreate}>
                            <Plus className="w-4 h-4 mr-2" />
                            Add Your First Experience
                        </Button>
                    </div>
                ) : (
                    <div className="relative space-y-0">
                        {/* Vertical line */}
                        <div className="absolute left-5 top-6 bottom-6 w-0.5 bg-gray-200 dark:bg-gray-700 hidden sm:block" />

                        {experiences.map((exp, idx) => (
                            <div key={exp.id} className="relative flex gap-4 pb-6">
                                {/* Dot */}
                                <div className="relative z-10 hidden sm:flex">
                                    <div className="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center shrink-0 mt-1">
                                        <Briefcase className="w-4 h-4 text-white" />
                                    </div>
                                </div>

                                <div className="flex-1 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl p-5 hover:shadow-md transition-shadow">
                                    <div className="flex items-start justify-between gap-3">
                                        <div className="min-w-0">
                                            <h3 className="font-semibold text-gray-900 dark:text-white">{exp.position}</h3>
                                            <p className="text-sm font-medium text-blue-600 dark:text-blue-400">{exp.company}</p>
                                            {exp.location && (
                                                <p className="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{exp.location}</p>
                                            )}
                                            <p className="text-xs text-gray-400 dark:text-gray-500 mt-1">
                                                {formatDate(exp.start_date)} → {exp.is_current ? 'Present' : formatDate(exp.end_date)}
                                                {exp.is_current && (
                                                    <span className="ml-2 bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 text-xs px-1.5 py-0.5 rounded-full">
                                                        Current
                                                    </span>
                                                )}
                                            </p>
                                        </div>
                                        <div className="flex gap-1 shrink-0">
                                            <button
                                                onClick={() => openEdit(exp)}
                                                className="p-1.5 text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors"
                                                title="Edit"
                                            >
                                                <Pencil className="w-4 h-4" />
                                            </button>
                                            <button
                                                onClick={() => handleDelete(exp.id)}
                                                disabled={deletingId === exp.id}
                                                className="p-1.5 text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition-colors disabled:opacity-50"
                                                title="Delete"
                                            >
                                                {deletingId === exp.id ? (
                                                    <LoaderCircle className="w-4 h-4 animate-spin" />
                                                ) : (
                                                    <Trash2 className="w-4 h-4" />
                                                )}
                                            </button>
                                        </div>
                                    </div>

                                    {exp.description && (
                                        <p className="text-sm text-gray-600 dark:text-gray-400 mt-3 leading-relaxed">
                                            {exp.description}
                                        </p>
                                    )}
                                </div>
                            </div>
                        ))}
                    </div>
                )}
            </div>

            {/* Modal */}
            {showModal && (
                <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
                    <div className="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
                        <div className="p-6 border-b border-gray-100 dark:border-gray-800">
                            <h2 className="text-lg font-semibold text-gray-900 dark:text-white">
                                {editingExp ? 'Edit Experience' : 'Add Experience'}
                            </h2>
                        </div>

                        <form onSubmit={submit} className="p-6 space-y-4">
                            <div className="grid grid-cols-2 gap-4">
                                <div className="col-span-2 sm:col-span-1">
                                    <Label htmlFor="position">Position / Job Title *</Label>
                                    <Input
                                        id="position"
                                        value={data.position}
                                        onChange={(e) => setData('position', e.target.value)}
                                        placeholder="Senior Developer"
                                        autoComplete="off"
                                    />
                                    {errors.position && <p className="text-xs text-red-500 mt-1">{errors.position}</p>}
                                </div>
                                <div className="col-span-2 sm:col-span-1">
                                    <Label htmlFor="company">Company *</Label>
                                    <Input
                                        id="company"
                                        value={data.company}
                                        onChange={(e) => setData('company', e.target.value)}
                                        placeholder="Acme Corp"
                                        autoComplete="off"
                                    />
                                    {errors.company && <p className="text-xs text-red-500 mt-1">{errors.company}</p>}
                                </div>
                            </div>

                            <div>
                                <Label htmlFor="location">Location</Label>
                                <Input
                                    id="location"
                                    value={data.location}
                                    onChange={(e) => setData('location', e.target.value)}
                                    placeholder="Dubai, UAE"
                                    autoComplete="off"
                                />
                            </div>

                            <div className="grid grid-cols-2 gap-4">
                                <div>
                                    <Label htmlFor="start_date">Start Date *</Label>
                                    <Input
                                        id="start_date"
                                        type="date"
                                        value={data.start_date}
                                        onChange={(e) => setData('start_date', e.target.value)}
                                    />
                                    {errors.start_date && <p className="text-xs text-red-500 mt-1">{errors.start_date}</p>}
                                </div>
                                <div>
                                    <Label htmlFor="end_date">End Date</Label>
                                    <Input
                                        id="end_date"
                                        type="date"
                                        value={data.end_date}
                                        onChange={(e) => setData('end_date', e.target.value)}
                                        disabled={data.is_current}
                                    />
                                </div>
                            </div>

                            <div className="flex items-center gap-2">
                                <input
                                    id="is_current"
                                    type="checkbox"
                                    checked={data.is_current}
                                    onChange={(e) => {
                                        setData('is_current', e.target.checked);
                                        if (e.target.checked) setData('end_date', '');
                                    }}
                                    className="rounded border-gray-300"
                                />
                                <Label htmlFor="is_current" className="cursor-pointer">I currently work here</Label>
                            </div>

                            <div>
                                <Label htmlFor="description">Description</Label>
                                <Textarea
                                    id="description"
                                    value={data.description}
                                    onChange={(e) => setData('description', e.target.value)}
                                    placeholder="Describe your responsibilities and achievements..."
                                    rows={3}
                                />
                            </div>

                            <div className="flex gap-3 pt-2">
                                <Button type="button" variant="outline" onClick={closeModal} className="flex-1">
                                    Cancel
                                </Button>
                                <Button type="submit" disabled={processing} className="flex-1">
                                    {processing && <LoaderCircle className="w-4 h-4 animate-spin mr-2" />}
                                    {editingExp ? 'Update' : 'Add Experience'}
                                </Button>
                            </div>
                        </form>
                    </div>
                </div>
            )}
        </AppLayout>
    );
}
