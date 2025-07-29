import React from 'react';
import { Link } from '@inertiajs/react';
import { QrCode } from 'lucide-react';

interface GuestHeaderProps {
    children: React.ReactNode;
}

const GuestHeader: React.FC<GuestHeaderProps> = ({ children }) => {
    return (
        <div className="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
            <nav className="bg-white shadow-sm border-b border-gray-200">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="flex justify-between items-center h-16">
                       <Link href={route('home')}>
                        <div className="flex items-center space-x-2">
                            <QrCode className="h-8 w-8 text-blue-600" />
                            <span className="text-xl font-bold text-gray-900">ProfileCard</span>
                        </div>
                       </Link>
                        <div className="flex items-center space-x-4">
                            <Link
                                href={route('login')}
                                className="text-gray-600 hover:text-gray-900 transition-colors"
                            >
                                Login
                            </Link>
                            <Link
                                href={route('register')}
                                className="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors"
                            >
                                Sign Up
                            </Link>
                        </div>
                    </div>
                </div>
            </nav>

            <main>
                {children}
            </main>
        </div>
    );
};

export default GuestHeader;
