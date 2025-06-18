<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Domain\User\Interfaces\UserRepositoryInterface;
use Infrastructure\Persistence\Repositories\EloquentUserRepository;
use Domain\Friendship\Interfaces\FriendshipRepositoryInterface;
use Infrastructure\Persistence\Repositories\EloquentFriendshipRepository;
use Domain\Chat\Interfaces\ConversationRepositoryInterface;
use Infrastructure\Persistence\Repositories\ElquentConversationRepository;
use Domain\Chat\Interfaces\MessageRepositoryInterface;
use Infrastructure\Persistence\Repositories\EloquentMessageRepository;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        $this->app->bind(UserRepositoryInterface::class, EloquentUserRepository::class);
        $this->app->bind(FriendshipRepositoryInterface::class,EloquentFriendshipRepository::class); 
        $this->app->bind(ConversationRepositoryInterface::class, ElquentConversationRepository::class);
        $this->app->bind(MessageRepositoryInterface::class, EloquentMessageRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
