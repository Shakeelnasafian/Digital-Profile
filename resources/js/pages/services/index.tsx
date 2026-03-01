import React, { useState } from 'react';
import { Head, useForm, router } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { LoaderCircle, Plus, Pencil, Trash2, Package } from 'lucide-react';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Services', href: '/services' },
];

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

const emptyForm = {
    title:          '',
    description:    '',
    starting_price: '',
    currency:       'USD',
    cta_label:      '',
    cta_url:        '',
    sort_order:     0,
};

const CURRENCIES = ['USD', 'GBP', 'EUR', 'AED', 'CAD', 'AUD'];

export default function ServicesIndex({ services }: { services: Service[] }) {
    const [showModal, setShowModal] = useState(false);
    const [editingService, setEditingService] = useState<Service | null>(null);
    const [deletingId, setDeletingId] = useState<number | null>(null);

    const { data, setData, post, patch, processing, errors, reset } = useForm(emptyForm);

    const openCreate = () => {
        reset();
        setEditingService(null);
        setShowModal(true);
    };

    const openEdit = (svc: Service) => {
        setEditingService(svc);
        setData({
            title:          svc.title,
            description:    svc.description ?? '',
            starting_price: svc.starting_price != null ? String(svc.starting_price) : '',
            currency:       svc.currency,
            cta_label:      svc.cta_label ?? '',
            cta_url:        svc.cta_url ?? '',
            sort_order:     svc.sort_order,
        });
        setShowModal(true);
    };

    const closeModal = () => {
        setShowModal(false);
        setEditingService(null);
        reset();
    };

    const submit = (e: React.FormEvent) => {
        e.preventDefault();
        if (editingService) {
            patch(route('services.update', editingService.id), { onSuccess: closeModal });
        } else {
            post(route('services.store'), { onSuccess: closeModal });
        }
    };

    const handleDelete = (id: number) => {
        if (!confirm('Delete this service?')) return;
        setDeletingId(id);
        router.delete(route('services.destroy', id), { onFinish: () => setDeletingId(null) });
    };

    const formatPrice = (svc: Service) => {
        if (svc.starting_price == null || svc.starting_price === '') return null;
        return `From ${svc.currency} ${parseFloat(String(svc.starting_price)).toLocaleString(undefined, { minimumFractionDigits: 0, maximumFractionDigits: 2 })}`;
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Services" />

            <div className="p-6 space-y-6">
                <div className="flex items-center justify-between">
                    <div>
                        <h1 className="text-2xl font-bold text-gray-900 dark:text-white">Services</h1>
                        <p className="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            Services you offer — displayed as a card grid on your public profile
                        </p>
                    </div>
                    <Button onClick={openCreate} className="flex items-center gap-2">
                        <Plus className="w-4 h-4" />
                        Add Service
                    </Button>
                </div>

                {services.length === 0 ? (
                    <div className="flex flex-col items-center justify-center py-20 text-center">
                        <Package className="w-16 h-16 text-gray-300 dark:text-gray-600 mb-4" />
                        <h3 className="text-lg font-semibold text-gray-700 dark:text-gray-300">No services yet</h3>
                        <p className="text-sm text-gray-500 dark:text-gray-400 mt-1 mb-6">
                            Add the services you offer so visitors can see what you do and book you.
                        </p>
                        <Button onClick={openCreate}>
                            <Plus className="w-4 h-4 mr-2" />
                            Add Your First Service
                        </Button>
                    </div>
                ) : (
                    <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        {services.map((svc) => (
                            <div
                                key={svc.id}
                                className="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl p-5 flex flex-col gap-3 hover:shadow-md transition-shadow"
                            >
                                <div className="flex items-start justify-between gap-2">
                                    <h3 className="font-semibold text-gray-900 dark:text-white">{svc.title}</h3>
                                    <div className="flex gap-1 shrink-0">
                                        <button
                                            type="button"
                                            onClick={() => openEdit(svc)}
                                            className="p-1.5 text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors"
                                            title="Edit"
                                        >
                                            <Pencil className="w-4 h-4" />
                                        </button>
                                        <button
                                            type="button"
                                            onClick={() => handleDelete(svc.id)}
                                            disabled={deletingId === svc.id}
                                            className="p-1.5 text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition-colors disabled:opacity-50"
                                            title="Delete"
                                        >
                                            {deletingId === svc.id ? (
                                                <LoaderCircle className="w-4 h-4 animate-spin" />
                                            ) : (
                                                <Trash2 className="w-4 h-4" />
                                            )}
                                        </button>
                                    </div>
                                </div>

                                {svc.description && (
                                    <p className="text-sm text-gray-600 dark:text-gray-400 leading-relaxed line-clamp-3">
                                        {svc.description}
                                    </p>
                                )}

                                {formatPrice(svc) && (
                                    <span className="inline-block text-xs font-medium bg-indigo-50 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-300 px-2.5 py-1 rounded-full w-fit">
                                        {formatPrice(svc)}
                                    </span>
                                )}

                                {svc.cta_url && (
                                    <a
                                        href={svc.cta_url}
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        className="mt-auto text-sm text-center bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg py-2 px-4 transition-colors"
                                    >
                                        {svc.cta_label || 'Get Started'}
                                    </a>
                                )}
                            </div>
                        ))}
                    </div>
                )}
            </div>

            {showModal && (
                <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
                    <div className="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
                        <div className="p-6 border-b border-gray-100 dark:border-gray-800">
                            <h2 className="text-lg font-semibold text-gray-900 dark:text-white">
                                {editingService ? 'Edit Service' : 'Add Service'}
                            </h2>
                        </div>

                        <form onSubmit={submit} className="p-6 space-y-4">
                            <div>
                                <Label htmlFor="title">Title *</Label>
                                <Input
                                    id="title"
                                    value={data.title}
                                    onChange={(e) => setData('title', e.target.value)}
                                    placeholder="Web Development"
                                    autoComplete="off"
                                />
                                {errors.title && <p className="text-xs text-red-500 mt-1">{errors.title}</p>}
                            </div>

                            <div>
                                <Label htmlFor="description">Description</Label>
                                <Textarea
                                    id="description"
                                    value={data.description}
                                    onChange={(e) => setData('description', e.target.value)}
                                    placeholder="What you offer, technologies, deliverables..."
                                    rows={3}
                                />
                            </div>

                            <div className="grid grid-cols-2 gap-4">
                                <div>
                                    <Label htmlFor="starting_price">Starting Price</Label>
                                    <Input
                                        id="starting_price"
                                        type="number"
                                        min={0}
                                        step="0.01"
                                        value={data.starting_price}
                                        onChange={(e) => setData('starting_price', e.target.value)}
                                        placeholder="500"
                                    />
                                    {errors.starting_price && <p className="text-xs text-red-500 mt-1">{errors.starting_price}</p>}
                                </div>
                                <div>
                                    <Label htmlFor="currency">Currency</Label>
                                    <select
                                        id="currency"
                                        value={data.currency}
                                        onChange={(e) => setData('currency', e.target.value)}
                                        className="w-full h-9 rounded-md border border-input bg-background px-3 py-1 text-sm shadow-sm focus:outline-none focus:ring-1 focus:ring-ring"
                                    >
                                        {CURRENCIES.map((c) => <option key={c} value={c}>{c}</option>)}
                                    </select>
                                </div>
                            </div>

                            <div className="grid grid-cols-2 gap-4">
                                <div>
                                    <Label htmlFor="cta_label">Button Label</Label>
                                    <Input
                                        id="cta_label"
                                        value={data.cta_label}
                                        onChange={(e) => setData('cta_label', e.target.value)}
                                        placeholder="Book Now"
                                        autoComplete="off"
                                    />
                                </div>
                                <div>
                                    <Label htmlFor="cta_url">Button URL</Label>
                                    <Input
                                        id="cta_url"
                                        type="url"
                                        value={data.cta_url}
                                        onChange={(e) => setData('cta_url', e.target.value)}
                                        placeholder="https://calendly.com/..."
                                        autoComplete="off"
                                    />
                                    {errors.cta_url && <p className="text-xs text-red-500 mt-1">{errors.cta_url}</p>}
                                </div>
                            </div>

                            <div>
                                <Label htmlFor="sort_order">Sort Order</Label>
                                <Input
                                    id="sort_order"
                                    type="number"
                                    min={0}
                                    value={data.sort_order}
                                    onChange={(e) => setData('sort_order', parseInt(e.target.value) || 0)}
                                />
                            </div>

                            <div className="flex gap-3 pt-2">
                                <Button type="button" variant="outline" onClick={closeModal} className="flex-1">
                                    Cancel
                                </Button>
                                <Button type="submit" disabled={processing} className="flex-1">
                                    {processing && <LoaderCircle className="w-4 h-4 animate-spin mr-2" />}
                                    {editingService ? 'Update' : 'Add Service'}
                                </Button>
                            </div>
                        </form>
                    </div>
                </div>
            )}
        </AppLayout>
    );
}
