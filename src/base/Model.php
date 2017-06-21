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
     * Insert AD
     * @return false|integer
     */
    public function insert()
    {
        if (!$this->validate()) {
            return false;
        }
        $data = [];
        foreach ($this as $key => $value) {
            $data[$key] = $value;
        }
        if (!empty($data)) {
            try {
                Application::$db->ping();
            } catch (\Exception $ex) {
                Application::$db->connect();
            }
            return Application::$db->insert(static::$tableName, $data);
        }

        return false;
    }

    public function save()
    {
        if (!$this->validate()) {
            return false;
        }
        $data = [];
        foreach ($this as $key => $value) {
            $data[$key] = $value;
        }
        if (!empty($data)) {
            try {
                Application::$db->ping();
            } catch (\Exception $ex) {
                Application::$db->connect();
            }
            Application::$db->where('id', $this->id);
            return Application::$db->update(static::$tableName, $data);
        }

        return false;
    }

    public function validate()
    {
        return true;
    }

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
