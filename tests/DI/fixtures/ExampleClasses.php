<?php
namespace Tests\DI\Fixture;

/**
 * Interface FakeConfigInterface
 *
 * @package Tests\DI\Fixture
 */
interface FakeConfigInterface
{
    /**
     * @param $item
     *
     * @return null|string
     */
    public function get($item) : ?string;

    /**
     * @param string $key
     * @param string $value
     */
    public function set(string $key, string $value);
}

/**
 * Class FakeConfig
 *
 * @package Tests\DI\Fixture
 */
class FakeConfig implements FakeConfigInterface
{
    /**
     * @var array
     */
    private $items = [];

    /**
     * @param string $key
     * @param string $value
     */
    public function set(string $key, string $value)
    {
        $this->items[$key] = $value;
    }

    /**
     * @param $item
     *
     * @return null|string
     */
    public function get($item) : ?string
    {
        return $this->items[$item] ?? null;
    }
}

/**
 * Class ExampleDIClass
 *
 * @package Tests\DI\Fixture
 */
class ExampleDIClass
{
    /**
     * @var FakeConfigInterface
     */
    private $config;

    /**
     * ExampleDIClass constructor.
     *
     * @param FakeConfigInterface $config
     */
    public function __construct(FakeConfigInterface $config)
    {
        $this->config = $config;
    }

    /**
     * @param $key
     * @param $value
     */
    public function setItem($key, $value)
    {
        $this->config->set($key, $value);
    }

    /**
     * @param $item
     *
     * @return null|string
     */
    public function showItem($item) : ?string
    {
        return $this->config->get($item);
    }
}