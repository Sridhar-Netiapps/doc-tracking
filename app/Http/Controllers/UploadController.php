<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Upload;
use App\Imports\VendorDocumentImport;
use App\Imports\ImportData;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

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
        $validator = Validator::make($request->all(), [
            'doc_type' => 'nullable|string',
            'excel_file' => 'required|file',
        ]);
        if ($validator->fails()) {
            if($request->doc_type){
                return redirect()->route('accounts.data_import')->with('error', $validator->errors()->first());
            } else {
                return redirect()->route('accounts.index',['type' => 'moved','dtype' => 'loan'])->with('error', $validator->errors()->first());
            }
        }
        $data = $this->CheckFile($request->file('excel_file'));
        if($data->getData()->success){
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
        else{
            if($request->doc_type){
                return redirect()->route('accounts.data_import')->with('error', $data->getData()->error);
            } else {
                return redirect()->route('accounts.index',['type' => 'moved','dtype' => 'loan'])->with('error', $data->getData()->error);
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
        $validator = Validator::make($request->all(), [
            'file' => [
                'required',
                'file',
                'max:2048'
            ]
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        return $this->CheckFile($request->file('file'));
        
    }
    public function CheckFile($file)
    {
        $allowedMimes = [
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'text/csv',
            'text/plain'
        ];

        $fileSignatures = [
            'd0cf11e0',
            '504b0304',
            '446f6375'
        ];

        // $file = $request->file('file');

        $extension = strtolower($file->getClientOriginalExtension());
        $mimeType  = $file->getMimeType();
        $allowedExt = ['xls','xlsx','csv'];

        if (!in_array($extension, $allowedExt)) {
            return response()->json(['error' => "Extension .$extension is not allowed"], 400);
        }

        if (!in_array($mimeType, $allowedMimes)) {
            return response()->json(['error' => "Invalid MIME type for .$extension"], 400);
        }

        $handle = fopen($file->getRealPath(), 'rb');
        $bytes  = fread($handle, 4);
        fclose($handle);

        $magicBytes = bin2hex($bytes);

        if (!in_array($magicBytes, $fileSignatures)) {
            return response()->json(['error' => "File signature mismatch. Potentially malicious file."], 400);
        }

        return response()->json(['success' => true]);
    }
}