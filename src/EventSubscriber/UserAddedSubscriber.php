<?php

namespace App\EventSubscriber;

use App\Event\UserAddedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserAddedSubscriber implements EventSubscriberInterface
{
    public function onUserAddedSendConfirmationEmail(UserAddedEvent $event): void
    {
        $user = $event->getUser();

        $email = [
            'subject' => 'Bienvenue sur SensioTV ' . $user->getFirstName(),
            'body' => 'Bonjour ' . $user->getFirstName() . ', votre inscription a bien été prise en compte !',
        ];

        dump($email);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'app.user_added' => 'onUserAddedSendConfirmationEmail',
        ];
    }
}
