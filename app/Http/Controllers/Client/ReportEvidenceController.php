<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\ReportEvidence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ReportEvidenceController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240|mimes:jpg,jpeg,png,gif,pdf,doc,docx',
        ]);

        $file = $request->file('file');
        $extension = $file->getClientOriginalExtension();
        $safeName = Str::uuid() . '.' . $extension;
        $path = $file->storeAs('temp/reports/' . auth()->id(), $safeName, 'public');

        $evidence = ReportEvidence::create([
            'report_id' => null,
            'user_id' => auth()->id(),
            'filename' => $safeName,
            'original_name' => $file->getClientOriginalName(),
            'path' => $path,
            'url' => Storage::url($path),
            'size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
        ]);

        return response()->json([
            'id' => $evidence->id,
            'original_name' => $evidence->original_name,
            'url' => $evidence->url,
            'size' => $evidence->file_size,
            'icon' => $evidence->icon,
            'is_image' => $evidence->isImage(),
        ]);
    }

    public function delete($id)
    {

        dd(333333333333);
//        $evidence = ReportEvidence::findOrFail($id);
//
//        if ($evidence->user_id !== auth()->id() || $evidence->report_id !== null) {
//            abort(403);
//        }
//
//        $evidence->delete();
//
//        return response()->json(['success' => true]);
    }
}
