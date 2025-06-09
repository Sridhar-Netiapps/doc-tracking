<?php

namespace App\Http\Controllers;

use App\Models\Courier;
use Illuminate\Http\Request;

class CourierController extends Controller
{
    // Display a listing of the couriers
    public function index()
    {
        $couriers = Courier::all();
        return view('couriers.index', compact('couriers'));
    }
    

    // Show the form for creating a new courier
    public function create()
    {
        return view('couriers.create');
    }

    // Store a newly created courier in the database
    public function store(Request $request)
    {
        $request->validate([
            'courier_id' => 'required|unique:couriers',
            'name' => 'required',
            'number' => 'required|regex:/^[0-9]+$/',
            'address' => 'required',
            'status' => 'required|in:Active,Inactive'

        ]);

        Courier::create($request->all());

        return redirect()->route('couriers.index')->with('success', 'Courier added successfully.');
    }

    // Display the specified courier
    public function show($id)
    {
        $courier = Courier::findOrFail($id);
        return view('couriers.show', compact('courier'));
    }
    
    // Show the form for editing the specified courier
    public function edit($id)
    {
        $courier = Courier::findOrFail($id);
        return view('couriers.edit', compact('courier'));
    }

    // Update the specified courier in the database
    public function update(Request $request, $id)
    {
        $courier = Courier::findOrFail($id);
        $courier->update($request->all());

        return redirect()->route('couriers.index')->with('success', 'Courier updated successfully.');
    }

    // Remove the specified courier from the database
    public function destroy($id)
    {
        Courier::destroy($id);
        return redirect()->route('couriers.index')->with('success', 'Courier deleted successfully.');
    }
}
