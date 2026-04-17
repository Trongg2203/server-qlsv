<?php

namespace App\Providers;

use App\Repositories\Module\IModuleRepository;
use App\Repositories\Module\ModuleRepository;
use App\Repositories\ModuleAction\IModuleActionRepository;
use App\Repositories\ModuleAction\ModuleActionRepository;
use App\Repositories\User\UserRepository;
use App\Repositories\User\IUserRepository;
use App\Repositories\UserGoal\IUserGoalRepository;
use App\Repositories\UserGoal\UserGoalRepository;
use App\Repositories\UserProfile\IUserProfileRepository;
use App\Repositories\UserProfile\UserProfileRepository;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    private $_listRepoMapInterface = [

        IUserRepository::class => UserRepository::class,
        IModuleActionRepository::class => ModuleActionRepository::class,
        IModuleRepository::class => ModuleRepository::class,
        IUserProfileRepository::class => UserProfileRepository::class,
        IUserGoalRepository::class => UserGoalRepository::class,
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

        $user_agent = Request::server('HTTP_USER_AGENT');
        // Next get the name of the useragent yes seperately and for good reason
        if (preg_match('/Mobile/i', $user_agent) && !preg_match('/Opera/i', $user_agent)) {
            $ua = "Mobile";
        } else {
            $ua = "Desktop";
        }

        View::share('UserAgent', $ua);

        // Paginator::defaultView('partials.pagination');
        // Paginator::defaultSimpleView('partials.pagination');
    }
}
