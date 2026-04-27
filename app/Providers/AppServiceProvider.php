<?php

namespace App\Providers;

use App\Repositories\CalorieCalculation\CalorieCalculationRepository;
use App\Repositories\CalorieCalculation\ICalorieCalculationRepository;
use App\Repositories\Food\FoodRepository;
use App\Repositories\Food\IFoodRepository;
use App\Repositories\FoodCategory\FoodCategoryRepository;
use App\Repositories\FoodCategory\IFoodCategoryRepository;
use App\Repositories\FoodRating\FoodRatingRepository;
use App\Repositories\FoodRating\IFoodRatingRepository;
use App\Repositories\MealPlan\IMealPlanRepository;
use App\Repositories\MealPlan\MealPlanRepository;
use App\Repositories\MealPlanDetail\IMealPlanDetailRepository;
use App\Repositories\MealPlanDetail\MealPlanDetailRepository;
use App\Repositories\Module\IModuleRepository;
use App\Repositories\Module\ModuleRepository;
use App\Repositories\ModuleAction\IModuleActionRepository;
use App\Repositories\ModuleAction\ModuleActionRepository;
use App\Repositories\User\IUserRepository;
use App\Repositories\User\UserRepository;
use App\Repositories\UserGoal\IUserGoalRepository;
use App\Repositories\UserGoal\UserGoalRepository;
use App\Repositories\UserProfile\IUserProfileRepository;
use App\Repositories\UserProfile\UserProfileRepository;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    private $_listRepoMapInterface = [
        IUserRepository::class              => UserRepository::class,
        IModuleActionRepository::class      => ModuleActionRepository::class,
        IModuleRepository::class            => ModuleRepository::class,
        IUserProfileRepository::class       => UserProfileRepository::class,
        IUserGoalRepository::class          => UserGoalRepository::class,
        ICalorieCalculationRepository::class => CalorieCalculationRepository::class,
        IFoodCategoryRepository::class      => FoodCategoryRepository::class,
        IFoodRepository::class              => FoodRepository::class,
        IFoodRatingRepository::class        => FoodRatingRepository::class,
        IMealPlanRepository::class          => MealPlanRepository::class,
        IMealPlanDetailRepository::class    => MealPlanDetailRepository::class,
    ];

    public function register()
    {
        foreach ($this->_listRepoMapInterface as $interface => $repository) {
            $this->app->bind($interface, $repository);
        }
    }

    public function boot()
    {
        $locale = Cookie::get('lang');
        if ($locale !== null) {
            $decryptedString = Crypt::decrypt($locale, false);
            $params = explode('|', $decryptedString);

            App::setLocale($params[1]);
        }

        // $user_agent = Request::server('HTTP_USER_AGENT');
        // // Next get the name of the useragent yes seperately and for good reason
        // if (preg_match('/Mobile/i', $user_agent) && !preg_match('/Opera/i', $user_agent)) {
        //     $ua = "Mobile";
        // } else {
        //     $ua = "Desktop";
        // }

        // View::share('UserAgent', $ua);

        // Paginator::defaultView('partials.pagination');
        // Paginator::defaultSimpleView('partials.pagination');
    }
}
