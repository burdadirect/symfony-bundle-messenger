<?php

namespace HBM\MessengerBundle\Event\Subscriber;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\Event\MessageEvent;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

class PrepareMessageSubscriber implements EventSubscriberInterface {

  private array $config;

  /**
   * PrepareMessageSubscriber constructor.
   *
   * @param ParameterBagInterface $parameterBag
   */
  public function __construct(ParameterBagInterface $parameterBag) {
    $this->config = $parameterBag->get('hbm.messenger.mailer');
  }

  /**
   * @return string[]
   */
  public static function getSubscribedEvents(): array {
    return [
      MessageEvent::class => 'onMessage',
    ];
  }

  /**
   * @param Email $email
   *
   * @return array
   */
  protected function getHeaderReplacements(Email $email): array {
    return $this->config['defaults'] ?? [];
  }

  /**
   * @param Email $email
   *
   * @return Address|null
   */
  protected function getFrom(Email $email): ?Address {
    return new Address($this->config['from']['mail'], $this->config['from']['name']);
  }

  /**
   * @throws \Exception
   */
  public function onMessage(MessageEvent $messageEvent) : void {
    $message = $messageEvent->getMessage();
    if (!$message instanceof Email) {
      return;
    }

    // Set From address.
    if ($from = $this->getFrom($message)) {
      $message->addFrom($from);
    }

    // Gather header replacements.
    $headerReplacements = $this->getHeaderReplacements($message);

    // Add headers.
    $headers = $this->config['headers'] ?: [];
    foreach ($headers as $header) {
      $headerValue = str_replace(array_keys($headerReplacements), array_values($headerReplacements), $header['value']);

      switch (strtolower($header['key'])) {
        case 'return-path':
          $message->getHeaders()->addPathHeader($header['key'], $headerValue);
          break;

        case 'from':
        case 'reply-to':
        case 'to':
        case 'cc':
        case 'bcc':
          $message->getHeaders()->addMailboxListHeader($header['key'], $headerValue);
          break;

        case 'message-id':
        case 'in-reply-to':
        case 'references':
          $message->getHeaders()->addIdHeader($header['key'], $headerValue);
          break;

        case 'sender':
          $message->getHeaders()->addMailboxHeader($header['key'], $headerValue);
          break;

        default:
          $message->getHeaders()->addTextHeader($header['key'], $headerValue);
      }
    }

    // Add environment-specific subject prefixes/postfixes.
    $subjectPrefix = $this->config['subject']['prefix'] ?? '';
    $subjectPostfix = $this->config['subject']['postfix'] ?? '';

    $message->subject($subjectPrefix.$message->getSubject().$subjectPostfix);
  }

}
