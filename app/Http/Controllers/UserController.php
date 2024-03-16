<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{


    public function __construct(public UserService $userService)
    {
    }


    public function index()
    {
        $users = $this->userService->list();
        return view('users.list',compact('users'));
    }


    public function create()
    {
        return view('users.create');
    }


    public function store(UserCreateRequest $request)
    {
        $this->userService->store($request);
        return redirect()->route('users.index');
    }


    public function show(string $id)
    {
        $user = $this->userService->getById($id);
        return view('users.show',compact('user'));
    }


    public function edit(string $id)
    {
        $user = $this->userService->getById($id);
        return view('users.edit',compact('user'));
    }


    public function update(UserUpdateRequest $request, string $id)
    {

        $this->userService->update($request,$id);
        return redirect()->route('users.index');
    }


    public function destroy(string $id)
    {

        $this->userService->delete($id);
        return redirect()->route('users.index');
    }


}
