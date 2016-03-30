<?php

/**
 * Test: HeadersExtension.basic
 */

use JedenWeb\DI\HeadersExtension;
use Nette\DI;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';

if (PHP_SAPI === 'cli') {
	Tester\Environment::skip('Headers are not testable in CLI mode');
}


$compiler = new DI\Compiler;
$compiler->addExtension('http', new HeadersExtension);
$loader = new DI\Config\Loader;
$config = $loader->load(__DIR__ . '/config.neon');

eval($compiler->compile($config, 'Container1', 'Nette\DI\Container'));

$container = new Container1;
$container->initialize();

$headers = headers_list();
Assert::contains('A: b', $headers);
Assert::notContains('C:', $headers);

echo ' '; @ob_flush(); flush();

Assert::true(headers_sent());

Assert::error(function () use ($container) {
	$container->initialize();
}, [
	[E_WARNING, 'Cannot modify header information - headers already sent %a%'],
	[E_WARNING, 'Cannot modify header information - headers already sent %a%'],
	[E_WARNING, 'Cannot modify header information - headers already sent %a%'],
	[E_WARNING, 'Cannot modify header information - headers already sent %a%'],
]);
