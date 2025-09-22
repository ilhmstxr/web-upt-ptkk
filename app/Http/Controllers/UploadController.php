<?php
// app/Http/Controllers/UploadController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function store(Request $request)
    {
        $request->validate(['file' => ['required', 'image', 'max:2048']]);
        $path = $request->file('file')->store('pertanyaan/opsi', 'public');

        return response()->json([
            'path' => $path,
            'url'  => Storage::disk('public')->url($path),
        ]);
    }
}
