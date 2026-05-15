<?php

namespace Database\Seeders;

use App\Models\Certification;
use App\Models\Education;
use App\Models\Experience;
use App\Models\Lead;
use App\Models\Profile;
use App\Models\ProfileViewEvent;
use App\Models\Project;
use App\Models\Service;
use App\Models\Team;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class DemoProfileSeeder extends Seeder
{
    /**
     * Seed two polished demo accounts with realistic portfolio data
     * across the application's main models.
     */
    public function run(): void
    {
        DB::transaction(function (): void {
            $accounts = collect($this->accounts())->map(fn (array $account) => $this->seedAccount($account));

            $this->seedTeam($accounts->all());
        });

        if ($this->command) {
            $this->command->info('Demo accounts created:');
            $this->command->line(' - maya.chen@demo.digitalprofile.test / DemoPass#2026');
            $this->command->line(' - omar.rahman@demo.digitalprofile.test / DemoPass#2026');
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function accounts(): array
    {
        return [
            [
                'user' => [
                    'name' => 'Maya Chen',
                    'email' => 'maya.chen@demo.digitalprofile.test',
                    'password' => 'DemoPass#2026',
                ],
                'profile' => [
                    'display_name' => 'Maya Chen',
                    'job_title' => 'Staff Product Designer',
                    'short_bio' => 'Product designer helping B2B software teams simplify onboarding, analytics, and design systems. Maya has led launches across fintech, collaboration tools, and AI-powered internal platforms.',
                    'email' => 'maya.chen@demo.digitalprofile.test',
                    'phone' => '+1 415 555 0148',
                    'whatsapp' => '+14155550148',
                    'website' => 'https://www.mayachen.design',
                    'linkedin' => 'https://www.linkedin.com/in/maya-chen-design',
                    'github' => 'https://github.com/mayachen-design',
                    'twitter' => 'https://x.com/mayachen_design',
                    'instagram' => 'https://www.instagram.com/mayachen.design',
                    'youtube' => 'https://www.youtube.com/@mayachen-design',
                    'tiktok' => 'https://www.tiktok.com/@mayachen.design',
                    'dribbble' => 'https://dribbble.com/mayachen',
                    'behance' => 'https://www.behance.net/mayachen',
                    'medium' => 'https://medium.com/@mayachen.design',
                    'location' => 'Dubai, United Arab Emirates',
                    'template' => 'glass',
                    'is_public' => true,
                    'skills' => 'Product Strategy, Design Systems, UX Research, Figma, Journey Mapping, Product Analytics, Accessibility, Design Leadership',
                    'availability_status' => 'Open to Opportunities',
                    'scheduling_url' => 'https://cal.com/mayachen/intro',
                    'custom_domain' => 'maya-chen-demo.test',
                    'domain_verification_token' => 'maya-demo-token',
                    'domain_verified_at' => Carbon::parse('2026-03-12 10:00:00'),
                ],
                'experiences' => [
                    [
                        'company' => 'Atlassian',
                        'position' => 'Senior Product Designer',
                        'location' => 'Remote',
                        'start_date' => '2022-04-01',
                        'end_date' => null,
                        'is_current' => true,
                        'description' => 'Led workflow redesigns for cross-functional planning and dashboard reporting, improving task completion rates and reducing setup friction for enterprise customers.',
                    ],
                    [
                        'company' => 'Canva',
                        'position' => 'Product Designer',
                        'location' => 'Sydney, Australia',
                        'start_date' => '2019-01-01',
                        'end_date' => '2022-03-15',
                        'is_current' => false,
                        'description' => 'Owned editor collaboration flows, template discovery, and reusable UI patterns used across onboarding and content creation surfaces.',
                    ],
                ],
                'educations' => [
                    [
                        'institution' => 'University of Washington',
                        'degree' => 'Bachelor of Design',
                        'field_of_study' => 'Interaction Design',
                        'start_year' => 2011,
                        'end_year' => 2015,
                        'is_current' => false,
                        'description' => 'Focused on visual communication, human-centered design, and product prototyping.',
                    ],
                ],
                'certifications' => [
                    [
                        'title' => 'Google UX Design Professional Certificate',
                        'issuer' => 'Google',
                        'issue_date' => '2021-08-20',
                        'expiry_date' => null,
                        'credential_url' => 'https://www.coursera.org/professional-certificates/google-ux-design',
                        'credential_id' => 'GUXD-MCHEN-2021',
                    ],
                    [
                        'title' => 'Certified Scrum Product Owner',
                        'issuer' => 'Scrum Alliance',
                        'issue_date' => '2023-02-11',
                        'expiry_date' => '2027-02-11',
                        'credential_url' => 'https://www.scrumalliance.org/get-certified/product-owner-track/certified-scrum-product-owner',
                        'credential_id' => 'CSPO-784221',
                    ],
                ],
                'services' => [
                    [
                        'title' => 'Product UX Audit',
                        'description' => 'A two-week audit covering onboarding, navigation, and conversion friction, with prioritized fixes and annotated design recommendations.',
                        'starting_price' => 1800.00,
                        'currency' => 'USD',
                        'cta_label' => 'Book Audit',
                        'cta_url' => 'https://cal.com/mayachen/ux-audit',
                        'sort_order' => 1,
                    ],
                    [
                        'title' => 'Design System Sprint',
                        'description' => 'Rapid design-system foundation for growing product teams, including component inventory, governance notes, and scalable handoff patterns.',
                        'starting_price' => 3200.00,
                        'currency' => 'USD',
                        'cta_label' => 'Plan Sprint',
                        'cta_url' => 'https://www.mayachen.design/services/design-system-sprint',
                        'sort_order' => 2,
                    ],
                    [
                        'title' => 'Executive Product Workshop',
                        'description' => 'Half-day alignment session for founders and product leads to shape roadmap priorities and user-value narratives.',
                        'starting_price' => 950.00,
                        'currency' => 'USD',
                        'cta_label' => 'Request Workshop',
                        'cta_url' => 'https://www.mayachen.design/contact',
                        'sort_order' => 3,
                    ],
                ],
                'projects' => [
                    [
                        'name' => 'Pulseboard Analytics',
                        'description' => 'A portfolio analytics workspace for revenue and activation teams. Maya redesigned dashboard navigation and KPI storytelling, cutting time-to-insight for weekly reviews.',
                        'project_url' => 'https://www.mayachen.design/work/pulseboard-analytics',
                        'start_date' => '2024-02-01',
                        'end_date' => '2024-08-30',
                        'status' => 'completed',
                        'image_key' => 'maya-pulseboard',
                        'media' => [
                            ['media_type' => 'image', 'sort_order' => 1, 'asset_key' => 'maya-pulseboard-overview'],
                            ['media_type' => 'image', 'sort_order' => 2, 'asset_key' => 'maya-pulseboard-kpis'],
                        ],
                    ],
                    [
                        'name' => 'Northstar Onboarding',
                        'description' => 'A self-serve onboarding revamp for a SaaS team serving operations managers. Maya mapped role-based entry points and introduced contextual product education.',
                        'project_url' => 'https://www.mayachen.design/work/northstar-onboarding',
                        'start_date' => '2025-01-15',
                        'end_date' => null,
                        'status' => 'ongoing',
                        'image_key' => 'maya-northstar',
                        'media' => [
                            ['media_type' => 'image', 'sort_order' => 1, 'asset_key' => 'maya-northstar-hero'],
                            ['media_type' => 'image', 'sort_order' => 2, 'asset_key' => 'maya-northstar-flow'],
                        ],
                    ],
                ],
                'testimonials' => [
                    [
                        'reviewer_name' => 'Lena Foster',
                        'reviewer_title' => 'Director of Product',
                        'reviewer_company' => 'Northstar Labs',
                        'content' => 'Maya brought clarity to a messy onboarding experience and helped our team make sharper decisions faster. Her workshop materials are still being used across product and support.',
                        'rating' => 5,
                        'is_approved' => true,
                        'created_at' => '2026-02-10 09:15:00',
                    ],
                    [
                        'reviewer_name' => 'Ethan Brooks',
                        'reviewer_title' => 'VP Design',
                        'reviewer_company' => 'Avenue Cloud',
                        'content' => 'She balances systems thinking with practical execution. Within three weeks we had a component map, governance proposal, and a backlog the whole team could align around.',
                        'rating' => 5,
                        'is_approved' => true,
                        'created_at' => '2026-01-25 14:20:00',
                    ],
                    [
                        'reviewer_name' => 'Nadia Al Mansoori',
                        'reviewer_title' => 'Founder',
                        'reviewer_company' => 'PilotDesk',
                        'content' => 'Maya immediately understood our customer journey and translated it into elegant flows that our engineers could ship without ambiguity.',
                        'rating' => 5,
                        'is_approved' => true,
                        'created_at' => '2025-12-18 11:00:00',
                    ],
                ],
                'leads' => [
                    [
                        'visitor_name' => 'Samuel Reed',
                        'visitor_email' => 'samuel.reed@northstarlabs.example',
                        'visitor_phone' => '+1 646 555 0116',
                        'message' => 'We are refreshing the reporting experience for our B2B product and would like a UX audit plus a short strategy sprint in April.',
                        'created_at' => '2026-03-03 13:00:00',
                    ],
                    [
                        'visitor_name' => 'Hana Lee',
                        'visitor_email' => 'hana.lee@avenuedigital.example',
                        'visitor_phone' => '+44 20 7946 0811',
                        'message' => 'Looking for design-system support for a scale-up with three product squads. Interested in pricing and timeline.',
                        'created_at' => '2026-03-08 10:30:00',
                    ],
                    [
                        'visitor_name' => 'Rami Haddad',
                        'visitor_email' => 'rami.haddad@pilotdesk.example',
                        'visitor_phone' => '+971 50 555 0141',
                        'message' => 'Could you run an executive workshop for our leadership team during the last week of March?',
                        'created_at' => '2026-03-14 16:45:00',
                    ],
                ],
                'views' => $this->makeViews(
                    '2026-02-20',
                    [
                        ['count' => 16, 'device_type' => 'desktop', 'referrer' => 'direct'],
                        ['count' => 11, 'device_type' => 'mobile', 'referrer' => 'linkedin'],
                        ['count' => 8, 'device_type' => 'mobile', 'referrer' => 'qr', 'is_qr_scan' => true],
                        ['count' => 6, 'device_type' => 'tablet', 'referrer' => 'instagram'],
                    ]
                ),
                'assets' => [
                    'profile' => ['path' => 'demo/profiles/maya-chen.svg', 'title' => 'Maya Chen', 'accent' => '#1f6feb', 'subtitle' => 'Staff Product Designer'],
                    'maya-pulseboard' => ['path' => 'demo/projects/maya-pulseboard.svg', 'title' => 'Pulseboard Analytics', 'accent' => '#1769e0', 'subtitle' => 'Analytics dashboard redesign'],
                    'maya-pulseboard-overview' => ['path' => 'demo/projects/maya-pulseboard-overview.svg', 'title' => 'Pulseboard Overview', 'accent' => '#1f6feb', 'subtitle' => 'Executive KPI summary'],
                    'maya-pulseboard-kpis' => ['path' => 'demo/projects/maya-pulseboard-kpis.svg', 'title' => 'Pulseboard KPIs', 'accent' => '#0f766e', 'subtitle' => 'Funnel and retention metrics'],
                    'maya-northstar' => ['path' => 'demo/projects/maya-northstar.svg', 'title' => 'Northstar Onboarding', 'accent' => '#b45309', 'subtitle' => 'Role-based onboarding flow'],
                    'maya-northstar-hero' => ['path' => 'demo/projects/maya-northstar-hero.svg', 'title' => 'Northstar Hero', 'accent' => '#b45309', 'subtitle' => 'Entry point personalization'],
                    'maya-northstar-flow' => ['path' => 'demo/projects/maya-northstar-flow.svg', 'title' => 'Northstar Flow', 'accent' => '#9333ea', 'subtitle' => 'Guided activation checklist'],
                ],
            ],
            [
                'user' => [
                    'name' => 'Omar Rahman',
                    'email' => 'omar.rahman@demo.digitalprofile.test',
                    'password' => 'DemoPass#2026',
                ],
                'profile' => [
                    'display_name' => 'Omar Rahman',
                    'job_title' => 'Principal AI Solutions Engineer',
                    'short_bio' => 'Engineer building internal AI copilots, data products, and customer-facing automation for modern software teams. Omar works at the intersection of product strategy, backend architecture, and applied machine learning.',
                    'email' => 'omar.rahman@demo.digitalprofile.test',
                    'phone' => '+971 55 555 0174',
                    'whatsapp' => '+971555550174',
                    'website' => 'https://www.omarrahman.dev',
                    'linkedin' => 'https://www.linkedin.com/in/omar-rahman-ai',
                    'github' => 'https://github.com/omarrahman-ai',
                    'twitter' => 'https://x.com/omarrahman_ai',
                    'instagram' => 'https://www.instagram.com/omarrahman.dev',
                    'youtube' => 'https://www.youtube.com/@omarrahman-ai',
                    'tiktok' => 'https://www.tiktok.com/@omarrahman.dev',
                    'dribbble' => null,
                    'behance' => null,
                    'medium' => 'https://medium.com/@omarrahman.dev',
                    'location' => 'Abu Dhabi, United Arab Emirates',
                    'template' => 'bold',
                    'is_public' => true,
                    'skills' => 'Laravel, Python, Retrieval Augmented Generation, API Design, Postgres, Docker, LLM Evaluation, System Architecture, Technical Leadership',
                    'availability_status' => 'Available',
                    'scheduling_url' => 'https://cal.com/omarrahman/discovery',
                    'custom_domain' => 'omar-rahman-demo.test',
                    'domain_verification_token' => 'omar-demo-token',
                    'domain_verified_at' => Carbon::parse('2026-03-11 15:45:00'),
                ],
                'experiences' => [
                    [
                        'company' => 'HubSpot',
                        'position' => 'Principal Solutions Engineer',
                        'location' => 'Remote',
                        'start_date' => '2023-06-01',
                        'end_date' => null,
                        'is_current' => true,
                        'description' => 'Architecting AI-assisted support tooling, evaluation pipelines, and secure CRM integrations for enterprise implementations.',
                    ],
                    [
                        'company' => 'Twilio',
                        'position' => 'Senior Software Engineer',
                        'location' => 'Dublin, Ireland',
                        'start_date' => '2019-02-01',
                        'end_date' => '2023-05-15',
                        'is_current' => false,
                        'description' => 'Built messaging automation services and developer tooling focused on reliability, observability, and API-first delivery.',
                    ],
                ],
                'educations' => [
                    [
                        'institution' => 'King\'s College London',
                        'degree' => 'MSc',
                        'field_of_study' => 'Artificial Intelligence',
                        'start_year' => 2016,
                        'end_year' => 2017,
                        'is_current' => false,
                        'description' => 'Studied machine learning, natural language processing, and intelligent agent systems.',
                    ],
                    [
                        'institution' => 'American University of Sharjah',
                        'degree' => 'BSc',
                        'field_of_study' => 'Computer Engineering',
                        'start_year' => 2011,
                        'end_year' => 2015,
                        'is_current' => false,
                        'description' => 'Focused on software engineering, distributed systems, and embedded architecture.',
                    ],
                ],
                'certifications' => [
                    [
                        'title' => 'AWS Certified Solutions Architect - Associate',
                        'issuer' => 'Amazon Web Services',
                        'issue_date' => '2022-11-14',
                        'expiry_date' => '2027-11-14',
                        'credential_url' => 'https://aws.amazon.com/certification/certified-solutions-architect-associate/',
                        'credential_id' => 'AWS-SAA-501294',
                    ],
                    [
                        'title' => 'TensorFlow Developer Certificate',
                        'issuer' => 'Google',
                        'issue_date' => '2021-05-08',
                        'expiry_date' => null,
                        'credential_url' => 'https://www.tensorflow.org/certificate',
                        'credential_id' => 'TF-DEV-219733',
                    ],
                ],
                'services' => [
                    [
                        'title' => 'AI Discovery Sprint',
                        'description' => 'A focused engagement to identify high-value AI use cases, define guardrails, and scope implementation for an engineering team.',
                        'starting_price' => 2500.00,
                        'currency' => 'USD',
                        'cta_label' => 'Start Sprint',
                        'cta_url' => 'https://cal.com/omarrahman/ai-discovery',
                        'sort_order' => 1,
                    ],
                    [
                        'title' => 'LLM Integration Architecture',
                        'description' => 'Architecture package for retrieval, tool use, evaluation, and deployment decisions tailored to production teams.',
                        'starting_price' => 4200.00,
                        'currency' => 'USD',
                        'cta_label' => 'Discuss Architecture',
                        'cta_url' => 'https://www.omarrahman.dev/services/llm-architecture',
                        'sort_order' => 2,
                    ],
                    [
                        'title' => 'Engineering Advisory Retainer',
                        'description' => 'Ongoing technical leadership support for startups shipping AI workflows, backend services, and product analytics.',
                        'starting_price' => 1600.00,
                        'currency' => 'USD',
                        'cta_label' => 'Request Retainer',
                        'cta_url' => 'https://www.omarrahman.dev/contact',
                        'sort_order' => 3,
                    ],
                ],
                'projects' => [
                    [
                        'name' => 'Atlas Support Copilot',
                        'description' => 'An internal AI assistant that helps support teams summarize customer history, propose responses, and surface account context from CRM and knowledge base systems.',
                        'project_url' => 'https://www.omarrahman.dev/work/atlas-support-copilot',
                        'start_date' => '2024-04-10',
                        'end_date' => '2024-12-20',
                        'status' => 'completed',
                        'image_key' => 'omar-atlas',
                        'media' => [
                            ['media_type' => 'image', 'sort_order' => 1, 'asset_key' => 'omar-atlas-chat'],
                            ['media_type' => 'image', 'sort_order' => 2, 'asset_key' => 'omar-atlas-evals'],
                        ],
                    ],
                    [
                        'name' => 'Signal API Platform',
                        'description' => 'A modular backend platform for event ingestion, scoring, and alerting. Omar led the Laravel and Python service design with strong observability and CI gates.',
                        'project_url' => 'https://www.omarrahman.dev/work/signal-api-platform',
                        'start_date' => '2025-02-03',
                        'end_date' => null,
                        'status' => 'ongoing',
                        'image_key' => 'omar-signal',
                        'media' => [
                            ['media_type' => 'image', 'sort_order' => 1, 'asset_key' => 'omar-signal-topology'],
                            ['media_type' => 'image', 'sort_order' => 2, 'asset_key' => 'omar-signal-observability'],
                        ],
                    ],
                ],
                'testimonials' => [
                    [
                        'reviewer_name' => 'Priya Nair',
                        'reviewer_title' => 'Head of Engineering',
                        'reviewer_company' => 'Atlas CX',
                        'content' => 'Omar is the kind of engineer who can move from architecture diagrams to production incident reviews without losing context. He helped us ship an AI assistant with discipline, not hype.',
                        'rating' => 5,
                        'is_approved' => true,
                        'created_at' => '2026-02-14 08:40:00',
                    ],
                    [
                        'reviewer_name' => 'Daniel Meyer',
                        'reviewer_title' => 'CTO',
                        'reviewer_company' => 'Signal Forge',
                        'content' => 'He quickly turned a rough idea into a credible technical plan, then supported our team through implementation details around APIs, evals, and operations.',
                        'rating' => 5,
                        'is_approved' => true,
                        'created_at' => '2026-01-12 17:30:00',
                    ],
                    [
                        'reviewer_name' => 'Salma Zayed',
                        'reviewer_title' => 'Product Lead',
                        'reviewer_company' => 'Mercury Desk',
                        'content' => 'Omar keeps tradeoffs visible. That made our roadmap discussions much better and reduced the gap between product ambition and what engineering could safely deliver.',
                        'rating' => 5,
                        'is_approved' => true,
                        'created_at' => '2025-12-06 12:10:00',
                    ],
                ],
                'leads' => [
                    [
                        'visitor_name' => 'Jessica Cole',
                        'visitor_email' => 'jessica.cole@atlascx.example',
                        'visitor_phone' => '+1 617 555 0177',
                        'message' => 'We need help scoping a retrieval-based support copilot and would like to review architecture options with our platform team.',
                        'created_at' => '2026-03-02 09:20:00',
                    ],
                    [
                        'visitor_name' => 'Yousef Karim',
                        'visitor_email' => 'yousef.karim@signalforge.example',
                        'visitor_phone' => '+971 52 555 0162',
                        'message' => 'Interested in a short advisory retainer for API platform design and deployment planning.',
                        'created_at' => '2026-03-10 18:05:00',
                    ],
                    [
                        'visitor_name' => 'Maria Gutierrez',
                        'visitor_email' => 'maria.gutierrez@mercurydesk.example',
                        'visitor_phone' => '+34 91 555 0182',
                        'message' => 'Can you support a discovery sprint around AI-assisted account summaries for our customer success team?',
                        'created_at' => '2026-03-16 11:55:00',
                    ],
                ],
                'views' => $this->makeViews(
                    '2026-02-20',
                    [
                        ['count' => 14, 'device_type' => 'desktop', 'referrer' => 'direct'],
                        ['count' => 12, 'device_type' => 'mobile', 'referrer' => 'linkedin'],
                        ['count' => 10, 'device_type' => 'desktop', 'referrer' => 'qr', 'is_qr_scan' => true],
                        ['count' => 7, 'device_type' => 'mobile', 'referrer' => 'twitter'],
                        ['count' => 5, 'device_type' => 'tablet', 'referrer' => 'whatsapp'],
                    ]
                ),
                'assets' => [
                    'profile' => ['path' => 'demo/profiles/omar-rahman.svg', 'title' => 'Omar Rahman', 'accent' => '#059669', 'subtitle' => 'Principal AI Solutions Engineer'],
                    'omar-atlas' => ['path' => 'demo/projects/omar-atlas.svg', 'title' => 'Atlas Support Copilot', 'accent' => '#0f766e', 'subtitle' => 'AI support workspace'],
                    'omar-atlas-chat' => ['path' => 'demo/projects/omar-atlas-chat.svg', 'title' => 'Atlas Chat', 'accent' => '#059669', 'subtitle' => 'Agent response drafting'],
                    'omar-atlas-evals' => ['path' => 'demo/projects/omar-atlas-evals.svg', 'title' => 'Atlas Evaluations', 'accent' => '#2563eb', 'subtitle' => 'Prompt and retrieval scoring'],
                    'omar-signal' => ['path' => 'demo/projects/omar-signal.svg', 'title' => 'Signal API Platform', 'accent' => '#7c3aed', 'subtitle' => 'Event-driven API architecture'],
                    'omar-signal-topology' => ['path' => 'demo/projects/omar-signal-topology.svg', 'title' => 'Signal Topology', 'accent' => '#7c3aed', 'subtitle' => 'Service and queue topology'],
                    'omar-signal-observability' => ['path' => 'demo/projects/omar-signal-observability.svg', 'title' => 'Signal Observability', 'accent' => '#dc2626', 'subtitle' => 'Latency and error dashboards'],
                ],
            ],
        ];
    }

    /**
     * @param  array<string, mixed>  $account
     * @return array{user: \App\Models\User, profile: \App\Models\Profile}
     */
    private function seedAccount(array $account): array
    {
        $assetPaths = $this->createAssets($account['assets']);

        $user = User::firstOrNew(['email' => $account['user']['email']]);
        $user->forceFill([
            'name' => $account['user']['name'],
            'email' => $account['user']['email'],
            'password' => $account['user']['password'],
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ])->save();

        Experience::where('user_id', $user->id)->delete();
        Education::where('user_id', $user->id)->delete();
        Certification::where('user_id', $user->id)->delete();
        Service::where('user_id', $user->id)->delete();
        Project::where('user_id', $user->id)->delete();

        $profile = Profile::firstOrNew(['user_id' => $user->id]);

        $existingProfileId = $profile->exists ? $profile->id : null;
        if ($existingProfileId) {
            ProfileViewEvent::where('profile_id', $existingProfileId)->delete();
            Lead::where('profile_id', $existingProfileId)->delete();
            Testimonial::where('profile_id', $existingProfileId)->delete();
        }

        $profile->fill([
            ...$account['profile'],
            'profile_image' => $assetPaths['profile'],
            'profile_views' => count($account['views']),
        ]);
        $profile->user()->associate($user);
        $profile->save();

        Experience::insert($this->withTimestamps($account['experiences'], ['user_id' => $user->id]));
        Education::insert($this->withTimestamps($account['educations'], ['user_id' => $user->id]));
        Certification::insert($this->withTimestamps($account['certifications'], ['user_id' => $user->id]));
        Service::insert($this->withTimestamps($account['services'], ['user_id' => $user->id]));

        foreach ($account['projects'] as $projectData) {
            $project = Project::create([
                'user_id' => $user->id,
                'name' => $projectData['name'],
                'description' => $projectData['description'],
                'project_url' => $projectData['project_url'],
                'image' => $assetPaths[$projectData['image_key']],
                'start_date' => $projectData['start_date'],
                'end_date' => $projectData['end_date'],
                'status' => $projectData['status'],
            ]);

            $project->media()->createMany(array_map(
                fn (array $media) => [
                    'file_path' => $assetPaths[$media['asset_key']],
                    'media_type' => $media['media_type'],
                    'sort_order' => $media['sort_order'],
                ],
                $projectData['media']
            ));
        }

        Lead::insert(array_map(
            fn (array $lead) => [
                ...$lead,
                'profile_id' => $profile->id,
            ],
            $account['leads']
        ));

        Testimonial::insert(array_map(
            fn (array $testimonial) => [
                ...$testimonial,
                'profile_id' => $profile->id,
            ],
            $account['testimonials']
        ));

        ProfileViewEvent::insert(array_map(
            fn (array $view) => [
                ...$view,
                'profile_id' => $profile->id,
            ],
            $account['views']
        ));

        return ['user' => $user, 'profile' => $profile->fresh()];
    }

    /**
     * @param  array<int, array{user: \App\Models\User, profile: \App\Models\Profile}>  $accounts
     */
    private function seedTeam(array $accounts): void
    {
        $owner = $accounts[0]['user'];
        $member = $accounts[1]['user'];

        $team = Team::firstOrNew(['name' => 'Northstar Studio']);
        $team->fill([
            'description' => 'Cross-functional product studio for design strategy, AI workflows, and modern web delivery.',
            'website' => 'https://www.northstarstudio.co',
            'owner_user_id' => $owner->id,
        ]);
        $team->save();

        $team->members()->sync([
            $owner->id => ['role' => 'owner'],
            $member->id => ['role' => 'member'],
        ]);
    }

    /**
     * @param  array<string, array<string, string>>  $assets
     * @return array<string, string>
     */
    private function createAssets(array $assets): array
    {
        $paths = [];

        foreach ($assets as $key => $asset) {
            $paths[$key] = $this->writeDemoSvg(
                $asset['path'],
                $asset['title'],
                $asset['accent'],
                $asset['subtitle']
            );
        }

        return $paths;
    }

    private function writeDemoSvg(string $relativePath, string $title, string $accent, string $subtitle): string
    {
        $fullPath = storage_path('app/public/'.$relativePath);

        File::ensureDirectoryExists(dirname($fullPath));

        $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 630" role="img" aria-labelledby="title desc">
  <title>{$this->escapeSvg($title)}</title>
  <desc>{$this->escapeSvg($subtitle)}</desc>
  <defs>
    <linearGradient id="bg" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="{$this->escapeSvg($accent)}" stop-opacity="0.95" />
      <stop offset="100%" stop-color="#0f172a" stop-opacity="1" />
    </linearGradient>
  </defs>
  <rect width="1200" height="630" fill="url(#bg)" rx="36" />
  <circle cx="1040" cy="128" r="120" fill="#ffffff" fill-opacity="0.08" />
  <circle cx="170" cy="520" r="180" fill="#ffffff" fill-opacity="0.07" />
  <rect x="76" y="72" width="190" height="16" rx="8" fill="#ffffff" fill-opacity="0.3" />
  <rect x="76" y="128" width="620" height="170" rx="24" fill="#ffffff" fill-opacity="0.12" />
  <rect x="76" y="344" width="460" height="18" rx="9" fill="#ffffff" fill-opacity="0.2" />
  <rect x="76" y="384" width="380" height="18" rx="9" fill="#ffffff" fill-opacity="0.16" />
  <text x="76" y="205" fill="#ffffff" font-size="64" font-family="Arial, Helvetica, sans-serif" font-weight="700">{$this->escapeSvg($title)}</text>
  <text x="76" y="438" fill="#e2e8f0" font-size="30" font-family="Arial, Helvetica, sans-serif">{$this->escapeSvg($subtitle)}</text>
  <text x="76" y="570" fill="#cbd5e1" font-size="20" font-family="Arial, Helvetica, sans-serif">Digital Profile Demo Asset</text>
</svg>
SVG;

        File::put($fullPath, $svg);

        return $relativePath;
    }

    private function escapeSvg(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_XML1, 'UTF-8');
    }

    /**
     * @param  array<int, array<string, mixed>>  $rows
     * @param  array<string, mixed>  $base
     * @return array<int, array<string, mixed>>
     */
    private function withTimestamps(array $rows, array $base = []): array
    {
        $timestamp = now();

        return array_map(
            fn (array $row) => [
                ...$base,
                ...$row,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            $rows
        );
    }

    /**
     * @param  array<int, array{count: int, device_type: string, referrer: string, is_qr_scan?: bool}>  $patterns
     * @return array<int, array<string, mixed>>
     */
    private function makeViews(string $startDate, array $patterns): array
    {
        $baseDate = Carbon::parse($startDate);
        $views = [];
        $dayOffset = 0;

        foreach ($patterns as $pattern) {
            for ($i = 0; $i < $pattern['count']; $i++) {
                $views[] = [
                    'device_type' => $pattern['device_type'],
                    'referrer' => $pattern['referrer'],
                    'is_qr_scan' => $pattern['is_qr_scan'] ?? false,
                    'viewed_at' => $baseDate->copy()->addDays($dayOffset % 28)->setTime(9 + ($i % 8), ($i * 7) % 60),
                ];
                $dayOffset++;
            }
        }

        return $views;
    }
}
