<?php
declare(strict_types=1);

namespace Chimera\MessageCreator\JmsSerializer\Tests\Functional;

use Chimera\MessageCreator\JmsSerializer\InputDataInjector;
use JMS\Serializer\Annotation as Serializer;

final class DoSomething
{
    /**
     * @Serializer\Type("string")
     * @Serializer\SerializedName(InputDataInjector::GENERATED_ID)
     *
     * @var string|null
     */
    public $id;

    /**
     * @Serializer\Type("string")
     *
     * @var string|null
     */
    public $foo;

    /**
     * @Serializer\Type("string")
     *
     * @var string|null
     */
    public $bar;

    /**
     * @Serializer\Type("string")
     *
     * @var string|null
     */
    public $baz;
}
