<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    public function index()
    {
        $vendors = Vendor::paginate(5)->withQueryString();
        return view('vendor.index', compact('vendors'));
    }


    public function create()
    {
        return view('vendor.create');
    }

        public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
        ]);

        Vendor::create([
            'name' => $request->name,
            'location' => $request->location,
            'created_by' => auth()->id(), // This line fixes the error
        ]);

        return redirect()->route('vendor.index')->with('success', 'Vendor created successfully.');
    }


    public function edit(Vendor $vendor)
    {
        return view('vendor.edit', compact('vendor'));
    }

    public function update(Request $request, Vendor $vendor)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
        ]);

        $vendor->update($request->all());

        return redirect()->route('vendor.index')->with('success', 'Vendor updated successfully.');
    }
    public function moveToRMA($id)
        {
            $request = RequestModel::findOrFail($id);
            $request->status = 'Moved to RMA';
            $request->save();

            return redirect()->back()->with('success', 'Request moved to RMA successfully.');
        }


    public function destroy(Vendor $vendor)
    {
        $vendor->delete();
        return redirect()->route('vendor.index')->with('success', 'Vendor deleted successfully.');
    }
}


