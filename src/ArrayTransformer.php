<?php
declare(strict_types=1);

namespace Chimera\MessageCreator\JmsSerializer;

use Chimera\Input;
use Chimera\MessageCreator;
use JMS\Serializer\ArrayTransformerInterface;

final class ArrayTransformer implements MessageCreator
{
    /**
     * @var ArrayTransformerInterface
     */
    private $transformer;

    public function __construct(ArrayTransformerInterface $transformer)
    {
        $this->transformer = $transformer;
    }

    /**
     * {@inheritdoc}
     */
    public function create(string $message, Input $input): object
    {
        return $this->transformer->fromArray(
            $input->getData(),
            $message,
            new DeserializationContext($input)
        );
    }
}
