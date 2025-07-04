import React from 'react';

interface StatCardProps {
    icon: React.ReactNode;
    title: string;
    value: string | number;
    changeText?: string;
    changeIcon?: React.ReactNode;
    changeClass?: string;
}

const StatCard: React.FC<StatCardProps> = ({
    icon,
    title,
    value,
    changeText,
    changeIcon,
    changeClass = 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500',
}) => {
    return (
        <div className="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
            <div className="flex h-12 w-12 items-center justify-center rounded-xl bg-gray-100 dark:bg-gray-800">
                {icon}
            </div>

            <div className="mt-5 flex items-end justify-between">
                <div>
                    <span className="text-sm text-gray-500 dark:text-gray-400">{title}</span>
                    <h4 className="mt-2 text-title-sm font-bold text-gray-800 dark:text-white/90">{value}</h4>
                </div>

                {changeText && (
                    <span
                        className={`flex items-center gap-1 rounded-full py-0.5 pl-2 pr-2.5 text-sm font-medium ${changeClass}`}
                    >
                        {changeIcon}
                        {changeText}
                    </span>
                )}
            </div>
        </div>
    );
};

export default StatCard;
