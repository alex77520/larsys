<?php

namespace App\Repositories;

use Illuminate\Http\Request;

class UploadRepository
{
    public function uploadImg(Request $request)
    {
        $type = $request->input('type');

        return $file = $request->file('uploadImg')->store('images/' . $type, 'public');
    }
}