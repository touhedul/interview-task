<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\Request;

class TrashedUserController extends Controller
{
    public function __construct(public UserService $userService)
    {
    }


    public function list()
    {
        $trashedUsers = $this->userService->trashedUserList();
        return view('users.trashed_user_list', compact('trashedUsers'));
    }



    public function restore($id)
    {
        $this->userService->trashedUserRestore($id);
        return redirect()->route('trashedUsers.list');
    }



    public function permanentDelete($id)
    {
        $this->userService->trashedUserPermanentDelete($id);
        return redirect()->route('trashedUsers.list');
    }
}
