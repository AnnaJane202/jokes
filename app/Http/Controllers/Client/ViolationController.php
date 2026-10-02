<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\Violation\StoreRequest;
use App\Http\Resources\Violation\ViolationResource;
use App\Models\AppealEvidence;
use App\Models\Violation;
use App\Services\ViolationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ViolationController extends Controller
{

    public function index(Request $request)
    {
        $violations = auth()->user()
            ->violations()
            ->with(['admin', 'appealEvidence'])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->type, fn($q) => $q->where('type', $request->type))
            ->latest()
            ->paginate(10);

        $violations->getCollection()->transform(fn($v) => ViolationResource::make($v)->resolve());

//        dd($violations);

        return inertia('Client/Profile/Violation/Index', [
            'violations' => $violations,
            'filters' => $request->only(['status', 'type']),
            'statuses' => Violation::getStatuses(),
            'types' => Violation::getViolationTypes(),
        ]);
    }

    public function show(Violation $violation)
    {
        // Проверяем, что нарушение принадлежит текущему пользователю
        if ($violation->user_id !== auth()->id()) {
            abort(403, 'Это не ваше нарушение');
        }

        $violation->load(['admin', 'appealEvidence']);

        return inertia('Client/Profile/Violation/Show', [
            'violation' => ViolationResource::make($violation)->resolve(),
        ]);
    }

    /**
     * Показать форму подачи апелляции
     */
    public function create(Violation $violation)
    {
//        dd($violation->user_id);
        // Проверяем, принадлежит ли нарушение текущему пользователю
        if ($violation->user_id !== auth()->id()) {
            abort(403);
        }

        // Проверяем, можно ли подать апелляцию
        if ($violation->status !== Violation::STATUS_ACTIVE) {
            return redirect()->route('client.violations.index', $violation)
                ->with('error', 'Апелляцию можно подать только по активному нарушению');
        }

        // Проверяем срок подачи (7 дней)
        if ($violation->created_at->addDays(7)->isPast()) {
            return redirect()->route('client.violations.index', $violation)
                ->with('error', 'Срок подачи апелляции истек');
        }

        // Проверяем, не подана ли уже апелляция
        if ($violation->appealed_at) {
            return redirect()->route('client.violations.index', $violation)
                ->with('error', 'Апелляция по этому нарушению уже подана');
        }

        $violation = ViolationResource::make($violation)->resolve();
        return inertia('Client/Profile/Violation/Appeal', compact('violation'));
    }


    /**
     * Сохранить апелляцию
     */
    public function store(StoreRequest $request, Violation $violation)
    {
        // Повторяем все проверки
        if ($violation->user_id !== auth()->id()) {
            abort(403);
        }

        if ($violation->status !== Violation::STATUS_ACTIVE) {
            return back()->with('error', 'Апелляцию можно подать только по активному нарушению');
        }

        if ($violation->created_at->addDays(7)->isPast()) {
            return back()->with('error', 'Срок подачи апелляции истек');
        }

        if ($violation->appealed_at) {
            return back()->with('error', 'Апелляция уже подана');
        }

        $data = $request->validated();
        ViolationService::storeAppeal($violation, $data);


        return redirect()->route('client.violations.index', $violation)
            ->with('success', 'Апелляция успешно подана. Мы рассмотрим её в ближайшее время.');

    }

    public function myAppeals(Request $request)
    {
        $appeals = auth()->user()
            ->violations()
            ->whereNotNull('appealed_at')
            ->with(['admin', 'admin'])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest('appealed_at')
            ->paginate(10);

        $appeals->getCollection()->transform(fn($v) => ViolationResource::make($v)->resolve());

        return inertia('Client/Profile/Appeal/Index', [
            'appeals' => $appeals,
            'filters' => $request->only(['status']),
        ]);
    }

    public function uploadAppealEvidence(Request $request) {

        $request->validate([
            'file' => 'required|file|max:10240|mimes:jpg,jpeg,png,pdf,doc,docx',
            'violation_id' => 'required|exists:violations,id',
        ]);

        $violation = Violation::findOrFail($request->violation_id);

        // Проверяем, что пользователь владеет нарушением
        if ($violation->user_id !== auth()->id()) {
            abort(403);
        }

        // Генерируем уникальное имя файла
        $fileName = Str::uuid() . '.' . $request->file('file')->getClientOriginalExtension();

        // Сохраняем файл
        $path = $request->file('file')->storeAs(
            'appeals/' . $violation->id,
            $fileName,
            'public' // Используем public диск
        );

        // Сохраняем информацию о файле в отдельной таблице (опционально)
        $evidenceFile = AppealEvidence::create([
            'violation_id' => $violation->id,
            'user_id' => auth()->id(),
            'filename' => $fileName,
            'original_name' => $request->file('file')->getClientOriginalName(),
            'path' => $path,
            'url' => Storage::url($path),
            'size' => $request->file('file')->getSize(),
            'mime_type' => $request->file('file')->getMimeType(),

        ]);

        return response()->json([
            'id' => $evidenceFile->id,
            'name' => $evidenceFile->original_name,
            'url' => $evidenceFile->url,
            'path' => $evidenceFile->path,
            'mime_type' => $evidenceFile->mime_type,

        ]);
    }


}
