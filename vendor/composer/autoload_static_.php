<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit4deb13efb25ac91838f52bb872a68164
{
    public static $files = array (
        '5255c38a0faeba867671b61dfda6d864' => __DIR__ . '/..' . '/paragonie/random_compat/lib/random.php',
        '0e6d7bf4a5811bfa5cf40c5ccd6fae6a' => __DIR__ . '/..' . '/symfony/polyfill-mbstring/bootstrap.php',
        '023d27dca8066ef29e6739335ea73bad' => __DIR__ . '/..' . '/symfony/polyfill-php70/bootstrap.php',
    );

    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Symfony\\Polyfill\\Php70\\' => 23,
            'Symfony\\Polyfill\\Mbstring\\' => 26,
            'Symfony\\Component\\HttpFoundation\\' => 33,
            'Symfony\\Component\\EventDispatcher\\' => 34,
        ),
        'O' => 
        array (
            'Omnipay\\PayPal\\' => 15,
            'Omnipay\\Coinbase\\' => 17,
            'Omnipay\\AmazonPayments\\' => 23,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Symfony\\Polyfill\\Php70\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-php70',
        ),
        'Symfony\\Polyfill\\Mbstring\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-mbstring',
        ),
        'Symfony\\Component\\HttpFoundation\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/http-foundation',
        ),
        'Symfony\\Component\\EventDispatcher\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/event-dispatcher',
        ),
        'Omnipay\\PayPal\\' => 
        array (
            0 => __DIR__ . '/..' . '/omnipay/paypal/src',
        ),
        'Omnipay\\Coinbase\\' => 
        array (
            0 => __DIR__ . '/..' . '/omnipay/coinbase/src',
        ),
        'Omnipay\\AmazonPayments\\' => 
        array (
            0 => __DIR__ . '/..' . '/lemonstand/omnipay-amazon/src',
        ),
    );

    public static $prefixesPsr0 = array (
        'O' => 
        array (
            'Omnipay\\Common\\' => 
            array (
                0 => __DIR__ . '/..' . '/omnipay/common/src',
            ),
        ),
        'G' => 
        array (
            'Guzzle\\Tests' => 
            array (
                0 => __DIR__ . '/..' . '/guzzle/guzzle/tests',
            ),
            'Guzzle' => 
            array (
                0 => __DIR__ . '/..' . '/guzzle/guzzle/src',
            ),
        ),
    );

    public static $classMap = array (
        'ArithmeticError' => __DIR__ . '/..' . '/symfony/polyfill-php70/Resources/stubs/ArithmeticError.php',
        'AssertionError' => __DIR__ . '/..' . '/symfony/polyfill-php70/Resources/stubs/AssertionError.php',
        'DivisionByZeroError' => __DIR__ . '/..' . '/symfony/polyfill-php70/Resources/stubs/DivisionByZeroError.php',
        'Error' => __DIR__ . '/..' . '/symfony/polyfill-php70/Resources/stubs/Error.php',
        'Omnipay\\Omnipay' => __DIR__ . '/..' . '/omnipay/common/src/Omnipay/Omnipay.php',
        'ParseError' => __DIR__ . '/..' . '/symfony/polyfill-php70/Resources/stubs/ParseError.php',
        'SessionUpdateTimestampHandlerInterface' => __DIR__ . '/..' . '/symfony/polyfill-php70/Resources/stubs/SessionUpdateTimestampHandlerInterface.php',
        'TypeError' => __DIR__ . '/..' . '/symfony/polyfill-php70/Resources/stubs/TypeError.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit4deb13efb25ac91838f52bb872a68164::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit4deb13efb25ac91838f52bb872a68164::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInit4deb13efb25ac91838f52bb872a68164::$prefixesPsr0;
            $loader->classMap = ComposerStaticInit4deb13efb25ac91838f52bb872a68164::$classMap;

        }, null, ClassLoader::class);
    }
}
