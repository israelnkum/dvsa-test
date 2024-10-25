<?php

namespace App\Helpers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SaveResource
{
    public function __invoke(Request $request, string $path, $key = 'file'): ?string
    {
        $fileName = null;

        if ($request->has($key) && $request->file !== "null") {
            $saveFile = new SaveFile(null, $request->file($key), $path, []);

            $fileName = $saveFile->saveFile();
        }

        return $fileName;
    }
}
