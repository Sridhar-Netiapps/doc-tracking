<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;

class InsuranceHomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('insurance.dashboard');
    }

    /**
     * Show the form for creating a new resource.
     */

    public function list()
    {
        return view('insurance.index');
    }

    public function claim_forms(){
        $path = public_path('insurance_images/hdfc.png');
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

        $pdf = PDF::loadView('insurance.templates.checklist', ['base64' => $base64])->setPaper('A4', 'portrait')
                ->setOptions([
                    'isHtml5ParserEnabled' => true,
                    'isPhpEnabled' => true,
                ]);
        //return $pdf->stream('document.pdf');
        return $pdf->download('checklist.pdf');
       
       // return view('templates.bajaj');
    }
    public function create()
    {
        return view('insurance/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
