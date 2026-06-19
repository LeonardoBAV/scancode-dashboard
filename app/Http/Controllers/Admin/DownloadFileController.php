<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\File;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DownloadFileController extends Controller
{
    public function __invoke(File $file): StreamedResponse
    {
        if (! filled($file->path) || ! Storage::disk('public')->exists($file->path)) {
            abort(404);
        }

        return Storage::disk('public')->download($file->path, basename($file->path));
    }
}
