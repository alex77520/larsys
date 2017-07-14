<?php

namespace App\Repositories;

use Illuminate\Http\Request;

class UploadRepository
{

    /**
     * @param Request $request
     * @return false|string
     */
    public function uploadImg(Request $request)
    {
        $type = $request->input('type');

        return $file = $request->file('uploadImg')->store('images/' . $type, 'public');
    }

    /**
     * @param Request $request
     * @return false|string
     */
    public function uploadFile(Request $request)
    {
        $type = $request->input('type');

        return $file = $request->file('uploadFile')->store('files/' . $type, 'public');
    }
}