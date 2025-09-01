<?php

namespace App\Http\Controllers;

use App\Models\Email;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EmailController extends Controller
{
    // app/Http/Controllers/EmailController.php
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = auth()->user();
            return $next($request);

        });
    }
    public function index()
    {
        // Fetch emails from the database
        $emails = Email::orderByDesc('created_at')->get();
        return view('emails.index', compact('emails')); // Pass emails to the view
    }


    public function create()
    {
        return view('emails.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sender' => 'required|email',
            'to' => 'required|email',
            'cc' => 'nullable|string',
            'bcc' => 'nullable|string',
            'subject' => 'required|string',
            'message' => 'required|string',
        ]);
        $validated['created_by'] = $this->user->id;
        // dd($validated);
        $email = Email::create($validated);

        // Simulate sending
        $email->update([
            'status' => 'sent',
            'sent_at' => Carbon::now()
        ]);

        return redirect()->route('emails.index')->with('success', 'Email sent!');
    }

    public function edit(Email $email)
    {
        return view('emails.edit', compact('email'));
    }

    public function update(Request $request, Email $email)
    {
        $validated = $request->validate([
            'sender' => 'required|email',
            'to' => 'required|email',
            'cc' => 'nullable|string',
            'bcc' => 'nullable|string',
            'subject' => 'required|string',
            'message' => 'required|string',
            'status' => 'required|in:draft,sent,failed'
        ]);

        $email->update($validated);

        return redirect()->route('emails.index')->with('success', 'Email updated!');
    }

    public function destroy(Email $email)
    {
        $email->delete();
        return redirect()->route('emails.index')->with('success', 'Email deleted!');
    }
}
