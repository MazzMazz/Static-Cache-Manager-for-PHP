<?php

use PHPUnit\Framework\TestCase;
use StaticCacheManager\StaticCacheContainer;

/**
 * Class StaticCacheContainerTest
 */
final class StaticCacheContainerTest extends TestCase
{
    /**
     * Checks if always the same CacheContainer is returned
     */
    public function testCacheContainerIsSingelton()
    {
        $instance1 = StaticCacheContainer::getInstance();
        $instance2 = StaticCacheContainer::getInstance();

        self::assertEquals($instance1, $instance2);
    }

    /**
     * Checks if caches are created
     */
    public function testCachesAreCreated()
    {
        StaticCacheContainer::getInstance()->clear();
        // Check if no caches are stored
        self::assertEquals(StaticCacheContainer::getInstance()->count(), 0);

        $instance = StaticCacheContainer::getInstance();

        // Create 2 caches
        $instance->get('NameCache');
        $instance->get('CurrencyCache');

        self::assertTrue(StaticCacheContainer::getInstance()->has('NameCache'));
        self::assertTrue(StaticCacheContainer::getInstance()->has('CurrencyCache'));
        self::assertEquals(2, StaticCacheContainer::getInstance()->count());
    }

    /**
     * Checks if Container is cleared properly
     */
    public function testCachesAreClearedProperly()
    {
        $containerInstance = StaticCacheContainer::getInstance();
        $containerInstance->clear();
        // Check if no caches are stored
        self::assertEquals(0, $containerInstance->count());
        self::assertEquals(0, StaticCacheContainer::getInstance()->count());

        $containerInstance->get('cache1');
        self::assertEquals(1, $containerInstance->count());
        self::assertEquals(1, StaticCacheContainer::getInstance()->count());

        $containerInstance->clear();
        self::assertEquals(0, $containerInstance->count());
        self::assertEquals(0, StaticCacheContainer::getInstance()->count());
    }

}
