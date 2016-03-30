<?php

namespace JedenWeb\DI;

use Nette\DI\CompilerExtension;
use Nette\PhpGenerator\ClassType;

/**
 * @author Pavel Jurásek
 */
class HeadersExtension extends CompilerExtension
{

	/** @var array */
	private $defaults = array(
		'headers' => array(),
	);


	/**
	 * @param ClassType $class
	 */
	public function afterCompile(ClassType $class)
	{
		if (PHP_SAPI === 'cli') {
			return;
		}
		$initialize = $class->methods['initialize'];
		$config = $this->getConfig($this->defaults);

		foreach ($config['headers'] as $key => $value) {
			if ($value != NULL) { // intentionally ==
				$initialize->addBody('header(?);', ["$key: $value"]);
			}
		}
	}

}
