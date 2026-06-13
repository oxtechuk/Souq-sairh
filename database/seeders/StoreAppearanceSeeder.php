<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class StoreAppearanceSeeder extends Seeder
{
    public function run(): void
    {
        Setting::updateOrCreate(['key' => 'store_top_bar'], ['value' => [
            'is_active' => true,
            'show_phone' => true,
            'show_email' => true,
            'show_address' => true,
            'phone_icon' => 'fa-phone',
            'email_icon' => 'fa-envelope',
            'address_icon' => 'fa-map-marker-alt',
            'bg_color' => '#291F00',
            'text_color' => '#ffffff',
        ]]);

        Setting::updateOrCreate(['key' => 'store_nav_items'], ['value' => [
            ['label' => 'الرئيسية', 'route' => 'new.home', 'order' => 1, 'is_active' => true],
            ['label' => 'جميع السيارات', 'route' => 'new.cars.index', 'order' => 2, 'is_active' => true],
            ['label' => 'العروض', 'route' => 'new.offers.index', 'order' => 3, 'is_active' => true],
            ['label' => 'المقارنة', 'route' => 'new.compare', 'order' => 4, 'is_active' => true],
            ['label' => 'من نحن', 'route' => 'new.about', 'order' => 5, 'is_active' => true],
            ['label' => 'المقالات', 'route' => 'new.blog.index', 'order' => 6, 'is_active' => true],
        ]]);

        Setting::updateOrCreate(['key' => 'store_nav_contact'], ['value' => [
            'label' => 'تواصل معنا',
            'route' => 'new.contact',
            'is_active' => true,
        ]]);

        Setting::updateOrCreate(['key' => 'store_footer_quick_links'], ['value' => [
            ['label' => 'الرئيسية', 'url' => 'route:new.home'],
            ['label' => 'تمويل السيارات', 'url' => 'route:new.contact'],
            ['label' => 'عروضنا', 'url' => 'route:new.offers.index'],
            ['label' => 'من نحن', 'url' => 'route:new.about'],
            ['label' => 'المقالات', 'url' => 'route:new.blog.index'],
        ]]);

        Setting::updateOrCreate(['key' => 'store_footer_services'], ['value' => [
            ['label' => 'صيانة دورية', 'url' => '#'],
            ['label' => 'إصلاح الأعطال', 'url' => '#'],
            ['label' => 'تجديد الرخصات', 'url' => '#'],
            ['label' => 'تحسين الأداء', 'url' => '#'],
            ['label' => 'تنظيف الأجزاء', 'url' => '#'],
        ]]);

        Setting::updateOrCreate(['key' => 'store_footer_bottom'], ['value' => [
            'copyright_text' => '© {year} SOUQ SAYARAH. جميع الحقوق محفوظة',
            'links' => [
                ['label' => 'سياسة الخصوصية', 'url' => '#'],
                ['label' => 'الشروط والأحكام', 'url' => '#'],
            ],
        ]]);

        // ===== Hero Section =====
        Setting::updateOrCreate(['key' => 'store_home_hero'], ['value' => [
            'title' => 'تخيّر موتِرك..',
            'subtitle' => 'وحنّا نيسّر لك التمويل',
            'description' => 'لديك التمويلات بين يديك مع خطط تمويل مرنة تناسب ميزانيتك، أيًا بدأت الحين مع "سوق سيارة" وعيش الرفاهية',
            'cta_1_text' => 'استعرض السيارات',
            'cta_2_text' => 'اطلب تمويلك الآن',
        ]]);

        // ===== Featured Cars Section =====
        Setting::updateOrCreate(['key' => 'featured_cars_section'], ['value' => [
            'title' => 'السيارات المميزة',
            'subtitle' => 'استكشف مجموعتنا مميزة، بمواصفات خاصة، وبأفضل عروض التمويل.',
        ]]);

        // ===== Testimonials Section =====
        Setting::updateOrCreate(['key' => 'testimonials_section'], ['value' => [
            'title' => 'تجارب نفخر بها',
            'subtitle' => 'لأن رضاكم مع غايتنا، نشارككم آراء نخبة من عملائنا حول خدماتنا وحلولنا التمويلية',
        ]]);

        // ===== Brands Section =====
        Setting::updateOrCreate(['key' => 'brands_section'], ['value' => [
            'title' => 'تختار براندك.. خيارات لا محدودة',
            'subtitle' => 'أكثر من ١٠ صناع سيارات حول العالم، نوفر لك أقوى الأسماء التي تجتمع فيها الثقة بالأداء',
        ]]);

        // ===== Offers Hero =====
        Setting::updateOrCreate(['key' => 'offers_hero'], ['value' => [
            'title' => 'عروض استثنائية.. ضُممت لك',
            'subtitle' => 'اكتشف أقوى عروض التمويل والتوفير الحصرية من "سوق سيارة"، وابدأ رحلتك اليوم.',
        ]]);

        // ===== Offers Grid Title =====
        Setting::updateOrCreate(['key' => 'offers_grid_title'], ['value' => 'صور من معرضنا']);

        // ===== Main Gallery (صور المعرض الرئيسي - صفحة من نحن) =====
        Setting::updateOrCreate(['key' => 'main_gallery'], ['value' => [
            'new-store/images/offer-card-1.jpg',
            'new-store/images/offer-card-2.jpg',
            'new-store/images/offer-card-3.jpg',
            'new-store/images/car-1.jpg',
            'new-store/images/car-1.png',
        ]]);

        // ===== Compare CTA =====
        Setting::updateOrCreate(['key' => 'compare_cta'], ['value' => [
            'title' => 'موترك بالتظارك، وحنا بالخدمة.',
            'subtitle' => 'فريقنا جاهز للإجابة على استفساراتك وتسهيل إجراءات تملك سيارتك القادمة',
            'button_1' => 'طلب تجربة قيادة',
            'button_2' => 'تواصل معنا لمساعدتك',
        ]]);

        // ===== About Story =====
        Setting::updateOrCreate(['key' => 'about_story'], ['value' => [
            'title' => 'قصتنا.. شغف يقوده الطموح',
            'description' => 'في "سوق سيارة"، لم نأت لنبيع السيارات فحسب، بل جئنا لنعيد تعريف تجربة تملكها في المملكة، عبر دمج الحلول التمويلية الذكية بالخيارات التي تليق بطموحاتكم. نحن شركاء دربكم نحو مستقبل أفضل وتجربة قيادة ممتعة وآمنة.',
        ]]);

        // ===== About Why Choose Us =====
        Setting::updateOrCreate(['key' => 'about_why_choose'], ['value' => [
            'title' => 'لماذا نحن؟',
            'features' => [
                [
                    'icon' => 'fa-layer-group',
                    'title' => 'خيارات لا محدودة',
                    'description' => 'نجمع لك كبرى العلامات التجارية تحت سقف واحد، لتضمن لك حرية الاختيار وتنوع الخيارات التي تناسب كل الأذواق.',
                ],
                [
                    'icon' => 'fa-hand-holding-dollar',
                    'title' => 'تمويل بلا تعقيد',
                    'description' => 'صممنا حلولنا التمويلية لتكون مرنة، سريعة، ومتوافقة مع احتياجاتك المالية دون أي شروط تعجيزية.',
                ],
                [
                    'icon' => 'fa-shield-alt',
                    'title' => 'شفافية مطلقة',
                    'description' => 'من الفحص وحتى الاستلام، الوضوح هو محركنا الأساسي في كل خطوة لضمان راحتك وثقتك الكاملة.',
                ],
            ],
        ]]);

        // ===== About Stats =====
        Setting::updateOrCreate(['key' => 'about_stats'], ['value' => [
            'title' => 'أكثر من معرض.. إحنا شركاء الدرب',
            'subtitle' => 'نرافقك في كل خطوة، من اختيار موديل حتى استلام المفتاح',
            'items' => [
                ['icon' => 'fa-award', 'number' => '10+', 'label' => 'أعوام من الثقة'],
                ['icon' => 'fa-heart', 'number' => '98%', 'label' => 'تجارب ناجحة'],
                ['icon' => 'fa-car', 'number' => '200+', 'label' => 'موديل بالمعارض'],
                ['icon' => 'fa-users', 'number' => '50K+', 'label' => 'عائلة سوق سيارة'],
            ],
        ]]);

        // ===== Contact Location =====
        Setting::updateOrCreate(['key' => 'contact_location'], ['value' => [
            'title' => 'تشرّفنا بزيارتك.. حنا بانتظارك',
            'subtitle' => 'نتعاون مع كبرى الجهات التمويلية والبنوك لنضمن لك أفضل نسبة تمويل وأسرع إجراءات.',
            'address' => 'الرياض، المملكة العربية السعودية',
            'email' => 'Tese@test.com',
            'phone' => '056 9567 947',
        ]]);

        // ===== Contact Info (legacy keys used by partials) =====
        Setting::updateOrCreate(['key' => 'contact_address'], ['value' => 'الرياض، المملكة العربية السعودية']);
        Setting::updateOrCreate(['key' => 'contact_phone'], ['value' => '056 9567 947']);
        Setting::updateOrCreate(['key' => 'contact_email'], ['value' => 'Tese@test.com']);

        // ===== Social Media (used by footer/contact) =====
        Setting::updateOrCreate(['key' => 'social_tiktok'], ['value' => '#']);
        Setting::updateOrCreate(['key' => 'social_facebook'], ['value' => '#']);
        Setting::updateOrCreate(['key' => 'social_instagram'], ['value' => '#']);
        Setting::updateOrCreate(['key' => 'social_twitter'], ['value' => '#']);
        Setting::updateOrCreate(['key' => 'social_snapchat'], ['value' => '#']);

        // ===== Hero Video & Breadcrumb =====
        Setting::updateOrCreate(['key' => 'hero_video'], ['value' => null]);
        Setting::updateOrCreate(['key' => 'breadcrumb_bg'], ['value' => null]);
        Setting::updateOrCreate(['key' => 'about_why_choose_image'], ['value' => null]);

        $this->command->info('✅ StoreAppearanceSeeder completed successfully');
    }
}
