<?php

namespace src\base;

use src\Application;

class Model
{
    /**
     * @var string
     */
    public static $tableName;

    /**
     * Return table as array
     * @param null $limit
     * @return array
     */
    static public function getTableRecords($limit = null)
    {
        if ($limit !== null && $limit > 0) {
            $queryResult = Application::$db->get(static::$tableName, $limit);
        } else {
            $queryResult = Application::$db->get(static::$tableName);
        }

        return $queryResult;
    }

    static public function truncate()
    {
        return Application::$db->rawQuery('TRUNCATE TABLE ' . static::$tableName);
    }
}
