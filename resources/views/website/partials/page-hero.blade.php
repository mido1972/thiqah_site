@props([
    'title' => '',
    'subtitle' => '',
    'ctaText' => null,
    'ctaUrl' => null,
])

<section class="section-dark">
    <div class="container-site">
        <div class="min-h-[200px] sm:min-h-[240px] flex items-center">
            <div class="max-w-3xl">
                <h1 class="h1">{{ $title }}</h1>

                @if($subtitle)
                    <p class="p mt-3">{{ $subtitle }}</p>
                @endif

                @if($ctaText && $ctaUrl)
                    <div class="mt-6">
                        <a href="{{ $ctaUrl }}" class="btn-primary">
                            {{ $ctaText }}
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
