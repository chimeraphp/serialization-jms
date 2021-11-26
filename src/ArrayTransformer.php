<?php
declare(strict_types=1);

namespace Chimera\MessageCreator\JmsSerializer;

use Chimera\Input;
use Chimera\MessageCreator;
use Chimera\MessageCreator\InputExtractor;
use JMS\Serializer\ArrayTransformerInterface;

final class ArrayTransformer implements MessageCreator
{
    public function __construct(private ArrayTransformerInterface $transformer, private InputExtractor $extractor)
    {
    }

    public function create(string $message, Input $input): object
    {
        // @phpstan-ignore-next-line
        return $this->transformer->fromArray($this->extractor->extractData($input), $message);
    }
}
