<?php
declare(strict_types=1);

namespace Chimera\MessageCreator\JmsSerializer\Tests\Unit;

use Chimera\IdentifierGenerator;
use Chimera\Input;
use Chimera\MessageCreator\JmsSerializer\DeserializationContext;
use Chimera\MessageCreator\JmsSerializer\InputDataInjector;
use JMS\Serializer\DeserializationContext as BaseContext;
use JMS\Serializer\EventDispatcher\Events;
use JMS\Serializer\EventDispatcher\PreDeserializeEvent;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use function assert;

/**
 * @coversDefaultClass \Chimera\MessageCreator\JmsSerializer\InputDataInjector
 */
final class InputDataInjectorTest extends TestCase
{
    private const DATA     = ['test' => 1];
    private const PROPERTY = 'id';

    /**
     * @var Input|MockObject
     */
    private $input;

    /**
     * @var PreDeserializeEvent
     */
    private $event;

    /**
     * @before
     */
    public function createInput(): void
    {
        $this->input = $this->createMock(Input::class);

        $context = new DeserializationContext($this->input);
        $context->increaseDepth();

        $this->event = new PreDeserializeEvent($context, self::DATA, ['name' => Events::PRE_DESERIALIZE, []]);
    }

    /**
     * @test
     *
     * @covers ::injectData()
     *
     * @uses \Chimera\MessageCreator\JmsSerializer\DeserializationContext
     */
    public function injectDataShouldBeSkippedWhenContextIsNotTheExpectedOne(): void
    {
        $event = new PreDeserializeEvent(new BaseContext(), self::DATA, ['name' => Events::PRE_DESERIALIZE, []]);

        $listener = new InputDataInjector();
        $listener->injectData($event);

        self::assertSame(self::DATA, $event->getData());
    }

    /**
     * @test
     *
     * @covers ::injectData()
     *
     * @uses \Chimera\MessageCreator\JmsSerializer\DeserializationContext
     */
    public function injectDataShouldBeSkippedWhenContextDepthIsNotOne(): void
    {
        $context = $this->event->getContext();
        assert($context instanceof DeserializationContext);

        $context->increaseDepth();

        $listener = new InputDataInjector(self::PROPERTY);
        $listener->injectData($this->event);

        self::assertSame(self::DATA, $this->event->getData());
    }

    /**
     * @test
     *
     * @covers ::injectData()
     * @covers ::mergeData()
     *
     * @uses \Chimera\MessageCreator\JmsSerializer\DeserializationContext
     */
    public function injectDataShouldNotAddAnythingWhenIdentifierAttributeIsNotConfigured(): void
    {
        $this->input->expects(self::once())
                    ->method('getAttribute')
                    ->with(IdentifierGenerator::class)
                    ->willReturn(null);

        $listener = new InputDataInjector();
        $listener->injectData($this->event);

        self::assertSame(self::DATA, $this->event->getData());
    }

    /**
     * @test
     *
     * @covers ::injectData()
     * @covers ::mergeData()
     *
     * @uses \Chimera\MessageCreator\JmsSerializer\DeserializationContext
     */
    public function injectDataShouldAddGeneratedIdToData(): void
    {
        $this->input->expects(self::once())
                    ->method('getAttribute')
                    ->with(IdentifierGenerator::class)
                    ->willReturn('abc123');

        $listener = new InputDataInjector(self::PROPERTY);
        $listener->injectData($this->event);

        self::assertSame(['test' => 1, self::PROPERTY => 'abc123'], $this->event->getData());
    }

    /**
     * @test
     */
    public function injectDataShouldUseTheCorrectDefaultValueWhenAddingGeneratedIdToData(): void
    {
        $this->input->expects(self::once())
                    ->method('getAttribute')
                    ->with(IdentifierGenerator::class)
                    ->willReturn('abc123');

        $listener = new InputDataInjector();
        $listener->injectData($this->event);

        self::assertSame(['test' => 1, '_input.id' => 'abc123'], $this->event->getData());
    }

    /**
     * @test
     */
    public function injectDataShouldNotInjectAnythingIfThePropertyIsAlreadySet(): void
    {
        $this->input->expects(self::once())
                    ->method('getAttribute')
                    ->with(IdentifierGenerator::class)
                    ->willReturn(4);

        $listener = new InputDataInjector('test');
        $listener->injectData($this->event);

        self::assertSame(['test' => 1], $this->event->getData());
    }
}
