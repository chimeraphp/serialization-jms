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
     */
    public ?string $id;

    /**
     * @Serializer\Type("string")
     */
    public ?string $foo;

    /**
     * @Serializer\Type("string")
     */
    public ?string $bar;

    /**
     * @Serializer\Type("string")
     */
    public ?string $baz;
}
