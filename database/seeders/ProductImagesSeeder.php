<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ProductImagesSeeder extends Seeder
{
    /**
     * Directory used when saving food images (relative to public/uploads/).
     */
    private const IMAGE_DIR = 'foods';

    /**
     * Path to the manifest JSON produced by AI_ck/download_food_images.py.
     * Relative to the Laravel project root.
     */
    private const MANIFEST_PATH = '../AI_ck/data/food_images_manifest.json';

    /**
     * Size prefixes stored by FileService.php.
     * lg = full-size, md = medium, xs = small/thumbnail.
     */
    private const SIZE_PREFIXES = ['lg', 'md', 'xs'];

    public function run(): void
    {
        $manifestPath = base_path(self::MANIFEST_PATH);

        if (!file_exists($manifestPath)) {
            $this->command->warn(
                "Manifest not found: {$manifestPath}\n" .
                "Run the image downloader first:\n" .
                "  python AI_ck/download_food_images.py\n" .
                "Then re-run: php artisan db:seed --class=ProductImagesSeeder"
            );
            return;
        }

        $manifest = json_decode(file_get_contents($manifestPath), true);

        if (empty($manifest['foods'])) {
            $this->command->warn('Manifest is empty — nothing to seed.');
            return;
        }

        // Build lookups: exact name → id  AND  slug → id (for diacritics-insensitive match)
        $foodRows = DB::table('foods')->select('id', 'name')->get();

        if ($foodRows->isEmpty()) {
            $this->command->warn(
                'No foods found in DB. Run ImportNutritionCsvSeeder and ' .
                'SupplementalGymFoodsSeeder first.'
            );
            return;
        }

        $exactMap    = [];   // exact name  → id
        $slugMap     = [];   // slug (no diacritics, lowercase) → id
        foreach ($foodRows as $row) {
            $exactMap[$row->name]          = $row->id;
            $slugMap[createSlug($row->name)] = $row->id;
        }

        $storagePath = public_path('uploads/' . self::IMAGE_DIR);

        $inserted = 0;
        $skipped  = 0;

        foreach ($manifest['foods'] as $entry) {
            $foodName = $entry['food_name'] ?? '';
            $fileName = $entry['file_name'] ?? '';
            $fileExt  = $entry['file_ext']  ?? 'jpg';
            $success  = $entry['success']   ?? false;

            if (!$success || empty($fileName)) {
                $skipped++;
                continue;
            }

            // Match food name to DB id — try exact, then slug-based
            $foodId = $exactMap[$foodName] ?? null;
            if (!$foodId) {
                $slug   = createSlug($foodName);
                $foodId = $slugMap[$slug] ?? null;
            }

            if (!$foodId) {
                $skipped++;
                continue;
            }

            // Verify at least one size file exists on disk
            $hasFile = false;
            foreach (self::SIZE_PREFIXES as $prefix) {
                if (file_exists("{$storagePath}/{$prefix}_{$fileName}.{$fileExt}")) {
                    $hasFile = true;
                    break;
                }
            }

            if (!$hasFile) {
                $skipped++;
                continue;
            }

            // Skip if already seeded for this food
            $exists = DB::table('product_images')
                ->where('food_id', $foodId)
                ->exists();

            if ($exists) {
                $skipped++;
                continue;
            }

            DB::table('product_images')->insert([
                'id'         => generateRandomString(10),
                'food_id'    => $foodId,
                'directory'  => self::IMAGE_DIR,
                'file_name'  => $fileName,
                'file_ext'   => $fileExt,
                'is_primary' => 1,
                'sort_order' => 0,
                'created_at' => now(),
            ]);

            $inserted++;
        }

        $this->command->info(
            "ProductImagesSeeder: {$inserted} images inserted, {$skipped} skipped."
        );
    }
}
