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

<div class="bg-[#1e1700] border-b border-[#3d2e00] text-white py-2 text-xs font-cairo">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-row justify-between items-center gap-2">

            {{-- Contact Actions (Phone & WhatsApp) --}}
            <div class="flex items-center gap-3" dir="ltr">
                <a href="tel:{{ $cleanPhone }}"
                   class="inline-flex items-center gap-2 px-3 py-1 bg-white/10 hover:bg-gold hover:text-black text-white rounded-full transition-all text-xs font-semibold backdrop-blur-sm">
                    <i class="fas fa-phone-alt text-[11px]"></i>
                    <span dir="ltr">{{ $phone }}</span>
                </a>
            </div>

            {{-- Location Address --}}
            <a href="https://maps.google.com/?q={{ urlencode($address) }}"
               target="_blank"
               class="inline-flex items-center gap-2 px-3 py-1 bg-white/10 hover:bg-white/20 text-white rounded-full transition-all text-xs font-medium backdrop-blur-sm">
                <i class="fas fa-map-marker-alt text-gold text-[11px]"></i>
                <span>{{ $address }}</span>
            </a>

        </div>
    </div>
</div>
