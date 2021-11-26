<?php
declare(strict_types=1);

namespace Chimera\MessageCreator\JmsSerializer\Tests\Unit;

use Chimera\Input;
use Chimera\MessageCreator\InputExtractor\UseInputData;
use Chimera\MessageCreator\JmsSerializer\ArrayTransformer;
use JMS\Serializer\ArrayTransformerInterface;
use PHPUnit\Framework\TestCase;
use stdClass;

/** @coversDefaultClass \Chimera\MessageCreator\JmsSerializer\ArrayTransformer */
final class ArrayTransformerTest extends TestCase
{
    /**
     * @test
     *
     * @covers ::__construct()
     * @covers ::create()
     */
    public function createShouldReturnANewInstanceOfGivenMessageUsingTheInputData(): void
    {
        $input       = $this->createMock(Input::class);
        $transformer = $this->createMock(ArrayTransformerInterface::class);
        $data        = ['test' => 1];

        $input->method('getData')
              ->willReturn($data);

        $transformer->expects(self::once())
                    ->method('fromArray')
                    ->with($data, stdClass::class)
                    ->willReturn((object) $data);

        $creator = new ArrayTransformer($transformer, new UseInputData());
        $message = $creator->create(stdClass::class, $input);

        self::assertSame(1, $message->test);
    }
}
