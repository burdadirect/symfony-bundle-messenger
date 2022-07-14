<?php

namespace HBM\MessengerBundle\Messenger\Message;

abstract class AbstractMessage {

  protected ?string $description;

  protected ?string $email;

  /**
   * Set description.
   *
   * @param string|null $description
   *
   * @return self
   */
  public function setDescription(?string $description) : self {
    $this->description = $description;

    return $this;
  }

  /**
   * Get description.
   *
   * @return string|null
   */
  public function getDescription() : ?string {
    return $this->description;
  }

  /**
   * Set email.
   *
   * @param string|null $email
   *
   * @return self
   */
  public function setEmail(?string $email): self {
    $this->email = $email;
    return $this;
  }

  /**
   * Get email.
   *
   * @return string|null
   */
  public function getEmail(): ?string {
    return $this->email;
  }

}
