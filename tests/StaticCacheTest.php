<?php

use PHPUnit\Framework\TestCase;
use StaticCacheManager\StaticCacheContainer;

/**
 * Class StaticCacheTest
 */
final class StaticCacheTest extends TestCase
{
    /**
     * Checks if always the same CacheContainer is returned
     */
    public function testCacheIsInstanceOfStaticCache()
    {
        $instance = StaticCacheContainer::getInstance();
        $cache = $instance->get('myCache');

        self::assertTrue($cache instanceof \StaticCacheManager\StaticCache);
    }

    /**
     *
     */
    public function testElementsAreCached()
    {
        $instance = StaticCacheContainer::getInstance();
        $cache1 = $instance->get('testCache1');
        $cache2 = $instance->get('testCache2');

        $cache1->set(1, 'One');
        $cache1->set(2, 'Two');
        $cache1->set(3, 'Three');

        $cache2->set(1, 'Ten');
        $cache2->set(2, 'Twenty');

        self::assertEquals('One', StaticCacheContainer::getInstance()->get('testCache1')->get(1));
        self::assertEquals('Two', StaticCacheContainer::getInstance()->get('testCache1')->get(2));
        self::assertEquals('Ten', StaticCacheContainer::getInstance()->get('testCache2')->get(1));

        self::assertEquals(3, StaticCacheContainer::getInstance()->get('testCache1')->count());
        self::assertEquals(2, StaticCacheContainer::getInstance()->get('testCache2')->count());
    }

    /**
     * @throws \StaticCacheManager\InvalidCacheArgumentException
     */
    public function testElementsAreRemoved()
    {
        $cache = StaticCacheContainer::getInstance()->get('single-elements-removed');

        $cache->set(1, 1);
        $cache->set(2, 2);
        $cache->set(3, 3);

        self::assertEquals(3, $cache->count());

        $cache->delete(1);
        $cache->delete(3);

        self::assertEquals(1, $cache->count());
        self::assertEquals(1, StaticCacheContainer::getInstance()->get('single-elements-removed')->count());
        self::assertTrue($cache->has(2));
        self::assertFalse($cache->has(1));
    }

    /**
     * @throws \StaticCacheManager\InvalidCacheArgumentException
     */
    public function testMultipleElementsAreAdded()
    {
        $cache = StaticCacheContainer::getInstance()->get('multiple-elements-added');

        $cache->setMultiple([
            111 => 'One',
            222 => 'Two',
            333 => 'Three',
        ]);

        self::assertTrue($cache->has(111));
        self::assertTrue(StaticCacheContainer::getInstance()->get('multiple-elements-added')->has(222));
        self::assertTrue($cache->has(333));
    }

    /**
     * @throws \StaticCacheManager\InvalidCacheArgumentException
     */
    public function testMultipleElementsAreRemoved()
    {
        $cache = StaticCacheContainer::getInstance()->get('multiple-elements-removed');

        $cache->set(1, 1);
        $cache->set(2, 2);
        $cache->set(3, 3);

        self::assertEquals(3, $cache->count());

        $cache->deleteMultiple([1,3]);

        self::assertEquals(1, $cache->count());
        self::assertEquals(1, StaticCacheContainer::getInstance()->get('multiple-elements-removed')->count());
        self::assertTrue($cache->has(2));
        self::assertFalse($cache->has(1));
    }

}
