<?php

namespace App\Services;

use App\Events\AddressEvent;
use App\Helpers\FileHelper;
use App\Interfaces\UserInterface;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

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

        $user = User::create(array_merge($request->all(), ['image' => $image]));

        event(new AddressEvent($user));

        return $user;
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
        event(new AddressEvent($user));
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
