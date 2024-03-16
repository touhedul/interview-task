<?php

namespace App\Services;

use App\Helpers\FileHelper;
use App\Interfaces\UserInterface;
use App\Models\User;
use Image;
use Illuminate\Support\Facades\Hash;

class UserService implements UserInterface
{


    public function list()
    {
        return User::where('id', '!=', auth()->id())->latest()->get();
    }


    public function getById($id)
    {
        return User::findOrFail($id);
    }


    public function store($request)
    {
        $image = (new FileHelper)->uploadImage($request);
        return User::create(array_merge($request->validated(), ['image' => $image]));
    }


    public function update($request,$id)
    {
        $user = $this->getById($id);
        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->hasFile('image')) {
            $user->image = (new FileHelper)->uploadImage($request);
        }
        if($request->password != NULL){
            $user->password = Hash::make($request->password);
        }
        $user->save();
        return $user;
    }


    public function delete($id)
    {
        $user = $this->getById($id);
        return $user->delete();
    }


    public function trashedUserList()
    {
        return User::onlyTrashed()->latest()->get();
    }


    public function trashedUserRestore($id)
    {
        $user = User::withTrashed()->where('id',$id)->firstOrFail();
        return $user->restore();
    }


    public function trashedUserPermanentDelete($id)
    {
        $user = User::withTrashed()->where('id',$id)->firstOrFail();
        return $user->forceDelete();
    }


}
