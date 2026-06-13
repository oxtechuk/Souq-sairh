<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use App\Models\Brand;
use App\Models\Car;
use App\Models\CarCategory;
use App\Models\CarImage;
use App\Models\Offer;
use App\Models\Partner;
use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class DesignContentSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedBrands();
        $this->seedCarCategories();
        $this->seedCars();
        $this->seedTestimonials();
        $this->seedPartners();
        $this->seedOffers();
        $this->seedBlogPosts();

        $this->command->info('✅ DesignContentSeeder completed successfully');
    }

    private function storagePath(string $relative): string
    {
        return 'new-store/images/'.ltrim($relative, '/');
    }

    private function seedBrands(): void
    {
        $brands = [
            ['name' => 'براند 1', 'slug' => 'brand-1', 'logo' => $this->storagePath('brands/brand-1.svg')],
            ['name' => 'براند 2', 'slug' => 'brand-2', 'logo' => $this->storagePath('brands/brand-2.svg')],
            ['name' => 'براند 3', 'slug' => 'brand-3', 'logo' => $this->storagePath('brands/brand-3.svg')],
        ];

        foreach ($brands as $brand) {
            Brand::firstOrCreate(['slug' => $brand['slug']], $brand);
        }

        $this->command->line('  ✓ Seeded '.count($brands).' brands');
    }

    private function seedCarCategories(): void
    {
        $categories = [
            ['name' => 'SUV', 'slug' => 'suv', 'sort_order' => 1],
            ['name' => 'سيدان', 'slug' => 'sedan', 'sort_order' => 2],
            ['name' => 'هاتشباك', 'slug' => 'hatchback', 'sort_order' => 3],
            ['name' => 'كوبيه', 'slug' => 'coupe', 'sort_order' => 4],
            ['name' => 'بيك أب', 'slug' => 'pickup', 'sort_order' => 5],
        ];

        foreach ($categories as $category) {
            CarCategory::firstOrCreate(['slug' => $category['slug']], $category);
        }

        $this->command->line('  ✓ Seeded '.count($categories).' car categories');
    }

    private function seedCars(): void
    {
        $categoryId = CarCategory::where('slug', 'suv')->value('id');
        $brandId = Brand::where('slug', 'brand-1')->value('id');

        $carsData = [
            ['name' => 'كيا سبورتاج', 'slug' => 'kia-sportage-1', 'model' => 'GLS 1.6L', 'year' => 2024, 'cash_price' => 92000, 'min_installment' => 92000, 'specs' => ['fuel' => 'بنزين', 'transmission' => 'أوتوماتيك', 'seats' => '5', 'type' => 'SUV']],
            ['name' => 'كيا سبورتاج', 'slug' => 'kia-sportage-2', 'model' => 'GLS 1.6L', 'year' => 2024, 'cash_price' => 92000, 'min_installment' => 92000, 'specs' => ['fuel' => 'بنزين', 'transmission' => 'أوتوماتيك', 'seats' => '5', 'type' => 'SUV']],
            ['name' => 'كيا سبورتاج', 'slug' => 'kia-sportage-3', 'model' => 'GLS 1.6L', 'year' => 2024, 'cash_price' => 92000, 'min_installment' => 92000, 'specs' => ['fuel' => 'بنزين', 'transmission' => 'أوتوماتيك', 'seats' => '5', 'type' => 'SUV']],
            ['name' => 'كيا سبورتاج', 'slug' => 'kia-sportage-4', 'model' => 'GLS 1.6L', 'year' => 2024, 'cash_price' => 92000, 'min_installment' => 92000, 'specs' => ['fuel' => 'بنزين', 'transmission' => 'أوتوماتيك', 'seats' => '5', 'type' => 'SUV']],
            ['name' => 'كيا سبورتاج', 'slug' => 'kia-sportage-5', 'model' => 'GLS 1.6L', 'year' => 2024, 'cash_price' => 92000, 'min_installment' => 92000, 'specs' => ['fuel' => 'بنزين', 'transmission' => 'أوتوماتيك', 'seats' => '5', 'type' => 'SUV']],
            ['name' => 'كيا سبورتاج', 'slug' => 'kia-sportage-6', 'model' => 'GLS 1.6L', 'year' => 2024, 'cash_price' => 92000, 'min_installment' => 92000, 'specs' => ['fuel' => 'بنزين', 'transmission' => 'أوتوماتيك', 'seats' => '5', 'type' => 'SUV']],
        ];

        $thumbnail = $this->storagePath('car-1.png');

        foreach ($carsData as $i => $car) {
            $carModel = Car::firstOrCreate(['slug' => $car['slug']], [
                'brand_id' => $brandId,
                'category_id' => $categoryId,
                'name' => $car['name'],
                'model' => $car['model'],
                'year' => $car['year'],
                'type' => 'suv',
                'cash_price' => $car['cash_price'],
                'min_down_payment' => 0,
                'min_installment' => $car['min_installment'],
                'specs' => $car['specs'],
                'thumbnail' => $thumbnail,
                'is_featured' => $i < 3,
                'is_active' => true,
                'is_highlighted' => 'none',
                'availability_status' => 'available',
            ]);

            if ($i === 0) {
                $this->seedCarImages($carModel->id);
            }
        }

        $this->command->line('  ✓ Seeded '.count($carsData).' cars');
    }

    private function seedCarImages(int $carId): void
    {
        $paths = [
            ['path' => $this->storagePath('car-1.png'), 'alt' => 'صورة خارجية 1', 'sort_order' => 1],
            ['path' => $this->storagePath('car-1.png'), 'alt' => 'صورة خارجية 2', 'sort_order' => 2],
            ['path' => $this->storagePath('car-1.png'), 'alt' => 'صورة خارجية 3', 'sort_order' => 3],
            ['path' => $this->storagePath('car-1.png'), 'alt' => 'صورة خارجية 4', 'sort_order' => 4],
            ['path' => $this->storagePath('car-1.png'), 'alt' => 'صورة داخلية 1', 'sort_order' => 5],
            ['path' => $this->storagePath('car-1.png'), 'alt' => 'صورة داخلية 2', 'sort_order' => 6],
            ['path' => $this->storagePath('car-1.png'), 'alt' => 'صورة داخلية 3', 'sort_order' => 7],
            ['path' => $this->storagePath('car-1.png'), 'alt' => 'صورة داخلية 4', 'sort_order' => 8],
        ];

        foreach ($paths as $image) {
            CarImage::create([
                'car_id' => $carId,
                'image_path' => $image['path'],
                'alt' => $image['alt'],
                'sort_order' => $image['sort_order'],
            ]);
        }

        $this->command->line('  ✓ Seeded '.count($paths).' car images');
    }

    private function seedTestimonials(): void
    {
        $testimonials = [
            ['name' => 'محمد القحطاني', 'title' => 'مهندس', 'content' => 'افضل معرض للسيارات الفاخرة في الرياض، خيارات متنوعة والاسعار ممتازة والتعامل سهل.', 'rating' => 5],
            ['name' => 'سارة الرفاعي', 'title' => 'مستشارة تقنية', 'content' => 'معرض السيارات الكهربائية في جدة يضم احدث الطرازات من الشركات العالمية وخدمات متكاملة.', 'rating' => 5],
            ['name' => 'احمد السالم', 'title' => 'طبيب', 'content' => 'معرض السيارات الكلاسيكية في الدمام يتيح للزوار تجربة سيارات تاريخية نادرة وصيانتها.', 'rating' => 5],
            ['name' => 'خالد العتيبي', 'title' => 'رجل اعمال', 'content' => 'خدمة ممتازة وفريق متعاون جدا، ساعدوني في اختيار السيارة المناسبة لميزانيتي بكل سهولة.', 'rating' => 5],
            ['name' => 'نورة الشمري', 'title' => 'معلمة', 'content' => 'تجربة شراء رائعة من البداية للنهاية، الاسعار تنافسية وخيارات التمويل مرنة جدا.', 'rating' => 5],
            ['name' => 'فيصل الدوسري', 'title' => 'محاسب', 'content' => 'انصح الجميع بزيارة سوق سيارة، الموظفون محترفون والسيارات بحالة ممتازة.', 'rating' => 5],
            ['name' => 'ريم الزهراني', 'title' => 'مديرة مشاريع', 'content' => 'حصلت على سيارتي بافضل سعر واسرع وقت، الخدمة احترافية والتوصيل كان في الموعد.', 'rating' => 5],
            ['name' => 'عبدالله المطيري', 'title' => 'مقاول', 'content' => 'تعاملت معهم مرتين وفي كل مرة تجربة افضل، ثقة عالية وشفافية تامة في التسعير.', 'rating' => 5],
            ['name' => 'هند الغامدي', 'title' => 'صيدلانية', 'content' => 'من افضل التجارب التي مررت بها في شراء سيارة، سهولة في الاجراءات وسرعة في التسليم.', 'rating' => 5],
        ];

        foreach ($testimonials as $t) {
            Testimonial::firstOrCreate(
                ['name' => ['ar' => $t['name']]],
                [
                    'name' => ['ar' => $t['name']],
                    'title' => ['ar' => $t['title']],
                    'content' => ['ar' => $t['content']],
                    'rating' => $t['rating'],
                    'is_visible' => true,
                ]
            );
        }

        $this->command->line('  ✓ Seeded '.count($testimonials).' testimonials');
    }

    private function seedPartners(): void
    {
        $partners = [
            ['name' => 'BSF', 'logo' => $this->storagePath('partners/bsf.png'), 'link' => '#', 'sort_order' => 1],
            ['name' => 'مصرف الإنماء', 'logo' => $this->storagePath('partners/alinma.png'), 'link' => '#', 'sort_order' => 2],
            ['name' => 'بنك البلاد', 'logo' => $this->storagePath('partners/albilad.png'), 'link' => '#', 'sort_order' => 3],
            ['name' => 'مصرف الراجحي', 'logo' => $this->storagePath('partners/alrajhi.png'), 'link' => '#', 'sort_order' => 4],
            ['name' => 'عبد اللطيف جميل', 'logo' => $this->storagePath('partners/abdul-latif.png'), 'link' => '#', 'sort_order' => 5],
        ];

        foreach ($partners as $partner) {
            Partner::firstOrCreate(['name' => $partner['name']], $partner);
        }

        $this->command->line('  ✓ Seeded '.count($partners).' partners');
    }

    private function seedOffers(): void
    {
        $carId = Car::min('id');

        if (! $carId) {
            $this->command->warn('  ⚠ No cars found, skipping offers');

            return;
        }

        $offers = [
            ['title' => 'عروض التمويل الشخصي', 'description' => 'استلم سيارتك بعائدين ربح تنافسي', 'image' => $this->storagePath('offer-card-1.jpg')],
            ['title' => 'عروض التمويل الشخصي', 'description' => 'استلم سيارتك بعائدين ربح تنافسي', 'image' => $this->storagePath('offer-card-2.jpg')],
            ['title' => 'عروض التمويل الشخصي', 'description' => 'استلم سيارتك بعائدين ربح تنافسي', 'image' => $this->storagePath('offer-card-3.jpg')],
        ];

        foreach ($offers as $i => $offer) {
            Offer::create([
                'car_id' => $carId + ($i % Car::count()),
                'title' => $offer['title'],
                'description' => $offer['description'],
                'image' => $offer['image'],
                'is_active' => true,
                'starts_at' => now(),
            ]);
        }

        $this->command->line('  ✓ Seeded '.count($offers).' offers');
    }

    private function seedBlogPosts(): void
    {
        $thumbnails = [
            $this->storagePath('offer-card-1.jpg'),
            $this->storagePath('offer-card-2.jpg'),
            $this->storagePath('offer-card-3.jpg'),
        ];

        $articles = [];
        for ($i = 0; $i < 6; $i++) {
            $articles[] = [
                'title' => 'كيف تختار السيارة الكهربائية المناسبة؟',
                'slug' => 'electric-car-guide-'.($i + 1),
                'thumbnail' => $thumbnails[$i % 3],
                'excerpt' => 'نصائح مهمة لاختيار السيارة الكهربائية التي تناسب احتياجاتك وميزانيتك',
                'content' => 'نصائح مهمة لاختيار السيارة الكهربائية التي تناسب احتياجاتك وميزانيتك. مع تزايد انتشار السيارات الكهربائية في المملكة، أصبح من المهم معرفة كيفية اختيار السيارة المناسبة لك. في هذا الدليل، نقدم لك أبرز النصائح والعوامل التي يجب مراعاتها عند شراء سيارتك الكهربائية الأولى.',
                'is_published' => true,
                'is_featured' => $i < 3,
                'published_at' => now()->subDays($i),
            ];
        }

        foreach ($articles as $article) {
            BlogPost::firstOrCreate(['slug' => $article['slug']], $article);
        }

        $this->command->line('  ✓ Seeded '.count($articles).' blog posts');
    }
}
