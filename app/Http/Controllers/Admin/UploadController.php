<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UploadController extends Controller
{
    public function uploadIcon(Request $request)
    {
        if ($request->ajax()) {

            $file = $request->file('uploadIcon')->store('images/icons', 'public');

            return response()->json(['msg' => '/storage/' . $file]);

        }

        return response()->json(['msg' => '上传失败']);
    }
}
