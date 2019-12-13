<?php
declare(strict_types=1);

namespace Chimera\MessageCreator\JmsSerializer\Tests\Functional;

use JMS\Serializer\Annotation as Serializer;

final class DoSomething
{
    /**
     * @Serializer\Type("string")
     * @var string|null
     */
    public $id;

    /**
     * @Serializer\Type("string")
     * @var string|null
     */
    public $foo;

    /**
     * @Serializer\Type("string")
     * @var string|null
     */
    public $bar;

    /**
     * @Serializer\Type("string")
     * @var string|null
     */
    public $baz;
}
