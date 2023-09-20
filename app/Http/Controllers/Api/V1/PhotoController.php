<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Photo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

class PhotoController extends Controller
{
    public function store(Request $request)
    {
        $user_id = Auth::id();

        $validator = Validator::make($request->all(), [
            'image' => 'required|image',
        ]);

        if ($validator->fails()) {
            return response(['error' => $validator->errors()], 400);
        }

        $photo          = new Photo;
        $image          = $request->file('image');
        $name           = $image->getClientOriginalName();
        $photo->name    = $name;
        $photo->user_id = $user_id;
        $fileName       = md5($name . time());
        $arr            = explode('.', $name);
        $ext            = end($arr);
        $fileName       = $fileName . "." . $ext;
        $photo->photo_size = $image->getSize();
        $photo->photo_url = $fileName;
        $photo->save();

        //  Resize 400px     
        $mediumPath = public_path('uploads/photos/medium');
        $img        = Image::make($image->path());
        $img->resize(500, 500, function ($constraint) {
            $constraint->aspectRatio();
        })->save($mediumPath . '/' . $fileName);

        // Save origin size
        $largePath = public_path('uploads/photos/large');
        $image->move($largePath, $fileName);

        //$imageDomain = app()->environment(['production']) ? "https://file.thegioiic.com" : 'http://localhost:8000';
        //$imageDomain . '/uploads/photos/medium/' . 
        $data = [
            'id' => $photo->id,
            'photo_url' => $fileName
        ];

        return response(['data' => $data]);
    }

    public function destroy($id)
    {
        $photo = Photo::find($id);

        if (!$photo) {
            return response(['error' => 'Photo not found'], 400);
        }

        if ($photo->delete()) {
            return response(['message' => 'OK']);
        } else {
            return response(['error' => 'Server error'], 400);
        }
    }
}
