<?php

namespace weatherUSA\PHPUtil;

use \PDO;
use \PDOStatement;
use \Exception;

if (version_compare(phpversion(), '8.0.0', '<'))
{
	class VersionSpecificPDOStatement extends PDOStatement
	{
		public $count = 0;

		/** @disregard version-specific may not work on newer php versions in IDE */
		public function fetchAll($how = NULL, $class_name = NULL, $ctor_args = NULL)
		{
			$args = func_get_args();
			$data = call_user_func_array([get_parent_class(), 'fetchAll'], $args);
			$this->count = count($data);
			return $data;
		}
	}
}
else
{
	class VersionSpecificPDOStatement extends PDOStatement
	{
		public $count = 0;

		public function fetchAll($mode = PDO::FETCH_DEFAULT, $fetch_argument = NULL, ...$args): array
		{
			$args = func_get_args();
			$data = call_user_func_array([get_parent_class(), 'fetchAll'], $args);
			$this->count = count($data);
			return $data;
		}
	}
}

class MyPDOStatement extends VersionSpecificPDOStatement
{
	public function fetchAllWithId()
	{
		$data = [];
		foreach ($this as $row)
		{
			$id = $row['id'];
			$data[$id] = $row;
		}
		return $data;
	}

	public function fetchAsKeyValue()
	{
		$data = [];
		$c = NULL;
		foreach ($this as $row)
		{
			if ($c === NULL)
			{
				$c = count($row);
				if ($c > 2) throw new Exception('Must have at most 2 columns (key, value) to use fetchAsKeyValue');
			}

			if ($c === 1)
			{
				$col1 = reset($row);
				$data[$col1] = $col1;
			}
			else
			{
				$col1 = reset($row);
				$col2 = next($row);
				$data[$col1] = $col2;
			}
		}
		return $data;
	}
}
