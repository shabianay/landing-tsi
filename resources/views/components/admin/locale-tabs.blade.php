@props(['model' => null, 'field' => '', 'label' => '', 'type' => 'text', 'rows' => 3, 'required' => false, 'placeholder' => ''])

@php
    $locales = ['id' => 'Indonesia', 'en' => 'English'];
    $currentLocale = app()->getLocale();
@endphp

<div x-data="{ activeTab: '{{ $currentLocale }}' }" class="space-y-3">
    <label class="block text-sm font-bold text-dark-300">{{ $label }} @if($required)<span class="text-red-400">*</span>@endif</label>

    <div class="flex gap-1 bg-dark-50/50 p-1 rounded-xl w-fit border border-dark-100/10">
        @foreach($locales as $code => $name)
            <button type="button" @click="activeTab = '{{ $code }}'"
                :class="activeTab === '{{ $code }}' ? 'bg-white text-primary-600 shadow-sm border border-dark-100/10' : 'text-dark-100/50 hover:text-dark-300'"
                class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-all duration-200">
                {{ $name }}
            </button>
        @endforeach
    </div>

    @foreach($locales as $code => $name)
        <div x-show="activeTab === '{{ $code }}'" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
            @if($type === 'textarea')
                <textarea name="trans_{{ $field }}_{{ $code }}" rows="{{ $rows }}" {{ $required && $code === $currentLocale ? 'required' : '' }}
                    placeholder="{{ $placeholder }}"
                    class="w-full px-4 py-3.5 bg-dark-50/50 border border-dark-100/10 rounded-xl focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 outline-none transition-all font-medium placeholder-dark-100/30 text-dark-300 resize-none">{{ old('trans_' . $field . '_' . $code, $model?->translations->where('locale', $code)->where('key', $field)->first()?->value ?? $model?->$field) }}</textarea>
            @else
                <input type="text" name="trans_{{ $field }}_{{ $code }}" value="{{ old('trans_' . $field . '_' . $code, $model?->translations->where('locale', $code)->where('key', $field)->first()?->value ?? $model?->$field) }}"
                    {{ $required && $code === $currentLocale ? 'required' : '' }}
                    placeholder="{{ $placeholder }}"
                    class="w-full px-4 py-3.5 bg-dark-50/50 border border-dark-100/10 rounded-xl focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 outline-none transition-all font-medium placeholder-dark-100/30 text-dark-300">
            @endif
        </div>
    @endforeach
</div>
