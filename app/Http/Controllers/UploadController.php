<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Upload;
use App\Imports\VendorDocumentImport;
use App\Imports\ImportData;
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
            'doc_type' => 'nullable|string',
            'excel_file' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        $file = $request->file('excel_file');
        $collection = \Maatwebsite\Excel\Facades\Excel::toCollection(null, $file);
        $rows = $collection->first();

        if ($rows->count() <= 1) {
            return back()->with('error', 'The uploaded file has no data rows beyond the header.');
        }
        
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('uploads/excel', $fileName, 'public');
        if ($request->doc_type) {
            $import = new ImportData($request->doc_type);
        }else {
            $import = new VendorDocumentImport();
        }
       
        Excel::import($import, $file);

        $upload = Upload::create([
            'file_name' => $fileName,
            'file_path' => $filePath,
            'created_by' => auth()->user()->id,
            'total_rows' => $import->getTotal(),
            'successful_rows' => $import->getSuccessCount(),
            'failed_rows' => count($import->failures()),
        ]);
        // dd($import->failures());
        if($import->failures()->isNotEmpty()){
            session()->flash('upload_failures', $import->failures());
            if($request->doc_type){
                return redirect()->route('accounts.data_import')->with('error', 'Upload Unsuccessfull.');
            } else {
                return redirect()->route('accounts.index',['type' => 'moved','dtype' => 'loan'])->with('error', 'Upload Unsuccessfull.');
            }
        }
        else{
            if($request->doc_type){
                return redirect()->route('accounts.data_import')->with('success', 'Upload completed Successfully.');
            } else {
                return redirect()->route('accounts.index',['type' => 'moved','dtype' => 'loan'])->with('success', 'Upload completed Successfully.');
            }
        }

    }
    
    public function download(Upload $upload)
    {
        return Storage::disk('public')->download($upload->file_path);
    }

    public function vendorSample()
    {
        $filePath = storage_path('app/template/vendor_documents_sample.csv');
        if (!file_exists($filePath)) {
            abort(404);
        }
        return response()->download($filePath, 'vendor_documents_sample.csv');
    }

    public function uploadFile(Request $request)
    {
        // Allowed MIME types & extensions (whitelist)
        $allowedMimes = [
            'image/jpeg',
            'image/png',
            'application/pdf',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document', // docx
            'application/msword',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'text/csv'
        ];

        $validator = Validator::make($request->all(), [
            'file' => [
                'required',
                'file',
                'max:2048', // 2MB limit
                function ($attribute, $value, $fail) use ($allowedMimes) {
                    if (!in_array($value->getMimeType(), $allowedMimes)) {
                        $fail('Invalid file type.');
                    }
                }
            ]
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $file = $request->file('file');

        // Double check extension (prevents image.jpg.php trick)
        $ext = strtolower($file->getClientOriginalExtension());
        $allowedExt = ['jpg','jpeg','png','pdf','doc','docx','xls','xlsx','csv'];

        if (!in_array($ext, $allowedExt)) {
            return response()->json(['error' => 'Invalid file extension'], 422);
        }

        // Generate secure filename
        $newFileName = Str::uuid()->toString() . '.' . $ext;

        // Store securely (never public path directly)
        $path = $file->storeAs('uploads', $newFileName, 'private');

        return response()->json(['success' => true, 'path' => $path]);
    }
}