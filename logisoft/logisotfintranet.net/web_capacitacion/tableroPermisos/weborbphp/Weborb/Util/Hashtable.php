<?php
class Hashtable
{
	private $keys;
	private $values;
	private $currentIndex;

	public function __construct()
	{
		$this->keys = array();
		$this->values = array();
		$this->currentIndex = 0;
	}

	public function containsKey(/*Object*/ $key)
	{
		if(in_array($key, $this->keys))
		{
			return true;
		}

		return false;
	}

	public function lenth()
	{
		return count($this->keys);
	}

	public function get(/*Object*/ $key)
	{
		if(!in_array($key, $this->keys))
		{
			throw new Exception("Hashtable has elment with key: " . $key);
			return false;
		}

		for($i = 0, $len = $this->lenth(); $i<$len; $i++)
		{
			if($this->keys[$i] = $key)
				return $this->values[$i];
		}
	}

	public function put(/*Object*/ $key, /*Object*/ $value)
	{
		$this->keys[$this->currentIndex] = $key;
		$this->values[$this->currentIndex] = $value;
		$this->currentIndex++;
	}
}
?>