<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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

    public function quillImageAds(Request $request)
    {
        return $this->storeQuillImage($request, 'advertisements/images');
    }

    private function storeQuillImage(Request $request, string $folder)
    {
        $request->validate([
            'image' => ['required','image','mimes:jpg,jpeg,png,webp','max:2048'],
        ]);

        try {
            $file = $request->file('image');
            if (!$file->isValid()) {
                return response()->json(['ok'=>false,'message'=>'الملف غير صالح أو لم يُرفع بشكل صحيح'], 422);
            }

            $path = $file->store($folder, 'public');
            $relativeUrl = '/storage/' . ltrim($path, '/');

            return response()->json(['ok'=>true,'url'=>$relativeUrl]);
        } catch (\Throwable $e) {
            Log::error('Quill upload error: '.$e->getMessage(), ['folder'=>$folder]);
            return response()->json(['ok'=>false,'message'=>'فشل رفع الصورة'], 500);
        }
    }
}
