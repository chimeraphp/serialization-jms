<?php
declare(strict_types=1);

namespace Chimera\MessageCreator\JmsSerializer\Tests\Functional;

use Chimera\IdentifierGenerator;
use Chimera\Input;

final class FakeInput implements Input
{
    /** @var array<string, mixed> */
    private readonly array $attributes;

    /** @param mixed[] $data */
    public function __construct(private readonly array $data, ?string $generatedId = null)
    {
        $attributes = [];

        if ($generatedId !== null) {
            $attributes[IdentifierGenerator::class] = $generatedId;
        }

        $this->attributes = $attributes;
    }

    public function getAttribute(string $name, mixed $default = null): mixed
    {
        return $this->attributes[$name] ?? $default;
    }

    /**
     * {@inheritdoc}
     */
    public function getData(): array
    {
        return $this->data;
    }
}
