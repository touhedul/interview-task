<?php

namespace App\Interfaces;

interface UserInterface
{
    public function list();

    public function getById($id);

    public function store($request);

    public function update($request,$id);

    public function delete($id);

    public function trashedUserList();

    public function trashedUserRestore($id);

    public function trashedUserPermanentDelete($id);
}
