<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ResolveReportRequest;
use App\Models\Report;
use App\Models\Violation;
use App\Services\ReportResolutionService;
use Illuminate\Http\Request;

class ReportController extends Controller
{

    protected ReportResolutionService $resolutionService;

    public function __construct(ReportResolutionService $resolutionService)
    {
        $this->resolutionService = $resolutionService;
    }
    public function index(Request $request)
    {
//        dd($request->type);
        $reports = Report::with(['reporter', 'reportedUser', 'reportable', 'evidenceFiles'])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->type, fn($q) => $q->where('type', $request->type))
            ->when($request->search, fn($q) => $q->where('reason', 'like', "%{$request->search}%"))
            ->latest()
            ->paginate(20);

//        $reports->getCollection()->transform(function ($report) {
//            $report->status_label = $report->getStatusLabel();
//            $report->type_label = $report->getTypeLabel();
//            return $report;
//        });
//        dd($reports);
        $stats = [
            'total' => Report::count(),
            'pending' => Report::where('status', Report::STATUS_PENDING)->count(),
            'resolved' => Report::where('status', Report::STATUS_RESOLVED)->count(),
            'rejected' => Report::where('status', Report::STATUS_REJECTED)->count(),
        ];

        return inertia('Admin/Report/Index', [
            'reports' => $reports,
            'filters' => $request->only(['status', 'type', 'search']),
            'stats' => $stats,
            'statusOptions' => Report::getStatusOptions(),
            'violationTypes' => Report::getViolationTypeOptionsArray(),
            'penaltyTypes' => Violation::getPenaltyOptions(),
        ]);
    }

    public function show(Report $report)
    {
        $report->load(['reporter', 'reportedUser', 'admin', 'reportable', 'evidenceFiles', 'violation']);

        $report->reportable_type_label = $report->getReportableTypeLabel();
        $report->reportable_title = $report->getReportableTitle();
        $report->reportable_url = $report->getReportableUrl();

        return inertia('Admin/Report/Show', [
            'report' => $report,
            'violationTypes' => Report::getViolationTypeOptionsArray(),
            'penaltyTypes' => Violation::getPenaltyOptions(),
        ]);
    }

    public function resolve(ResolveReportRequest $request, Report $report)
    {

//        dd($report);
//        try {
//            $this->resolutionService->resolve(
//                $report,
//                $request->action,
//                $request->violation_type,
//                $request->violation_reason,
//                $request->penalty_type,
//                $request->duration_days,
//                $request->moderator_comment
//            );
//
//            return redirect()->route('admin.reports.index')
//                ->with('success', 'Жалоба обработана');
//        } catch (\Exception $e) {
//            return back()->with('error', 'Ошибка: ' . $e->getMessage());
//        }


        try {
            $this->resolutionService->resolve(
                $report,
                $request->action,
                $request->violation_type,
                $request->violation_reason,
                $request->penalty_type,
                $request->duration_days,
                $request->moderator_comment
            );

            return redirect()->route('admin.reports.index')
                ->with('success', 'Жалоба обработана');
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ], 500);
        }
    }
}
