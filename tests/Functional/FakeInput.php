<?php
declare(strict_types=1);

namespace Chimera\MessageCreator\JmsSerializer\Tests\Functional;

use Chimera\IdentifierGenerator;
use Chimera\Input;

final class FakeInput implements Input
{
    /** @var mixed[] */
    private array $data;

    /** @var mixed[] */
    private array $attributes = [];

    /** @param mixed[] $data */
    public function __construct(array $data, ?string $generatedId = null)
    {
        $this->data = $data;

        if ($generatedId === null) {
            return;
        }

        $this->attributes[IdentifierGenerator::class] = $generatedId;
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
