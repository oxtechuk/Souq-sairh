<?php

namespace App\Console\Commands;

use App\Models\Car;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class FixCarSlugs extends Command
{
    protected $signature = 'cars:fix-slugs
                            {--dry-run : عرض التغييرات فقط بدون تطبيقها}
                            {--force : تطبيق التغييرات مباشرة بدون تأكيد}';

    protected $description = 'تنظيف slugs السيارات — إزالة uniqid العشوائي وإصلاحها لتكون صديقة لـ SEO، مع حفظ القديم للـ 301 redirect';

    public function handle(): int
    {
        $isDryRun = $this->option('dry-run');
        $cars = Car::all(['id', 'name', 'slug', 'year']);

        $this->info("🔍 إجمالي السيارات: {$cars->count()}");
        $this->newLine();

        $changed = 0;
        $skipped = 0;
        $redirectMap = []; // old_slug => new_slug

        foreach ($cars as $car) {
            $oldSlug = $car->slug;

            // استخراج الاسم الإنجليزي من الـ slug (إزالة السنة وما بعدها)
            // النمط القديم: toyota-highlander-gle-hybrid-2026-2026-6a8cda3d85ab7
            // المطلوب: toyota-highlander-gle-hybrid-2026

            // جلب الاسم الإنجليزي من الـ model (translatable)
            $nameEn = is_array($car->getTranslations('name'))
                ? ($car->getTranslations('name')['en'] ?? $car->name)
                : $car->name;

            $baseSlug = Str::slug($nameEn . '-' . $car->year);

            // لو الـ slug الحالي يبدأ بالـ base ويحتوي hash أو سنة مكررة → يحتاج إصلاح
            $needsFix = ($oldSlug !== $baseSlug) && (
                str_contains($oldSlug, $car->year . '-' . $car->year) || // سنة مكررة
                preg_match('/[a-f0-9]{8,}/', $oldSlug) ||                // hash عشوائي
                strlen($oldSlug) > strlen($baseSlug) + 5                 // طويل جداً
            );

            if (! $needsFix) {
                $skipped++;
                continue;
            }

            // إيجاد slug فريد جديد
            $newSlug = $baseSlug;
            $counter = 1;
            while (
                Car::where('slug', $newSlug)->where('id', '!=', $car->id)->exists()
            ) {
                $newSlug = $baseSlug . '-' . $counter++;
            }

            $redirectMap[$oldSlug] = $newSlug;

            $this->line("  <fg=yellow>تعديل:</> <fg=red>{$oldSlug}</> → <fg=green>{$newSlug}</>");

            if (! $isDryRun) {
                $car->slug = $newSlug;
                $car->save();
            }

            $changed++;
        }

        $this->newLine();

        if ($isDryRun) {
            $this->warn("⚠️  وضع المعاينة — لم يتم تطبيق أي تغييرات");
        }

        $this->info("✅ تم تعديل: {$changed} سيارة");
        $this->info("⏭️  لم تحتج تعديل: {$skipped} سيارة");

        // حفظ خريطة الـ redirects في ملف
        if (! $isDryRun && count($redirectMap) > 0) {
            $redirectsPath = storage_path('app/car_slug_redirects.json');
            file_put_contents($redirectsPath, json_encode($redirectMap, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            $this->newLine();
            $this->info("📄 تم حفظ خريطة الـ redirects في: {$redirectsPath}");
            $this->info("   استخدمها لإضافة 301 redirects في الـ web.php أو .htaccess");
        }

        return self::SUCCESS;
    }
}
