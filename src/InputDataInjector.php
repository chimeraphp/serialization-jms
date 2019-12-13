<?php
declare(strict_types=1);

namespace Chimera\MessageCreator\JmsSerializer;

use Chimera\IdentifierGenerator;
use Chimera\Input;
use JMS\Serializer\EventDispatcher\PreDeserializeEvent;

final class InputDataInjector
{
    private $generatedProperty = '_input.id';

    public function __construct(string $generatedProperty = '_input.id')
    {
        $this->generatedProperty = $generatedProperty;
    }

    public function injectData(PreDeserializeEvent $event): void
    {
        $context = $event->getContext();

        if (! $context instanceof DeserializationContext || $context->getDepth() !== 1) {
            return;
        }

        $event->setData(
            $this->mergeData((array) $event->getData(), $context->getInput())
        );
    }

    /**
     * @param mixed[] $data
     *
     * @return mixed[]
     */
    private function mergeData(array $data, Input $input): array
    {
        $generatedId = $input->getAttribute(IdentifierGenerator::class);

        if ($generatedId !== null && isset($data[$this->generatedProperty]) === false) {
            $data[$this->generatedProperty] = $generatedId;
        }

        return $data;
    }
}
