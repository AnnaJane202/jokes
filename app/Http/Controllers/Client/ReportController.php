<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\ReportRequest;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Report;
use App\Models\User;
use App\Services\ReportService;
use Illuminate\Http\Request;

class ReportController extends Controller
{

    protected ReportService $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function index()
    {
        $reports = auth()->user()
            ->reports()
            ->with(['reportedUser', 'admin', 'evidenceFiles'])
            ->latest()
            ->paginate(20);

        $reports->getCollection()->transform(function ($report) {
            $report->type_label = $report->getTypeLabel();
            $report->status_label = $report->getStatusLabel();
            return $report;
        });

        return inertia('Client/Report/Index', [
            'reports' => $reports,
        ]);
    }

    public function show(Report $report)
    {
        if ($report->reporter_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $report->load([
            'reporter',
            'reportedUser',
            'admin',
            'violation',
            'evidenceFiles',
            'reportable'
        ]);

        // Форматируем данные для удобства во Vue
        $report->evidence_files = $report->evidenceFiles->map(function ($file) {
            return [
                'id' => $file->id,
                'original_name' => $file->original_name,
                'url' => $file->url,
                'size_formatted' => $file->file_size,
                'icon' => $file->icon,
            ];
        });

        $report->reportable_type_label = $report->getReportableTypeLabel();
        $report->reportable_title = $report->getReportableTitle();
        $report->reportable_url = $report->getReportableUrl();



        return inertia('Client/Report/Show', [
            'report' => $report,
        ]);
    }


    public function create(Request $request, string $type, int $id)
    {
        $reportable = $this->getReportable($type, $id);
//        dd('before');

//        $array = Report::getViolationTypes();
//        dd($array);
        if(!$reportable) {
            abort(404);
        }
//        dd('after');


        return inertia('Client/Report/Create', [
            'reportable' => [
                'type' => $type,
                'id' => $id,
                'title' => $reportable->title ??  $reportable->name ?? 'Контент',
                'author' => $reportable->user?->name ?? 'Неизвестный пользователь',
            ],
            'reportTypes' => Report::getViolationTypeOptionsArray(),
        ]);
    }

    public function store(ReportRequest $request)
    {
        try {
            $report = $this->reportService->createReport(
                $request->only(['reportable_type', 'reportable_id', 'type', 'reason']),
                $request->input('evidence_ids', [])
            );

            return redirect()->route('post.index')
                ->with('success', 'Жалоба отправлена. Администрация рассмотрит её в ближайшее время.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }



    private function getReportable(string $type, int $id)
    {
        return match($type) {
            'post' => Post::find($id),
            'comment' => Comment::find($id),
            'user' => User::find($id),
            default => null,
        };
    }
}
