<?php

namespace App\Http\Controllers;

use App\Upload;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request,User $user)
    {
        if ($request->hasFile('image')) {
            if ($request->file('image')->isValid()) {

                $validated = $request->validate([
                    'image' => 'mimes:jpeg,png|max:1014',
                ]);

                $name = uniqid('file'.$user->id);
                $extension = $request->image->extension();
                $request->image
                    ->storeAs('/public/images', $name.".".$extension);
                $url = Storage::url('images/'.$name.".".$extension);
                $user->upload()->delete();
                $upload = Upload::create([
                    'user_id' => $user->id,
                    'file' => $url
                ]);
                return response()->json(compact('upload'));
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return void
     */
    public function destroy(User $user)
    {
        $user->upload()->delete();
    }
}
