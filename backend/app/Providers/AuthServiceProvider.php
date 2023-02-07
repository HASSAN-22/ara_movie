<?php

namespace App\Providers;

use App\Models\BankPortal;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Checkout;
use App\Models\Comment;
use App\Models\ConfigSite;
use App\Models\Discount;
use App\Models\Movie;
use App\Models\MovieLink;
use App\Models\MovieTag;
use App\Models\Page;
use App\Models\Plan;
use App\Models\ReportLink;
use App\Models\Slider;
use App\Models\User;
use App\Models\VipTransaction;
use App\Models\WalletTransaction;
use App\Models\WatchList;
use App\Policies\BankPortalPolicy;
use App\Policies\CartPolicy;
use App\Policies\CategoryPolicy;
use App\Policies\CheckoutPolicy;
use App\Policies\CommentPolicy;
use App\Policies\ConfigSitePolicy;
use App\Policies\DiscountPolicy;
use App\Policies\MovieLinkPolicy;
use App\Policies\MoviePolicy;
use App\Policies\MovieTagPolicy;
use App\Policies\PagePolicy;
use App\Policies\PlanPolicy;
use App\Policies\ReportLinkPolicy;
use App\Policies\SliderPolicy;
use App\Policies\UserPolicy;
use App\Policies\VipTransactionPolicy;
use App\Policies\WalletTransactionPolicy;
use App\Policies\WatchListPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Category::class => CategoryPolicy::class,
        User::class => UserPolicy::class,
        Movie::class => MoviePolicy::class,
        MovieLink::class => MovieLinkPolicy::class,
        MovieTag::class => MovieTagPolicy::class,
        Plan::class => PlanPolicy::class,
        Discount::class => DiscountPolicy::class,
        Cart::class => CartPolicy::class,
        BankPortal::class => BankPortalPolicy::class,
        WalletTransaction::class => WalletTransactionPolicy::class,
        Checkout::class => CheckoutPolicy::class,
        VipTransaction::class => VipTransactionPolicy::class,
        Comment::class => CommentPolicy::class,
        Page::class => PagePolicy::class,
        ConfigSite::class => ConfigSitePolicy::class,
        WatchList::class => WatchListPolicy::class,
        Slider::class => SliderPolicy::class,
        ReportLink::class => ReportLinkPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
