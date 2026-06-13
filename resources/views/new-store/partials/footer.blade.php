{{-- Footer Partial --}}
@php
    $phone       = $settings['contact_phone'] ?? '056 9567 947';
    $email       = $settings['contact_email'] ?? 'info@souqsayara.com';
    $address     = $settings['contact_address'] ?? 'الرياض، المملكة العربية السعودية';
    $tiktok      = $settings['social_tiktok'] ?? '#';
    $facebook    = $settings['social_facebook'] ?? '#';
    $instagram   = $settings['social_instagram'] ?? '#';
    $footerCar   = asset('new-store/images/footer-car.svg');
    $footerMask  = asset('new-store/images/Mask group.svg');
@endphp

<footer class="footer-wrapper" dir="rtl">

    {{-- Illustrations --}}
    <div class="footer-illustrations">
        <div class="footer-car-illustration">
            <img src="{{ $footerCar }}" alt="Car Illustration" loading="lazy" />
        </div>
        <div class="footer-mask-illustration">
            <img src="{{ $footerMask }}" alt="سوق سيارة" loading="lazy" />
        </div>
    </div>

    {{-- Main Footer --}}
    <div class="footer-main">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="footer-grid">

                {{-- Quick Links --}}
                <div class="footer-column">
                    <h3 class="footer-heading">
                        <span class="footer-divider">|</span>
                        روابط سريعة
                    </h3>
                    <ul class="footer-links">
                        <li><a href="{{ route('new.home') }}"><span class="link-bullet">●</span> الرئيسية</a></li>
                        <li><a href="{{ route('new.calculator') }}"><span class="link-bullet">●</span> تمويل السيارات</a></li>
                        <li><a href="{{ route('new.offers.index') }}"><span class="link-bullet">●</span> عروضنا</a></li>
                        <li><a href="{{ route('new.about') }}"><span class="link-bullet">●</span> من نحن</a></li>
                        <li><a href="{{ route('new.blog.index') }}"><span class="link-bullet">●</span> المقالات</a></li>
                    </ul>
                </div>

                {{-- Services --}}
                <div class="footer-column">
                    <h3 class="footer-heading">
                        <span class="footer-divider">|</span>
                        خدماتنا
                    </h3>
                    <ul class="footer-links">
                        <li><a href="#"><span class="link-bullet">●</span> صيانة دورية</a></li>
                        <li><a href="#"><span class="link-bullet">●</span> إصلاح الأعطال</a></li>
                        <li><a href="#"><span class="link-bullet">●</span> تجديد الرخصات</a></li>
                        <li><a href="#"><span class="link-bullet">●</span> تحسين الأداء</a></li>
                        <li><a href="#"><span class="link-bullet">●</span> تنظيف الأجزاء</a></li>
                    </ul>
                </div>

                {{-- Contact --}}
                <div class="footer-column">
                    <h3 class="footer-heading">
                        <span class="footer-divider">|</span>
                        تواصل معنا
                    </h3>
                    <ul class="footer-contact">
                        <li>
                            <div class="contact-item">
                                <div class="contact-icon"><i class="fas fa-phone"></i></div>
                                <div class="contact-text">
                                    <p class="contact-label">اتصل بنا</p>
                                    <p class="contact-value">{{ $phone }}</p>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="contact-item">
                                <div class="contact-icon"><i class="fas fa-envelope"></i></div>
                                <div class="contact-text">
                                    <p class="contact-label">راسلنا</p>
                                    <p class="contact-value">{{ $email }}</p>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="contact-item">
                                <div class="contact-icon"><i class="fas fa-map-marker-alt"></i></div>
                                <div class="contact-text">
                                    <p class="contact-label">الموقع</p>
                                    <p class="contact-value">{{ $address }}</p>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>

                {{-- Social --}}
                <div class="footer-column">
                    <h3 class="footer-heading">
                        <span class="footer-divider">|</span>
                        تابعنا
                    </h3>
                    <div class="social-icons">
                        <a href="{{ $tiktok }}" class="social-icon social-tiktok">
                            <i class="fab fa-tiktok"></i>
                        </a>
                        <a href="{{ $facebook }}" class="social-icon social-facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="{{ $instagram }}" class="social-icon social-instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Footer Bottom --}}
    <div class="footer-bottom">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="footer-bottom-content">
                <p class="copyright">© {{ date('Y') }} SOUQ SAYARAH. جميع الحقوق محفوظة</p>
                <div class="footer-bottom-links">
                    <a href="#">سياسة الخصوصية</a>
                    <a href="#">الشروط والأحكام</a>
                </div>
            </div>
        </div>
    </div>

</footer>
