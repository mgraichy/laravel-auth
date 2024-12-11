<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Video;
use Carbon\CarbonImmutable;
class VideoController extends Controller
{
    protected array $whitelist = ['http://localhost:5173'];

    public function getVideoStrings(Request $request, Video $video)
    {
        try {
            $clientId = intval($request->query('client_id'));

            $videoStrings = $video->where('client_id', $clientId)->get();

            $finalVideoStrings = [];
            foreach ($videoStrings as $vid) {
                $finalVideoStrings[] = [
                    'id'        => $vid->id,
                    'title'     => $vid->title,
                    'name'      => $user->name ?? 'M Graichy',
                    'initials'  => $user->name[0] ?? 'M',
                    'comment'   => $vid->comment,
                    'src'       => $vid->video,
                    'views'     => $vid->views,
                    'date'      => $vid->created_at,
                ];
            }

            // We return an object rather than an array to prevent XSS:
            return response()->json(['data' => $finalVideoStrings]);
        } catch (\Throwable) {
            return response()->json(['error' => 'something went wrong'], 422);
        }
    }

    public function getVideos(Request $request)
    {
        $url = request()->headers->get('referer');
        $urlLength = strlen($url) - 1;
        if ($url[$urlLength] == '/') {
            $url = substr($url, 0, -1);
        }

        if (!in_array($url, $this->whitelist)) {
            return response(null, 401);
        }

        $fileName = $request->query('file');
        $dir = dirname(__DIR__, 3).'/videos';
        $file = $dir.'/'.$fileName;

        if (!file_exists($file)) {
            return response(null, 404);
        }

        $fileModificationTime = filemtime($file);
        $lastModified = CarbonImmutable::createFromTimestamp($fileModificationTime)->format('D, d M Y H:i:s') . ' GMT';
        $entityTag = hash_file('sha256', $file);
        $expires = CarbonImmutable::now('UTC')->addDay()->format('D, d M Y H:i:s') . ' GMT';

        if ($request->header('If-Modified-Since') === $lastModified ||
            $request->header('If-None-Match') === $entityTag
        ) {
                return response(null, 304);
        }

        $headers = [
            'Content-Type' => 'video/mp4',
            'Content-Length' => filesize($file),
            'Content-Disposition' => 'inline; filename="' . basename($file) . '"',
            // Caching on the browser (only):
            'Cache-Control' => 'private, max-age=86400, must-revalidate',
            'Last-Modified' => $lastModified,
            'ETag' => '$entityTag',
            // Older browsers:
            'Expires' => $expires,
            // CORS:
            'Access-Control-Allow-Origin' => $url,
            'Access-Control-Allow-Credentials' => true,
            'Access-Control-Allow-Methods' => 'GET, OPTIONS',
        ];

        return response()->file($file, $headers);
    }
}
