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
            'doc_type' => 'nullable|string|in:loan,goldloan,aof,dtrf',
            'excel_file' => 'required|file|max:102400',
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
            $fileName = now()->format('YmdHis') . '_' . Str::random(8) . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('uploads/excel', $fileName, 'public');

            $upload = Upload::create([
                'file_name' => $fileName,
                'file_path' => $filePath,
                'process' => $request->doc_type ? 'data_import' : 'rma_vendor_movement',
                'created_by' => auth()->id(),
                'total_rows' => 0,
                'successful_rows' => 0,
                'failed_rows' => 0,
                'status' => 'processing',
                'remarks' => 'Upload accepted. Import is processing in background.',
            ]);

            try {
                ini_set('memory_limit', '1024M');
                set_time_limit(0);

                if ($request->doc_type) {
                    $import = new ImportData($request->doc_type, (int) auth()->id());
                } else {
                    $import = new VendorDocumentImport((int) auth()->id());
                }

                Excel::import($import, $filePath, 'public');

                $failures = $import->failures();
                $upload->update([
                    'total_rows' => $import->getTotal(),
                    'successful_rows' => $import->getSuccessCount(),
                    'failed_rows' => $failures->count(),
                    'status' => $failures->isNotEmpty() ? 'completed_with_errors' : 'completed',
                    'remarks' => $failures->isNotEmpty()
                        ? 'Upload completed with some failures.'
                        : 'Upload completed successfully.',
                ]);
            } catch (\Throwable $e) {
                $upload->update([
                    'status' => 'failed',
                    'remarks' => $e->getMessage(),
                ]);

                if($request->doc_type){
                    return redirect()->route('accounts.data_import')->with('error', 'Unable to start upload processing. Please try again.');
                }

                return redirect()->route('accounts.index',['type' => 'moved','dtype' => 'loan'])->with('error', 'Unable to start upload processing. Please try again.');
            }

            if($request->doc_type){
                return redirect()->route('accounts.data_import')->with('success', 'Upload completed successfully.');
            }

            return redirect()->route('accounts.index',['type' => 'moved','dtype' => 'loan'])->with('success', 'Upload completed successfully.');
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

        if ($extension === 'csv') {
            return response()->json(['success' => true]);
        }

        if (!in_array($magicBytes, $fileSignatures)) {
            return response()->json(['error' => "File signature mismatch. Potentially malicious file."], 400);
        }

        return response()->json(['success' => true]);
    }
}
