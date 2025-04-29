<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AccountOpeningDocument;
use App\Models\DtrfDocument;
use App\Models\GoldLoanDocument;
use App\Models\LoanDocument;
use Carbon\Carbon;

class DocumentController extends Controller
{
    public function index(Request $request, $type)
    {
        $start_date = Carbon::now()->subWeek()->startOfWeek(); 
        $end_date = Carbon::now()->subWeek()->endOfWeek();

        if ($type == 'new') {
            $loan_document = LoanDocument::whereBetween('account_creation_date', [$start_date, $end_date])->paginate(100);
            $gold_loan_document = GoldLoanDocument::whereBetween('account_creation_date', [$start_date, $end_date])->paginate(100);
            $dtrf_document = DtrfDocument::whereBetween('dtr_file_date', [$start_date, $end_date])->paginate(100);
            $account_opening_document = AccountOpeningDocument::whereBetween('account_creation_date', [$start_date, $end_date])->paginate(100);

            // Set totals as 0 when type is 'new' (optional, or you can calculate if needed)
            $loan_total = $loan_document->total();
            $gold_loan_total = $gold_loan_document->total();
            $dtrf_total = $dtrf_document->total();
            $aof_total = $account_opening_document->total();
        } else {
            $loan_document = LoanDocument::paginate(100);
            $gold_loan_document = GoldLoanDocument::paginate(100);
            $dtrf_document = DtrfDocument::paginate(100);
            $account_opening_document = AccountOpeningDocument::paginate(100);

            $loan_total = $loan_document->total();
            $gold_loan_total = $gold_loan_document->total();
            $dtrf_total = $dtrf_document->total();
            $aof_total = $account_opening_document->total();
        }

        return view('accounts.accounts', [
            'loan_document' => $loan_document,
            'gold_loan_document' => $gold_loan_document,
            'dtrf_document' => $dtrf_document,
            'account_opening_document' => $account_opening_document,
            'type' => $type,
            'loan_total' => $loan_total,
            'gold_loan_total' => $gold_loan_total,
            'dtrf_total' => $dtrf_total,
            'aof_total' => $aof_total,
        ]);
    }
    public function bulkReview(Request $request)
    {
        // dd($request->all());
        // $loan_document = $gold_loan_document = $dtrf_document = $account_opening_document = NULL;
        
        // if(isset($request->loan_ids))
        //     $loan_document = LoanDocument::whereIn('id',$request->loan_ids)->get();
        // if(isset($request->goldloan_ids))
        //     $gold_loan_document = GoldLoanDocument::whereIn('id',$request->goldloan_ids)->get();
        // if(isset($request->dtrf_ids))
        //     $dtrf_document = DtrfDocument::whereIn('id',$request->dtrf_ids)->get();
        // if(isset($request->aof_ids))
        //     $account_opening_document = AccountOpeningDocument::whereIn('id',$request->aof_ids)->get();
        // $accountIds = $request->input('account_ids', []);
        
        // if (empty($accountIds)) {
        //     return redirect()->back()->with('error', 'Please select at least one account.');
        // }
        // dd($loan_document);
        $type = 'new';
        // $accounts = LoanDocument::whereIn('id', $accountIds)->get();

        $allDocuments = collect(); 
        if (isset($request->loan_ids)) {
            $loans = LoanDocument::whereIn('id', $request->loan_ids)->get()
                        ->map(function ($item) {
                            $item->doc_type = 'loan';
                            return $item;
                        });
            $allDocuments = $allDocuments->merge($loans);
        }
        if (isset($request->goldloan_ids)) {
            $goldloans = GoldLoanDocument::whereIn('id', $request->goldloan_ids)->get()
                        ->map(function ($item) {
                            $item->doc_type = 'goldloan';
                            return $item;
                        });
            $allDocuments = $allDocuments->merge($goldloans);
        }
        if (isset($request->dtrf_ids)) {
            $dtrfs = DtrfDocument::whereIn('id', $request->dtrf_ids)->get()
                        ->map(function ($item) {
                            $item->doc_type = 'dtrf';
                            return $item;
                        });
            $allDocuments = $allDocuments->merge($dtrfs);
        }
        if (isset($request->aof_ids)) {
            $aofs = AccountOpeningDocument::whereIn('id', $request->aof_ids)->get()
                        ->map(function ($item) {
                            $item->doc_type = 'aof';
                            return $item;
                        });
            $allDocuments = $allDocuments->merge($aofs);
        }
        // dd($allDocuments->toArray());
        // return view('accounts.bulkReview', compact('gold_loan_document','type','dtrf_document','account_opening_document','loan_document'));
        return view('accounts.index', compact('allDocuments','type'));

        // if (isset($request->loan_ids)) {
        //     $loans = LoanDocument::whereIn('id', $request->loan_ids)->get()
        //         ->map(function ($item) {
        //             $item->doc_type = 'loan';
        //             return $item;
        //         });
        //     $allDocuments = $allDocuments->merge($loans);
        // }

    }
    public function addCourierDetails(Request $request)
    {
        dd($request->all());
        $validated = $request->validate([
            'courier_name' => 'required|string',
            'awb_pod' => 'required|string',
            'dispatch_date' => 'required|date',
            'loan_ids'=> 'required|array',
            'goldloan_ids'=> 'required|array',
            'dtrf_ids'=> 'required|array',
            'aof_ids'=> 'required|array'
        ]);

        $dispatch = new CourierDispatch;
        $dispatch->courier_name = $validated['courier_name'];
        $dispatch->awb_pod = $validated['awb_pod'];
        $dispatch->dispatch_date = $validated['dispatch_date'];
        $dispatch->loan_ids= $validated['loan_ids'];
        $dispatch->goldloan_ids= $validated['goldloan_ids'];
        $dispatch->dtrf_ids= $validated['dtrf_ids'];
        $dispatch->aof_ids= $validated['aof_ids'];

        return view('accounts.index', compact('allDocuments','type'));
    }

}

