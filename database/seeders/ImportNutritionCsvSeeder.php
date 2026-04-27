<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class ImportNutritionCsvSeeder extends Seeder
{
    public function run(): void
    {
        $csvPath = base_path('../AI_ck/data/raw/nutrition_data.csv');

        if (!is_file($csvPath)) {
            throw new RuntimeException("nutrition_data.csv not found at: {$csvPath}");
        }

        DB::table('foods')->delete();
        DB::table('food_categories')->delete();

        $handle = fopen($csvPath, 'rb');
        if ($handle === false) {
            throw new RuntimeException("Cannot open file: {$csvPath}");
        }

        $header = fgetcsv($handle);
        if (!$header) {
            fclose($handle);
            throw new RuntimeException('CSV header is empty.');
        }

        $header[0] = preg_replace('/^\xEF\xBB\xBF/', '', (string) $header[0]);
        $indexes = array_flip(array_map('trim', $header));

        $required = ['name_vi', 'category', 'calories', 'protein_g', 'carbs_g', 'fat_g'];
        foreach ($required as $col) {
            if (!array_key_exists($col, $indexes)) {
                fclose($handle);
                throw new RuntimeException("Missing required column: {$col}");
            }
        }

        $categories = [];
        $categoryRows = [];
        $foodRows = [];
        $seenNames = [];

        $categorySeq = 1;
        $foodSeq = 1;

        while (($row = fgetcsv($handle)) !== false) {
            $name = trim((string) ($row[$indexes['name_vi']] ?? ''));
            $categoryName = trim((string) ($row[$indexes['category']] ?? ''));
            $calories = (float) ($row[$indexes['calories']] ?? 0);
            $protein = (float) ($row[$indexes['protein_g']] ?? 0);
            $carbs = (float) ($row[$indexes['carbs_g']] ?? 0);
            $fat = (float) ($row[$indexes['fat_g']] ?? 0);

            if ($name === '' || $categoryName === '' || $calories <= 0) {
                continue;
            }

            $normalizedName = mb_strtolower(preg_replace('/\s+/', ' ', $name));
            if (isset($seenNames[$normalizedName])) {
                continue;
            }
            $seenNames[$normalizedName] = true;

            if (!isset($categories[$categoryName])) {
                $categoryId = sprintf('CAT%07d', $categorySeq++);
                $categories[$categoryName] = $categoryId;
                $categoryRows[] = [
                    'id' => $categoryId,
                    'name' => mb_substr($categoryName, 0, 255),
                    'description' => 'Imported from viendinhduong nutrition dataset',
                    'sort_order' => 0,
                ];
            }

            $foodRows[] = [
                'id' => sprintf('FOO%07d', $foodSeq++),
                'category_id' => $categories[$categoryName],
                'name' => mb_substr($name, 0, 255),
                'serving_size' => 100,
                'serving_unit' => 'g',
                'calories' => round($calories, 2),
                'protein' => max(0, round($protein, 2)),
                'carbs' => max(0, round($carbs, 2)),
                'fat' => max(0, round($fat, 2)),
                'meal_type' => 0,
                'popularity_score' => 0,
                'created_at' => now(),
            ];
        }

        fclose($handle);

        foreach (array_chunk($categoryRows, 200) as $chunk) {
            DB::table('food_categories')->insert($chunk);
        }

        foreach (array_chunk($foodRows, 500) as $chunk) {
            DB::table('foods')->insert($chunk);
        }

        $this->command->info('Imported categories: ' . count($categoryRows));
        $this->command->info('Imported foods: ' . count($foodRows));
    }
}
