<?php
if (!function_exists('doctor_e')) {
    function doctor_e($value): string
    {
        return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('doctor_status_label')) {
    function doctor_status_label(?string $status): string
    {
        return match ($status) {
            'wait' => 'Menunggu',
            'call' => 'Dipanggil',
            'progress' => 'Diperiksa',
            'done' => 'Selesai',
            'skip' => 'Dilewati',
            'cancel' => 'Batal',
            default => $status ?: '-',
        };
    }
}

if (!function_exists('doctor_badge')) {
    function doctor_badge(?string $status): string
    {
        $status = $status ?: 'wait';

        return '<span class="badge ' . doctor_e($status) . '"><i></i>' . doctor_e(doctor_status_label($status)) . '</span>';
    }
}

if (!function_exists('doctor_icon')) {
    function doctor_icon(string $name, int $size = 18): string
    {
        $paths = [
            'clock'     => 'M12 8v4l3 2M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z',
            'megaphone' => 'M3 11v2a2 2 0 0 0 2 2h2l4 4v-4l8-3V8l-8-3v10M7 15V9',
            'check'     => 'M20 6 9 17l-5-5',
            'x'         => 'M18 6 6 18M6 6l12 12',
            'play'      => 'M8 5v14l11-7L8 5Z',
            'next'      => 'M5 5l8 7-8 7V5Zm10 0h3v14h-3V5Z',
            'logout'    => 'M10 17l5-5-5-5M15 12H3M21 19V5a2 2 0 0 0-2-2h-6M13 21h6a2 2 0 0 0 2-2',
            'user'      => 'M20 21a8 8 0 0 0-16 0M12 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z',
            'pulse'     => 'M3 12h4l3-8 4 16 3-8h4',
            'calendar'  => 'M8 2v4M16 2v4M3 10h18M5 4h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2Z',
            'shield'    => 'M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z',
        ];

        $path = $paths[$name] ?? $paths['user'];

        return '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="' . $path . '"></path></svg>';
    }
}

if (!function_exists('doctor_initial')) {
    function doctor_initial(string $name): string
    {
        $words = explode(' ', trim(str_replace(['dr.', 'drg.'], '', strtolower($name))));
        $chars = '';

        foreach ($words as $w) {
            if ($w !== '') {
                $chars .= strtoupper(substr($w, 0, 1));
            }

            if (strlen($chars) >= 2) {
                break;
            }
        }

        return $chars ?: 'DR';
    }
}
