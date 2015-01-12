<?php

namespace AccountingSystem\Template;

interface Engine
{
	public function render($template, $data = []);
}