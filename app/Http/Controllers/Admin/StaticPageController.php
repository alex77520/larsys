<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StaticPageController extends Controller
{
    public function index()
    {
        $page = view('admin/log', compact('logs'))->render();

        Storage::disk('public')->put('log/1.html', $page);

        return redirect('/storage/log/1.html');
    }

    public function makeContentPage($id)
    {

    }
}
