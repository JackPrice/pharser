<?php namespace Pharser\Grammar;

/**
 * Tests for the Set object.
 *
 * @author Jack Price <jackprice@outlook.com>
 */
class SetTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateFromArray()
    {
        new Set([]);
        new Set([1, 2]);
        new Set(['a', 'b']);
    }

    public function testCreateFromArrayWithClassValidator()
    {
        new Set([], 'stdClass');
        new Set([new \stdClass()], 'stdClass');
        new Set([new \DateTime(), new \DateTimeImmutable()], 'DateTimeInterface');
    }

    public function testCreateFromArrayWithCallableValidator()
    {
        new Set([], 'stdClass');
        new Set([new \stdClass()], 'stdClass');
        new Set([new \DateTime(), new \DateTimeImmutable()], function () {
            return true;
        });
    }

    public function testCreateFromArrayWithClassValidatorInvalid()
    {
        $this->setExpectedException(\InvalidArgumentException::class);

        new Set([1, new \DateTimeImmutable()], 'DateTimeInterface');
    }

    public function testCreateFromArrayWithCallableValidatorInvalid()
    {
        $this->setExpectedException(\InvalidArgumentException::class);

        new Set([1, new \DateTimeImmutable()], function () {
            return false;
        });
    }

    public function testOffsetSetWithClassValidator()
    {
        $set = new Set([new \DateTime(), new \DateTimeImmutable()], 'DateTimeInterface');

        $set[0] = new \DateTime();
        $set->offsetSet(3, new \DateTimeImmutable());
    }

    public function testOffsetSetWithClassValidatorInvalid()
    {
        $this->setExpectedException(\InvalidArgumentException::class);

        $set = new Set([new \DateTime(), new \DateTimeImmutable()], 'DateTimeInterface');

        $set[0] = new \stdClass();
    }

    public function testOffsetExists()
    {
        $set = new Set([1 => 'foo', 'bar' => true]);

        $this->assertEquals(true, $set->offsetExists(1));
        $this->assertEquals(true, $set->offsetExists('bar'));
        $this->assertEquals(false, $set->offsetExists('baz'));
        $this->assertEquals(false, $set->offsetExists(0));
        $this->assertEquals(true, isset($set[1]));
        $this->assertEquals(true, isset($set['bar']));
        $this->assertEquals(false, isset($set['baz']));
        $this->assertEquals(false, isset($set[0]));
    }

    public function testOffsetGet()
    {
        $set = new Set([1 => 'foo', 'bar' => true]);

        $this->assertEquals('foo', $set->offsetGet(1));
        $this->assertEquals(true, $set->offsetGet('bar'));
        $this->assertEquals('foo', $set[1]);
        $this->assertEquals(true, $set['bar']);
    }

    public function testOffsetSet()
    {
        $set = new Set([1 => 'foo', 'bar' => true]);

        $set->offsetSet(2, 'baz');
        $set[3] = true;

        $this->assertEquals('baz', $set[2]);
        $this->assertEquals(true, $set[3]);
    }

    public function testOffsetUnset()
    {
        $set = new Set([1 => 'foo', 'bar' => true]);

        $set->offsetUnset(1);
        unset($set['bar']);

        $this->assertFalse($set->offsetExists(1));
        $this->assertFalse($set->offsetExists('bar'));
    }

    public function testContains()
    {
        $object = new \DateTime();

        $set = new Set(['foo', 'bar', $object]);

        $this->assertTrue($set->contains('foo'));
        $this->assertFalse($set->contains('baz'));
        $this->assertTrue($set->contains($object));
        $this->assertFalse($set->contains(new \stdClass()));
    }

    public function testHasMatching()
    {
        $set = new Set([1, 2, 3]);

        $this->assertTrue($set->hasMatching(function () {
            return true;
        }));

        $this->assertFalse($set->hasMatching(function () {
            return false;
        }));
    }

    public function testFilter()
    {
        $set = new Set([1, 2, 3, 4]);

        $this->assertEquals([1, 4], $set->filter(function ($i) {
            return $i === 1 || $i === 4;
        })->toArray());
    }

    public function testExclude()
    {
        $set = new Set([1, 2, 3, 4]);

        $this->assertEquals([2, 3], $set->exclude(function ($i) {
            return $i === 1 || $i === 4;
        })->toArray());
    }
}
