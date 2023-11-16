<?php

namespace App\Event;

/**
 * Centralize all Business events thrown by the App.
 */
final class AppDomainEvents
{
    //...

    /**
     * @Event("App\Event\UserAddedEvent")
     */
    public const USER_ADDED = 'app.user_added';
}
