<?php

namespace HBM\MessengerBundle\Messenger\Handler;

use HBM\MessengerBundle\Messenger\Message\Command;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\HttpKernel\KernelInterface;

#[\Symfony\Component\Messenger\Attribute\AsMessageHandler]
class CommandHandler
{
  private Application $application;

  /**
   * CommandHandler constructor.
   *
   * @param KernelInterface $kernel
   */
  public function __construct(KernelInterface $kernel) {
    $this->application = new Application($kernel);
    $this->application->setAutoExit(false);
  }

  /**
   * @param Command $command
   *
   * @throws \Exception
   */
  public function __invoke(Command $command) {
    // Find command.
    $consoleCommand = $this->application->find($command->getName());

    // Assemble inputs.
    $inputs = $command->getParameters();
    $inputs['command'] = $consoleCommand;

    // Run command.
    $consoleCommand->run(new ArrayInput($inputs), new NullOutput());
  }

}
