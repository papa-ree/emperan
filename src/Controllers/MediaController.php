<?php

namespace Bale\Emperan\Controllers;

use Illuminate\Support\Facades\Storage;

class MediaController
{
    public function show($path)
    {
        $path = urldecode($path);

        $disk = Storage::disk('minio');

        if (!$disk->exists($path)) {
            abort(404);
        }

        $stream = $disk->readStream($path);
        $mime = $disk->mimeType($path);

        return response()->stream(function () use ($stream) {
            ob_clean(); // FIX
            fpassthru($stream);
            fclose($stream);
        }, 200, [
            'Content-Type' => $mime,
            'Cache-Control' => 'max-age=3600, public',
        ]);
    }
}
