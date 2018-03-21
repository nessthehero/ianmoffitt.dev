<?php

// In progress

class NodeParser {

	protected $node;
	protected $type;
	protected $mode;
	protected $lang;

	const DEFAULT_MODE = 'full';

	public function __construct($node, $mode) {

		$this->node = $node;
		$this->type = $this->nvl($node->type, '');
		$this->mode = $this->nvl($mode, DEFAULT_MODE);
		$this->lang = $this->nvl($node->language, 'und');

	}

	// Get field name and view mode
	public function parse($field, $mode = DEFAULT_MODE) {

		$this->setMode($mode);

		return $this->clean($this->raw($field));

	}

	// Returns raw data from field
	public function raw($field) {

	}

	// Applies view mode to raw data (or returns custom object)
	private function clean($raw) {

	}

	// Change mode
	private function setMode($mode) {
		$this->mode = $mode;
	}

	// Value fallback catcher
	public function nvl(/* [(array $array, $key) | $value]... */)
	{
	    $count = func_num_args();

	    for ($i = 0; $i < $count - 1; $i++)
	    {
	        $arg = func_get_arg($i);

	        if (!isset($arg))
	        {
	            continue;
	        }

	        if (is_array($arg))
	        {

	            $key = func_get_arg($i + 1);

	            if (is_null($key) || is_string($key) || is_int($key) || is_float($key) || is_bool($key))
	            {

	                if (isset($arg[$key]))
	                {
	                    // print_r($arg);
	                    return $arg[$key];
	                }

	                $i++;
	                continue;
	            }
	        }

	        return $arg;
	    }

	    if ($i < $count)
	    {
	        return func_get_arg($i);
	    }

	    return null;
	}

}
