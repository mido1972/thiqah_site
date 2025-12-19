@php
    // توقعنا إن $siteSettings Array فيه: facebook, linkedin, twitter, instagram, youtube, tiktok
    $ss = fn ($key, $default = null) => data_get($siteSettings ?? [], $key, $default);

    $items = [
        'facebook'  => ['url' => $ss('facebook'),  'label' => 'Facebook'],
        'linkedin'  => ['url' => $ss('linkedin'),  'label' => 'LinkedIn'],
        'twitter'   => ['url' => $ss('twitter'),   'label' => 'X'],
        'instagram' => ['url' => $ss('instagram'), 'label' => 'Instagram'],
        'youtube'   => ['url' => $ss('youtube'),   'label' => 'YouTube'],
        'tiktok'    => ['url' => $ss('tiktok'),    'label' => 'TikTok'],
    ];

    $items = array_filter($items, fn ($i) => !empty($i['url']));
@endphp

@if(!empty($items))
    <div class="flex flex-wrap items-center gap-2">
        @foreach($items as $key => $item)
            <a
                href="{{ $item['url'] }}"
                target="_blank"
                rel="noopener noreferrer"
                class="group inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-700 shadow-sm transition hover:border-orange-300 hover:bg-orange-50 focus:outline-none focus:ring-2 focus:ring-orange-200"
                aria-label="{{ $item['label'] }}"
                title="{{ $item['label'] }}"
            >
                @switch($key)

                    @case('facebook')
                        <svg viewBox="0 0 24 24" class="h-5 w-5" fill="currentColor" aria-hidden="true">
                            <path d="M13.5 22v-8h2.7l.4-3h-3.1V9.1c0-.9.2-1.5 1.6-1.5H16.7V5c-.3 0-1.4-.1-2.6-.1-2.6 0-4.4 1.6-4.4 4.5V11H7v3h2.7v8h3.8z"/>
                        </svg>
                        @break

                    @case('linkedin')
                        <svg viewBox="0 0 24 24" class="h-5 w-5" fill="currentColor" aria-hidden="true">
                            <path d="M6.94 6.5A2.06 2.06 0 1 1 7 2.38a2.06 2.06 0 0 1-.06 4.12zM4.9 21.5h4.1v-13H4.9v13zM10.9 8.5h3.9v1.8h.05c.54-1.02 1.86-2.1 3.83-2.1 4.1 0 4.86 2.7 4.86 6.2v7.1h-4.1v-6.3c0-1.5-.03-3.5-2.13-3.5-2.13 0-2.46 1.66-2.46 3.38v6.42h-4.1v-13z"/>
                        </svg>
                        @break

                    @case('twitter')
                        <svg viewBox="0 0 24 24" class="h-5 w-5" fill="currentColor" aria-hidden="true">
                            <path d="M18.9 2H22l-6.8 7.8L23 22h-6.9l-5.4-7L4.6 22H1.5l7.3-8.4L1 2h7.1l4.9 6.3L18.9 2zm-1.1 18h1.7L7.2 3.9H5.4L17.8 20z"/>
                        </svg>
                        @break

                    @case('instagram')
                        <svg viewBox="0 0 24 24" class="h-5 w-5" fill="currentColor" aria-hidden="true">
                            <path d="M7.5 2h9A5.5 5.5 0 0 1 22 7.5v9A5.5 5.5 0 0 1 16.5 22h-9A5.5 5.5 0 0 1 2 16.5v-9A5.5 5.5 0 0 1 7.5 2zm9 2h-9A3.5 3.5 0 0 0 4 7.5v9A3.5 3.5 0 0 0 7.5 20h9a3.5 3.5 0 0 0 3.5-3.5v-9A3.5 3.5 0 0 0 16.5 4z"/>
                            <path d="M12 7a5 5 0 1 1 0 10 5 5 0 0 1 0-10zm0 2a3 3 0 1 0 0 6 3 3 0 0 0 0-6z"/>
                            <path d="M17.6 6.8a1.1 1.1 0 1 1-2.2 0 1.1 1.1 0 0 1 2.2 0z"/>
                        </svg>
                        @break

                    @case('youtube')
                        <svg viewBox="0 0 24 24" class="h-5 w-5" fill="currentColor" aria-hidden="true">
                            <path d="M21.6 7.2s-.2-1.6-.8-2.3c-.8-.8-1.7-.8-2.1-.9C15.9 3.7 12 3.7 12 3.7h0s-3.9 0-6.7.3c-.4 0-1.3.1-2.1.9-.6.7-.8 2.3-.8 2.3S2.1 9 2.1 10.8v1.7c0 1.8.3 3.6.3 3.6s.2 1.6.8 2.3c.8.8 1.9.8 2.4.9 1.7.2 6.4.3 6.4.3s3.9 0 6.7-.3c.4 0 1.3-.1 2.1-.9.6-.7.8-2.3.8-2.3s.3-1.8.3-3.6v-1.7c0-1.8-.3-3.6-.3-3.6zM10.3 14.9V8.9l5.3 3-5.3 3z"/>
                        </svg>
                        @break

                    @case('tiktok')
                        <svg viewBox="0 0 24 24" class="h-5 w-5" fill="currentColor" aria-hidden="true">
                            <path d="M16.7 2c.3 2.2 1.6 3.5 3.8 3.8v3.2c-1.3.1-2.4-.2-3.6-.9v7.2c0 3.7-3.1 5.9-6.2 5.2-2.1-.5-3.6-2.3-3.7-4.6-.2-2.9 2.3-5.2 5.2-5.1.3 0 .6.1.9.2v3.4c-.3-.1-.6-.2-.9-.2-1.1 0-2 .9-2 2 0 1 .7 1.8 1.7 2 .9.2 2.1-.3 2.1-2V2h2.7z"/>
                        </svg>
                        @break

                @endswitch
            </a>
        @endforeach
    </div>
@endif
