<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\File;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DownloadFileController extends Controller
{
    public function __invoke(File $file): StreamedResponse
    {
        if (! filled($file->path) || ! $file->existsOnDisk()) {
            abort(404);
        }

        return $file->storage()->download($file->path, $file->fileName() ?? basename($file->path));
    }
}
