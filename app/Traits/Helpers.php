<?php

namespace App\Traits;

trait Helpers
{
    protected function flatten(array $element, string $field_name = 'children'): array
    {
        $flatArray = array();
        foreach ($element as $key => $node) {
            if (array_key_exists($field_name, $node)) {
                $flatArray = array_merge($flatArray, $this->flatten($node[$field_name]));
                unset($node[$field_name]);
                $flatArray[] = $node;
            } else {
                $flatArray[] = $node;
            }
        }
        return $flatArray;
    }
}
