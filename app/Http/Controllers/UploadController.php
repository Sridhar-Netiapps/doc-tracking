<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Upload;
use App\Imports\VendorDocumentImport;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class UploadController extends Controller
{
    public function index()
    {
        $uploads = Upload::latest()->paginate(10);
        return view('uploads.index', compact('uploads'));
    }

    public function create()
    {
        return view('uploads.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        $file = $request->file('excel_file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('uploads/excel', $fileName, 'public');

        $import = new VendorDocumentImport();
        Excel::import($import, $file);

        $upload = Upload::create([
            'file_name' => $fileName,
            'file_path' => $filePath,
            'created_by' => auth()->user()->id,
            'total_rows' => $import->getTotal(),
            'successful_rows' => $import->getSuccessCount(),
            'failed_rows' => count($import->failures()),
        ]);
        // dd($import);
        // Store failures in session for user to view
        session(['upload_failures' => $import->failures()]);

        return redirect()->route('accounts.index','moved')->with('success', 'Upload completed Successfully.');
    }

    public function download(Upload $upload)
    {
        return Storage::disk('public')->download($upload->file_path);
    }
}