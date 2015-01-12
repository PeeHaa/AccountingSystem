<?php

namespace AccountingSystem\Template;

use Mustache_Engine;

class MustacheEngineAdapter implements Engine
{
	private $engine;

	public function __construct(Mustache_Engine $engine)
	{
		$this->engine = $engine;
	}

	public function render($template, $data = [])
	{
		return $this->engine->render($template, $data);
	}

    public function templateExists($templateName)
    {
        $path = rtrim(__DIR__, "src/Template/") . 'stem/templates/' . $templateName . '.mustache';

        if(file_exists($path)) {
            return true;
        } else {
            return false;
        }
    }
}