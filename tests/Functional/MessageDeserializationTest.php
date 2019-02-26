<?php
declare(strict_types=1);

namespace Chimera\MessageCreator\JmsSerializer\Tests\Functional;

use Chimera\MessageCreator\JmsSerializer\ArrayTransformer;
use Chimera\MessageCreator\JmsSerializer\InputDataInjector;
use Doctrine\Common\Annotations\AnnotationRegistry;
use JMS\Serializer\ArrayTransformerInterface;
use JMS\Serializer\EventDispatcher\EventDispatcher;
use JMS\Serializer\EventDispatcher\Events;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializerInterface;
use PHPUnit\Framework\TestCase;
use function assert;

final class MessageDeserializationTest extends TestCase
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @beforeClass
     */
    public static function registerAutoloader(): void
    {
        AnnotationRegistry::registerUniqueLoader('class_exists');
    }

    /**
     * @before
     */
    public function createSerializer(): void
    {
        $injector = new InputDataInjector();

        $addListeners = static function (EventDispatcher $dispatcher) use ($injector): void {
            $dispatcher->addListener(Events::PRE_DESERIALIZE, [$injector, 'injectData']);
        };

        $this->serializer = SerializerBuilder::create()->configureListeners($addListeners)
                                                       ->build();
    }

    /**
     * @test
     *
     * @covers \Chimera\MessageCreator\JmsSerializer\ArrayTransformer
     * @covers \Chimera\MessageCreator\JmsSerializer\InputDataInjector
     * @covers \Chimera\MessageCreator\JmsSerializer\DeserializationContext
     */
    public function inputDataShouldBeUsedOnDeserialization(): void
    {
        $message = $this->createMessage(['foo' => 'one', 'bar' => 'two', 'baz' => 'three']);

        self::assertNull($message->id);
        self::assertSame('one', $message->foo);
        self::assertSame('two', $message->bar);
        self::assertSame('three', $message->baz);
    }

    /**
     * @test
     *
     * @covers \Chimera\MessageCreator\JmsSerializer\ArrayTransformer
     * @covers \Chimera\MessageCreator\JmsSerializer\InputDataInjector
     * @covers \Chimera\MessageCreator\JmsSerializer\DeserializationContext
     */
    public function generatedIdShouldBeUsedOnDeserialization(): void
    {
        $message = $this->createMessage([], '1234');

        self::assertSame('1234', $message->id);
        self::assertNull($message->foo);
        self::assertNull($message->bar);
        self::assertNull($message->baz);
    }

    /**
     * @test
     *
     * @covers \Chimera\MessageCreator\JmsSerializer\ArrayTransformer
     * @covers \Chimera\MessageCreator\JmsSerializer\InputDataInjector
     * @covers \Chimera\MessageCreator\JmsSerializer\DeserializationContext
     */
    public function dataAndIdShouldBeUsedOnDeserialization(): void
    {
        $message = $this->createMessage(['foo' => 'one', 'bar' => 'two', 'baz' => 'three'], '1234');

        self::assertSame('1234', $message->id);
        self::assertSame('one', $message->foo);
        self::assertSame('two', $message->bar);
        self::assertSame('three', $message->baz);
    }

    /**
     * @param mixed[] $data
     */
    private function createMessage(
        array $data = [],
        ?string $generatedId = null
    ): DoSomething {
        assert($this->serializer instanceof ArrayTransformerInterface);

        $creator = new ArrayTransformer($this->serializer);
        $message = $creator->create(DoSomething::class, new FakeInput($data, $generatedId));
        assert($message instanceof DoSomething);

        return $message;
    }
}
