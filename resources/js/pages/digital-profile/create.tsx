import { useForm } from '@inertiajs/react'
import AppLayout from '@/layouts/app-layout'
import { Head } from '@inertiajs/react'
import { type BreadcrumbItem } from '@/types'

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Create Digital Profile', href: '/dashboard/profiles/create' },
]

export default function Create() {
    const { data, setData, post, processing, errors } = useForm({
        full_name: '',
        job_title: '',
        email: '',
        phone: '',
        whatsapp: '',
        website: '',
        linkedin: '',
        github: '',
        location: '',
        profile_image: null,
        template: 'default',
    })

    const submit = (e) => {
        e.preventDefault()
        post(route('digital-profiles.store'), {
            forceFormData: true,
        })
    }

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Create Digital Profile" />

            <form onSubmit={submit} className="space-y-6 w-fit m-5 p-6 bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800">

                <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {/* Full Name */}
                    <div>
                        <label className="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-1.5">Full Name</label>
                        <input
                            type="text"
                            value={data.full_name}
                            onChange={e => setData('full_name', e.target.value)}
                            placeholder="John Doe"
                            className="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:ring focus:ring-blue-300/10 dark:border-gray-700 dark:text-white/90 dark:placeholder:text-white/30"
                        />
                        {errors.full_name && <p className="text-sm text-red-500 mt-1">{errors.full_name}</p>}
                    </div>

                    {/* Email */}
                    <div>
                        <label className="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <div className="relative">
                            <span className="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                <svg width="18" height="18" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 4a2 2 0 012-2h12a2 2 0 012 2v0.01l-8 5-8-5V4zM2 6.08V16a2 2 0 002 2h12a2 2 0 002-2V6.08l-7.447 4.67a1 1 0 01-1.106 0L2 6.08z" />
                                </svg>
                            </span>
                            <input
                                type="email"
                                name="email"
                                value={data.email}
                                onChange={(e) => setData('email', e.target.value)}
                                className="pl-10 w-full border border-gray-300 rounded-md h-11 text-sm focus:ring-blue-500 focus:border-blue-500"
                                placeholder="info@gmail.com"
                            />
                        </div>
                    </div>

                    {/* Phone with Country Code on Left */}
                    <div>
                        <label className="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                        <div className="flex rounded-md shadow-sm">
                            <select
                                className="border border-gray-300 text-sm text-gray-700 rounded-l-md px-2 bg-white"
                                defaultValue="US"
                            >
                                <option value="US">US</option>
                                <option value="AE">UAE</option>
                                <option value="GB">UK</option>
                            </select>
                            <input
                                type="tel"
                                name="phone"
                                value={data.phone}
                                onChange={(e) => setData('phone', e.target.value)}
                                className="flex-1 border-t border-b border-r border-gray-300 rounded-r-md h-11 text-sm px-3"
                                placeholder="+1 (555) 000-0000"
                            />
                        </div>
                    </div>

                    {/* URL with "http://" Prefix */}
                    <div>
                        <label className="block text-sm font-medium text-gray-700 mb-1">URL</label>
                        <div className="flex rounded-md shadow-sm">
                            <span className="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                http://
                            </span>
                            <input
                                type="text"
                                value={data.website}
                                onChange={(e) => setData('website', e.target.value)}
                                className="flex-1 block w-full h-11 rounded-none rounded-r-md border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500"
                                placeholder="www.example.com"
                            />
                        </div>
                    </div>

                    {/* Website with Copy Button */}
                    <div>
                        <label className="block text-sm font-medium text-gray-700 mb-1">Website</label>
                        <div className="relative">
                            <input
                                type="text"
                                value={data.website}
                                onChange={(e) => setData('website', e.target.value)}
                                className="block w-full h-11 rounded-md border border-gray-300 text-sm pl-4 pr-20 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="www.tailadmin.com"
                            />
                        </div>
                    </div>

                    {/* Profile Image Upload */}
                    <div>
                        <label className="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-1.5">Profile Image</label>
                        <input
                            type="file"
                            onChange={e => setData('profile_image', e.target.files[0])}
                            className="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:border file:border-gray-200 file:rounded file:text-sm file:bg-gray-50 hover:file:bg-gray-100 dark:file:border-gray-700 dark:file:bg-gray-800 dark:file:text-white/70"
                        />
                    </div>

                    <div className="pt-4">
                        <button
                            type="submit"
                            disabled={processing}
                            className="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition"
                        >
                            Create Profile
                        </button>
                    </div>
                </div>
            </form>
        </AppLayout>
    )
}
