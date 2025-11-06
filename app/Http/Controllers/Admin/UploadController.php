<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function quillImage(Request $request)
    {
        $request->validate([
            'image' => ['required','image','mimes:jpg,jpeg,png,webp','max:2048'], // 2MB
        ]);

        $path = $request->file('image')->store('news/images', 'public');

        return response()->json([
            'ok'  => true,
            'url' => asset('storage/'.$path),
        ]);
    }
}
