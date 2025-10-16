{{-- Custom Payment Method Display Component --}}
@props(['method'])

@php
    $colorPrimary = $method->color_primary ?? '#007bff';
    $colorSecondary = $method->color_secondary ?? '#ffffff';
    $buttonText = $method->button_text ?? 'Pay now';
    $customFields = is_array($method->custom_form_fields) ? $method->custom_form_fields : json_decode($method->custom_form_fields ?? '[]', true);
    $instructionsHtml = $method->instructions_html ?? '';
    $customCss = $method->custom_css ?? '';
@endphp

<div class="custom-payment-method-wrapper mb-4" data-method-key="{{ $method->key }}" style="display:none;">
    @if($customCss)
        <style>{{ $customCss }}</style>
    @endif
    
    <div class="card border-0 shadow-sm custom-method-card" style="border-left: 4px solid {{ $colorPrimary }} !important; transition: all 0.3s;">
        <div class="card-body p-4">
            <div class="d-flex align-items-center mb-3">
                <div class="flex-shrink-0 me-3">
                    @if($method->icon_path)
                        @php
                            $src = $method->icon_path;
                            if ($src && str_starts_with($src, 'public/')) {
                                $src = 'storage/' . substr($src, 7);
                            }
                        @endphp
                        <div class="payment-logo-container" style="width:56px;height:56px;border-radius:10px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.1);">
                            <img src="{{ asset($src) }}" alt="{{ $method->name }}" style="width:100%;height:100%;object-fit:contain;">
                        </div>
                    @elseif($method->icon)
                        <div class="payment-icon-container" style="width:56px;height:56px;background:{{ $colorPrimary }};border-radius:10px;display:flex;align-items:center;justify-content:center;box-shadow:0 2px 8px rgba(0,0,0,0.1);">
                            <i class="mdi {{ $method->icon }} text-white" style="font-size:28px;"></i>
                        </div>
                    @else
                        <div class="payment-icon-container" style="width:56px;height:56px;background:{{ $colorPrimary }};border-radius:10px;display:flex;align-items:center;justify-content:center;box-shadow:0 2px 8px rgba(0,0,0,0.1);">
                            <i class="mdi mdi-credit-card text-white" style="font-size:28px;"></i>
                        </div>
                    @endif
                </div>
                <div class="flex-grow-1">
                    <h5 class="mb-1 fw-bold" style="color:{{ $colorPrimary }};">{{ $method->name }}</h5>
                    @if($method->description)
                        <p class="text-muted small mb-0">{{ $method->description }}</p>
                    @endif
                </div>
            </div>

            @if($instructionsHtml)
                <div class="alert alert-info mb-3" style="background-color: {{ $colorPrimary }}15; border-color: {{ $colorPrimary }}30; color: {{ $colorPrimary }};">
                    {!! $instructionsHtml !!}
                </div>
            @endif

            <div class="custom-fields-container">
                @foreach($customFields as $field)
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            {{ $field['label'] ?? 'Field' }}
                            @if($field['required'] ?? false)
                                <span class="text-danger">*</span>
                            @endif
                        </label>
                        <input 
                            type="{{ $field['type'] ?? 'text' }}" 
                            class="form-control custom-method-field" 
                            name="custom_{{ $method->key }}_{{ $field['id'] ?? '' }}"
                            placeholder="{{ $field['placeholder'] ?? '' }}"
                            {{ ($field['required'] ?? false) ? 'required' : '' }}
                            style="border-color: {{ $colorPrimary }}40;"
                        >
                    </div>
                @endforeach
            </div>

            <button 
                type="button" 
                class="btn w-100 fw-semibold custom-method-submit" 
                data-method="{{ $method->key }}"
                style="background-color: {{ $colorPrimary }}; color: {{ $colorSecondary }}; border: none; padding: 12px; border-radius: 8px; transition: all 0.3s;"
                onmouseover="this.style.opacity='0.9'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(0,0,0,0.15)';"
                onmouseout="this.style.opacity='1'; this.style.transform='translateY(0)'; this.style.boxShadow='none';"
            >
                <i class="mdi mdi-lock me-2"></i>{{ $buttonText }}
            </button>
        </div>
    </div>
</div>

<style>
.custom-method-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.12) !important;
}

.custom-method-field:focus {
    border-color: {{ $colorPrimary }} !important;
    box-shadow: 0 0 0 0.2rem {{ $colorPrimary }}25 !important;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.custom-payment-method-wrapper {
    animation: fadeInUp 0.4s ease-out;
}
</style>
