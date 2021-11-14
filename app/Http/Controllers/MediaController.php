<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use JD\Cloudder\Facades\Cloudder;
use App\Models\User;

class MediaController extends Controller
{
    public function media(Request $request)
    {
        $this->validate($request, [
            'avatar' => 'image|mimes:jpeg,png,jpg|max:1048|required',
        ]);


        $image_name = $request->file('avatar')->getRealPath();




//the upload method handles the uploading of the file and can accept attributes to define what should happen to the image

//Also note you could set a default height for all the images and Cloudinary does a good job of handling and rendering the image.
            Cloudder::upload($image_name, null, array(
                "folder" => "timezone",  "overwrite" => FALSE,
                "resource_type" => "image", "responsive" => TRUE, "transformation" => array("quality" => "70", "width" => "250", "height" => "250", "crop" => "scale")
            ));

//Cloudinary returns the publicId of the media uploaded which we'll store in our database for ease of access when displaying it.

            $public_id = Cloudder::getPublicId();

            $width = 250;
            $height = 250;

//The show method returns the URL of the media file on Cloudinary
            $image_url = Cloudder::show(Cloudder::getPublicId(), ["width" => $width, "height" => $height, "crop" => "scale", "quality" => 70, "secure" => "true"]);

//In a situation where the user has already uploaded a file we could use the delete method to remove the media and upload a new one.
            if ($public_id != null) {
                $image_public_id_exist = User::select('public_id')->where('id', Auth::user()->id)->get();
                Cloudder::delete($image_public_id_exist);
            }

            $user = User::find(Auth::user()->id);
            $user->public_id = $public_id;
            $user->avatar_url = $image_url;
            $user->update();
            return back()->with('success_msg', 'Media successfully updated!');

    }
}
