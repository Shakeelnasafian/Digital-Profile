<?php

namespace App\Services;

use App\Models\Profile;

class ProfileCompletionService
{
    private array $weights = [
        'profile_image'    => 15,
        'short_bio'        => 15,
        'skills'           => 10,
        'experience'       => 15,
        'projects'         => 10,
        'education'        => 10,
        'certifications'   => 5,
        'contact'          => 5,
        'social_links'     => 5,
        'availability'     => 5,
        'scheduling_url'   => 5,
    ];

    public function getScore(Profile $profile): int
    {
        $score = 0;

        foreach ($this->weights as $key => $weight) {
            if ($this->isDone($profile, $key)) {
                $score += $weight;
            }
        }

        return min(100, $score);
    }

    public function getChecklist(Profile $profile): array
    {
        $items = [
            ['key' => 'profile_image',  'label' => 'Upload a profile photo',          'href' => '/profile/' . $profile->id . '/edit'],
            ['key' => 'short_bio',      'label' => 'Write a short bio',               'href' => '/profile/' . $profile->id . '/edit'],
            ['key' => 'skills',         'label' => 'Add your skills',                 'href' => '/profile/' . $profile->id . '/edit'],
            ['key' => 'experience',     'label' => 'Add work experience',             'href' => '/experience'],
            ['key' => 'projects',       'label' => 'Add a project',                   'href' => '/projects'],
            ['key' => 'education',      'label' => 'Add your education',              'href' => '/education'],
            ['key' => 'certifications', 'label' => 'Add a certification',             'href' => '/certifications'],
            ['key' => 'contact',        'label' => 'Add phone or WhatsApp',           'href' => '/profile/' . $profile->id . '/edit'],
            ['key' => 'social_links',   'label' => 'Add a social media link',         'href' => '/profile/' . $profile->id . '/edit'],
            ['key' => 'availability',   'label' => 'Set your availability status',    'href' => '/profile/' . $profile->id . '/edit'],
            ['key' => 'scheduling_url', 'label' => 'Add a scheduling / booking link', 'href' => '/profile/' . $profile->id . '/edit'],
        ];

        return array_map(function ($item) use ($profile) {
            return [
                'label' => $item['label'],
                'href'  => $item['href'],
                'done'  => $this->isDone($profile, $item['key']),
            ];
        }, $items);
    }

    private function isDone(Profile $profile, string $key): bool
    {
        return match ($key) {
            'profile_image'  => !empty($profile->getRawOriginal('profile_image')),
            'short_bio'      => !empty($profile->short_bio),
            'skills'         => !empty($profile->skills),
            'experience'     => $profile->user->experiences()->exists(),
            'projects'       => $profile->user->projects()->exists(),
            'education'      => $profile->educations()->exists(),
            'certifications' => $profile->certifications()->exists(),
            'contact'        => !empty($profile->phone) || !empty($profile->whatsapp),
            'social_links'   => !empty($profile->twitter) || !empty($profile->instagram) ||
                                !empty($profile->youtube) || !empty($profile->tiktok) ||
                                !empty($profile->linkedin) || !empty($profile->github),
            'availability'   => !empty($profile->availability_status),
            'scheduling_url' => !empty($profile->scheduling_url),
            default          => false,
        };
    }
}
