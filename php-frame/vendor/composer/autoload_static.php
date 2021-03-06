<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit1b75e63f60cd39fa900b9a16c77f3f3e
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'Psr\\Container\\' => 14,
        ),
        'A' => 
        array (
            'App\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Psr\\Container\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/container/src',
        ),
        'App\\' => 
        array (
            0 => __DIR__ . '/../..' . '/app',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit1b75e63f60cd39fa900b9a16c77f3f3e::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit1b75e63f60cd39fa900b9a16c77f3f3e::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
