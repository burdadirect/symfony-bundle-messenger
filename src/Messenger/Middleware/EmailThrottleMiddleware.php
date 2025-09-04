<?php

namespace HBM\MessengerBundle\Messenger\Middleware;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Mailer\Messenger\SendEmailMessage;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Stamp\ReceivedStamp;

class EmailThrottleMiddleware implements MiddlewareInterface
{
    /** @var int */
    private int $sleep = 0;

    /**
     * EmailThrottleMiddleware constructor.
     */
    public function __construct(ParameterBagInterface $parameterBag)
    {
        $mailsPerSecond = $parameterBag->get('hbm.messenger')['mailer']['mails_per_second'] ?: null;

        if ($mailsPerSecond) {
            //  A micro second is one millionth of a second.
            $this->sleep = (int) (1 / $mailsPerSecond * 1000000);
        }
    }

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        if (($envelope->getMessage() instanceof SendEmailMessage) && $envelope->last(ReceivedStamp::class)) {
            usleep($this->sleep);
        }

        return $stack->next()->handle($envelope, $stack);
    }
}
