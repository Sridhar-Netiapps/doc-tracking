<?php

namespace App\Http\Controllers;

use App\Models\ProcessStatus;
use Illuminate\Http\Request;
use Auth;

class ProcessStatusController extends Controller
{
    // Display all process statuses
    public function index()
    {
        $statuses = ProcessStatus::all();
        return view('process_status.index', compact('statuses'));
    }

    // Display form to create a new process status
    public function create()
    {
        return view('process_status.create');
    }

    // Store a newly created process status in the database
    public function store(Request $request)
    {
        // Validate the incoming data
        $validatedData = $request->validate([
            'name' => 'required|regex:/^[a-zA-Z\s]+$/|max:55',  // Name validation
            'status' => 'required|in:0,1', // Status validation (0 or 1)
        ]);

        // Save the data to the database
        ProcessStatus::create([
            'name' => $validatedData['name'],
            'status' => $validatedData['status'],
            'created_by' => Auth::user()->id,
        ]);

        // Redirect with success message
        return redirect()->route('process_status.index')->with('success', 'Process status created successfully!');
    }

    // Display form to edit an existing process status
    public function edit($id)
    {
        // Find the process status by ID
        $status = ProcessStatus::findOrFail($id);
        return view('process_status.edit', compact('status'));
    }

    // Update an existing process status in the database
    public function update(Request $request, $id)
    {
        // Validate the incoming data
        $validatedData = $request->validate([
            'name' => 'required|regex:/^[a-zA-Z\s]+$/|max:55',  // Name validation
            'status' => 'required|in:0,1', // Status validation (0 or 1)
        ]);

        // Find the process status by ID
        $status = ProcessStatus::findOrFail($id);

        // Update the data
        $status->update([
            'name' => $validatedData['name'],
            'status' => $validatedData['status'],
            'updated_by' => Auth::user()->id,
        ]);

        // Redirect with success message
        return redirect()->route('process_status.index')->with('success', 'Process status updated successfully!');
    }

    // Delete a process status from the database
    public function destroy($id)
    {
        // Find and delete the process status
        $status = ProcessStatus::findOrFail($id);
        $status->delete();

        // Redirect with success message
        return redirect()->route('process_status.index')->with('success', 'Process status deleted successfully!');
    }
}
