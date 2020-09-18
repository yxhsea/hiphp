<?php

class App implements Psr\Container\ContainerInterface
{
    public $binding = [];       // 绑定关系
    private static $instance;   // 这个类的实例
    protected $instances = [];  // 所有实例的存放

    private function __construct()
    {
        self::$instance = $this; // App 类的实例
        $this->register();       // 注册绑定
        $this->boot();           //
    }

    /**
     * 获取对象
     * @param string $abstract
     * @return mixed
     */
    public function get($abstract)
    {
        if (isset($this->instances[$abstract])) {
            return $this->instances[$abstract];
        }

        $instance = $this->binding[$abstract]['concrete']($this);
        if ($this->binding[$abstract]['is_singleton']) { // is_singleton 指定是单例模式
            $this->instances[$abstract] = $instance;
        }

        return $instance;
    }

    /**
     * 获取当前实例对象
     * @return App
     */
    public function getContainer()
    {
        return self::$instance ?? self::$instance = new self();
    }

    /**
     * 绑定类
     * @param $abstract
     * @param $concrete
     * @param bool $is_singleton 标识是否需要单例
     */
    public function bind($abstract, $concrete, $is_singleton = false)
    {
        // 生成闭包
        if (! $concrete instanceof \Closure) {
            $concrete = function () use ($concrete) {
                // 反射类
                $reflector = new ReflectionClass($concrete);

                // 反射构造函数
                $constructor = $reflector->getConstructor();
                if (is_null($constructor)) {
                    return $reflector->newInstance();
                }

                // 反射构造参数
                $parameters = $constructor->getParameters();

                // 依赖参数
                $dependencies = [];
                foreach ($parameters as $parameter) {
                    if ($parameter->getClass()) {
                        $dependencies[] = $this->get($parameter->getClass()->name);
                    }
                }

                $instances = $dependencies;
                return $reflector->newInstanceArgs($instances);
            };
        }

        // 存储到 $binding 数组中
        $this->binding[$abstract] = compact('concrete', 'is_singleton');
    }

    public function has($id)
    {
        // TODO: Implement has() method.
    }

    /**
     * 服务注册
     */
    protected function register()
    {
        // 注册配置
        $this->bind('config', '', true);
    }

    /**
     * 服务启动
     */
    protected function boot()
    {

    }
}
