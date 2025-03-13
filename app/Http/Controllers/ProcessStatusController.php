<?php

namespace App\Http\Controllers;

use App\Models\ProcessStatus;
use Illuminate\Http\Request;

class ProcessStatusController extends Controller 
{
    public function index()
    {
        $statuses = ProcessStatus::all();
        return view('process_status.index', compact('statuses'));
    }

    public function create()
    {
        return view('process_status.create');
    }

    public function store(Request $request)
    {
    $validatedData = $request->validate([
        'name' => 'required|regex:/^[a-zA-Z\s]+$/|max:20', 
        'status' => 'required|in:0,1',
        'created_by' => 'required|exists:process_status,id',
    ],[
            'name.regex' => 'The name must contain only letters and spaces.',
            'name.max' => 'The name must not exceed 20 characters.',
        ]);
      
   

    $validatedData['created_by'] = 1; 
    ProcessStatus::create($validatedData);

    return redirect()->route('process_status.index')->with('success', 'Process status created successfully!');
    }


    public function edit($id)
    {
        $status = ProcessStatus::findOrFail($id);
        return view('process_status.edit', compact('status'));
    }

    public function update(Request $request, $id)
    {
    $validatedData = $request->validate([
        'name' => 'required|regex:/^[a-zA-Z\s]+$/|max:20', 
        'status' => 'required|in:0,1',
    ]);

    $status = ProcessStatus::findOrFail($id);
    $validatedData['updated_by'] = 1; 
    $status->update($validatedData);

    return redirect()->route('process_status.index')->with('success', 'Process status updated successfully!');
    }

    public function destroy($id)
    {
        $status = ProcessStatus::findOrFail($id);
        $status->delete();

        return redirect()->route('process_status.index')->with('success', 'Process status deleted successfully!');
    }

    // public function validate_gst(){
    //   return view('gst');
      
    // }
}
