<?php namespace Zenodorus;

use \PHPUnit\Framework\TestCase;

class ArrayTest extends TestCase
{
    /**
     * @Arrays::pluck()
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
        $this->assertNull($result);
    }

    /**
     * @Arrays::flatten()
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
     * @Arrays::isEmpty()
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

    /**
     * @Arrays::removeByValue()
     */

    public function testRemoveByValueInt()
    {
        $array = [1,0,2,4];
        $result = Arrays::removeByValue($array, 0);
        $this->assertNotContains(0, $result);
        $this->assertContains(1, $result);
        $this->assertContains(2, $result);
        $this->assertContains(4, $result);
    }
    
    public function testMultipleRemoveByValueInt()
    {
        $array = [1,0,2,4];
        $result = Arrays::removeByValue($array, 0, 4);
        $this->assertNotContains(0, $result);
        $this->assertNotContains(4, $result);
        $this->assertContains(1, $result);
        $this->assertContains(2, $result);
    }

    public function testRemoveByValueString()
    {
        $array = ['star','wars','trek','gate'];
        $result = Arrays::removeByValue($array, 'trek');
        $this->assertNotContains('trek', $result);
        $this->assertContains('star', $result);
        $this->assertContains('wars', $result);
        $this->assertContains('gate', $result);
    }
    
    public function testMultipleRemoveByValueString()
    {
        $array = ['star','wars','trek','gate'];
        $result = Arrays::removeByValue($array, 'trek', 'wars');
        $this->assertNotContains('trek', $result);
        $this->assertNotContains('wars', $result);
        $this->assertContains('star', $result);
        $this->assertContains('gate', $result);
    }
    
    public function testRemoveByValueArray()
    {
        $array = [['star','wars'],['star','trek'],['star','gate']];
        $result = Arrays::removeByValue($array, ['star','trek']);
        $this->assertNotContains(['star','trek'], $result);
        $this->assertContains(['star','wars'], $result);
        $this->assertContains(['star','gate'], $result);
    }

    public function testMultipleRemoveByValueArray()
    {
        $array = [['star','wars'],['star','trek'],['star','gate']];
        $result = Arrays::removeByValue($array, ['star','trek'], ['star','wars']);
        $this->assertNotContains(['star','trek'], $result);
        $this->assertNotContains(['star','wars'], $result);
        $this->assertContains(['star','gate'], $result);
    }

    public function testRemoveByValueMixed()
    {
        $array = [
            22,
            'star wars',
            ['test' => 'value'],
            'far' => 'scape',
        ];
        $result = Arrays::removeByValue($array, 'scape');
        $this->assertNotContains('scape', $result);
        $this->assertContains('star wars', $result);
        $this->assertContains(['test' => 'value'], $result);
        $this->assertContains(22, $result);
    }

    public function testMultipleRemoveByValueMixed()
    {
        $array = [
            22,
            'star wars',
            ['test' => 'value'],
            'far' => 'scape',
        ];
        $result = Arrays::removeByValue($array, 'scape', ['test' => 'value']);
        $this->assertNotContains('scape', $result);
        $this->assertNotContains(['test' => 'value'], $result);
        $this->assertContains('star wars', $result);
        $this->assertContains(22, $result);
    }
}
