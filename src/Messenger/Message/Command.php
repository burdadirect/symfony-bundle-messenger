<?php

namespace HBM\MessengerBundle\Messenger\Message;

class Command extends AbstractMessage
{
    private string $name;

    private array $parameters = [];

    /**
     * Set command name.
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get command name.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set command parameters.
     */
    public function setParameters(array $parameters): self
    {
        $this->parameters = $parameters;

        return $this;
    }

    /**
     * Get command parameters.
     */
    public function getParameters(): ?array
    {
        return $this->parameters;
    }
}
