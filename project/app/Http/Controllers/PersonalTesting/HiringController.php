<?php

namespace App\Http\Controllers\PersonalTesting;

use App\Helpers\HiringTestConvertingHelper;
use App\Http\Controllers\Controller;
use App\Mail\NewHiring;
use App\Models\PersonalTesting\Hiring_test;
use App\Models\Worker;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Browsershot\Browsershot;
use Spatie\LaravelPdf\Enums\Format;
use Spatie\LaravelPdf\Enums\Unit;
use Spatie\LaravelPdf\PdfBuilder;
use function Spatie\LaravelPdf\Support\pdf;


class HiringController extends Controller
{
    private HiringTestConvertingHelper $helper;
    private array $HR_emails;

    public function __construct()
    {
        $this->helper = new HiringTestConvertingHelper();
        $this->HR_emails =  config('app_notifications.HR_mail_emails');
    }

    public function index(): Response
    {
        return Inertia::render('PersonalTesting/hiring/Index', [
            'questions' => (config('Cattell.Cattell_questions'))
        ]);
    }

    public function store(Request $request): void
    {
        $validated = $request->validate([
            'about' => 'required',
            'raw_test_results' => 'required',
        ]);

        Hiring_test::create([
            'name' => $validated['about']['last_name'] . Carbon::now()->format('Y-m-d'),
            'about' => $validated['about'],
            'raw_test_results' => $validated['raw_test_results'],
        ]);

        $mail= new NewHiring(
            name: $validated['about']['last_name'] . ' ' . $validated['about']['first_name'],
            vacancy:$validated['about']['vacancy']
        );

        Mail::to($this->HR_emails)->send($mail);
    }

    public function update(Request $request, Hiring_test $person): void
    {
        $validated = $request->validate([
            'recommender_id' => 'sometimes|nullable|exists:workers,name',
            'hired_at' => 'sometimes|nullable|date',
        ]);

        $person->update($validated);
    }

    public function show($monthYear): Response
    {
        $date = Carbon::createFromFormat('Y-m', $monthYear);

        // Получаем текущий, предыдущий и следующий месяцы
        $currentMonth = $date->translatedFormat('F Y');
        $previousMonth = $date->copy()->subMonth();
        $nextMonth = $date->copy()->addMonth();

        // Проверяем, есть ли записи за предыдущий и следующий месяцы
        $hasPreviousMonth = Hiring_test::whereYear('created_at', $previousMonth->year)
            ->whereMonth('created_at', $previousMonth->month)
            ->exists();

        $hasNextMonth = Hiring_test::whereYear('created_at', $nextMonth->year)
            ->whereMonth('created_at', $nextMonth->month)
            ->exists();

        // Фильтруем записи по текущему месяцу
        $hiringData = Hiring_test::whereYear('created_at', $date->year)
            ->whereMonth('created_at', $date->month)
            ->orderBy('created_at', 'desc')
            ->get();

        $workers = Worker::where('is_blocked', false)->pluck('name')->all();

        return Inertia::render('PersonalTesting/hiring/Show', [
            'hiringData' => $hiringData,
            'workers' => $workers,
            'filterData' => [
                'currentMonth' => $currentMonth,
                'hasPreviousMonth' => $hasPreviousMonth,
                'hasNextMonth' => $hasNextMonth,
                'previousMonth' => $previousMonth->format('Y-m'),
                'nextMonth' => $nextMonth->format('Y-m')
            ]
        ]);
    }

    public function performance(Hiring_test $person): View
    {
        $sten_results = $this->helper->convertRawPointsToSten($person['raw_test_results']);
        $test_results = $this->helper->convertStenToChartPositions($sten_results);

        return view('PersonalTesting/hiring', [
            'test_results' => $test_results,
            'about' => $person['about'],
            'created_at' => $person['created_at'],
            'isDownloadable' => false]);
    }

    public function ExportToPDF(Hiring_test $person): PdfBuilder
    {
        $sten_results = $this->helper->convertRawPointsToSten($person['raw_test_results']);
        $test_results = $this->helper->convertStenToChartPositions($sten_results);

        $fileName = $person['name'] . '.pdf';

        return pdf()
            ->view('PersonalTesting.hiring', [
                'test_results' => $test_results,
                'about' => $person['about'],
                'created_at' => $person['created_at'],
                'isDownloadable' => true
            ])
            ->withBrowsershot(function (Browsershot $browsershot) {
                $browsershot->noSandbox();
            })
            ->format(Format::A4)
            ->margins(40, 20, 25, 20, Unit::Pixel)
            ->name($fileName)->download();
    }

}
