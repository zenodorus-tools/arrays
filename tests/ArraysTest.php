<?php namespace Zenodorus;

use \PHPUnit\Framework\TestCase;

class StringTest extends TestCase
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

    public function testPluckKeyDoesntExistSafe()
    {
        $directions = [
            'star',
            'gate',
            'universe',
        ];
        $result = Arrays::pluck(self::SAMPLE, $directions, true);
        $this->assertEquals(null, $result);
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
}
