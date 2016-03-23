<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Storage;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

class StorageController extends Controller
{
    public function getStoredFile(Request $request, $path_name)
    {
        $file_name = $request->get('file');
        if(!isset($file_name))
            abort(404);

        try
        {
            $files = Storage::get(
                $path_name . "/" . $file_name
            );
        }
        catch(FileNotFoundException $e)
        {
            abort(404);
        }

        header("Content-type: application/octect-stream");
        header("Accept-Ranges: bytes");
        header("Content-Disposition: attachment; filename=" . $file_name);
        return $files;
    }
}