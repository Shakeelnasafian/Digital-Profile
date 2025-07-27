import React from 'react';
import { Head, Link } from '@inertiajs/react';
import {
    User,
    QrCode,
    Share2,
    ArrowRight,
    CheckCircle,
    Star,
    Smartphone,
    Globe,
    Users
} from 'lucide-react';

export default function HomePage() {
    return (<>
        <Head title="Home" />
        <div className="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
            {/* Navigation */}
            <nav className="bg-white shadow-sm border-b border-gray-200">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="flex justify-between items-center h-16">
                        <div className="flex items-center space-x-2">
                            <QrCode className="h-8 w-8 text-blue-600" />
                            <span className="text-xl font-bold text-gray-900">ProfileCard</span>
                        </div>
                        <div className="flex items-center space-x-4">
                            <Link href={route('login')} className="text-gray-600 hover:text-gray-900 transition-colors">
                                Login
                            </Link>
                            <Link href={route('register')} className="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                Sign Up
                            </Link>
                        </div>
                    </div>
                </div>
            </nav>

            {/* Hero Section */}
            <section className="py-20 px-4 sm:px-6 lg:px-8">
                <div className="max-w-7xl mx-auto text-center">
                    <h1 className="text-4xl md:text-6xl font-bold text-gray-900 mb-6 leading-tight">
                        Create Your Digital
                        <span className="text-blue-600 block">Profile Card in Minutes</span>
                    </h1>
                    <p className="text-xl text-gray-600 mb-8 max-w-3xl mx-auto leading-relaxed">
                        Share your professional information instantly with a simple QR code.
                        No more exchanging business cards - just scan and connect.
                    </p>
                    <Link
                        href={route('login')}
                        className="bg-blue-600 text-white px-8 py-4 rounded-xl text-lg font-semibold hover:bg-blue-700 transition-all duration-200 shadow-lg hover:shadow-xl flex items-center mx-auto space-x-2 w-70 justify-center"
                    >
                        <span>Create Your Card</span>
                        <ArrowRight className="h-5 w-5" />
                    </Link>
                </div>
            </section>

            {/* How It Works Section */}
            <section className="py-20 bg-white">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center mb-16">
                        <h2 className="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                            How It Works
                        </h2>
                        <p className="text-xl text-gray-600 max-w-2xl mx-auto">
                            Get your digital profile card up and running in just three simple steps
                        </p>
                    </div>

                    <div className="grid md:grid-cols-3 gap-8">
                        {/* Step 1 */}
                        <div className="text-center group">
                            <div className="bg-blue-50 w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:bg-blue-100 transition-colors">
                                <User className="h-10 w-10 text-blue-600" />
                            </div>
                            <h3 className="text-xl font-semibold text-gray-900 mb-3">
                                1. Create Your Profile
                            </h3>
                            <p className="text-gray-600 leading-relaxed">
                                Upload your photo, add your details, bio, and showcase your projects.
                                Customize your digital identity in minutes.
                            </p>
                        </div>

                        {/* Step 2 */}
                        <div className="text-center group">
                            <div className="bg-green-50 w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:bg-green-100 transition-colors">
                                <QrCode className="h-10 w-10 text-green-600" />
                            </div>
                            <h3 className="text-xl font-semibold text-gray-900 mb-3">
                                2. Get Your QR Code
                            </h3>
                            <p className="text-gray-600 leading-relaxed">
                                Receive a unique QR code that links directly to your profile.
                                Print it, save it, or add it to your email signature.
                            </p>
                        </div>

                        {/* Step 3 */}
                        <div className="text-center group">
                            <div className="bg-purple-50 w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:bg-purple-100 transition-colors">
                                <Share2 className="h-10 w-10 text-purple-600" />
                            </div>
                            <h3 className="text-xl font-semibold text-gray-900 mb-3">
                                3. Share & Connect
                            </h3>
                            <p className="text-gray-600 leading-relaxed">
                                Let others scan your QR code to instantly view your profile,
                                projects, and contact information. Networking made simple.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            {/* Sample Card Mockup */}
            <section className="py-20 bg-gray-50">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center mb-16">
                        <h2 className="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                            See Your Profile Card in Action
                        </h2>
                        <p className="text-xl text-gray-600">
                            Here's what your digital profile card will look like
                        </p>
                    </div>

                    <div className="flex justify-center">
                        <div className="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full mx-4 border border-gray-200">
                            <div className="text-center mb-6">
                                <div className="w-24 h-24 bg-gray-300 rounded-full mx-auto mb-4"></div>
                                <h3 className="text-xl font-semibold text-gray-900">John Doe</h3>
                                <p className="text-gray-600">Frontend Developer</p>
                            </div>

                            <div className="space-y-3 mb-6">
                                <div className="flex items-center space-x-3 text-gray-600">
                                    <Globe className="h-4 w-4" />
                                    <span className="text-sm">johndoe.com</span>
                                </div>
                                <div className="flex items-center space-x-3 text-gray-600">
                                    <Smartphone className="h-4 w-4" />
                                    <span className="text-sm">+1 (555) 123-4567</span>
                                </div>
                            </div>

                            <div className="text-center">
                                <div className="w-32 h-32 bg-gray-200 mx-auto rounded-lg flex items-center justify-center">
                                    <QrCode className="h-16 w-16 text-gray-400" />
                                </div>
                                <p className="text-xs text-gray-500 mt-2">Scan to view profile</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {/* Benefits Section */}
            <section className="py-20 bg-white">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center mb-16">
                        <h2 className="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                            Why Choose Digital Profile Cards?
                        </h2>
                    </div>

                    <div className="grid md:grid-cols-3 gap-8">
                        <div className="text-center">
                            <div className="bg-green-50 w-16 h-16 rounded-xl flex items-center justify-center mx-auto mb-4">
                                <CheckCircle className="h-8 w-8 text-green-600" />
                            </div>
                            <h3 className="text-lg font-semibold text-gray-900 mb-2">Eco-Friendly</h3>
                            <p className="text-gray-600">No more paper business cards. Go digital and help the environment.</p>
                        </div>

                        <div className="text-center">
                            <div className="bg-blue-50 w-16 h-16 rounded-xl flex items-center justify-center mx-auto mb-4">
                                <Smartphone className="h-8 w-8 text-blue-600" />
                            </div>
                            <h3 className="text-lg font-semibold text-gray-900 mb-2">Always Updated</h3>
                            <p className="text-gray-600">Update your information anytime and it reflects instantly for everyone.</p>
                        </div>

                        <div className="text-center">
                            <div className="bg-purple-50 w-16 h-16 rounded-xl flex items-center justify-center mx-auto mb-4">
                                <Users className="h-8 w-8 text-purple-600" />
                            </div>
                            <h3 className="text-lg font-semibold text-gray-900 mb-2">Easy Networking</h3>
                            <p className="text-gray-600">Share your complete professional profile with just one scan.</p>
                        </div>
                    </div>
                </div>
            </section>

            {/* Testimonials */}
            <section className="py-20 bg-gray-50">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center mb-16">
                        <h2 className="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                            What Our Users Say
                        </h2>
                    </div>

                    <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                        {[
                            {
                                name: "Sarah Chen",
                                role: "Marketing Director",
                                content: "This has revolutionized how I network at conferences. No more fumbling for business cards!"
                            },
                            {
                                name: "Mike Rodriguez",
                                role: "Freelance Designer",
                                content: "My clients love how easy it is to access my portfolio and contact information."
                            },
                            {
                                name: "Emily Johnson",
                                role: "Startup Founder",
                                content: "The QR code feature makes networking so much more efficient and professional."
                            }
                        ].map((testimonial, index) => (
                            <div key={index} className="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                                <div className="flex mb-4">
                                    {[...Array(5)].map((_, i) => (
                                        <Star key={i} className="h-4 w-4 text-yellow-400 fill-current" />
                                    ))}
                                </div>
                                <p className="text-gray-600 mb-4 italic">"{testimonial.content}"</p>
                                <div>
                                    <p className="font-semibold text-gray-900">{testimonial.name}</p>
                                    <p className="text-sm text-gray-500">{testimonial.role}</p>
                                </div>
                            </div>
                        ))}
                    </div>
                </div>
            </section>

            {/* Footer */}
            <footer className="bg-gray-900 text-white py-12">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center">
                        <div className="flex items-center justify-center space-x-2 mb-6">
                            <QrCode className="h-8 w-8 text-blue-400" />
                            <span className="text-2xl font-bold">ProfileCard</span>
                        </div>
                        <p className="text-gray-400 mb-8 max-w-2xl mx-auto">
                            Create your digital profile card today and start networking smarter, not harder.
                        </p>
                        <div className="flex flex-col sm:flex-row justify-center items-center space-y-4 sm:space-y-0 sm:space-x-4">
                            <Link href={route('login')} className="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors w-full sm:w-auto">
                                Get Started Free
                            </Link>
                            <button className="border border-gray-600 text-gray-300 px-6 py-3 rounded-lg hover:bg-gray-800 transition-colors w-full sm:w-auto">
                                Learn More
                            </button>
                        </div>
                        <div className="mt-8 pt-8 border-t border-gray-800 text-center text-gray-400">
                            <p>&copy; 2025 ProfileCard. All rights reserved.</p>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </>)
}