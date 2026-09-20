@php
    $items = $this->getChecklist();
    $progress = $this->getProgress();
@endphp

<x-filament-widgets::widget>
    @if($progress < 100)
        <x-filament::section :heading="'Antes de publicar: '.$progress.'% completado'" description="Pendientes para que el sitio quede listo con información real." icon="heroicon-o-rocket-launch" collapsible>
            <div class="grid gap-3 md:grid-cols-2">
                @foreach($items as $item)
                    <a href="{{ $item['url'] }}" class="flex items-start gap-3 rounded-lg p-3 ring-1 ring-gray-950/5 transition hover:bg-gray-50 dark:ring-white/10 dark:hover:bg-white/5">
                        @if($item['done'])
                            <x-filament::icon icon="heroicon-o-check-circle" class="mt-0.5 h-5 w-5 shrink-0 text-success-600" />
                        @else
                            <x-filament::icon icon="heroicon-o-exclamation-circle" class="mt-0.5 h-5 w-5 shrink-0 text-warning-600" />
                        @endif
                        <div>
                            <p class="text-sm font-medium {{ $item['done'] ? 'text-gray-500 line-through dark:text-gray-400' : 'text-gray-950 dark:text-white' }}">{{ $item['label'] }}</p>
                            @unless($item['done'])<p class="text-xs text-gray-500 dark:text-gray-400">{{ $item['hint'] }}</p>@endunless
                        </div>
                    </a>
                @endforeach
            </div>
        </x-filament::section>
    @endif
</x-filament-widgets::widget>
