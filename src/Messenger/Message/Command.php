<?php

namespace HBM\MessengerBundle\Messenger\Message;

class Command extends AbstractMessage
{
    private string $name;

    private array $parameters = [];

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setParameters(array $parameters): self
    {
        $this->parameters = $parameters;

        return $this;
    }

    public function getParameters(): ?array
    {
        return $this->parameters;
    }

    public function setParameter(string $key, mixed $value): self {
        $this->parameters[$key] = $value;

        return $this;
    }

    public function getParameter(string $key, mixed $default = null): mixed {
        return $this->parameters[$key] ?? $default;
    }
}
