<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\File;
use App\Models\Video;

class VideoController extends Controller
{
    public function showVids()
    {
        return view('vids');
    }

    public function getVideos(Request $request, Video $video, $client_id = 1)
    {
        try {
            // $this->fillInDB($faker);
            // $user = auth()->user();
            // $user_id = $user->id ?? 1;
            $clickedId = intval($request->query('id'));

            $videos = $video->where('client_id', $client_id)->get();

            $finalVideos = [];
            foreach ($videos as $vid) {
                if ($clickedId == $vid->id) continue;
                $finalVideos[] = [
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

            // Must return an object rather than an array to prevent XSS:
            return response()->json(['data' => $finalVideos]);
        } catch (\Throwable) {
            return response()->json(['error' => 'something went wrong'], 422);
        }
    }

    // See also config/filesystems.php for 'videos':
    public function store(Request $request, Video $video)
    {
        // dd(auth()->user());
        $input = $request->all();
        Validator::validate($input, [
            'html_name' => [
                'required',
                File::types(['mp4'])
                    ->min(1024)
                    ->max(8 * 1024),
            ],
        ]);

        $path = Storage::disk('videos')->putFile('', $request->file('html_name'));

        return view('vids');
    }

}
