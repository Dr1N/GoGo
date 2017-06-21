<?php

namespace src\base;

use src\Application;

class Model
{
    /**
     * @var string
     */
    static public  $tableName;

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

    static public function findAll($limit = null)
    {
        $models = [];
        $table = static::getTableRecords($limit);
        foreach ($table as $item) {
            $models[] = static::createObjectFromArray($item);
        }
        return $models;
    }
    
    static public function findOne($id)
    {
         Application::$db->where('id', $id);
         $result = Application::$db->getOne(static::$tableName);
        
         return static::createObjectFromArray($result);
    }

    static public function createObjectFromArray($array)
    {
        if (empty($array)) return null;

        $model = new static();
        foreach ($array as $field => $value) {
            if (property_exists(static::class, $field)) {
                $model->$field = $value;
            }
        }

        return $model;
    }

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
