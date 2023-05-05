import ApplicationLogo from '@/Components/ApplicationLogo';
import { Link } from '@inertiajs/react';

export default function Guest({ children }) {
    return (
        <div className="min-h-screen flex items-center justify-center pt-6 sm:pt-0 bg-gradient-to-b from-blue-500 to-blue-700">
                <div className="w-full sm:max-w-md px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                    {children}
                </div>
        </div>
    );
}
