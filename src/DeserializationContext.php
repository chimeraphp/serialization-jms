<?php
declare(strict_types=1);

namespace Chimera\MessageCreator\JmsSerializer;

use Chimera\Input;
use JMS\Serializer\DeserializationContext as BaseContext;

final class DeserializationContext extends BaseContext
{
    /**
     * @var Input
     */
    private $input;

    public function __construct(Input $input)
    {
        parent::__construct();

        $this->input = $input;
    }

    public function getInput(): Input
    {
        return $this->input;
    }
}
