<?php

/*
   Connect to a database using PDO (PHP Data Objects)
   Compatibility: PHP 8.2+
*/

namespace weatherUSA\PHPUtil;

use \PDO;
use \PDOException;
use \Exception;

class MyPDO extends PDO
{
	function tableStatus($table)
	{
		$query = 'SHOW TABLE STATUS LIKE "' . $table . '"';
		$stmt = parent::prepare($query);
		$result = $stmt->execute();
		$rtval = $stmt->fetch(PDO::FETCH_ASSOC);
		$stmt->closeCursor();
		return $rtval;
	}

	function arrayToSqlIn($array)
	{
		$str_in = '';
		$ran = FALSE;
		foreach($array as $str)
		{
			$str_in .= ($ran ? ', ' : '') . $this->quote($str);
			$ran = TRUE;
		}
		if(strlen($str_in) === 0)
		{
			return 'NULL';
		}
		else
		{
			return $str_in;
		}
	}

	/* @deprecated */
	function array_to_mysql_in($array)
	{
		return $this->arrayToSqlIn($array);
	}

	function getField($query, $params = NULL)
	{
		if($params !== NULL)
		{
			// handle prepared statement
			$stmt = parent::prepare($query);
			$stmt->execute($params);
		}
		else
		{
			$stmt = parent::query($query);
		}
		$row = $stmt->fetch();
		if(is_array($row))
		{
			$retval = current($row);
		}
		else
		{
			$retval = $row;
		}
		$stmt->closeCursor();
		return $retval;
	}

	/* @deprecated */
	function get_field($query)
	{
		return $this->getField($query);
	}

	static function connectAC($db_config, $read_only = false)
	{
		$host = $db_config['host'];
		if ($read_only && isset($db_config['read_replica_host']))
		{
			$host = $db_config['read_replica_host'];
		}
		$dsn = "{$db_config['driver']}:host={$host};dbname={$db_config['db']}";
		if($db_config['driver'] == 'mysql') $dsn .= ";charset=utf8mb4";
		if($db_config['driver'] == 'pgsql') $dsn .= ';sslmode=require';
		try
		{
			$db = new MyPDO($dsn, $db_config['user'], $db_config['pass'], [
				PDO::ATTR_STATEMENT_CLASS => [MyPDOStatement::class],
				PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
				PDO::ATTR_EMULATE_PREPARES => false,
			]);
			return $db;
		}
		catch(PDOException $e)
		{
			error_log('Failed to connect to the database server. The error message was: ' . $e->getMessage());
			throw new Exception('Failed to connect to the database server. This error has been logged.');
		}
		return false;
	}

	static function connectByDSN($dsn, #[\SensitiveParameter] $db_config, $read_only = false)
	{
		$host = $db_config['host'];
		if ($read_only && isset($db_config['read_replica_host']))
		{
			$host = $db_config['read_replica_host'];
		}
		try
		{
			$db = new MyPDO($dsn, $db_config['user'], $db_config['pass'], [
				PDO::ATTR_STATEMENT_CLASS => [MyPDOStatement::class],
				PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
				PDO::ATTR_EMULATE_PREPARES => false,
			]);
			return $db;
		}
		catch(PDOException $e)
		{
			error_log('Failed to connect to the database server. The error message was: ' . $e->getMessage());
			throw new Exception('Failed to connect to the database server. This error has been logged.');
		}
		return false;
	}
}
