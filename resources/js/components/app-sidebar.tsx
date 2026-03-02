import { NavFooter } from '@/components/nav-footer';
import { NavMain } from '@/components/nav-main';
import { NavUser } from '@/components/nav-user';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem, type SharedData } from '@/types';
import { Link, usePage } from '@inertiajs/react';
import { LayoutGrid, User, FolderOpen, Briefcase, GraduationCap, Award, Users, Star, Package, Bell, UsersRound } from 'lucide-react';
import AppLogo from './app-logo';

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
        icon: LayoutGrid,
    },
    {
        title: 'My Digital Card',
        href: '/profile/create',
        icon: User,
    },
    {
        title: 'Projects',
        href: '/projects',
        icon: FolderOpen,
    },
    {
        title: 'Experience',
        href: '/experience',
        icon: Briefcase,
    },
    {
        title: 'Education',
        href: '/education',
        icon: GraduationCap,
    },
    {
        title: 'Certifications',
        href: '/certifications',
        icon: Award,
    },
    {
        title: 'Services',
        href: '/services',
        icon: Package,
    },
    {
        title: 'Leads',
        href: '/leads',
        icon: Users,
    },
    {
        title: 'Testimonials',
        href: '/testimonials',
        icon: Star,
    },
    {
        title: 'Teams',
        href: '/teams',
        icon: UsersRound,
    },
];

const footerNavItems: NavItem[] = [];

export function AppSidebar() {
    const { unread_notifications } = usePage<SharedData & { unread_notifications: number }>().props;
    const page = usePage();

    return (
        <Sidebar collapsible="icon" variant="inset">
            <SidebarHeader>
                <SidebarMenu>
                    <SidebarMenuItem>
                        <SidebarMenuButton size="lg" asChild>
                            <Link href="/dashboard" prefetch>
                                <AppLogo />
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarHeader>

            <SidebarContent>
                <NavMain items={mainNavItems} />

                {/* Notifications with badge */}
                <div className="px-2 py-1">
                    <SidebarMenu>
                        <SidebarMenuItem>
                            <SidebarMenuButton
                                asChild
                                isActive={page.url.startsWith('/notifications')}
                                tooltip={{ children: 'Notifications' }}
                            >
                                <Link href="/notifications" prefetch className="relative flex items-center gap-2">
                                    <Bell className="shrink-0" />
                                    <span>Notifications</span>
                                    {unread_notifications > 0 && (
                                        <span className="ml-auto min-w-[18px] h-[18px] rounded-full bg-red-500 text-white text-[10px] font-bold flex items-center justify-center px-1">
                                            {unread_notifications > 99 ? '99+' : unread_notifications}
                                        </span>
                                    )}
                                </Link>
                            </SidebarMenuButton>
                        </SidebarMenuItem>
                    </SidebarMenu>
                </div>
            </SidebarContent>

            <SidebarFooter>
                <NavFooter items={footerNavItems} className="mt-auto" />
                <NavUser />
            </SidebarFooter>
        </Sidebar>
    );
}
