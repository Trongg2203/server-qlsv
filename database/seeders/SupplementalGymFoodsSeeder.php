<?php
// -*- UTF-8 -*-
declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * SupplementalGymFoodsSeeder
 * 
 * Thêm các món ăn bổ sung cho gym với đầy đủ dấu tiếng Việt
 * Dữ liệu nghiên cứu từ: Viện Dinh Dưỡng Quốc Gia, USDA Food Database, các cookbook fitness Việt Nam
 * 
 * Source: https://viendinhduong.vn
 */
class SupplementalGymFoodsSeeder extends Seeder
{
    public function run(): void
    {
        $categoryMap = [];

        $existingCategories = DB::table('food_categories')->select(['id', 'name'])->get();
        foreach ($existingCategories as $category) {
            $categoryMap[mb_strtolower($category->name)] = $category->id;
        }

        // Dữ liệu bổ sung chi tiết cho gym: Cutting (cao protein, thấp carbs), Maintenance (cân bằng), Bulking (cao carbs+protein)
        // Nguồn: Viện Dinh Dưỡng Quốc Gia, các cookbook fitness Việt, dinh dưỡng truyền thống
        $supplementalFoods = [
            // ===== CUTTING (GIẢM CÂN): Cao Protein, Thấp Carbs, Thấp Fat =====
            // Thịt Gà
            ['category' => 'Món giảm cân', 'name' => 'Ức gà luộc', 'calories' => 165, 'protein' => 31.0, 'carbs' => 0.0, 'fat' => 3.6, 'meal_type' => 2],
            ['category' => 'Món giảm cân', 'name' => 'Ức gà nướng lá chanh', 'calories' => 172, 'protein' => 30.5, 'carbs' => 1.8, 'fat' => 4.1, 'meal_type' => 3],
            ['category' => 'Món giảm cân', 'name' => 'Ức gà hấp gừng', 'calories' => 168, 'protein' => 31.0, 'carbs' => 0.5, 'fat' => 3.8, 'meal_type' => 2],
            ['category' => 'Món giảm cân', 'name' => 'Gà nạc chiên không dầu', 'calories' => 185, 'protein' => 32.0, 'carbs' => 2.0, 'fat' => 5.0, 'meal_type' => 3],
            ['category' => 'Món giảm cân', 'name' => 'Dùm gà hấp', 'calories' => 145, 'protein' => 28.0, 'carbs' => 1.0, 'fat' => 2.5, 'meal_type' => 2],
            
            // Cá & Hải Sản
            ['category' => 'Món giảm cân', 'name' => 'Cá ngừ hấp', 'calories' => 132, 'protein' => 28.0, 'carbs' => 0.0, 'fat' => 1.3, 'meal_type' => 2],
            ['category' => 'Món giảm cân', 'name' => 'Cá hồi áp chảo ít dầu', 'calories' => 208, 'protein' => 22.0, 'carbs' => 0.0, 'fat' => 13.0, 'meal_type' => 3],
            ['category' => 'Món giảm cân', 'name' => 'Cá ba sa kho tiêu ít dầu', 'calories' => 176, 'protein' => 18.0, 'carbs' => 4.5, 'fat' => 8.5, 'meal_type' => 3],
            ['category' => 'Món giảm cân', 'name' => 'Cá bông hấp', 'calories' => 121, 'protein' => 26.0, 'carbs' => 0.5, 'fat' => 1.5, 'meal_type' => 2],
            ['category' => 'Món giảm cân', 'name' => 'Cá chép hấp', 'calories' => 127, 'protein' => 25.0, 'carbs' => 0.0, 'fat' => 2.8, 'meal_type' => 2],
            ['category' => 'Món giảm cân', 'name' => 'Tôm hấp sả', 'calories' => 99, 'protein' => 24.0, 'carbs' => 0.3, 'fat' => 0.3, 'meal_type' => 3],
            ['category' => 'Món giảm cân', 'name' => 'Tôm luộc nước muối', 'calories' => 106, 'protein' => 22.0, 'carbs' => 1.2, 'fat' => 0.8, 'meal_type' => 3],
            ['category' => 'Món giảm cân', 'name' => 'Mực luộc tỏi ớt', 'calories' => 93, 'protein' => 20.5, 'carbs' => 1.2, 'fat' => 0.8, 'meal_type' => 3],
            ['category' => 'Món giảm cân', 'name' => 'Cua luộc nước muối', 'calories' => 82, 'protein' => 18.0, 'carbs' => 0.0, 'fat' => 0.5, 'meal_type' => 3],
            
            // Trứng & Sữa
            ['category' => 'Món giảm cân', 'name' => 'Trứng gà luộc', 'calories' => 155, 'protein' => 13.0, 'carbs' => 1.1, 'fat' => 11.0, 'meal_type' => 1],
            ['category' => 'Món giảm cân', 'name' => 'Lòng trắng trứng luộc', 'calories' => 52, 'protein' => 11.0, 'carbs' => 0.7, 'fat' => 0.2, 'meal_type' => 1],
            ['category' => 'Món giảm cân', 'name' => 'Sữa chua Hy Lạp không đường', 'calories' => 59, 'protein' => 10.3, 'carbs' => 3.6, 'fat' => 0.4, 'meal_type' => 4],
            ['category' => 'Món giảm cân', 'name' => 'Phô mai cottage không béo', 'calories' => 98, 'protein' => 11.0, 'carbs' => 3.5, 'fat' => 5.0, 'meal_type' => 4],
            
            // Thịt Xỏ
            ['category' => 'Món giảm cân', 'name' => 'Thịt bò nạc hấp', 'calories' => 165, 'protein' => 28.0, 'carbs' => 0.0, 'fat' => 6.0, 'meal_type' => 2],
            ['category' => 'Món giảm cân', 'name' => 'Thịt bò xào cần tây', 'calories' => 189, 'protein' => 16.5, 'carbs' => 5.0, 'fat' => 11.0, 'meal_type' => 3],
            ['category' => 'Món giảm cân', 'name' => 'Thịt heo nạc hấp', 'calories' => 142, 'protein' => 26.0, 'carbs' => 0.0, 'fat' => 4.2, 'meal_type' => 2],
            ['category' => 'Món giảm cân', 'name' => 'Thịt vịt nạc luộc', 'calories' => 189, 'protein' => 28.0, 'carbs' => 0.0, 'fat' => 8.0, 'meal_type' => 3],
            
            // Đậu & Thực phẩm thực vật
            ['category' => 'Món giảm cân', 'name' => 'Đậu hũ non hấp nấm', 'calories' => 96, 'protein' => 10.5, 'carbs' => 3.5, 'fat' => 4.7, 'meal_type' => 3],
            ['category' => 'Món giảm cân', 'name' => 'Đậu hũ trắng hấp', 'calories' => 76, 'protein' => 8.0, 'carbs' => 1.5, 'fat' => 4.5, 'meal_type' => 3],
            ['category' => 'Món giảm cân', 'name' => 'Edamame hấp muối', 'calories' => 111, 'protein' => 11.0, 'carbs' => 10.0, 'fat' => 5.0, 'meal_type' => 4],
            
            // ===== MAINTENANCE (CÂN BẰNG): Protein + Carbs + Fat cân đối =====
            // Cơm & Carbs chính
            ['category' => 'Món đa dạng gym', 'name' => 'Cơm trắng nấu', 'calories' => 130, 'protein' => 2.6, 'carbs' => 28.0, 'fat' => 0.3, 'meal_type' => 2],
            ['category' => 'Món đa dạng gym', 'name' => 'Gạo lứt nấu', 'calories' => 111, 'protein' => 2.6, 'carbs' => 23.0, 'fat' => 0.9, 'meal_type' => 2],
            ['category' => 'Món đa dạng gym', 'name' => 'Cơm tấm gà nướng bơ dầu', 'calories' => 175, 'protein' => 14.0, 'carbs' => 22.0, 'fat' => 3.5, 'meal_type' => 2],
            ['category' => 'Món đa dạng gym', 'name' => 'Khoai lang luộc', 'calories' => 86, 'protein' => 1.6, 'carbs' => 20.1, 'fat' => 0.1, 'meal_type' => 2],
            ['category' => 'Món đa dạng gym', 'name' => 'Khoai tây hấp', 'calories' => 77, 'protein' => 1.7, 'carbs' => 17.0, 'fat' => 0.1, 'meal_type' => 2],
            ['category' => 'Món đa dạng gym', 'name' => 'Mì sợi nấu', 'calories' => 131, 'protein' => 3.3, 'carbs' => 25.0, 'fat' => 1.1, 'meal_type' => 2],
            ['category' => 'Món đa dạng gym', 'name' => 'Bánh mì trắng', 'calories' => 265, 'protein' => 9.0, 'carbs' => 49.0, 'fat' => 3.2, 'meal_type' => 1],
            ['category' => 'Món đa dạng gym', 'name' => 'Bánh mì đen nguyên cám', 'calories' => 247, 'protein' => 12.5, 'carbs' => 41.3, 'fat' => 4.2, 'meal_type' => 1],
            ['category' => 'Món đa dạng gym', 'name' => 'Yến mạch ngâm sữa không đường', 'calories' => 117, 'protein' => 4.4, 'carbs' => 19.9, 'fat' => 2.6, 'meal_type' => 1],
            ['category' => 'Món đa dạng gym', 'name' => 'Gạo dẻo nấu', 'calories' => 130, 'protein' => 2.5, 'carbs' => 28.0, 'fat' => 0.4, 'meal_type' => 2],
            
            // Mì & Noodles
            ['category' => 'Món đa dạng gym', 'name' => 'Phở bò nạc', 'calories' => 155, 'protein' => 11.8, 'carbs' => 18.5, 'fat' => 3.8, 'meal_type' => 2],
            ['category' => 'Món đa dạng gym', 'name' => 'Phở gà', 'calories' => 148, 'protein' => 12.0, 'carbs' => 19.0, 'fat' => 3.2, 'meal_type' => 2],
            ['category' => 'Món đa dạng gym', 'name' => 'Bún bò rau nhiều thịt nạc', 'calories' => 162, 'protein' => 12.0, 'carbs' => 19.0, 'fat' => 3.9, 'meal_type' => 2],
            ['category' => 'Món đa dạng gym', 'name' => 'Bún gà xay rau củ', 'calories' => 148, 'protein' => 12.5, 'carbs' => 18.0, 'fat' => 3.2, 'meal_type' => 2],
            ['category' => 'Món đa dạng gym', 'name' => 'Miến gà nấm', 'calories' => 138, 'protein' => 10.5, 'carbs' => 17.2, 'fat' => 2.8, 'meal_type' => 2],
            ['category' => 'Món đa dạng gym', 'name' => 'Hủ tiếu gà', 'calories' => 152, 'protein' => 11.0, 'carbs' => 18.0, 'fat' => 3.5, 'meal_type' => 2],
            ['category' => 'Món đa dạng gym', 'name' => 'Cơm chiên gà', 'calories' => 189, 'protein' => 13.0, 'carbs' => 24.0, 'fat' => 5.0, 'meal_type' => 2],
            ['category' => 'Món đa dạng gym', 'name' => 'Bánh canh cua', 'calories' => 168, 'protein' => 10.0, 'carbs' => 22.0, 'fat' => 4.0, 'meal_type' => 2],
            ['category' => 'Món đa dạng gym', 'name' => 'Bún cá thu', 'calories' => 160, 'protein' => 13.4, 'carbs' => 19.0, 'fat' => 3.4, 'meal_type' => 2],
            
            // Rau & Salad
            ['category' => 'Món đa dạng gym', 'name' => 'Bông cải xanh luộc', 'calories' => 34, 'protein' => 2.8, 'carbs' => 7.0, 'fat' => 0.4, 'meal_type' => 0],
            ['category' => 'Món đa dạng gym', 'name' => 'Đậu que luộc', 'calories' => 31, 'protein' => 1.8, 'carbs' => 7.0, 'fat' => 0.2, 'meal_type' => 0],
            ['category' => 'Món đa dạng gym', 'name' => 'Cà rốt luộc', 'calories' => 35, 'protein' => 0.8, 'carbs' => 8.2, 'fat' => 0.2, 'meal_type' => 0],
            ['category' => 'Món đa dạng gym', 'name' => 'Rau cần vịt luộc', 'calories' => 23, 'protein' => 2.9, 'carbs' => 3.6, 'fat' => 0.4, 'meal_type' => 0],
            ['category' => 'Món đa dạng gym', 'name' => 'Cải xoăn luộc', 'calories' => 49, 'protein' => 4.3, 'carbs' => 9.0, 'fat' => 0.6, 'meal_type' => 0],
            ['category' => 'Món đa dạng gym', 'name' => 'Rau spinach hấp', 'calories' => 23, 'protein' => 2.7, 'carbs' => 3.6, 'fat' => 0.4, 'meal_type' => 0],
            ['category' => 'Món đa dạng gym', 'name' => 'Nấm hương xào', 'calories' => 35, 'protein' => 1.7, 'carbs' => 6.0, 'fat' => 0.2, 'meal_type' => 0],
            ['category' => 'Món đa dạng gym', 'name' => 'Dưa chuột tươi', 'calories' => 16, 'protein' => 0.7, 'carbs' => 3.6, 'fat' => 0.1, 'meal_type' => 0],
            ['category' => 'Món đa dạng gym', 'name' => 'Cà chua tươi', 'calories' => 18, 'protein' => 0.9, 'carbs' => 3.9, 'fat' => 0.2, 'meal_type' => 0],
            ['category' => 'Món đa dạng gym', 'name' => 'Xà lách trộn dầu olive', 'calories' => 58, 'protein' => 1.2, 'carbs' => 2.2, 'fat' => 5.0, 'meal_type' => 0],
            
            // Trái cây & Đồ uống
            ['category' => 'Món đa dạng gym', 'name' => 'Chuối chín', 'calories' => 89, 'protein' => 1.1, 'carbs' => 22.8, 'fat' => 0.3, 'meal_type' => 4],
            ['category' => 'Món đa dạng gym', 'name' => 'Táo đỏ', 'calories' => 52, 'protein' => 0.3, 'carbs' => 13.8, 'fat' => 0.2, 'meal_type' => 4],
            ['category' => 'Món đa dạng gym', 'name' => 'Cam tươi', 'calories' => 47, 'protein' => 0.9, 'carbs' => 11.8, 'fat' => 0.3, 'meal_type' => 4],
            ['category' => 'Món đa dạng gym', 'name' => 'Nho tươi', 'calories' => 67, 'protein' => 0.6, 'carbs' => 16.0, 'fat' => 0.2, 'meal_type' => 4],
            ['category' => 'Món đa dạng gym', 'name' => 'Dâu tây tươi', 'calories' => 32, 'protein' => 0.7, 'carbs' => 7.7, 'fat' => 0.3, 'meal_type' => 4],
            ['category' => 'Món đa dạng gym', 'name' => 'Dâu đen hầm', 'calories' => 132, 'protein' => 8.9, 'carbs' => 23.7, 'fat' => 0.5, 'meal_type' => 4],
            ['category' => 'Món đa dạng gym', 'name' => 'Đậu lăng hầm', 'calories' => 116, 'protein' => 9.0, 'carbs' => 20.1, 'fat' => 0.4, 'meal_type' => 4],
            ['category' => 'Món đa dạng gym', 'name' => 'Đu đủ tươi', 'calories' => 43, 'protein' => 0.5, 'carbs' => 10.8, 'fat' => 0.3, 'meal_type' => 4],
            ['category' => 'Món đa dạng gym', 'name' => 'Dưa hấu tươi', 'calories' => 30, 'protein' => 0.6, 'carbs' => 7.6, 'fat' => 0.2, 'meal_type' => 4],
            
            // ===== BULKING (TĂNG CÂN): Cao Protein + Cao Carbs + Calories =====
            // Thịt & Protein cao
            ['category' => 'Món tăng cân', 'name' => 'Cá thu nướng', 'calories' => 205, 'protein' => 20.0, 'carbs' => 0.0, 'fat' => 13.2, 'meal_type' => 3],
            ['category' => 'Món tăng cân', 'name' => 'Thịt gà rán giòn', 'calories' => 256, 'protein' => 26.0, 'carbs' => 7.0, 'fat' => 13.0, 'meal_type' => 3],
            ['category' => 'Món tăng cân', 'name' => 'Cá hồi nướng', 'calories' => 280, 'protein' => 25.0, 'carbs' => 0.0, 'fat' => 20.0, 'meal_type' => 3],
            ['category' => 'Món tăng cân', 'name' => 'Thịt bò nướng', 'calories' => 271, 'protein' => 26.0, 'carbs' => 0.0, 'fat' => 18.0, 'meal_type' => 3],
            
            // Carbs & Năng lượng cao
            ['category' => 'Món tăng cân', 'name' => 'Hạt hạnh nhân rang không muối', 'calories' => 579, 'protein' => 21.2, 'carbs' => 21.6, 'fat' => 49.9, 'meal_type' => 4],
            ['category' => 'Món tăng cân', 'name' => 'Bơ (nửa trái)', 'calories' => 160, 'protein' => 2.0, 'carbs' => 8.6, 'fat' => 14.7, 'meal_type' => 4],
            ['category' => 'Món tăng cân', 'name' => 'Hạt điều rang', 'calories' => 553, 'protein' => 18.2, 'carbs' => 30.0, 'fat' => 43.8, 'meal_type' => 4],
            ['category' => 'Món tăng cân', 'name' => 'Mật ong nguyên chất', 'calories' => 304, 'protein' => 0.3, 'carbs' => 82.0, 'fat' => 0.0, 'meal_type' => 4],
            ['category' => 'Món tăng cân', 'name' => 'Dầu dừa nguyên chất', 'calories' => 892, 'protein' => 0.0, 'carbs' => 0.0, 'fat' => 99.0, 'meal_type' => 4],
            ['category' => 'Món tăng cân', 'name' => 'Bột cacao nguyên chất', 'calories' => 328, 'protein' => 12.0, 'carbs' => 46.0, 'fat' => 12.0, 'meal_type' => 4],
            
            // Sữa & Dairy
            ['category' => 'Món tăng cân', 'name' => 'Sữa tươi không đường 200ml', 'calories' => 134, 'protein' => 6.6, 'carbs' => 9.8, 'fat' => 7.7, 'meal_type' => 1],
            ['category' => 'Món tăng cân', 'name' => 'Sữa đặc 200ml', 'calories' => 326, 'protein' => 8.0, 'carbs' => 54.0, 'fat' => 8.2, 'meal_type' => 1],
            ['category' => 'Món tăng cân', 'name' => 'Phô mai mozzarella', 'calories' => 280, 'protein' => 28.0, 'carbs' => 3.2, 'fat' => 17.0, 'meal_type' => 4],
            
            // Trứng
            ['category' => 'Món tăng cân', 'name' => 'Trứng gà chiên', 'calories' => 155, 'protein' => 13.6, 'carbs' => 1.1, 'fat' => 11.0, 'meal_type' => 1],
            ['category' => 'Món tăng cân', 'name' => 'Trứng gà xào tỏi', 'calories' => 168, 'protein' => 13.0, 'carbs' => 2.0, 'fat' => 12.0, 'meal_type' => 1],
            ['category' => 'Món tăng cân', 'name' => 'Trứng cút nướng', 'calories' => 180, 'protein' => 16.0, 'carbs' => 3.0, 'fat' => 11.0, 'meal_type' => 1],
            
            // Đồ ăn nhẹ & Carbs
            ['category' => 'Món tăng cân', 'name' => 'Bánh quy bơ', 'calories' => 491, 'protein' => 5.0, 'carbs' => 55.0, 'fat' => 28.0, 'meal_type' => 4],
            ['category' => 'Món tăng cân', 'name' => 'Granola ngũ cốc', 'calories' => 471, 'protein' => 11.0, 'carbs' => 63.0, 'fat' => 20.0, 'meal_type' => 1],
            ['category' => 'Món tăng cân', 'name' => 'Đậu phộng rang muối', 'calories' => 588, 'protein' => 25.8, 'carbs' => 20.0, 'fat' => 49.2, 'meal_type' => 4],
            ['category' => 'Món tăng cân', 'name' => 'Socola đen 70%', 'calories' => 598, 'protein' => 12.0, 'carbs' => 45.0, 'fat' => 43.0, 'meal_type' => 4],
            
            // Smoothie & Thức uống
            ['category' => 'Món tăng cân', 'name' => 'Smoothie chuối + sữa', 'calories' => 156, 'protein' => 5.0, 'carbs' => 28.0, 'fat' => 2.0, 'meal_type' => 4],
            ['category' => 'Món tăng cân', 'name' => 'Nước cam tươi', 'calories' => 47, 'protein' => 0.9, 'carbs' => 11.8, 'fat' => 0.3, 'meal_type' => 4],
            ['category' => 'Món tăng cân', 'name' => 'Nước mía tươi', 'calories' => 50, 'protein' => 0.2, 'carbs' => 12.0, 'fat' => 0.0, 'meal_type' => 4],
            
            // ===== INTERNATIONAL GYM FOODS (Quốc tế có thể nấu ở Việt Nam) =====
            // Asian Fusion & International Friendly
            ['category' => 'Món quốc tế gym', 'name' => 'Ức gà xào bông cải xanh', 'calories' => 220, 'protein' => 25.0, 'carbs' => 12.0, 'fat' => 6.0, 'meal_type' => 2],
            ['category' => 'Món quốc tế gym', 'name' => 'Thịt bò kiểu Hàn nướng', 'calories' => 280, 'protein' => 24.0, 'carbs' => 8.0, 'fat' => 16.0, 'meal_type' => 3],
            ['category' => 'Món quốc tế gym', 'name' => 'Gà cà ri xanh kiểu Thái', 'calories' => 320, 'protein' => 26.0, 'carbs' => 15.0, 'fat' => 16.0, 'meal_type' => 2],
            ['category' => 'Món quốc tế gym', 'name' => 'Cá hồi sốt teriyaki', 'calories' => 310, 'protein' => 28.0, 'carbs' => 12.0, 'fat' => 15.0, 'meal_type' => 3],
            ['category' => 'Món quốc tế gym', 'name' => 'Taco cá nướng', 'calories' => 320, 'protein' => 24.0, 'carbs' => 38.0, 'fat' => 6.0, 'meal_type' => 2],
            ['category' => 'Món quốc tế gym', 'name' => 'Gà xào rau củ lẫn lộn', 'calories' => 240, 'protein' => 26.0, 'carbs' => 14.0, 'fat' => 8.0, 'meal_type' => 2],
            ['category' => 'Món quốc tế gym', 'name' => 'Mì Ý sốt thịt bò nạc', 'calories' => 520, 'protein' => 28.0, 'carbs' => 58.0, 'fat' => 15.0, 'meal_type' => 2],
            ['category' => 'Món quốc tế gym', 'name' => 'Bánh pancake protein', 'calories' => 280, 'protein' => 20.0, 'carbs' => 35.0, 'fat' => 5.0, 'meal_type' => 1],
            ['category' => 'Món quốc tế gym', 'name' => 'Sữa chua Hy Lạp + ngũ cốc', 'calories' => 280, 'protein' => 15.0, 'carbs' => 42.0, 'fat' => 4.0, 'meal_type' => 1],
            ['category' => 'Món quốc tế gym', 'name' => 'Hải sản nướng lẫn lộn', 'calories' => 180, 'protein' => 28.0, 'carbs' => 3.0, 'fat' => 5.0, 'meal_type' => 3],
            
            // Rice & Grain Dishes (with Protein)
            ['category' => 'Món quốc tế gym', 'name' => 'Gạo lứt + thịt nạc nướng', 'calories' => 280, 'protein' => 18.0, 'carbs' => 33.0, 'fat' => 5.0, 'meal_type' => 2],
            ['category' => 'Món quốc tế gym', 'name' => 'Cơm chiên gà nạc', 'calories' => 310, 'protein' => 20.0, 'carbs' => 35.0, 'fat' => 8.0, 'meal_type' => 2],
            ['category' => 'Món quốc tế gym', 'name' => 'Quinoa nấu với rau + gà', 'calories' => 340, 'protein' => 22.0, 'carbs' => 42.0, 'fat' => 8.0, 'meal_type' => 2],
            ['category' => 'Món quốc tế gym', 'name' => 'Lúa mạch nấu + cá hấp', 'calories' => 320, 'protein' => 26.0, 'carbs' => 38.0, 'fat' => 6.0, 'meal_type' => 2],
            ['category' => 'Món quốc tế gym', 'name' => 'Mì ống lứt + sốt cà chua', 'calories' => 380, 'protein' => 16.0, 'carbs' => 65.0, 'fat' => 4.0, 'meal_type' => 2],
            
            // Salads & Light Meals
            ['category' => 'Món quốc tế gym', 'name' => 'Salad cá ngừ dầu olive', 'calories' => 220, 'protein' => 25.0, 'carbs' => 12.0, 'fat' => 8.0, 'meal_type' => 2],
            ['category' => 'Món quốc tế gym', 'name' => 'Salad gà nướng + rau xanh', 'calories' => 210, 'protein' => 28.0, 'carbs' => 10.0, 'fat' => 6.0, 'meal_type' => 2],
            ['category' => 'Món quốc tế gym', 'name' => 'Salad đậu gà + rau tươi', 'calories' => 200, 'protein' => 12.0, 'carbs' => 28.0, 'fat' => 5.0, 'meal_type' => 2],
            ['category' => 'Món quốc tế gym', 'name' => 'Salad tôm + dâu tây', 'calories' => 165, 'protein' => 24.0, 'carbs' => 8.0, 'fat' => 4.0, 'meal_type' => 2],
            
            // High-Protein Comfort Foods
            ['category' => 'Món quốc tế gym', 'name' => 'Bánh hamburger thịt nạc', 'calories' => 420, 'protein' => 28.0, 'carbs' => 42.0, 'fat' => 12.0, 'meal_type' => 2],
            ['category' => 'Món quốc tế gym', 'name' => 'Gà chiên không dầu + cơm', 'calories' => 380, 'protein' => 32.0, 'carbs' => 28.0, 'fat' => 8.0, 'meal_type' => 2],
            ['category' => 'Món quốc tế gym', 'name' => 'Pizza topping gà + rau', 'calories' => 380, 'protein' => 22.0, 'carbs' => 42.0, 'fat' => 12.0, 'meal_type' => 2],
            ['category' => 'Món quốc tế gym', 'name' => 'Bánh sandwich ức gà', 'calories' => 380, 'protein' => 28.0, 'carbs' => 35.0, 'fat' => 10.0, 'meal_type' => 1],
            
            // Soups & Broths
            ['category' => 'Món quốc tế gym', 'name' => 'Canh thịt bò + rau', 'calories' => 145, 'protein' => 18.0, 'carbs' => 8.0, 'fat' => 3.0, 'meal_type' => 2],
            ['category' => 'Món quốc tế gym', 'name' => 'Canh gà + noodle nguyên hạt', 'calories' => 240, 'protein' => 22.0, 'carbs' => 24.0, 'fat' => 4.0, 'meal_type' => 2],
            ['category' => 'Món quốc tế gym', 'name' => 'Sup hải sản + rau', 'calories' => 180, 'protein' => 24.0, 'carbs' => 6.0, 'fat' => 5.0, 'meal_type' => 2],
            ['category' => 'Món quốc tế gym', 'name' => 'Sup đậu lăng + rau', 'calories' => 210, 'protein' => 14.0, 'carbs' => 32.0, 'fat' => 2.0, 'meal_type' => 2],
            
            // Pre/Post Workout Meals
            ['category' => 'Món quốc tế gym', 'name' => 'Bánh gạo + bơ đậu phộng', 'calories' => 380, 'protein' => 14.0, 'carbs' => 44.0, 'fat' => 17.0, 'meal_type' => 4],
            ['category' => 'Món quốc tế gym', 'name' => 'Chuối + mật ong tự nhiên', 'calories' => 240, 'protein' => 1.2, 'carbs' => 56.0, 'fat' => 0.5, 'meal_type' => 4],
            ['category' => 'Món quốc tế gym', 'name' => 'Sinh tố protein chuối', 'calories' => 290, 'protein' => 28.0, 'carbs' => 38.0, 'fat' => 3.0, 'meal_type' => 4],
            ['category' => 'Món quốc tế gym', 'name' => 'Hỗn hợp hạt + hoa quả', 'calories' => 380, 'protein' => 12.0, 'carbs' => 45.0, 'fat' => 16.0, 'meal_type' => 4],
            
            // Breakfast Specials
            ['category' => 'Món quốc tế gym', 'name' => 'Trứng omelet + rau cà chua', 'calories' => 180, 'protein' => 16.0, 'carbs' => 4.0, 'fat' => 10.0, 'meal_type' => 1],
            ['category' => 'Món quốc tế gym', 'name' => 'Trứng trộn + bánh mì nguyên hạt', 'calories' => 320, 'protein' => 18.0, 'carbs' => 32.0, 'fat' => 11.0, 'meal_type' => 1],
            ['category' => 'Món quốc tế gym', 'name' => 'Granola + sữa tươi', 'calories' => 380, 'protein' => 10.0, 'carbs' => 62.0, 'fat' => 8.0, 'meal_type' => 1],
            ['category' => 'Món quốc tế gym', 'name' => 'Trứng cút nướng + khoai tây', 'calories' => 240, 'protein' => 18.0, 'carbs' => 18.0, 'fat' => 8.0, 'meal_type' => 1],
        ];


        $foodsToInsert = [];

        $existingFoodNames = DB::table('foods')->pluck('name')->map(function ($name) {
            return mb_strtolower(trim((string) $name));
        })->all();
        $existingFoodSet = array_fill_keys($existingFoodNames, true);

        $categoryCounter = (int) DB::table('food_categories')->count() + 1;
        $foodCounter = (int) DB::table('foods')->count() + 1;

        foreach ($supplementalFoods as $item) {
            $categoryKey = mb_strtolower($item['category']);
            if (!isset($categoryMap[$categoryKey])) {
                $categoryId = sprintf('CAT%07d', $categoryCounter++);
                $categoryMap[$categoryKey] = $categoryId;

                DB::table('food_categories')->insert([
                    'id' => $categoryId,
                    'name' => $item['category'],
                    'description' => 'Danh mục thực phẩm gym bổ sung từ Viện Dinh Dưỡng Quốc Gia',
                    'sort_order' => 0,
                ]);
            }

            $normalizedFoodName = mb_strtolower(trim($item['name']));
            if (isset($existingFoodSet[$normalizedFoodName])) {
                continue;
            }

            $existingFoodSet[$normalizedFoodName] = true;
            $foodsToInsert[] = [
                'id' => sprintf('FOO%07d', $foodCounter++),
                'category_id' => $categoryMap[$categoryKey],
                'name' => $item['name'],
                'serving_size' => 100,
                'serving_unit' => 'g',
                'calories' => $item['calories'],
                'protein' => $item['protein'],
                'carbs' => $item['carbs'],
                'fat' => $item['fat'],
                'meal_type' => $item['meal_type'],
                'popularity_score' => 0,
                'created_at' => now(),
            ];
        }

        foreach (array_chunk($foodsToInsert, 200) as $chunk) {
            DB::table('foods')->insert($chunk);
        }
    }
}
