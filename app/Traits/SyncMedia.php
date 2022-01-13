<?php

namespace App\Traits;

trait SyncMedia
{
    public function syncMediaOne(array $item, string $field, $collection = null, string $file = 'file', string $name = 'name')
    {
        if (is_null($collection)) {
            $collection = $field;
        }
        if (array_key_exists($field, $item) && !!$item = $item[$field]) {
            if (array_key_exists($file, $item) && $item[$file] instanceof \Illuminate\Http\UploadedFile) {
                $this->clearMediaCollection($collection);
                $new_medium = ($this->addMedia($item[$file])->toMediaCollection($collection));
                if (array_key_exists($name, $item)) {
                    $new_medium->$name = $item[$name];
                    $new_medium->file_name = preg_replace(" /[&'#%]/", "", $new_medium->file_name);
                    $new_medium->save();
                }
                return $new_medium;
            }
            return $this->updateMedia([$item], $collection);
        }
        return $this->updateMedia([], $collection);
        return null;
    }

    public function syncMediaMany(array $items, string $field, $collection = null, string $file = 'file', string $name = 'name')
    {
        if (is_null($collection)) {
            $collection = $field;
        }
        if (array_key_exists($field, $items)) {
            $media = collect($items[$field])->map(function ($item) use ($collection, $file, $name) {
                if (array_key_exists($file, $item) && $item[$file] instanceof \Illuminate\Http\UploadedFile) {
                    $new_medium = ($this->addMedia($item[$file])->toMediaCollection($collection));
                    if (array_key_exists($name, $item)) {
                        $new_medium->name = $item[$name];
                        $new_medium->file_name = preg_replace(" /[&'#%]/", "", $new_medium->file_name);
                    }
                    return $new_medium;
                }
                return $item;
            })->toArray();
        } else {
            $media = [];
        }
        return $this->updateMedia($media, $collection);
    }

    /*
    public function syncMediaMany(array $items_file, array $items_data = [], $collection = 'default', string $file = 'file', string $name = 'name')
    {
        $keys = [];

        $media = [];
        if (!!$items_file) {
            $keys = array_keys($items_file);
            foreach ($items_file as $key => $item) {
                $new_medium = ($this->addMedia($item[$file])->toMediaCollection($collection))->toArray();
                if (array_key_exists($key, $items_data) && array_key_exists($name, $items_data[$key])) {
                    $new_medium[$name] = $items_data[$key][$name];
                }
                array_push($media, $new_medium);
            }
        }

        $i = 0;
        $media_data = array_reduce($items_data, function ($rows, $row) use (&$i, &$keys) {
            if (!array_key_exists($i, $keys)) {
                array_push($rows, $row);
            }
            $i++;
            return $rows;
        }, []);

        return $this->updateMedia(array_merge($media, $media_data), $collection);
    }
    */

    /*
    public function syncMedia(array $files, array $new_media = [], $collection = 'default')
    {
        $media = $this->mapWithUploadedMedia($files, $collection);

        return $this->updateMedia($media, $collection);
    }

    protected function mapWithUploadedMedia(array $media, $collection = 'default')
    {
        return collect($media)->map(function ($medium) use ($collection) {
            if (!$medium instanceof \Illuminate\Http\UploadedFile) {
                return $medium;
            }

            return $this->addMedia($medium)->toMediaCollection($collection);
        })->toArray();
    }
    */
}
