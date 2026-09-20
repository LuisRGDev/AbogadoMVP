<?php

namespace App\Support;

use Illuminate\Support\HtmlString;

/**
 * Iconos SVG en línea (trazo fino, 24×24). Los de marca social son rellenos.
 */
class Icons
{
    /** @var array<string, string> */
    private const STROKE = [
        'arrow' => '<path d="M4 12h16M14 6l6 6-6 6"/>',
        'arrow-up' => '<path d="M12 20V4M6 10l6-6 6 6"/>',
        'left' => '<path d="M20 12H4M10 6l-6 6 6 6"/>',
        'chevron' => '<path d="M6 9l6 6 6-6"/>',
        'menu' => '<path d="M4 8h16M4 16h16"/>',
        'close' => '<path d="M5 5l14 14M19 5L5 19"/>',
        'check' => '<path d="M5 12.5l4.5 4.5L19 7.5"/>',
        'building' => '<path d="M4 21V5l9-2v18M13 8l7 2v11M2 21h20M8 8h1M8 12h1M8 16h1M16 13h1M16 17h1"/>',
        'columns' => '<path d="M3 8l9-5 9 5M5 8v11M10 8v11M14 8v11M19 8v11M3 21h18"/>',
        'people' => '<circle cx="9" cy="8" r="3.2"/><path d="M3 20c0-3.5 2.7-6 6-6s6 2.5 6 6"/><circle cx="17" cy="9" r="2.4"/><path d="M16 14.2c3 0 5 2 5 5"/>',
        'house' => '<path d="M3 11l9-7 9 7M5 10v10h14V10M10 20v-6h4v6"/>',
        'document' => '<path d="M6 3h8l4 4v14H6zM14 3v4h4M9 12h6M9 16h6"/>',
        'mark' => '<circle cx="12" cy="12" r="9"/><path d="M15 9.5a3.5 3.5 0 1 0 0 5"/>',
        'pin' => '<path d="M12 21s7-5.6 7-11a7 7 0 1 0-14 0c0 5.4 7 11 7 11z"/><circle cx="12" cy="10" r="2.5"/>',
        'phone' => '<path d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2z"/>',
        'mail' => '<rect x="3" y="5" width="18" height="14" rx="1"/><path d="M3 7l9 6 9-6"/>',
        'clock' => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
        'whatsapp' => '<path d="M20 11.5a8 8 0 0 1-11.9 7L4 20l1.5-4A8 8 0 1 1 20 11.5z"/><path d="M9 8.5c0 3 3 6 6 6l1-1.5-2-1-1 .7c-.8-.4-1.6-1.2-2-2l.7-1-1-2z"/>',
        'scale' => '<path d="M12 3v18M5 21h14M6 7h12"/><path d="M6 7l-3 7a3 3 0 0 0 6 0L6 7zM18 7l-3 7a3 3 0 0 0 6 0l-3-7z"/>',
        'shield' => '<path d="M12 3l8 3v6c0 5-3.5 8-8 9-4.5-1-8-4-8-9V6l8-3z"/><path d="M9 12l2 2 4-4"/>',
        'calendar' => '<rect x="3" y="5" width="18" height="16" rx="1"/><path d="M3 10h18M8 3v4M16 3v4"/>',
        'calculator' => '<rect x="5" y="3" width="14" height="18" rx="1"/><path d="M8 7h8M8 12h1M12 12h1M16 12h.01M8 16h1M12 16h1M16 16h.01"/>',
        'search' => '<circle cx="11" cy="11" r="6"/><path d="M20 20l-4.5-4.5"/>',
        'share' => '<circle cx="18" cy="5" r="2.5"/><circle cx="6" cy="12" r="2.5"/><circle cx="18" cy="19" r="2.5"/><path d="M8.2 10.8l7.6-4.4M8.2 13.2l7.6 4.4"/>',
        'link' => '<path d="M10 14a4 4 0 0 0 5.7 0l3-3a4 4 0 0 0-5.7-5.7l-1 1M14 10a4 4 0 0 0-5.7 0l-3 3a4 4 0 0 0 5.7 5.7l1-1"/>',
        'download' => '<path d="M12 4v11M7 11l5 5 5-5M4 20h16"/>',
        'external' => '<path d="M14 4h6v6M20 4l-9 9M18 14v6H4V6h6"/>',
        'user' => '<circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 3.6-7 8-7s8 3 8 7"/>',
        'briefcase' => '<rect x="3" y="7" width="18" height="13" rx="1"/><path d="M9 7V4h6v3M3 13h18"/>',
        'quote' => '<path d="M7 17c-2 0-3-1.5-3-3.5C4 10 6 7 9 6M17 17c-2 0-3-1.5-3-3.5 0-3.5 2-6.5 5-7.5"/>',
        'video' => '<rect x="3" y="6" width="13" height="12" rx="1"/><path d="M16 10l5-3v10l-5-3"/>',
        'id-card' => '<rect x="3" y="5" width="18" height="14" rx="1"/><circle cx="9" cy="11" r="2"/><path d="M6 16c0-1.7 1.3-3 3-3s3 1.3 3 3M14 10h4M14 14h4"/>',
        'lock' => '<rect x="5" y="10" width="14" height="10" rx="1"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/>',
        'target' => '<circle cx="12" cy="12" r="9"/><circle cx="12" cy="12" r="5"/><circle cx="12" cy="12" r="1"/>',
        'eye' => '<path d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/>',
        'info' => '<circle cx="12" cy="12" r="9"/><path d="M12 11v6M12 7.5h.01"/>',
        'instagram' => '<rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r=".6"/>',
        'youtube' => '<rect x="2.5" y="5" width="19" height="14" rx="4"/><path d="M10 9l5 3-5 3z"/>',
    ];

    /** @var array<string, string> */
    private const FILLED = [
        'facebook' => '<path d="M24 12.07C24 5.44 18.63.07 12 .07S0 5.44 0 12.07c0 5.99 4.39 10.95 10.13 11.85v-8.39H7.08v-3.47h3.05V9.43c0-3.01 1.79-4.67 4.53-4.67 1.31 0 2.69.23 2.69.23v2.95h-1.52c-1.49 0-1.96.93-1.96 1.87v2.25h3.33l-.53 3.47h-2.8v8.39C19.61 23.03 24 18.06 24 12.07z"/>',
        'linkedin' => '<path d="M20.45 20.45h-3.55v-5.57c0-1.33-.03-3.04-1.85-3.04-1.85 0-2.14 1.45-2.14 2.94v5.67H9.35V9h3.41v1.56h.05c.48-.9 1.64-1.85 3.37-1.85 3.6 0 4.27 2.37 4.27 5.46v6.28zM5.34 7.43a2.06 2.06 0 1 1 0-4.13 2.06 2.06 0 0 1 0 4.13zM7.12 20.45H3.56V9h3.56v11.45zM22.23 0H1.77C.79 0 0 .77 0 1.73v20.54C0 23.23.79 24 1.77 24h20.45c.98 0 1.78-.77 1.78-1.73V1.73C24 .77 23.2 0 22.22 0z"/>',
        'x' => '<path d="M18.24 2.25h3.31l-7.23 8.26 8.5 11.24h-6.66l-5.21-6.82-5.97 6.82H1.68l7.73-8.84L1.25 2.25h6.83l4.71 6.23 5.45-6.23zm-1.16 17.52h1.83L7.08 4.13H5.12l11.96 15.64z"/>',
    ];

    public static function has(string $name): bool
    {
        return isset(self::STROKE[$name]) || isset(self::FILLED[$name]);
    }

    public static function render(string $name, string $class = '', int $size = 24, ?string $label = null): HtmlString
    {
        $aria = $label ? 'role="img" aria-label="'.e($label).'"' : 'aria-hidden="true" focusable="false"';
        $classes = trim('icon '.$class);

        if (isset(self::FILLED[$name])) {
            return new HtmlString('<svg class="'.e($classes).'" width="'.$size.'" height="'.$size.'" viewBox="0 0 24 24" fill="currentColor" '.$aria.'>'.self::FILLED[$name].'</svg>');
        }

        $body = self::STROKE[$name] ?? self::STROKE['arrow'];

        return new HtmlString('<svg class="'.e($classes).'" width="'.$size.'" height="'.$size.'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.35" stroke-linecap="round" stroke-linejoin="round" '.$aria.'>'.$body.'</svg>');
    }
}
