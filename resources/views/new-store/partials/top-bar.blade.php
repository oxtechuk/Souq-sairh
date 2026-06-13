{{-- Top Bar Partial --}}
@php
  $settings    = $settings ?? collect();
    $phone    = $settings['contact_phone'] ?? '+966 50 000 0000';
    $email    = $settings['contact_email'] ?? 'info@cars.com';
    $address  = $settings['contact_address'] ?? 'الرياض - المملكة العربية السعودية';
@endphp

<div class="bg-[#291F00] text-white py-3 text-sm" style="font-family: 'Cairo';">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row-reverse justify-between items-center gap-3 sm:gap-0">

            {{-- Contact Info --}}
            <div class="flex flex-wrap gap-4 sm:gap-8 justify-center sm:justify-start" dir="ltr">
                <span class="flex items-center gap-2">
                    <i class="fas fa-phone"></i>
                    <span>{{ $phone }}</span>
                </span>
                <span class="flex items-center gap-2">
                    <i class="fas fa-envelope"></i>
                    <span>{{ $email }}</span>
                </span>
            </div>

            {{-- Location --}}
            <div class="flex items-center gap-2">
                <i class="fas fa-map-marker-alt"></i>
                <span>{{ $address }}</span>
            </div>

        </div>
    </div>
</div>
