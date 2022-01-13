<?php

namespace App\Traits;

use Intervention\Image\ImageManagerStatic;

trait FileManager
{
    private $_imageSizes = [];
    private $_imageExtensions = ['jpg', 'jpeg', 'gif', 'png', 'bmp', 'svg', 'svgz', 'cgm', 'djv', 'djvu', 'ico', 'ief', 'jpe', 'pbm', 'pgm', 'pnm', 'ppm', 'ras', 'rgb', 'tif', 'tiff', 'wbmp', 'xbm', 'xpm', 'xwd'];

    protected function setImageSizes($imageSizes)
    {
        $this->_imageSizes = $imageSizes;
    }

    protected function addFile($request_file, $directory_path)
    {
        if (!request()->hasFile($request_file)) {
            return [];
        }

        $files = request()->file($request_file);

        $holder = [];
        foreach ($files as $keyFile => $file) {
            $size = $file->getSize();
            $type = $file->getType();
            $completeFileName = $file->getClientOriginalName();
            $fileNameOnly = pathinfo($completeFileName, PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $name_alternative = str_replace(' ', '_', $fileNameOnly) . '_' . rand() . '_' . time();

            $folder_name = 'original';
            $file_name = $name_alternative . '_' . $folder_name . '.' . $extension;
            $folder_path = preg_replace('/([^:])(\/{2,})/', '$1/', $directory_path . '/' . $folder_name);
            $file_path = $folder_path . '/' . $folder_name;
            $public_folder_path = public_path($folder_path);

            $file->move(public_path($folder_path), $file_name);

            if (in_array($extension, $this->_imageExtensions)) {
                /*
                $file_image = ImageManagerStatic::make($file_path);
                $file_image->backup();

                foreach ($this->_imageSizes as $keyFileSize => $size) {
                    $folder_name = $size[0] . '_x_' . $size[1];
                    $file_name = $name_alternative . '_' . $folder_name . '.' . $extension;
                    $folder_path = preg_replace('/([^:])(\/{2,})/', '$1/', $directory_path . '/' . $folder_name);
                    $file_path = $folder_path . '/' . $file_name;
                    $public_folder_path = public_path($folder_path);
                    if (!is_dir($public_folder_path)) {
                        mkdir($public_folder_path, 0755);
                    }
                    $file_image->fit($size[0], $size[1]);
                    $file_image->save($file_path, 50);
                    $file_image->reset();
                }
                */
            }

            $holder = array_merge($holder, [['name' => $name_alternative, 'title' => $completeFileName, 'description' => '', 'url' => asset($directory_path), 'extension' => $extension, 'properties' => ['size' => $size, 'type' => $type]]]);
        }

        return $holder;
    }

    protected function removeFile($model_files, $directory_path)
    {
        foreach ($model_files as $keyFile => $file) {

            if (in_array($file->extension, $this->_imageExtensions)) {
                /*
                foreach ($this->_imageSizes as $keyFileSize => $size) {
                    $folder_name = $size[0] . '_x_' . $size[1];
                    $file_name = $file->name . '_' . $folder_name . '.' . $file->extension;
                    $folder_path = preg_replace('/([^:])(\/{2,})/', '$1/', $directory_path . '/' . $folder_name);
                    $file_path = $folder_path . '/' . $file_name;
                    $public_file_path = public_path($file_path);
                    if (file_exists($public_file_path)) {
                        unlink($public_file_path);
                    }
                }
                */
            }

            $folder_name = 'original';
            $file_name = $file->name . '_' . $folder_name . '.' . $file->extension;
            $folder_path = preg_replace('/([^:])(\/{2,})/', '$1/', $directory_path . '/' . $folder_name);
            $file_path = $folder_path . '/' . $file_name;
            $public_file_path = public_path($file_path);
            if (file_exists($public_file_path)) {
                unlink($public_file_path);
            }
        }
    }
}
