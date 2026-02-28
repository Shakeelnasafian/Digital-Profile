import React, { useState } from 'react';
import { Head, useForm, router } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { LoaderCircle, Plus, Pencil, Trash2, GraduationCap } from 'lucide-react';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Education', href: '/education' },
];

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

const emptyForm = {
    institution: '',
    degree: '',
    field_of_study: '',
    start_year: new Date().getFullYear(),
    end_year: '',
    is_current: false,
    description: '',
};

export default function EducationIndex({ educations }: { educations: Education[] }) {
    const [showModal, setShowModal] = useState(false);
    const [editingEdu, setEditingEdu] = useState<Education | null>(null);
    const [deletingId, setDeletingId] = useState<number | null>(null);

    const { data, setData, post, patch, processing, errors, reset } = useForm(emptyForm);

    const openCreate = () => {
        reset();
        setEditingEdu(null);
        setShowModal(true);
    };

    const openEdit = (edu: Education) => {
        setEditingEdu(edu);
        setData({
            institution: edu.institution,
            degree: edu.degree,
            field_of_study: edu.field_of_study ?? '',
            start_year: edu.start_year,
            end_year: edu.end_year ?? '',
            is_current: edu.is_current,
            description: edu.description ?? '',
        });
        setShowModal(true);
    };

    const closeModal = () => {
        setShowModal(false);
        setEditingEdu(null);
        reset();
    };

    const submit = (e: React.FormEvent) => {
        e.preventDefault();
        if (editingEdu) {
            patch(route('education.update', editingEdu.id), { onSuccess: closeModal });
        } else {
            post(route('education.store'), { onSuccess: closeModal });
        }
    };

    const handleDelete = (id: number) => {
        if (!confirm('Delete this education entry?')) return;
        setDeletingId(id);
        router.delete(route('education.destroy', id), {
            onFinish: () => setDeletingId(null),
        });
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Education" />

            <div className="p-6 space-y-6">
                {/* Header */}
                <div className="flex items-center justify-between">
                    <div>
                        <h1 className="text-2xl font-bold text-gray-900 dark:text-white">Education</h1>
                        <p className="text-sm text-gray-500 dark:text-gray-400 mt-1">Your academic background and qualifications</p>
                    </div>
                    <Button onClick={openCreate} className="flex items-center gap-2">
                        <Plus className="w-4 h-4" />
                        Add Education
                    </Button>
                </div>

                {/* Timeline */}
                {educations.length === 0 ? (
                    <div className="flex flex-col items-center justify-center py-20 text-center">
                        <GraduationCap className="w-16 h-16 text-gray-300 dark:text-gray-600 mb-4" />
                        <h3 className="text-lg font-semibold text-gray-700 dark:text-gray-300">No education added yet</h3>
                        <p className="text-sm text-gray-500 dark:text-gray-400 mt-1 mb-6">Add your academic history to complete your profile.</p>
                        <Button onClick={openCreate}>
                            <Plus className="w-4 h-4 mr-2" />
                            Add Your First Education
                        </Button>
                    </div>
                ) : (
                    <div className="relative space-y-0">
                        <div className="absolute left-5 top-6 bottom-6 w-0.5 bg-gray-200 dark:bg-gray-700 hidden sm:block" />

                        {educations.map((edu) => (
                            <div key={edu.id} className="relative flex gap-4 pb-6">
                                <div className="relative z-10 hidden sm:flex">
                                    <div className="w-10 h-10 rounded-full bg-indigo-600 flex items-center justify-center shrink-0 mt-1">
                                        <GraduationCap className="w-4 h-4 text-white" />
                                    </div>
                                </div>

                                <div className="flex-1 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl p-5 hover:shadow-md transition-shadow">
                                    <div className="flex items-start justify-between gap-3">
                                        <div className="min-w-0">
                                            <h3 className="font-semibold text-gray-900 dark:text-white">{edu.degree}</h3>
                                            {edu.field_of_study && (
                                                <p className="text-sm text-indigo-600 dark:text-indigo-400">{edu.field_of_study}</p>
                                            )}
                                            <p className="text-sm font-medium text-gray-700 dark:text-gray-300">{edu.institution}</p>
                                            <p className="text-xs text-gray-400 dark:text-gray-500 mt-1">
                                                {edu.start_year} → {edu.is_current ? 'Present' : (edu.end_year ?? '—')}
                                                {edu.is_current && (
                                                    <span className="ml-2 bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 text-xs px-1.5 py-0.5 rounded-full">
                                                        Current
                                                    </span>
                                                )}
                                            </p>
                                        </div>
                                        <div className="flex gap-1 shrink-0">
                                            <button
                                                onClick={() => openEdit(edu)}
                                                className="p-1.5 text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors"
                                                title="Edit"
                                            >
                                                <Pencil className="w-4 h-4" />
                                            </button>
                                            <button
                                                onClick={() => handleDelete(edu.id)}
                                                disabled={deletingId === edu.id}
                                                className="p-1.5 text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition-colors disabled:opacity-50"
                                                title="Delete"
                                            >
                                                {deletingId === edu.id ? (
                                                    <LoaderCircle className="w-4 h-4 animate-spin" />
                                                ) : (
                                                    <Trash2 className="w-4 h-4" />
                                                )}
                                            </button>
                                        </div>
                                    </div>

                                    {edu.description && (
                                        <p className="text-sm text-gray-600 dark:text-gray-400 mt-3 leading-relaxed">
                                            {edu.description}
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
                                {editingEdu ? 'Edit Education' : 'Add Education'}
                            </h2>
                        </div>

                        <form onSubmit={submit} className="p-6 space-y-4">
                            <div>
                                <Label htmlFor="institution">Institution *</Label>
                                <Input
                                    id="institution"
                                    value={data.institution}
                                    onChange={(e) => setData('institution', e.target.value)}
                                    placeholder="University of Oxford"
                                    autoComplete="off"
                                />
                                {errors.institution && <p className="text-xs text-red-500 mt-1">{errors.institution}</p>}
                            </div>

                            <div className="grid grid-cols-2 gap-4">
                                <div>
                                    <Label htmlFor="degree">Degree *</Label>
                                    <Input
                                        id="degree"
                                        value={data.degree}
                                        onChange={(e) => setData('degree', e.target.value)}
                                        placeholder="Bachelor of Science"
                                        autoComplete="off"
                                    />
                                    {errors.degree && <p className="text-xs text-red-500 mt-1">{errors.degree}</p>}
                                </div>
                                <div>
                                    <Label htmlFor="field_of_study">Field of Study</Label>
                                    <Input
                                        id="field_of_study"
                                        value={data.field_of_study}
                                        onChange={(e) => setData('field_of_study', e.target.value)}
                                        placeholder="Computer Science"
                                        autoComplete="off"
                                    />
                                </div>
                            </div>

                            <div className="grid grid-cols-2 gap-4">
                                <div>
                                    <Label htmlFor="start_year">Start Year *</Label>
                                    <Input
                                        id="start_year"
                                        type="number"
                                        min={1950}
                                        max={2035}
                                        value={data.start_year}
                                        onChange={(e) => setData('start_year', parseInt(e.target.value))}
                                    />
                                    {errors.start_year && <p className="text-xs text-red-500 mt-1">{errors.start_year}</p>}
                                </div>
                                <div>
                                    <Label htmlFor="end_year">End Year</Label>
                                    <Input
                                        id="end_year"
                                        type="number"
                                        min={1950}
                                        max={2035}
                                        value={data.end_year}
                                        onChange={(e) => setData('end_year', e.target.value)}
                                        disabled={data.is_current}
                                    />
                                    {errors.end_year && <p className="text-xs text-red-500 mt-1">{errors.end_year}</p>}
                                </div>
                            </div>

                            <div className="flex items-center gap-2">
                                <input
                                    id="is_current"
                                    type="checkbox"
                                    checked={data.is_current}
                                    onChange={(e) => {
                                        setData('is_current', e.target.checked);
                                        if (e.target.checked) setData('end_year', '');
                                    }}
                                    className="rounded border-gray-300"
                                />
                                <Label htmlFor="is_current" className="cursor-pointer">I am currently studying here</Label>
                            </div>

                            <div>
                                <Label htmlFor="description">Description</Label>
                                <Textarea
                                    id="description"
                                    value={data.description}
                                    onChange={(e) => setData('description', e.target.value)}
                                    placeholder="Thesis topic, achievements, activities..."
                                    rows={3}
                                />
                            </div>

                            <div className="flex gap-3 pt-2">
                                <Button type="button" variant="outline" onClick={closeModal} className="flex-1">
                                    Cancel
                                </Button>
                                <Button type="submit" disabled={processing} className="flex-1">
                                    {processing && <LoaderCircle className="w-4 h-4 animate-spin mr-2" />}
                                    {editingEdu ? 'Update' : 'Add Education'}
                                </Button>
                            </div>
                        </form>
                    </div>
                </div>
            )}
        </AppLayout>
    );
}
