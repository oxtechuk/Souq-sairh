{{-- Top Bar Partial --}}
@php
    $settings    = $settings ?? collect();
      $phone    = $settings['contact_phone'] ?? '+966 50 000 0000';
      $email    = $settings['contact_email'] ?? 'info@cars.com';
      $address  = $settings['contact_address'] ?? 'الرياض - المملكة العربية السعودية';
      $whatsapp = $settings['contact_whatsapp'] ?? $phone;
      $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
      $cleanWhatsapp = preg_replace('/[^0-9]/', '', $whatsapp);
@endphp

<div class="bg-[#291F00] text-white py-2 text-xs" style="font-family: 'Cairo';">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row-reverse justify-between items-center gap-2 sm:gap-0">

            {{-- Contact Actions --}}
            <div class="flex flex-wrap gap-2 justify-center sm:justify-start" dir="ltr">

                <a href="tel:{{ $cleanPhone }}"
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-primary-dark hover:bg-green-700 text-white rounded-md transition-all hover:shadow-lg">
                    <i class="fas fa-phone-alt text-[10px]"></i>
                    <span class="text-[11px] font-bold">{{ $phone }}</span>
                </a>



                {{--                <a href="mailto:{{ $email }}"--}}
                {{--                   class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition-all hover:shadow-lg">--}}
                {{--                    <i class="fas fa-envelope text-[10px]"></i>--}}
                {{--                    <span class="text-[11px] font-bold">{{ $email }}</span>--}}
                {{--                </a>--}}

            </div>

            {{-- Location --}}
            <a href="https://maps.google.com/?q={{ urlencode($address) }}"
               target="_blank"
               class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-primary-dark text-white rounded-md transition-all hover:shadow-lg">
                <i class="fas fa-map-marker-alt text-[10px]"></i>
                <span class="text-[11px] font-bold">{{ $address }}</span>
            </a>

        </div>
    </div>
</div>
