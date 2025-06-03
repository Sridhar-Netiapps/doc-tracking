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
         $pdf = PDF::loadView('templates.birlagroup')->setPaper('A4', 'portrait')
                ->setOptions([
                    'isHtml5ParserEnabled' => true,
                    'isPhpEnabled' => true,
                ]);
          return $pdf->stream('document.pdf');
       // return $pdf->download('maxlife.pdf');
       
        return view('templates.maxlife');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
