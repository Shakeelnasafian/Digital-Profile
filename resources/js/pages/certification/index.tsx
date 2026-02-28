import React, { useState } from 'react';
import { Head, useForm, router } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { LoaderCircle, Plus, Pencil, Trash2, Award, ExternalLink } from 'lucide-react';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Certifications', href: '/certifications' },
];

interface Certification {
    id: number;
    title: string;
    issuer: string;
    issue_date: string;
    expiry_date?: string;
    credential_url?: string;
    credential_id?: string;
}

const emptyForm = {
    title: '',
    issuer: '',
    issue_date: '',
    expiry_date: '',
    credential_url: '',
    credential_id: '',
};

function formatDate(dateStr?: string) {
    if (!dateStr) return '';
    const d = new Date(dateStr);
    return d.toLocaleDateString('en-US', { month: 'short', year: 'numeric' });
}

export default function CertificationIndex({ certifications }: { certifications: Certification[] }) {
    const [showModal, setShowModal] = useState(false);
    const [editingCert, setEditingCert] = useState<Certification | null>(null);
    const [deletingId, setDeletingId] = useState<number | null>(null);

    const { data, setData, post, patch, processing, errors, reset } = useForm(emptyForm);

    const openCreate = () => {
        reset();
        setEditingCert(null);
        setShowModal(true);
    };

    const openEdit = (cert: Certification) => {
        setEditingCert(cert);
        setData({
            title: cert.title,
            issuer: cert.issuer,
            issue_date: cert.issue_date ? cert.issue_date.split('T')[0] : '',
            expiry_date: cert.expiry_date ? cert.expiry_date.split('T')[0] : '',
            credential_url: cert.credential_url ?? '',
            credential_id: cert.credential_id ?? '',
        });
        setShowModal(true);
    };

    const closeModal = () => {
        setShowModal(false);
        setEditingCert(null);
        reset();
    };

    const submit = (e: React.FormEvent) => {
        e.preventDefault();
        if (editingCert) {
            patch(route('certifications.update', editingCert.id), { onSuccess: closeModal });
        } else {
            post(route('certifications.store'), { onSuccess: closeModal });
        }
    };

    const handleDelete = (id: number) => {
        if (!confirm('Delete this certification?')) return;
        setDeletingId(id);
        router.delete(route('certifications.destroy', id), {
            onFinish: () => setDeletingId(null),
        });
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Certifications" />

            <div className="p-6 space-y-6">
                {/* Header */}
                <div className="flex items-center justify-between">
                    <div>
                        <h1 className="text-2xl font-bold text-gray-900 dark:text-white">Certifications</h1>
                        <p className="text-sm text-gray-500 dark:text-gray-400 mt-1">Your professional certifications and achievements</p>
                    </div>
                    <Button onClick={openCreate} className="flex items-center gap-2">
                        <Plus className="w-4 h-4" />
                        Add Certification
                    </Button>
                </div>

                {/* Grid */}
                {certifications.length === 0 ? (
                    <div className="flex flex-col items-center justify-center py-20 text-center">
                        <Award className="w-16 h-16 text-gray-300 dark:text-gray-600 mb-4" />
                        <h3 className="text-lg font-semibold text-gray-700 dark:text-gray-300">No certifications yet</h3>
                        <p className="text-sm text-gray-500 dark:text-gray-400 mt-1 mb-6">Add your certifications to showcase your expertise.</p>
                        <Button onClick={openCreate}>
                            <Plus className="w-4 h-4 mr-2" />
                            Add Your First Certification
                        </Button>
                    </div>
                ) : (
                    <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        {certifications.map((cert) => (
                            <div
                                key={cert.id}
                                className="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl p-5 hover:shadow-md transition-shadow"
                            >
                                <div className="flex items-start justify-between gap-2">
                                    <div className="w-9 h-9 rounded-lg bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center shrink-0">
                                        <Award className="w-5 h-5 text-amber-600 dark:text-amber-400" />
                                    </div>
                                    <div className="flex gap-1 shrink-0">
                                        <button
                                            onClick={() => openEdit(cert)}
                                            className="p-1.5 text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors"
                                            title="Edit"
                                        >
                                            <Pencil className="w-4 h-4" />
                                        </button>
                                        <button
                                            onClick={() => handleDelete(cert.id)}
                                            disabled={deletingId === cert.id}
                                            className="p-1.5 text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition-colors disabled:opacity-50"
                                            title="Delete"
                                        >
                                            {deletingId === cert.id ? (
                                                <LoaderCircle className="w-4 h-4 animate-spin" />
                                            ) : (
                                                <Trash2 className="w-4 h-4" />
                                            )}
                                        </button>
                                    </div>
                                </div>

                                <div className="mt-3">
                                    <h3 className="font-semibold text-gray-900 dark:text-white text-sm leading-tight">{cert.title}</h3>
                                    <p className="text-xs text-amber-600 dark:text-amber-400 font-medium mt-0.5">{cert.issuer}</p>
                                    <p className="text-xs text-gray-400 dark:text-gray-500 mt-1">
                                        Issued {formatDate(cert.issue_date)}
                                        {cert.expiry_date && ` · Expires ${formatDate(cert.expiry_date)}`}
                                    </p>
                                    {cert.credential_id && (
                                        <p className="text-xs text-gray-400 dark:text-gray-500 mt-0.5 font-mono">
                                            ID: {cert.credential_id}
                                        </p>
                                    )}
                                </div>

                                {cert.credential_url && (
                                    <a
                                        href={cert.credential_url}
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        className="mt-3 inline-flex items-center gap-1 text-xs text-blue-600 dark:text-blue-400 hover:underline"
                                    >
                                        <ExternalLink className="w-3 h-3" />
                                        Verify Certificate
                                    </a>
                                )}
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
                                {editingCert ? 'Edit Certification' : 'Add Certification'}
                            </h2>
                        </div>

                        <form onSubmit={submit} className="p-6 space-y-4">
                            <div>
                                <Label htmlFor="title">Certification Title *</Label>
                                <Input
                                    id="title"
                                    value={data.title}
                                    onChange={(e) => setData('title', e.target.value)}
                                    placeholder="AWS Solutions Architect"
                                    autoComplete="off"
                                />
                                {errors.title && <p className="text-xs text-red-500 mt-1">{errors.title}</p>}
                            </div>

                            <div>
                                <Label htmlFor="issuer">Issuing Organisation *</Label>
                                <Input
                                    id="issuer"
                                    value={data.issuer}
                                    onChange={(e) => setData('issuer', e.target.value)}
                                    placeholder="Amazon Web Services"
                                    autoComplete="off"
                                />
                                {errors.issuer && <p className="text-xs text-red-500 mt-1">{errors.issuer}</p>}
                            </div>

                            <div className="grid grid-cols-2 gap-4">
                                <div>
                                    <Label htmlFor="issue_date">Issue Date *</Label>
                                    <Input
                                        id="issue_date"
                                        type="date"
                                        value={data.issue_date}
                                        onChange={(e) => setData('issue_date', e.target.value)}
                                    />
                                    {errors.issue_date && <p className="text-xs text-red-500 mt-1">{errors.issue_date}</p>}
                                </div>
                                <div>
                                    <Label htmlFor="expiry_date">Expiry Date</Label>
                                    <Input
                                        id="expiry_date"
                                        type="date"
                                        value={data.expiry_date}
                                        onChange={(e) => setData('expiry_date', e.target.value)}
                                    />
                                    {errors.expiry_date && <p className="text-xs text-red-500 mt-1">{errors.expiry_date}</p>}
                                </div>
                            </div>

                            <div>
                                <Label htmlFor="credential_url">Credential URL</Label>
                                <Input
                                    id="credential_url"
                                    type="url"
                                    value={data.credential_url}
                                    onChange={(e) => setData('credential_url', e.target.value)}
                                    placeholder="https://..."
                                    autoComplete="off"
                                />
                                {errors.credential_url && <p className="text-xs text-red-500 mt-1">{errors.credential_url}</p>}
                            </div>

                            <div>
                                <Label htmlFor="credential_id">Credential ID</Label>
                                <Input
                                    id="credential_id"
                                    value={data.credential_id}
                                    onChange={(e) => setData('credential_id', e.target.value)}
                                    placeholder="ABC-12345"
                                    autoComplete="off"
                                />
                            </div>

                            <div className="flex gap-3 pt-2">
                                <Button type="button" variant="outline" onClick={closeModal} className="flex-1">
                                    Cancel
                                </Button>
                                <Button type="submit" disabled={processing} className="flex-1">
                                    {processing && <LoaderCircle className="w-4 h-4 animate-spin mr-2" />}
                                    {editingCert ? 'Update' : 'Add Certification'}
                                </Button>
                            </div>
                        </form>
                    </div>
                </div>
            )}
        </AppLayout>
    );
}
