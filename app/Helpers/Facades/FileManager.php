<?php

namespace App\Helpers\Facades;

use App\Services\FileSystem\Contracts\FileSystemable;
use Illuminate\Support\Facades\Facade;

/**
 * @method static bool canUpload($spaceRequest)
 * @method static \App\Services\FileSystem\Storage path($pathName)
 * @method static \App\Services\FileSystem\Storage ofModel(FileSystemable $model)
 * @method static delete($files)
 * @method static changeTypeable($idable, $typeable)
 *
 * @see \App\Services\FileSystem\Storage
 *
 */
class FileManager extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'fileManager';
    }
}
