<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class HasManySyncServiceProvider extends ServiceProvider
{
    public function boot()
    {
        \Illuminate\Database\Eloquent\Relations\HasMany::macro('sync', function (array $data, $deleting = true) {

            $changes = ['created' => [], 'deleted' => [], 'updated' => []];

            $relatedKeyName = $this->getRelated()->getKeyName();

            $current = $this->newQuery()->pluck($relatedKeyName)->all();

            $castKey = function ($value) {
                if (is_null($value)) {
                    return $value;
                }
                return is_numeric($value) ? (int) $value : (string) $value;
            };

            $castKeys = function ($keys) use ($castKey) {
                return (array) array_map(function ($key) use ($castKey) {
                    return $castKey($key);
                }, $keys);
            };

            $deletedKeys = array_diff(
                $current,
                $castKeys(
                    \Illuminate\Support\Arr::pluck($data, $relatedKeyName)
                )
            );

            if ($deleting && count($deletedKeys) > 0) {
                $this->getRelated()->destroy($deletedKeys);
                $changes['deleted'] = $deletedKeys;
            }

            $newRows = \Illuminate\Support\Arr::where($data, function ($row) use ($relatedKeyName) {
                return \Illuminate\Support\Arr::get($row, $relatedKeyName) === null;
            });

            $updatedRows = \Illuminate\Support\Arr::where($data, function ($row) use ($relatedKeyName) {
                return \Illuminate\Support\Arr::get($row, $relatedKeyName) !== null;
            });

            if (count($newRows) > 0) {
                $newRecords = $this->createMany($newRows);
                $changes['created'] = $castKeys(
                    $newRecords->pluck($relatedKeyName)->toArray()
                );
            }

            foreach ($updatedRows as $row) {
                $this->getRelated()->where($relatedKeyName, $castKey(\Illuminate\Support\Arr::get($row, $relatedKeyName)))->update($row);
            }

            $changes['updated'] = $castKeys(\Illuminate\Support\Arr::pluck($updatedRows, $relatedKeyName));

            return $changes;
        });
    }
}
