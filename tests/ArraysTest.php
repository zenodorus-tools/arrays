<?php namespace Zenodorus;

use \PHPUnit\Framework\TestCase;

class ArrayTest extends TestCase
{
    /**
     * @Strings::pluck()
     */
    const SAMPLE = [
        'star' => [
            'wars',
            'trek',
            'gate' => [
                'atlantis'
            ]
        ],
        'far' => 'scape',
        'babylon' => 5,
    ];

    public function testPluckBasic()
    {
        $expected = 'atlantis';
        $directions = ['star', 'gate', 0];
        $this->assertEquals($expected, Arrays::pluck(self::SAMPLE, $directions));
    }

    public function testPluckKeyDoesntExist()
    {
        $directions = [
            'star',
            'gate',
            'universe',
        ];
        $result = Arrays::pluck(self::SAMPLE, $directions);
        $this->assertInstanceOf('Zenodorus\\ZenodorusError', $result);
        $this->assertEquals('pluck::not-found', $result->getCode());
    }

    /**
     * @Strings::flatten()
     */

    public function testFlattenBasic()
    {
        $expected = [
            'wars',
            'trek',
            'atlantis',
            'scape',
            5,
        ];
        $this->assertEquals($expected, Arrays::flatten(self::SAMPLE));
    }

    /**
     * @Strings::isEmpty()
     */

    public function testArrayIsEmpty()
    {
        $test = [
            '',
            "",
            null,
            '',
        ];
        $this->assertTrue(Arrays::isEmpty($test));
    }

    public function testArrayIsNotEmpty()
    {
        $test1 = [
            false,
        ];
        $test2 = [
            0
        ];
        $test3 = [
            '',
            false
        ];
        $this->assertFalse(Arrays::isEmpty($test1));
        $this->assertFalse(Arrays::isEmpty($test2));
        $this->assertFalse(Arrays::isEmpty($test3));
    }

    public function testArrayCompact()
    {
        $test1 = [
          'star',
          'wars',
          'trek',
        ];
        $test2 = [
            'star',
            'wars',
            'trek',
            false,
        ];
        $test3 = [
            'star',
            'wars',
            'trek',
            null,
        ];
        $this->assertEquals($test1, Arrays::compact($test1), "False positives are removing non-false fields.");
        $this->assertEquals($test1, Arrays::compact($test2), "Did not remove literal `false` value.");
        $this->assertEquals($test1, Arrays::compact($test3), "Did not remove literal `null` value.");
        $this->assertEquals(4, count(Arrays::compact($test3, true)), "Null not accurately considered not false.");
    }
}
