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
        // Validation rules for storing new process status
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:0,1',
            'created_by' => 'required|exists:process_status,id', // Assumes 'users' table exists
        ]);

        // Create a new ProcessStatus
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
            'name' => 'required|string|max:255',
            'status' => 'required|in:0,1',
            'updated_by' => 'required|exists:process_status,id',
        ]);

        $status = ProcessStatus::findOrFail($id);
        $status->update($validatedData);

        return redirect()->route('process_status.index')->with('success', 'Process status updated successfully!');
    }

    public function destroy($id)
    {
        $status = ProcessStatus::findOrFail($id);
        $status->delete();

        return redirect()->route('process_status.index')->with('success', 'Process status deleted successfully!');
    }
}
