<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        // 'user.deleted' => [
        //     'App\events\UserEvent@userDeleted'
        // ],
        // 'banner.deleted' => [
        //     'App\events\BannerEvent@bannerDeleted'
        // ],
        // 'section.deleted' => [
        //     'App\events\SectionEvent@sectionDeleted'
        // ],
        // 'category.deleted' => [
        //     'App\events\CategoryEvent@categoryDeleted'
        // ],
        // 'brand.deleted' => [
        //     'App\events\BrandEvent@brandDeleted'
        // ],
        // 'product.deleted' => [
        //     'App\events\ProductEvent@productDeleted'
        // ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}
