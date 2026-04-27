<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class FileService
{
    const isUploadFullSize = true;
    const PREFIX_FULL_SIZE = 'lg';
    const PREFIX_MEDIUM_SIZE = 'md';
    const PREFIX_SMALL_SIZE = 'xs';

    const DEFAULT_TYPE_DISK = 'public';

    public $image;

    public function __construct()
    {
        $this->image = new ImageManager(new Driver());
    }

    public function upload(UploadedFile $uploadFile, $directory = '')
    {
        try {

            $ext = $uploadFile->getClientOriginalExtension();
            $size = $uploadFile->getSize();

            // if ($ext === 'gif') {
            //     $imgSource = $uploadFile;
            // } else {
            //     $imgSource = Image::make($uploadFile->getRealPath());
            // }
            $imgSource = $uploadFile;
            $this->createDirectory($directory);
            $randomStr = generateRandomString(10);

            //origin size image
            $this->uploadBySize($imgSource, self::PREFIX_FULL_SIZE, $randomStr, $ext, $directory);
            $this->uploadBySize($imgSource, self::PREFIX_MEDIUM_SIZE, $randomStr, $ext, $directory);
            $this->uploadBySize($imgSource, self::PREFIX_SMALL_SIZE, $randomStr, $ext, $directory);

            return ['name' => $randomStr, 'size' => $size, 'ext' => $ext];
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function deleteFile($path)
    {
        $typeDisk = self::DEFAULT_TYPE_DISK;

        $path = config('master.path_storage') . $path;

        if (Storage::disk($typeDisk)
            ->delete($path)
        ) {
            return true;
        }

        return false;
    }

    public function deleteDirectory($path)
    {
        $typeDisk = self::DEFAULT_TYPE_DISK;

        $path = config('master.path_storage') . $path;

        if (Storage::disk($typeDisk)
            ->deleteDirectory($path)
        ) {
            return true;
        }

        return false;
    }

    public function createDirectory($path)
    {
        $typeDisk = self::DEFAULT_TYPE_DISK;

        $path = config('master.path_storage') . $path;

        if (!Storage::disk($typeDisk)
            ->exists($path)) {
            Storage::disk($typeDisk)->makeDirectory($path);
        }

        return false;
    }

    private function uploadBySize($file, $size, $name, $ext, $directory)
    {

        $storagePath = config('filesystems.disks.' . self::DEFAULT_TYPE_DISK . '.root');

        if (substr($storagePath, strlen($storagePath) - 1, strlen($storagePath)) != '/') {
            $storagePath = $storagePath . '/';
        }

        if (substr($directory, strlen($directory) - 1, strlen($directory)) != '/') {
            $directory = $directory . '/';
        }

        if (!str_contains($ext, '.')) {
            $ext = '.' . $ext;
        }

        $full_file_name_random = $size . '_' . $name . $ext;
        $path_upload = $directory . $full_file_name_random;
        $full_path_upload = $storagePath . $path_upload;


        if ($size == self::PREFIX_MEDIUM_SIZE || $size == self::PREFIX_SMALL_SIZE) {
            $width = config('master.array_resize')[$size]->width;
            $height = config('master.array_resize')[$size]->height;

            $image = $this->image->read($file->getRealPath());

            $sourceFile = $image->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $sourceFile->save($full_path_upload);
        } else {
            $file->storeAs(config('master.path_storage') . $directory, $full_file_name_random, ['disk' => self::DEFAULT_TYPE_DISK]);
        }
    }

}
