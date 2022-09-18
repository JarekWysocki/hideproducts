<?php

declare(strict_types=1);

class_exists('AbstractFormInput') or require_once _PS_MODULE_DIR_.'webixahideoptionalinfoinproducts/classes/AbstractFormInput.php';

class SwapCustomFormInput extends AbstractFormInput
{
    protected $multiple = false;

    protected $search = false;

    protected $options = [];

    public function getType(): string
    {
        return 'swap-custom';
    }

    public function isMultiple(): bool
    {
        return $this->multiple;
    }

    public function setMultiple(bool $multiple): self
    {
        $this->multiple = $multiple;

        return $this;
    }

    public function isSearch(): bool
    {
        return $this->search;
    }

    public function setSearch(bool $search): self
    {
        $this->search = $search;

        return $this;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function setOptions(array $data, string $idKey, string $nameKey): self
    {
        $this->options = [
            'query' => $data,
            'id' => $idKey,
            'name' => $nameKey
        ];

        return $this;
    }

    public function getConfiguration(): array
    {
        $configuration = parent::getConfiguration();

        $configuration['multiple'] = $this->isMultiple();
        $configuration['search'] = $this->isSearch();
        $configuration['options'] = $this->getOptions();

        return $configuration;
    }
}
