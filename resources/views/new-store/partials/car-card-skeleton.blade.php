{{-- Skeleton Card Partial --}}
<div class="car-card-skeleton shrink-0 w-[280px] bg-white rounded-[22px] overflow-hidden border border-[#D5DDEB] animate-pulse flex flex-col justify-between" dir="rtl">
  {{-- Header Image Skeleton --}}
  <div class="relative bg-gradient-to-b from-gray-100 to-gray-200 h-[195px] w-full flex items-center justify-center">
    <div class="w-12 h-12 rounded-full bg-gray-300/60 flex items-center justify-center">
      <i class="fas fa-car text-2xl text-gray-400/50"></i>
    </div>
    {{-- Year Badge Skeleton --}}
    <div class="absolute top-3.5 left-3.5 bg-gray-300/80 h-5 w-12 rounded-full"></div>
  </div>

  {{-- Title Skeleton --}}
  <div class="h-[58px] px-4 pt-2.5 flex flex-col items-center justify-center gap-1.5">
    <div class="h-4 bg-gray-200 rounded-full w-3/4"></div>
    <div class="h-3 bg-gray-200 rounded-full w-1/2"></div>
  </div>

  {{-- Pricing Skeleton --}}
  <div class="grid grid-cols-2 py-2.5 px-3 border-t border-b border-[#E6ECF5] bg-[#FCFDFE]">
    <div class="flex flex-col items-center justify-center border-l border-[#E6ECF5] px-2 gap-1.5">
      <div class="h-2.5 bg-gray-200 rounded w-12"></div>
      <div class="h-4 bg-gray-200 rounded w-20"></div>
    </div>
    <div class="flex flex-col items-center justify-center px-2 gap-1.5">
      <div class="h-2.5 bg-gray-200 rounded w-14"></div>
      <div class="h-4 bg-gray-200 rounded w-18"></div>
    </div>
  </div>

  {{-- Specs Grid Skeleton (2x2 Micro Badges) --}}
  <div class="grid grid-cols-2 gap-2 px-3.5 py-3 bg-[#F8FAFD]" dir="rtl">
    <div class="h-7 bg-white rounded-lg border border-[#E2E8F0]"></div>
    <div class="h-7 bg-white rounded-lg border border-[#E2E8F0]"></div>
    <div class="h-7 bg-white rounded-lg border border-[#E2E8F0]"></div>
    <div class="h-7 bg-white rounded-lg border border-[#E2E8F0]"></div>
  </div>

  {{-- Action Buttons Skeleton (Side by Side) --}}
  <div class="p-3.5 pt-2 flex items-center gap-2 border-t border-[#E6ECF5]">
    <div class="flex-1 h-[44px] bg-gray-200 rounded-xl"></div>
    <div class="w-16 h-[44px] bg-gray-200 rounded-xl"></div>
  </div>
</div>
