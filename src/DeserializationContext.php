<?php
declare(strict_types=1);

namespace Chimera\MessageCreator\JmsSerializer;

use Chimera\Input;
use JMS\Serializer\DeserializationContext as BaseContext;

/** @deprecated */
final class DeserializationContext extends BaseContext
{
    public function __construct(private Input $input)
    {
        parent::__construct();
    }

    public function getInput(): Input
    {
        return $this->input;
    }
}
