<?php

namespace HBM\MessengerBundle\Messenger\Message;

class Command extends AbstractMessage {

  private string $name;

  private array $parameters;

  /**
   * Set command name.
   *
   * @param string $name
   *
   * @return self
   */
  public function setName(string $name) : self {
    $this->name = $name;

    return $this;
  }

  /**
   * Get command name.
   *
   * @return string
   */
  public function getName() : string {
    return $this->name;
  }

  /**
   * Set command parameters.
   *
   * @param array $parameters
   *
   * @return self
   */
  public function setParameters(array $parameters) : self {
    $this->parameters = $parameters;

    return $this;
  }

  /**
   * Get command parameters.
   *
   * @return array|null
   */
  public function getParameters() : ?array {
    return $this->parameters;
  }

}
