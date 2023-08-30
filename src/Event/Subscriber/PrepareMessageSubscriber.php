<?php

namespace HBM\MessengerBundle\Event\Subscriber;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\Event\MessageEvent;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

class PrepareMessageSubscriber implements EventSubscriberInterface
{
    private array $config;

    /**
     * PrepareMessageSubscriber constructor.
     */
    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->config = $parameterBag->get('hbm.messenger.mailer');
    }

    /**
     * @return string[]
     */
    public static function getSubscribedEvents(): array
    {
        return [
          MessageEvent::class => 'onMessage',
        ];
    }

    public function onMessage(MessageEvent $messageEvent): void
    {
        $message = $messageEvent->getMessage();

        if (!$message instanceof Email) {
            return;
        }

        $this->handleFrom($message);
        $this->handleSubject($message);
        $this->handleHeaders($message);
    }

    protected function handleFrom(Email $email): void
    {
        // Set From address.
        if ($from = $this->getFrom($email)) {
            $email->addFrom($from);
        }
    }

    protected function handleHeaders(Email $email): void
    {
        // Gather headers.
        $headers = $this->getHeaders($email);

        // Gather header replacements.
        $headerReplacements = $this->getHeaderReplacements($email);

        // Add headers.
        foreach ($headers as $header) {
            $headerValue = str_replace(array_keys($headerReplacements), array_values($headerReplacements), $header['value']);

            switch (strtolower($header['key'])) {
                case 'return-path':
                    $email->getHeaders()->addPathHeader($header['key'], $headerValue);

                    break;

                case 'from':
                case 'reply-to':
                case 'to':
                case 'cc':
                case 'bcc':
                    $email->getHeaders()->addMailboxListHeader($header['key'], $headerValue);

                    break;

                case 'message-id':
                case 'in-reply-to':
                case 'references':
                    $email->getHeaders()->addIdHeader($header['key'], $headerValue);

                    break;

                case 'sender':
                    $email->getHeaders()->addMailboxHeader($header['key'], $headerValue);

                    break;

                default:
                    $email->getHeaders()->addTextHeader($header['key'], $headerValue);
            }
        }
    }

    protected function handleSubject(Email $email): void
    {
        // Add environment-specific subject prefixes/postfixes.
        $subjectPrefix  = $this->config['subject']['prefix'] ?? '';
        $subjectPostfix = $this->config['subject']['postfix'] ?? '';

        $email->subject($subjectPrefix . $email->getSubject() . $subjectPostfix);
    }

    protected function getHeaders(Email $email): array
    {
        return $this->config['headers'] ?: [];
    }

    protected function getHeaderReplacements(Email $email): array
    {
        return $this->config['defaults'] ?? [];
    }

    protected function getFrom(Email $email): ?Address
    {
        return new Address($this->config['from']['mail'], $this->config['from']['name']);
    }
}
