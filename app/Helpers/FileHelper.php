<?php

namespace App\Helpers;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class FileHelper
{

    public function uploadImage($request)
    {
        $imageName = NULL;
        if ($request->hasFile('image')) {
            $imageFile = $request->file('image');
            $imageName = time() . uniqid() . '.' . $imageFile->getClientOriginalExtension();
            $manager = new ImageManager(new Driver());
            $image = $manager->read($imageFile);
            $path = storage_path('app/public/images/');
            if (!file_exists($path)) {
                mkdir($path, 666, true);
            }
            $image->resize(90, 90)->save($path . $imageName, 50);
        }
        return $imageName;
    }
}
