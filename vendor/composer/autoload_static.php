<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit1a2bde252f1257bcf38b65699caf52c5
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'Psr\\Cache\\' => 10,
        ),
        'D' => 
        array (
            'Doctrine\\Common\\Lexer\\' => 22,
            'Doctrine\\Common\\Annotations\\' => 28,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Psr\\Cache\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/cache/src',
        ),
        'Doctrine\\Common\\Lexer\\' => 
        array (
            0 => __DIR__ . '/..' . '/doctrine/lexer/lib/Doctrine/Common/Lexer',
        ),
        'Doctrine\\Common\\Annotations\\' => 
        array (
            0 => __DIR__ . '/..' . '/doctrine/annotations/lib/Doctrine/Common/Annotations',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit1a2bde252f1257bcf38b65699caf52c5::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit1a2bde252f1257bcf38b65699caf52c5::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit1a2bde252f1257bcf38b65699caf52c5::$classMap;

        }, null, ClassLoader::class);
    }
}
