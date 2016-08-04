<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitddce5f4f71807dd85b7a99bd9e791d3e
{
    public static $files = array (
        'ad155f8f1cf0d418fe49e248db8c661b' => __DIR__ . '/..' . '/react/promise/src/functions_include.php',
        'a0edc8309cc5e1d60e3047b5df6b7052' => __DIR__ . '/..' . '/guzzlehttp/psr7/src/functions_include.php',
    );

    public static $prefixLengthsPsr4 = array (
        'w' => 
        array (
            'wgm\\services\\src\\' => 17,
        ),
        'R' => 
        array (
            'React\\Stream\\' => 13,
            'React\\Socket\\' => 13,
            'React\\SocketClient\\' => 19,
            'React\\Promise\\' => 14,
            'React\\HttpClient\\' => 17,
            'React\\EventLoop\\' => 16,
            'React\\Dns\\' => 10,
            'React\\Cache\\' => 12,
        ),
        'P' => 
        array (
            'Psr\\Http\\Message\\' => 17,
        ),
        'G' => 
        array (
            'GuzzleHttp\\Psr7\\' => 16,
        ),
        'C' => 
        array (
            'Clue\\React\\Soap\\' => 16,
            'Clue\\React\\Buzz\\' => 16,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'wgm\\services\\src\\' => 
        array (
            0 => __DIR__ . '/../..' . '/app',
        ),
        'React\\Stream\\' => 
        array (
            0 => __DIR__ . '/..' . '/react/stream/src',
        ),
        'React\\Socket\\' => 
        array (
            0 => __DIR__ . '/..' . '/react/socket/src',
        ),
        'React\\SocketClient\\' => 
        array (
            0 => __DIR__ . '/..' . '/react/socket-client/src',
        ),
        'React\\Promise\\' => 
        array (
            0 => __DIR__ . '/..' . '/react/promise/src',
        ),
        'React\\HttpClient\\' => 
        array (
            0 => __DIR__ . '/..' . '/react/http-client/src',
        ),
        'React\\EventLoop\\' => 
        array (
            0 => __DIR__ . '/..' . '/react/event-loop/src',
        ),
        'React\\Dns\\' => 
        array (
            0 => __DIR__ . '/..' . '/react/dns/src',
        ),
        'React\\Cache\\' => 
        array (
            0 => __DIR__ . '/..' . '/react/cache/src',
        ),
        'Psr\\Http\\Message\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/http-message/src',
        ),
        'GuzzleHttp\\Psr7\\' => 
        array (
            0 => __DIR__ . '/..' . '/guzzlehttp/psr7/src',
        ),
        'Clue\\React\\Soap\\' => 
        array (
            0 => __DIR__ . '/..' . '/clue/soap-react/src',
        ),
        'Clue\\React\\Buzz\\' => 
        array (
            0 => __DIR__ . '/..' . '/clue/buzz-react/src',
        ),
    );

    public static $prefixesPsr0 = array (
        'E' => 
        array (
            'Evenement' => 
            array (
                0 => __DIR__ . '/..' . '/evenement/evenement/src',
            ),
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitddce5f4f71807dd85b7a99bd9e791d3e::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitddce5f4f71807dd85b7a99bd9e791d3e::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInitddce5f4f71807dd85b7a99bd9e791d3e::$prefixesPsr0;

        }, null, ClassLoader::class);
    }
}
