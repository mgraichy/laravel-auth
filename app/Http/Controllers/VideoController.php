<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\File;
use App\Models\Video;

class VideoController extends Controller
{
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
        $fileName = $request->query('file');
        $dir = dirname(__DIR__, 3).'/videos';
        $file = $dir.'/'.$fileName;

        if (!file_exists($file)) {
            http_response_code(404);
            exit;
        }

        header('Content-Type: video/mp4');
        header('Content-Length: ' . filesize($file));
        header('Content-Disposition: inline; filename="' . basename($file) . '"');
        header('Cache-Control: private, max-age=86400, must-revalidate');
        header('Access-Control-Allow-Origin: http://localhost:5173');
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Allow-Methods: GET, OPTIONS');
        readfile($file);
        exit;
    }
}
