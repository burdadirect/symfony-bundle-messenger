<?php

namespace HBM\MessengerBundle\Messenger\Middleware;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Messenger\SendEmailMessage;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Stamp\ReceivedStamp;

abstract class AbstractRestoreEntitiesMiddleware implements MiddlewareInterface
{
    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        if (($envelope->getMessage() instanceof SendEmailMessage) && $envelope->last(ReceivedStamp::class)) {
            /** @var SendEmailMessage $message */
            $message = $envelope->getMessage();
            $email   = $message->getMessage();

            if ($email instanceof TemplatedEmail) {
                $context = $email->getContext();
                foreach ($context as $key => $value) {
                    $this->restoreEntity($context, $key, $value);
                }
                $email->context($context);
            }
        }

        return $stack->next()->handle($envelope, $stack);
    }

    abstract protected function restoreEntity(array &$context, $key, $value): void;
}
