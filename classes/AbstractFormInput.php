<?php

declare(strict_types=1);

abstract class AbstractFormInput
{
    protected $name = '';

    protected $class = '';

    protected $required = false;

    protected $label = '';

    protected $col = 12;

    protected $description = '';

    abstract public function getType(): string;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function setClass(string $class): self
    {
        $this->class = $class;

        return $this;
    }

    public function isRequired(): bool
    {
        return $this->required;
    }

    public function setRequired(bool $required): self
    {
        $this->required = $required;

        return $this;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getCol(): int
    {
        return $this->col;
    }

    public function setCol(int $col): self
    {
        $this->col = $col;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getConfiguration(): array
    {
        return [
            'type' => $this->getType(),
            'label' => $this->getLabel(),
            'name' => $this->getName(),
            'class' => $this->getClass(),
            'required' => $this->isRequired(),
            'col' => $this->getCol(),
            'desc' => $this->getDescription(),
        ];
    }
}
