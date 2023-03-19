<?php
declare(strict_types=1);

namespace Chimera\MessageCreator\JmsSerializer\Tests\Functional;

use Chimera\MessageCreator\InputExtractor\AppendGeneratedIdentifier;
use Chimera\MessageCreator\InputExtractor\UseInputData;
use Chimera\MessageCreator\JmsSerializer\ArrayTransformer;
use JMS\Serializer\SerializerBuilder;
use PHPUnit\Framework\Attributes as PHPUnit;
use PHPUnit\Framework\TestCase;

#[PHPUnit\CoversClass(ArrayTransformer::class)]
final class MessageDeserializationTest extends TestCase
{
    #[PHPUnit\Test]
    public function inputDataShouldBeUsedOnDeserialization(): void
    {
        $message = $this->createMessage(['foo' => 'one', 'bar' => 'two', 'baz' => 'three']);

        self::assertFalse(isset($message->id));
        self::assertSame('one', $message->foo);
        self::assertSame('two', $message->bar);
        self::assertSame('three', $message->baz);
    }

    #[PHPUnit\Test]
    public function generatedIdShouldBeUsedOnDeserialization(): void
    {
        $message = $this->createMessage([], '1234');

        self::assertSame('1234', $message->id);
        self::assertFalse(isset($message->foo, $message->bar, $message->baz));
    }

    #[PHPUnit\Test]
    public function dataAndIdShouldBeUsedOnDeserialization(): void
    {
        $message = $this->createMessage(['foo' => 'one', 'bar' => 'two', 'baz' => 'three'], '1234');

        self::assertSame('1234', $message->id);
        self::assertSame('one', $message->foo);
        self::assertSame('two', $message->bar);
        self::assertSame('three', $message->baz);
    }

    /** @param mixed[] $data */
    private function createMessage(
        array $data = [],
        ?string $generatedId = null,
    ): DoSomething {
        $creator = new ArrayTransformer(
            SerializerBuilder::create()->build(),
            new AppendGeneratedIdentifier(new UseInputData()),
        );

        return $creator->create(DoSomething::class, new FakeInput($data, $generatedId));
    }
}
