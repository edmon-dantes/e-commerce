<?php
namespace App\Traits;

trait JsonTools
{
    protected function json_decode($value) {
        if (is_string($value) && preg_match('~%[0-9A-F]{2}~i', $value)) {
            return json_decode(urldecode($value), true);
        }
        return null;
    }
}
