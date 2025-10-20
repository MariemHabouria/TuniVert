<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share user badges with all views for navbar display
        View::composer('*', function ($view) {
            if (Auth::check()) {
                $user = Auth::user();
                
                // Get user's latest earned badge
                $latestBadge = DB::table('user_badges as ub')
                    ->join('badges as b', 'b.id', '=', 'ub.badge_id')
                    ->where('ub.user_id', $user->id)
                    ->orderBy('ub.awarded_at', 'desc')
                    ->select('b.icon', 'b.name', 'ub.awarded_at')
                    ->first();
                
                // Get badge count
                $badgeCount = DB::table('user_badges')->where('user_id', $user->id)->count();
                
                $view->with('userLatestBadge', $latestBadge);
                $view->with('userBadgeCount', $badgeCount);
            }
        });
    }
}