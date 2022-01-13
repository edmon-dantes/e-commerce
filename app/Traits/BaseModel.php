<?php

namespace App\Traits;

trait BaseModel
{
    public function getTableColumns()
    {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }
}




/*
 public function scopeSearch($query, $value): void
    {
        if (is_null($value)) {
            return;
        }

        $value = $this->json_decode($value);

        if (is_string($value)) {
            foreach ($this->getTableColumns() as $field) {
                $methodName = "scopeSearch" . Str::ucfirst(Str::camel($field));
                if (method_exists($this, $methodName)) {
                    $search = explode(",", $value);
                    foreach($search as $find) {
                        $this->$methodName($query, trim($find));
                    }
                }
            }
        } else if (is_array($value)) {
            foreach ($value as $field => $text) {
                $methodName = "scopeSearch" . Str::ucfirst(Str::camel($field));
                if (method_exists($this, $methodName)) {
                    $search = explode(",", $text);
                    foreach($search as $find) {
                        $this->$methodName($query, trim($find));
                    }
                }
            }
        }
    }

    public function scopeSort($query, $value): void
    {
        if (is_null($value)) {
            return;
        }

        $value = $this->json_decode($value);

        if (is_array($value)) {
            foreach ($value as $field => $text) {
                $methodName = "scopeSort" . Str::ucfirst(Str::camel($field));
                if (method_exists($this, $methodName)) {
                    $this->$methodName($query, in_array($direction = Str::upper($text), ['ASC', 'DESC']) ? $direction : 'ASC');
                }
            }
        }
    }
 */
