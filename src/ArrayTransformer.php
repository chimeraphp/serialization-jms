<?php
declare(strict_types=1);

namespace Chimera\MessageCreator\JmsSerializer;

use Chimera\Input;
use Chimera\MessageCreator;
use Chimera\MessageCreator\InputExtractor;
use JMS\Serializer\ArrayTransformerInterface;

final class ArrayTransformer implements MessageCreator
{
    private ArrayTransformerInterface $transformer;
    private InputExtractor $extractor;

    public function __construct(ArrayTransformerInterface $transformer, InputExtractor $extractor)
    {
        $this->transformer = $transformer;
        $this->extractor   = $extractor;
    }

    public function create(string $message, Input $input): object
    {
        return $this->transformer->fromArray(
            $this->extractor->extractData($input),
            $message,
            new DeserializationContext($input)
        );
    }
}
