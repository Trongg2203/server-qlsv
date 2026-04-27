<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Seed 80 người dùng gym Việt Nam thực tế cùng với:
 * - user_profiles  (thông tin thể chất)
 * - user_goals     (mục tiêu tập luyện: cutting / bulking / maintaining)
 * - calorie_calculations (BMR / TDEE / macro targets)
 *
 * Thứ tự insert: users → user_profiles → user_goals → calorie_calculations
 */
class GymUsersSeeder extends Seeder
{
    /* ──────────────────────────────────────────
     |  HẰng số
     | ────────────────────────────────────────── */
    const GOAL_CUTTING    = 0;
    const GOAL_BULKING    = 1;
    const GOAL_MAINTAINING = 2;

    // Activity level (theo UserProfileModel): 0=Sedentary .. 4=Extremely Active
    const ACT_SEDENTARY     = 0;   // ×1.2
    const ACT_LIGHT         = 1;   // ×1.375
    const ACT_MODERATE      = 2;   // ×1.55
    const ACT_VERY          = 3;   // ×1.725
    const ACT_EXTREME       = 4;   // ×1.9

    /* ──────────────────────────────────────────
     |  Dữ liệu người dùng (80 người)
     | ────────────────────────────────────────── */
    private function userProfiles(): array
    {
        // [name, email_prefix, gender (0=nam/1=nữ), dob YYYY-MM-DD, height_cm, weight_kg, activity, goal]
        // Phân phối: 40 nam / 40 nữ; 30% cutting / 40% bulking / 30% maintaining
        return [
            // ── NAM (40) ──────────────────────────────────────
            ['Nguyễn Văn Nam',   'nguyen.van.nam',   0, '2001-03-15', 172, 70, 3, 1],
            ['Trần Minh Tuấn',   'tran.minh.tuan',   0, '1999-07-22', 175, 75, 3, 1],
            ['Lê Hoàng Phúc',    'le.hoang.phuc',    0, '2002-11-05', 170, 65, 2, 0],
            ['Phạm Văn Đức',     'pham.van.duc',     0, '1998-04-18', 178, 80, 4, 1],
            ['Hoàng Minh Khoa',  'hoang.minh.khoa',  0, '2003-08-30', 168, 62, 2, 1],
            ['Vũ Quang Hùng',    'vu.quang.hung',    0, '1997-12-01', 180, 85, 4, 1],
            ['Đặng Văn Bảo',     'dang.van.bao',     0, '2000-05-14', 173, 73, 3, 0],
            ['Bùi Thành Long',   'bui.thanh.long',   0, '2001-09-27', 176, 77, 3, 1],
            ['Ngô Minh Nhật',    'ngo.minh.nhat',    0, '1999-02-08', 171, 68, 2, 2],
            ['Trịnh Văn Dũng',   'trinh.van.dung',   0, '2002-06-20', 169, 60, 2, 0],
            ['Dương Quốc Toàn',  'duong.quoc.toan',  0, '1998-10-11', 177, 82, 4, 1],
            ['Hồ Văn Khải',      'ho.van.khai',      0, '2004-01-25', 167, 58, 1, 2],
            ['Trương Minh Tùng', 'truong.minh.tung', 0, '2000-08-03', 174, 71, 3, 1],
            ['Đinh Văn Hải',     'dinh.van.hai',     0, '1999-04-16', 172, 69, 3, 0],
            ['Cao Minh Hiếu',    'cao.minh.hieu',    0, '2003-12-09', 170, 64, 2, 2],
            ['Phan Văn Khánh',   'phan.van.khanh',   0, '2001-07-21', 175, 74, 3, 1],
            ['Lý Minh Đức',      'ly.minh.duc',      0, '1997-03-04', 179, 83, 4, 1],
            ['Mai Văn Thắng',    'mai.van.thang',    0, '2002-09-17', 168, 61, 2, 0],
            ['Tô Quang Vinh',    'to.quang.vinh',    0, '2000-11-30', 173, 72, 3, 2],
            ['Nguyễn Hữu Lâm',  'nguyen.huu.lam',   0, '1996-06-13', 176, 78, 4, 1],
            ['Trần Quốc Bảo',   'tran.quoc.bao',    0, '2001-01-07', 174, 76, 3, 1],
            ['Lê Văn Tài',       'le.van.tai',       0, '1999-08-24', 171, 67, 2, 0],
            ['Phạm Minh Khôi',  'pham.minh.khoi',   0, '2003-05-18', 178, 79, 3, 1],
            ['Hoàng Văn Thành',  'hoang.van.thanh',  0, '1998-11-02', 180, 87, 4, 1],
            ['Vũ Tiến Dũng',    'vu.tien.dung',     0, '2002-03-26', 169, 63, 2, 2],
            ['Đặng Minh Quân',  'dang.minh.quan',   0, '2000-07-10', 175, 73, 3, 0],
            ['Bùi Văn Hưng',    'bui.van.hung',     0, '1997-09-22', 177, 81, 4, 1],
            ['Ngô Thanh Sơn',   'ngo.thanh.son',    0, '2001-12-04', 172, 70, 3, 2],
            ['Trịnh Minh Trí',  'trinh.minh.tri',   0, '1999-04-15', 170, 65, 2, 1],
            ['Dương Văn Tâm',   'duong.van.tam',    0, '2004-02-28', 168, 59, 1, 0],
            ['Hồ Minh Khoa',    'ho.minh.khoa',     0, '2002-06-11', 173, 72, 3, 1],
            ['Trương Văn Lực',  'truong.van.luc',   0, '2000-10-05', 176, 76, 3, 1],
            ['Đinh Quang Huy',  'dinh.quang.huy',   0, '1998-08-19', 174, 74, 3, 0],
            ['Cao Văn Phong',   'cao.van.phong',    0, '2003-01-31', 171, 66, 2, 2],
            ['Phan Minh Đạt',   'phan.minh.dat',    0, '2001-05-14', 179, 84, 4, 1],
            ['Lý Văn Hào',      'ly.van.hao',       0, '1999-09-08', 175, 75, 3, 1],
            ['Mai Quang Đăng',  'mai.quang.dang',   0, '2002-11-22', 172, 69, 2, 0],
            ['Tô Văn Linh',     'to.van.linh',      0, '2000-03-16', 170, 63, 2, 2],
            ['Nguyễn Thanh Tú', 'nguyen.thanh.tu',  0, '1997-07-29', 178, 82, 4, 1],
            ['Trần Văn Kiên',   'tran.van.kien',    0, '2001-10-12', 173, 71, 3, 1],

            // ── NỮ (40) ──────────────────────────────────────
            ['Nguyễn Thị Hương', 'nguyen.thi.huong', 1, '2002-04-08', 162, 54, 2, 0],
            ['Trần Thị Mai',     'tran.thi.mai',     1, '1999-09-21', 158, 50, 2, 2],
            ['Lê Thị Lan',       'le.thi.lan',       1, '2001-06-14', 160, 52, 2, 0],
            ['Phạm Thị Linh',    'pham.thi.linh',    1, '2003-02-27', 164, 57, 3, 1],
            ['Hoàng Thị Ngọc',   'hoang.thi.ngoc',   1, '1998-11-10', 159, 51, 2, 0],
            ['Vũ Thị Hoa',       'vu.thi.hoa',       1, '2000-07-03', 161, 55, 2, 2],
            ['Đặng Thị Thu',     'dang.thi.thu',     1, '2002-12-16', 165, 58, 3, 1],
            ['Bùi Thị Yến',      'bui.thi.yen',      1, '1999-03-30', 157, 48, 1, 0],
            ['Ngô Thị Ánh',      'ngo.thi.anh',      1, '2004-08-13', 163, 56, 2, 2],
            ['Trịnh Thị Diễm',   'trinh.thi.diem',   1, '2001-05-26', 160, 53, 2, 1],
            ['Dương Thị Hằng',   'duong.thi.hang',   1, '1997-10-09', 166, 60, 3, 1],
            ['Hồ Thị Phương',    'ho.thi.phuong',    1, '2002-01-22', 158, 49, 1, 2],
            ['Trương Thị Ly',    'truong.thi.ly',    1, '2000-06-05', 162, 55, 2, 0],
            ['Đinh Thị Bích',    'dinh.thi.bich',    1, '1999-11-18', 160, 52, 2, 1],
            ['Cao Thị Nhung',    'cao.thi.nhung',    1, '2003-04-01', 155, 47, 1, 0],
            ['Phan Thị Thảo',    'phan.thi.thao',    1, '2001-08-15', 163, 57, 3, 2],
            ['Lý Thị Hồng',      'ly.thi.hong',      1, '1998-02-28', 161, 54, 2, 1],
            ['Mai Thị Kim',       'mai.thi.kim',      1, '2002-07-11', 159, 51, 2, 0],
            ['Tô Thị Thanh',     'to.thi.thanh',     1, '2000-10-24', 165, 59, 3, 1],
            ['Nguyễn Thị Quỳnh', 'nguyen.thi.quynh', 1, '1999-01-07', 162, 53, 2, 2],
            ['Trần Thị Ngọc',    'tran.thi.ngoc',    1, '2003-06-20', 160, 50, 2, 0],
            ['Lê Thị Vân',       'le.thi.van',       1, '2001-11-03', 164, 58, 3, 1],
            ['Phạm Thị Hiền',    'pham.thi.hien',    1, '1997-05-16', 157, 47, 1, 2],
            ['Hoàng Thị Loan',   'hoang.thi.loan',   1, '2002-09-29', 162, 55, 2, 0],
            ['Vũ Thị Châu',      'vu.thi.chau',      1, '2000-03-12', 166, 61, 3, 1],
            ['Đặng Thị Thúy',    'dang.thi.thuy',    1, '1999-07-25', 159, 50, 2, 2],
            ['Bùi Thị Nga',      'bui.thi.nga',      1, '2004-01-08', 161, 54, 2, 0],
            ['Ngô Thị Trâm',     'ngo.thi.tram',     1, '2001-05-21', 163, 57, 3, 1],
            ['Trịnh Thị Mỹ',     'trinh.thi.my',     1, '1998-10-04', 160, 52, 2, 2],
            ['Dương Thị Thùy',   'duong.thi.thuy',   1, '2002-02-17', 165, 59, 3, 1],
            ['Hồ Thị An',        'ho.thi.an',        1, '2000-07-30', 158, 49, 1, 0],
            ['Trương Thị Hải',   'truong.thi.hai',   1, '1999-12-13', 162, 55, 2, 1],
            ['Đinh Thị Phúc',    'dinh.thi.phuc',    1, '2003-04-26', 160, 51, 2, 2],
            ['Cao Thị Tuyết',    'cao.thi.tuyet',    1, '2001-09-09', 163, 56, 3, 0],
            ['Phan Thị Giang',   'phan.thi.giang',   1, '1998-06-22', 161, 53, 2, 1],
            ['Lý Thị Xuân',      'ly.thi.xuan',      1, '2002-11-05', 159, 49, 1, 0],
            ['Mai Thị Cúc',       'mai.thi.cuc',      1, '2000-03-19', 164, 58, 3, 1],
            ['Tô Thị Lành',      'to.thi.lanh',      1, '1999-08-01', 160, 52, 2, 2],
            ['Nguyễn Thị Bảo',   'nguyen.thi.bao',   1, '2003-01-14', 162, 54, 2, 0],
            ['Trần Thị Kiều',    'tran.thi.kieu',    1, '2001-05-28', 166, 62, 3, 1],
        ];
    }

    /* ──────────────────────────────────────────
     |  Helpers
     | ────────────────────────────────────────── */

    private function activityMultiplier(int $level): float
    {
        return match ($level) {
            self::ACT_SEDENTARY => 1.2,
            self::ACT_LIGHT     => 1.375,
            self::ACT_MODERATE  => 1.55,
            self::ACT_VERY      => 1.725,
            self::ACT_EXTREME   => 1.9,
            default             => 1.55,
        };
    }

    /** Mifflin-St Jeor BMR (kcal/day) */
    private function calcBMR(int $gender, float $weight, float $height, int $age): float
    {
        // gender: 0=nam, 1=nữ
        $base = 10 * $weight + 6.25 * $height - 5 * $age;
        return $gender === 0 ? $base + 5 : $base - 161;
    }

    /** Tính tuổi từ ngày sinh (YYYY-MM-DD) */
    private function ageFromDob(string $dob): int
    {
        return (int) date_diff(date_create($dob), date_create('today'))->y;
    }

    /* ──────────────────────────────────────────
     |  run()
     | ────────────────────────────────────────── */

    public function run(): void
    {
        $users = $this->userProfiles();

        foreach ($users as $u) {
            [$name, $emailPrefix, $gender, $dob, $height, $weight, $activity, $goal] = $u;

            /* ── 1. users ──────────────────────────────── */
            $userId = generateRandomString(10);
            $salt   = generateRandomString(5);
            DB::table('users')->insert([
                'id'             => $userId,
                'name'           => $name,
                'email'          => $emailPrefix . '@gmail.com',
                'password'       => md5('Password@123' . $salt),
                'salt'           => $salt,
                'role'           => 0,  // user
                'account_status' => 1,  // active
                'created_at'     => now(),
            ]);

            /* ── 2. user_profiles ─────────────────────── */
            $bmiValue = round($weight / pow($height / 100, 2), 2);
            $bmiCat   = match(true) {
                $bmiValue < 18.5 => 'Underweight',
                $bmiValue < 25.0 => 'Normal',
                $bmiValue < 30.0 => 'Overweight',
                default          => 'Obese',
            };
            DB::table('user_profiles')->insert([
                'id'             => generateRandomString(10),
                'user_id'        => $userId,
                'date_of_birth'  => $dob,
                'gender'         => $gender,
                'height'         => $height,
                'current_weight' => $weight,
                'bmi'            => $bmiValue,
                'bmi_category'   => $bmiCat,
                'activity_level' => $activity,
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);

            /* ── 3. user_goals ────────────────────────── */
            $startDate  = date('Y-m-d', strtotime('-' . rand(60, 120) . ' days'));
            $targetDate = date('Y-m-d', strtotime($startDate . ' +' . rand(90, 150) . ' days'));

            if ($goal === self::GOAL_CUTTING) {
                $targetWeight = round($weight - rand(5, 12), 1);
                $changeRate   = round(rand(5, 9) / 10, 2); // 0.5 – 0.9 kg/week
            } elseif ($goal === self::GOAL_BULKING) {
                $targetWeight = round($weight + rand(4, 8), 1);
                $changeRate   = round(rand(2, 4) / 10, 2); // 0.2 – 0.4 kg/week
            } else {
                $targetWeight = round($weight + rand(-1, 1), 1);
                $changeRate   = 0.00;
            }

            $goalId = generateRandomString(10);
            DB::table('user_goals')->insert([
                'id'                 => $goalId,
                'user_id'            => $userId,
                'goal_type'          => $goal,
                'start_weight'       => $weight,
                'target_weight'      => $targetWeight,
                'weekly_change_rate' => $changeRate,
                'start_date'         => $startDate,
                'target_date'        => $targetDate,
                'status'             => 0,   // active
                'created_at'         => now(),
                'updated_at'         => now(),
            ]);

            /* ── 4. calorie_calculations ──────────────── */
            $age  = $this->ageFromDob($dob);
            $bmr  = round($this->calcBMR($gender, $weight, $height, $age), 2);
            $tdee = round($bmr * $this->activityMultiplier($activity), 2);

            if ($goal === self::GOAL_CUTTING) {
                $targetCal  = max($gender === 1 ? 1200 : 1500, round($tdee - 500, 2));
                $macroRatio = 0; // 40P/30C/30F
                $protPct    = 0.40; $carbPct = 0.30; $fatPct = 0.30;
            } elseif ($goal === self::GOAL_BULKING) {
                $targetCal  = round($tdee + 300, 2);
                $macroRatio = 1; // 30P/45C/25F
                $protPct    = 0.30; $carbPct = 0.45; $fatPct = 0.25;
            } else {
                $targetCal  = $tdee;
                $macroRatio = 2; // 30P/40C/30F
                $protPct    = 0.30; $carbPct = 0.40; $fatPct = 0.30;
            }

            $proteinG = round($targetCal * $protPct / 4, 2);
            $carbsG   = round($targetCal * $carbPct / 4, 2);
            $fatG     = round($targetCal * $fatPct / 9, 2);

            DB::table('calorie_calculations')->insert([
                'id'              => generateRandomString(10),
                'user_id'         => $userId,
                'goal_id'         => $goalId,
                'bmr'             => $bmr,
                'tdee'            => $tdee,
                'target_calories' => $targetCal,
                'protein_grams'   => $proteinG,
                'carbs_grams'     => $carbsG,
                'fat_grams'       => $fatG,
                'macro_ratio'     => $macroRatio,
                'valid_from'      => $startDate,
                'created_at'      => now(),
            ]);
        }

        $this->command->info('GymUsersSeeder: ' . count($users) . ' users seeded (profiles + goals + calorie_calculations).');
    }
}
