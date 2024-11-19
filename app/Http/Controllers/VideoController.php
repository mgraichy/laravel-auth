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

    public function getVideos(Request $request, Video $video)
    {
        // $this->fillInDB($faker);
        $user = auth()->user();
        $user_id = $user->id ?? 1;
        $clickedId = intval($request->query('id'));

        $videos = $video->where('user_id', $user_id)->get();

        $finalVideos = [];
        foreach ($videos as $vid) {
            if ($clickedId == $vid->id) continue;
            $finalVideos[] = [
                'id'        => $vid->id,
                'title'     => $vid->title,
                'name'      => $user->name ?? 'M Graichy',
                'initials'  => $user->name[0] ?? 'M',
                'src'       => $vid->video,
                'views'     => $vid->views,
                'date'      => $vid->created_at,
            ];
        }

        return $finalVideos;
    }

    public function getVideo(Request $request, Video $video)
    {
        $clickedId = intval($request->query('id'));
        $user = auth()->user();
        // $user_id = $user->id ?? 1;

        $individualVideo = $video->where('id', $clickedId)->get();
        // $videos = $video->where('user_id', $user_id)->get();

        $finalVideo = [];
        foreach ($individualVideo as $vid) {
            $finalVideo = [
                'id'       => $clickedId,
                'title'    => $vid->title,
                'name'     => $user->name ?? 'M Graichy',
                'initials' => $user->name[0] ?? 'M',
                'src'      => $vid->video,
                'views'    => $vid->views,
                'comment'  => $vid->comment,
                'date'     => $vid->created_at,
            ];
        }

        // return ['video' => $finalVideo, 'comments' => $finalComments];
        return $finalVideo;
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
