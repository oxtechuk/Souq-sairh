<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class DesignDataSeeder extends Seeder
{
    private string $sourcePath = '';

    private string $targetDir = 'new-store/images';

    private string $storageDisk = 'public';

    public function run(): void
    {
        $this->sourcePath = base_path('resources/views/souq_sayrat-main (1)/souq_sayrat-main/client/assets/images');

        if (! is_dir($this->sourcePath)) {
            $this->command->error('Source directory not found: '.$this->sourcePath);

            return;
        }

        $this->copyDirectory('', '');
        $this->copyDirectory('brands', 'brands');
        $this->copyDirectory('offers', 'offers');
        $this->copyDirectory('partners', 'partners');

        $this->seedSettings();

        $this->command->info('✅ DesignDataSeeder completed successfully');
    }

    private function copyDirectory(string $subDir, string $storageSubDir): void
    {
        $srcDir = $this->subPath($subDir);
        if (! is_dir($srcDir)) {
            return;
        }

        $targetPath = $this->targetDir.($storageSubDir ? '/'.$storageSubDir : '');
        Storage::disk($this->storageDisk)->makeDirectory($targetPath);

        $files = File::files($srcDir);
        foreach ($files as $file) {
            $destPath = $targetPath.'/'.$file->getFilename();
            Storage::disk($this->storageDisk)->put($destPath, $file->getContents());
            $this->command->line("  ✓ Copied {$file->getFilename()}");
        }
    }

    private function subPath(string $dir): string
    {
        return $dir ? $this->sourcePath.'/'.$dir : $this->sourcePath;
    }

    private function seedSettings(): void
    {
        $storagePath = 'new-store/images';

        Setting::updateOrCreate(['key' => 'logo'], ['value' => "{$storagePath}/Logo.svg"]);
        Setting::updateOrCreate(['key' => 'hero_ad_1_image'], ['value' => "{$storagePath}/car-slide-1.png"]);
        Setting::updateOrCreate(['key' => 'hero_ad_2_image'], ['value' => "{$storagePath}/car-slide-2.png"]);
        Setting::updateOrCreate(['key' => 'hero_ad_3_image'], ['value' => "{$storagePath}/car-slide-3.png"]);

        Setting::updateOrCreate(['key' => 'social_tiktok'], ['value' => '#']);
        Setting::updateOrCreate(['key' => 'social_facebook'], ['value' => '#']);
        Setting::updateOrCreate(['key' => 'social_instagram'], ['value' => '#']);

        $this->command->line('  ✓ Settings seeded successfully');
    }
}
