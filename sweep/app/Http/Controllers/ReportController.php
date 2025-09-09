<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    // User creates report
    public function store(Request $request, $reportedId)
    {
        $request->validate([
            'reason' => 'required|string|min:5',
        ]);

        if ($request->user()->id == $reportedId) {
            return response()->json(['error' => 'You cannot report yourself'], 403);
        }

        $report = Report::create([
            'reporter_id' => $request->user()->id,
            'reported_id' => $reportedId,
            'reason' => $request->reason,
            'description' => $request->description
        ]);

        return response()->json(['success' => true, 'data' => $report]);
    }

    // Admin checks reports
    public function index()
    {
        $reports = Report::with(['reporter:id,name,email','reported:id,name,email'])
            ->latest()->get();

        return response()->json($reports);
    }

    // Admin gives feedback
    public function feedback(Request $request, $id)
    {
        $request->validate([
            'admin_feedback' => 'required|string|min:3',
            'status' => 'required|in:pending,reviewed'
        ]);

        $report = Report::findOrFail($id);
        dd($report);
        $report->update([
            'admin_feedback' => $request->admin_feedback,
            'status' => $request->status,
        ]);

        return response()->json(['success' => true, 'data' => $report]);
    }
}
