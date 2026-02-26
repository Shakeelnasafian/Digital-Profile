import React, { useState } from 'react';
import { Head, useForm, router } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { LoaderCircle, Plus, Pencil, Trash2, ExternalLink, FolderOpen } from 'lucide-react';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Projects', href: '/projects' },
];

interface Project {
    id: number;
    name: string;
    description?: string;
    project_url?: string;
    start_date?: string;
    end_date?: string;
    status: 'planned' | 'ongoing' | 'completed';
    created_at: string;
}

const statusColors: Record<string, string> = {
    planned: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
    ongoing: 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
    completed: 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
};

const emptyForm = {
    name: '',
    description: '',
    project_url: '',
    start_date: '',
    end_date: '',
    status: 'ongoing' as const,
};

export default function ProjectsIndex({ projects }: { projects: Project[] }) {
    const [showModal, setShowModal] = useState(false);
    const [editingProject, setEditingProject] = useState<Project | null>(null);
    const [deletingId, setDeletingId] = useState<number | null>(null);

    const { data, setData, post, patch, processing, errors, reset } = useForm(emptyForm);

    const openCreate = () => {
        reset();
        setEditingProject(null);
        setShowModal(true);
    };

    const openEdit = (project: Project) => {
        setEditingProject(project);
        setData({
            name: project.name,
            description: project.description ?? '',
            project_url: project.project_url ?? '',
            start_date: project.start_date ?? '',
            end_date: project.end_date ?? '',
            status: project.status,
        });
        setShowModal(true);
    };

    const closeModal = () => {
        setShowModal(false);
        setEditingProject(null);
        reset();
    };

    const submit = (e: React.FormEvent) => {
        e.preventDefault();
        if (editingProject) {
            patch(route('projects.update', editingProject.id), {
                onSuccess: closeModal,
            });
        } else {
            post(route('projects.store'), {
                onSuccess: closeModal,
            });
        }
    };

    const handleDelete = (id: number) => {
        setDeletingId(id);
        router.delete(route('projects.destroy', id), {
            onFinish: () => setDeletingId(null),
        });
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Projects" />

            <div className="p-6 space-y-6">
                {/* Header */}
                <div className="flex items-center justify-between">
                    <div>
                        <h1 className="text-2xl font-bold text-gray-900 dark:text-white">Projects</h1>
                        <p className="text-sm text-gray-500 dark:text-gray-400 mt-1">Showcase your work and portfolio</p>
                    </div>
                    <Button onClick={openCreate} className="flex items-center gap-2">
                        <Plus className="w-4 h-4" />
                        Add Project
                    </Button>
                </div>

                {/* Projects Grid */}
                {projects.length === 0 ? (
                    <div className="flex flex-col items-center justify-center py-20 text-center">
                        <FolderOpen className="w-16 h-16 text-gray-300 dark:text-gray-600 mb-4" />
                        <h3 className="text-lg font-semibold text-gray-700 dark:text-gray-300">No projects yet</h3>
                        <p className="text-sm text-gray-500 dark:text-gray-400 mt-1 mb-6">Start adding your projects to showcase your work.</p>
                        <Button onClick={openCreate}>
                            <Plus className="w-4 h-4 mr-2" />
                            Add Your First Project
                        </Button>
                    </div>
                ) : (
                    <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        {projects.map((project) => (
                            <div
                                key={project.id}
                                className="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl p-5 flex flex-col gap-3 hover:shadow-md transition-shadow"
                            >
                                <div className="flex items-start justify-between gap-2">
                                    <h3 className="font-semibold text-gray-900 dark:text-white text-base leading-tight">{project.name}</h3>
                                    <span className={`text-xs font-medium px-2 py-0.5 rounded-full shrink-0 ${statusColors[project.status]}`}>
                                        {project.status.charAt(0).toUpperCase() + project.status.slice(1)}
                                    </span>
                                </div>

                                {project.description && (
                                    <p className="text-sm text-gray-600 dark:text-gray-400 line-clamp-3">{project.description}</p>
                                )}

                                {(project.start_date || project.end_date) && (
                                    <p className="text-xs text-gray-400 dark:text-gray-500">
                                        {project.start_date && new Date(project.start_date).toLocaleDateString('en-US', { month: 'short', year: 'numeric' })}
                                        {project.end_date && ` → ${new Date(project.end_date).toLocaleDateString('en-US', { month: 'short', year: 'numeric' })}`}
                                        {!project.end_date && project.start_date && ' → Present'}
                                    </p>
                                )}

                                <div className="flex items-center gap-2 mt-auto pt-2 border-t border-gray-100 dark:border-gray-800">
                                    {project.project_url && (
                                        <a
                                            href={project.project_url}
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            className="text-xs text-blue-600 dark:text-blue-400 flex items-center gap-1 hover:underline"
                                        >
                                            <ExternalLink className="w-3 h-3" />
                                            View Project
                                        </a>
                                    )}
                                    <div className="ml-auto flex gap-2">
                                        <button
                                            onClick={() => openEdit(project)}
                                            className="p-1.5 text-gray-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-400 transition-colors"
                                            title="Edit"
                                        >
                                            <Pencil className="w-4 h-4" />
                                        </button>
                                        <button
                                            onClick={() => handleDelete(project.id)}
                                            disabled={deletingId === project.id}
                                            className="p-1.5 text-gray-500 hover:text-red-600 dark:text-gray-400 dark:hover:text-red-400 transition-colors disabled:opacity-50"
                                            title="Delete"
                                        >
                                            {deletingId === project.id ? (
                                                <LoaderCircle className="w-4 h-4 animate-spin" />
                                            ) : (
                                                <Trash2 className="w-4 h-4" />
                                            )}
                                        </button>
                                    </div>
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
                                {editingProject ? 'Edit Project' : 'Add New Project'}
                            </h2>
                        </div>

                        <form onSubmit={submit} className="p-6 space-y-4">
                            <div>
                                <Label htmlFor="name">Project Name *</Label>
                                <Input
                                    id="name"
                                    value={data.name}
                                    onChange={(e) => setData('name', e.target.value)}
                                    placeholder="My Awesome Project"
                                    autoComplete="off"
                                />
                                {errors.name && <p className="text-xs text-red-500 mt-1">{errors.name}</p>}
                            </div>

                            <div>
                                <Label htmlFor="description">Description</Label>
                                <Textarea
                                    id="description"
                                    value={data.description}
                                    onChange={(e) => setData('description', e.target.value)}
                                    placeholder="What did you build? What problems did it solve?"
                                    rows={3}
                                />
                                {errors.description && <p className="text-xs text-red-500 mt-1">{errors.description}</p>}
                            </div>

                            <div>
                                <Label htmlFor="project_url">Project URL</Label>
                                <Input
                                    id="project_url"
                                    type="url"
                                    value={data.project_url}
                                    onChange={(e) => setData('project_url', e.target.value)}
                                    placeholder="https://github.com/you/project"
                                />
                                {errors.project_url && <p className="text-xs text-red-500 mt-1">{errors.project_url}</p>}
                            </div>

                            <div>
                                <Label htmlFor="status">Status *</Label>
                                <select
                                    id="status"
                                    value={data.status}
                                    onChange={(e) => setData('status', e.target.value as typeof data.status)}
                                    className="w-full h-10 rounded-md border border-gray-300 dark:border-gray-700 px-3 text-sm dark:bg-gray-900 dark:text-white"
                                >
                                    <option value="planned">Planned</option>
                                    <option value="ongoing">Ongoing</option>
                                    <option value="completed">Completed</option>
                                </select>
                                {errors.status && <p className="text-xs text-red-500 mt-1">{errors.status}</p>}
                            </div>

                            <div className="grid grid-cols-2 gap-4">
                                <div>
                                    <Label htmlFor="start_date">Start Date</Label>
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
                                    />
                                    {errors.end_date && <p className="text-xs text-red-500 mt-1">{errors.end_date}</p>}
                                </div>
                            </div>

                            <div className="flex gap-3 pt-2">
                                <Button type="button" variant="outline" onClick={closeModal} className="flex-1">
                                    Cancel
                                </Button>
                                <Button type="submit" disabled={processing} className="flex-1">
                                    {processing && <LoaderCircle className="w-4 h-4 animate-spin mr-2" />}
                                    {editingProject ? 'Update Project' : 'Add Project'}
                                </Button>
                            </div>
                        </form>
                    </div>
                </div>
            )}
        </AppLayout>
    );
}
