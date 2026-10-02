<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Appeal\ProcessAppealRequest;
use App\Http\Requests\Admin\Violation\StoreRequest;
use App\Http\Resources\User\UserResource;
use App\Http\Resources\Violation\ViolationPaginatedResource;
use App\Http\Resources\Violation\ViolationResource;
use App\Http\Resources\Violation\ViolationWithAllResource;
use App\Models\User;
use App\Models\Violation;
use App\Services\AppealService;
use App\Services\ViolationService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class ViolationController extends Controller
{

    protected ViolationService $violationService;
    protected AppealService $appealService;

    public function __construct(ViolationService $violationService, AppealService $appealService)
    {

        $this->violationService = $violationService;
        $this->appealService = $appealService;
    }


    public function index(Request $request)
    {
        $tab = $request->get('tab', 'active');
//        dd($tab);
        // Статистика для виджетов
        $stats = [
            'active' => Violation::where('status', 'active')->count(),
            'appeals' => Violation::where('status', 'appealed')->count(),
        ];
        // Активные нарушения (не рассмотренные)
//        $activeViolations = Violation::where('status', 'active')
//            ->whereNull('appealed_at')->paginate(5); // без апелляций
//
//        $activeViolations = ViolationPaginatedResource::collection($activeViolations)->resolve();

        // Получаем пагинатор
        if ($tab === 'active') {
            $paginator = Violation::where('status', 'active')
                ->latest()
                ->paginate(2);

//            dd($paginator);


            return inertia('Admin/Violation/Index', [

                'activeViolations' => [
                    'data' => ViolationResource::collection($paginator->items()),
                    'links' => $this->formatPaginationLinks($paginator),
                    'meta' => [
                        'current_page' => $paginator->currentPage(),
                        'from' => $paginator->firstItem(),
                        'last_page' => $paginator->lastPage(),
                        'per_page' => $paginator->perPage(),
                        'to' => $paginator->lastItem(),
                        'total' => $paginator->total(),
                    ],
                ],
                'stats' => $stats,
                'pendingAppeals' => null, // ← явно передаем null
                'tab' => $tab
            ]);
        }







        // Получаем пагинатор
        if ($tab === 'appeals') {

            // Апелляции (ожидают рассмотрения)
            $pendingAppeals  = Violation::where('status', 'appealed')
                //->whereNull('appealed_at') // без апелляций
                //->whereNull('appeal_decided_at')
                //->orderBy('appealed_at', 'asc') // сначала старые
                ->paginate(2);



            return inertia('Admin/Violation/Index', [
                'activeViolations' => null, // ← явно передаем null
                'pendingAppeals' => [
                    'data' => ViolationResource::collection($pendingAppeals->items()),
                    'links' => $this->formatPaginationLinks($pendingAppeals),
                    'meta' => [
                        'current_page' => $pendingAppeals->currentPage(),
                        'from' => $pendingAppeals->firstItem(),
                        'last_page' => $pendingAppeals->lastPage(),
                        'per_page' => $pendingAppeals->perPage(),
                        'to' => $pendingAppeals->lastItem(),
                        'total' => $pendingAppeals->total(),
                    ],
                ],
                'stats' => $stats,
                'tab' => $tab
            ]);
        }






//        return inertia('Admin/Violation/Index', compact('activeViolations', 'pendingAppeals', 'stats'));
    }

    /**
     * Форма вынесения нарушения
     */
    public function create(User $user)
    {

        $violationTypes = [
            Violation::TYPE_SPAM => 'Спам',
            Violation::TYPE_ABUSE => 'Оскорбления',
            Violation::TYPE_HARASSMENT => 'Травля',
            Violation::TYPE_COPYRIGHT => 'Нарушение авторских прав',
            Violation::TYPE_ILLEGAL => 'Незаконный контент',
            Violation::TYPE_FRAUD => 'Мошенничество',
            Violation::TYPE_OTHER => 'Другое',
        ];

        $penaltyTypes = [
            Violation::PENALTY_WARNING => 'Предупреждение',
            Violation::PENALTY_TEMP_BAN => 'Временная блокировка',
            Violation::PENALTY_PERMANENT_BAN => 'Постоянная блокировка',
            Violation::PENALTY_CONTENT_REMOVAL => 'Удаление контента',
        ];

        $user = UserResource::make($user)->resolve();



        return inertia('Admin/Violation/Create', compact('user', 'violationTypes', 'penaltyTypes'));

    }


    /**
     * Сохранить нарушение
     */
    public function store(StoreRequest $request)
    {
        $data = $request->validated();

        try {
            $this->violationService->issueViolation([
                'user_id' => $request->user_id,
                'admin_id' => auth()->id(),
                'type' => $request->type,
                'reason' => $request->reason,
                'details' => $request->details,
                'penalty_type' => $request->penalty_type,
                'duration_days' => $request->duration_days,
            ]);

            return redirect()->route('admin.users.show', $user)
                ->with('success', 'Нарушение вынесено');

        } catch (\Exception $e) {
            return back()->with('error', 'Ошибка: ' . $e->getMessage());
        }
    }

    /**
     * История нарушений пользователя
     */
    public function userHistory(User $user)
    {
        $violations = $user->violations()
            ->latest()
            ->paginate(20);

        return inertia('Admin/Violation/History', compact('violations', 'user'));
    }

    /**
     * Апелляции (список)
     */
    public function appeals()
    {
        $appeals = Violation::where('status', Violation::STATUS_APPEALED)
            ->with(['user', 'moderator'])
            ->latest('appealed_at')
            ->paginate(20);

        return view('admin.violations.appeals', compact('appeals'));
    }


//    /**
//     * Показать форму подачи апелляции
//     */
//    public function appealForm(Violation $violation)
//    {
////        dd($violation->user_id);
//        // Проверяем, принадлежит ли нарушение текущему пользователю
//        if ($violation->user_id !== auth()->id()) {
//            abort(403);
//        }
//
//        // Проверяем, можно ли подать апелляцию
//        if ($violation->status !== Violation::STATUS_ACTIVE) {
//            return redirect()->route('profile.violations.show', $violation)
//                ->with('error', 'Апелляцию можно подать только по активному нарушению');
//        }
//
//        // Проверяем срок подачи (7 дней)
//        if ($violation->created_at->addDays(7)->isPast()) {
//            return redirect()->route('profile.violations.show', $violation)
//                ->with('error', 'Срок подачи апелляции истек');
//        }
//
//        // Проверяем, не подана ли уже апелляция
//        if ($violation->appealed_at) {
//            return redirect()->route('profile.violations.show', $violation)
//                ->with('error', 'Апелляция по этому нарушению уже подана');
//        }
//
//        $violation = ViolationResource::make($violation)->resolve();
//        return inertia('Client/Profile/Violation/Appeal', compact('violation'));
//    }

    /**
     * Быстрое одобрение апелляции
     */
    public function quickApprove(Violation $violation)
    {
        // Проверяем, что апелляция еще не рассмотрена
        if ($violation->status !== 'appealed') {
            return back()->with('error', 'Апелляция уже рассмотрена');
        }

        try {
            $this->violationService->quickAppealApprove($violation, auth()->id());

            return back()->with('success', 'Апелляция одобрена, наказание снято');
        } catch (\Exception $e) {
            return back()->with('error', 'Ошибка: ' . $e->getMessage());
        }


    }

    /**
     * Быстрое отклонение апелляции
     */
    public function quickReject(Violation $violation) {
        // Проверяем, что апелляция еще не рассмотрена
        if ($violation->status !== 'appealed') {
            return back()->with('error', 'Апелляция уже рассмотрена');
        }

        try {
            $this->violationService->quickAppealReject($violation, auth()->id());

            return back()->with('success', 'Апелляция отклонена, наказание оставлено в силе');
        } catch (\Exception $e) {
            return back()->with('error', 'шибка: ' . $e->getMessage());
        }
    }


    /**
     * Показать форму рассмотрения  апелляции
     */
    public function review(Violation $violation)
    {
        if ($violation->status !== 'appealed') {
            return redirect()->route('admin.violations.index')
                ->with('error', 'Апелляция уже рассмотрена');
        }

        // Загружаем все нужные связи
        $violation->load('user', 'admin', 'appealEvidence');




        $penaltyOptions = Violation::getPenaltyOptions();

        // История нарушений пользователя
        $userViolations = $violation->user->violations()
            ->where('id', '!=', $violation->id)
            ->latest()
            ->take(10)
            ->get();

        $userViolations = ViolationWithAllResource::collection($userViolations)->resolve();




        $repeatCount = $violation->user->violations()
        ->where('type', $violation->type)
        ->where('created_at', '>', now()->subDays(120))
        ->count();

        $violation = ViolationWithAllResource::make($violation)->resolve();

        return inertia('Admin/Violation/Appeal/Review', compact('userViolations', 'violation', 'repeatCount', 'penaltyOptions') );




    }

    /**
     * Рассмотреть апелляцию
     */
    public function processReview(ProcessAppealRequest $request, Violation $violation)
    {
//        dd($violation);
        try {
            $result = $this->appealService->process(
                $violation,
                $request->decision,
                $request->new_penalty_type,
                $request->new_duration_days,
                $request->comment,
                $request->notify_user ?? true
            );

            return redirect()
                ->route('admin.violation.index')
                ->with('success', $result['message']);

        } catch (\Exception $e) {
            return back()
                ->with('error', 'Ошибка: ' . $e->getMessage())
                ->withInput();
        }

    }

    private function formatPaginationLinks($paginator): array
    {
        $links = [];

        $links[] = [
            'url' => $paginator->previousPageUrl(),
            'label' => '&laquo; Previous',
            'active' => false,
        ];

        for ($i = 1; $i <= $paginator->lastPage(); $i++) {
            $links[] = [
                'url' => $paginator->url($i),
                'label' => (string) $i,
                'active' => $i === $paginator->currentPage(),
            ];
        }

        $links[] = [
            'url' => $paginator->nextPageUrl(),
            'label' => 'Next &raquo;',
            'active' => false,
        ];

        return $links;
    }
}
