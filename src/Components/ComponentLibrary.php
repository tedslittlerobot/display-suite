<?php

namespace Tlr\Display\Components;

use Illuminate\Contracts\Container\Container;
use Tlr\Display\Components\AssetManager;

class ComponentLibrary
{

    /**
     * The registered components
     *
     * @var array
     */
    protected $components;

    /**
     * The asset manager instance
     *
     * @var \Tlr\Display\Components\AssetManager
     */
    protected $assets;

    /**
     * The application container instance
     *
     * @var \Illuminate\Contracts\Container\Container
     */
    protected $container;

    public function __construct(AssetManager $assets, Container $container, array $components = [])
    {
        $this->assets = $assets;
        $this->container = $container;
        $this->components = $components;
    }

    /**
     * Register a component
     *
     * @param  string $name
     * @param  string $class
     * @return \Tlr\Display\Components\ComponentLibrary
     */
    public function register($name, $class)
    {
        $this->components[$name] = $class;

        return $this;
    }

    /**
     * Construct a component
     *
     * @param  string  $name
     * @param  array   $input
     * @return mixed
     */
    public function component($name, array $input = [])
    {
        $class = $this->components[$name];

        $component = $this->container->make($class, $input);

        $this->assets->activate($component->getAssets());

        return $component;
    }

    /**
     * Dynamically call a component view
     *
     * @param  string $method
     * @param  array  $arguments
     * @return \Illuminate\Contracts\Support\Htmlable
     */
    public function __call($method, array $arguments)
    {
        return $this->component(
            lcfirst(studly_case($method)),
            $arguments
        );
    }

}
