@props([
    'title',
    'value',
    'total' => 0,
    'unit' => '',
    'description' => '',
    'color' => 'blue', // 'green', 'yellow', 'red', 'blue', 'orange'
    'icon' => '', // Material symbol name
    'showProgress' => true, // Whether to show the bottom progress bar
])

@php
    // Validation
    $val = floatval(preg_replace('/[^\d.-]/', '', (string)$value));
    $tot = floatval(preg_replace('/[^\d.-]/', '', (string)$total));
    
    // Percentage
    $percentage = $tot > 0 ? min(100, round(($val / $tot) * 100)) : 100;
    if ($tot == 0) $percentage = 100;
    if ($val == 0) $percentage = 0;
    
    // Config colors & shadows
    $barColor = match($color) {
        'green' => '#26db63',
        'yellow' => '#fac710',
        'orange' => '#f59e0b',
        'red' => '#ef4444',
        default => '#4B6A95',
    };
    
    $fillColor = match($color) {
        'green' => '#26db63',
        'yellow' => '#f6cd00',
        'orange' => '#f59e0b',
        'red' => '#ef4444',
        default => '#4B6A95',
    };

    $shadowColor = match($color) {
        'green' => 'rgba(38, 219, 99, 0.8)',
        'yellow' => 'rgba(246, 205, 0, 0.8)',
        'orange' => 'rgba(245, 158, 11, 0.8)',
        'red' => 'rgba(239, 68, 68, 0.8)',
        default => 'rgba(75, 106, 149, 0.8)',
    };
    // We use the full string length (including spaces) to determine font size
    $valLen = strlen(trim(strip_tags((string)$value)));
    $valueTextSize = $valLen >= 12 ? 'text-[24px]' : ($valLen >= 10 ? 'text-[28px]' : ($valLen >= 8 ? 'text-[32px]' : 'text-[40px]'));
@endphp

<div class="bg-white rounded-lg p-6 flex flex-col shadow-[0_12px_30px_rgba(0,0,0,0.04),inset_0_2px_4px_rgba(255,255,255,0.8)] h-full w-full min-h-[200px] group hover:-translate-y-1 transition-transform duration-300 relative overflow-hidden">
    
    <!-- Title and Icon -->
    <div class="flex justify-between items-start mb-2">
        <div class="text-[14px] text-[#8c90a1] font-medium">{{ $title }}</div>
        <!-- Icon -->
        @if($icon)
            <div class="flex items-center justify-center w-8 h-8 rounded-full shadow-sm" style="background-color: {{ $barColor }}20; color: {{ $barColor }}">
                <span class="material-symbols-outlined text-[16px]">{{ $icon }}</span>
            </div>
        @endif
    </div>
    
    <!-- Main Display -->
    <div class="flex items-baseline -mt-1 mb-2 flex-wrap">
        <span class="{{ $valueTextSize }} font-semibold text-[#121212] leading-tight tracking-[-1px]">{{ $value }}</span>
        @if($unit)
            <span class="text-[14px] font-bold text-[#8c90a1] ml-1.5 mt-2">{{ $unit }}</span>
        @endif
    </div>
    
    <!-- Description (Animated) -->
    @if($description)
        <p class="text-[13px] text-gray-500 mb-4 leading-tight opacity-75 group-hover:opacity-100 transition-opacity duration-300 {{ !$showProgress ? 'mb-0' : '' }}">
            {{ $description }}
        </p>
    @endif
    
    <!-- Meter Section -->
    @if($showProgress)
    <div class="mt-auto">
        <div class="flex h-[8px] gap-1 w-full bg-[#eef0f5] rounded-full overflow-hidden mt-3 shadow-inner">
            <!-- Fill -->
            @if($percentage > 0)
            <div class="h-full rounded-full transition-all duration-1000" 
                 style="width: {{ $percentage }}%; background-color: {{ $fillColor }}; background-image: repeating-linear-gradient(-45deg, rgba(255,255,255,0.25), rgba(255,255,255,0.25) 2px, transparent 2px, transparent 8px);">
            </div>
            @endif
        </div>
        <div class="flex justify-between text-[10px] text-[#a0a4b3] mt-1 font-bold tracking-wider">
            <span>{{ $percentage }}%</span>
            <span>100%</span>
        </div>
    </div>
    @endif

    {{ $decoration ?? '' }}
</div>
