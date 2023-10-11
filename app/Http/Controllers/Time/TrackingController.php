<?php

namespace App\Http\Controllers\Time;

use Carbon\Carbon;
use DateTimeImmutable;
use League\Csv\Writer;
use App\Models\TimeTracking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class TrackingController extends Controller
{
    public function index()
    {
        $timeTrackings = TimeTracking::where('user_id', Auth::id())->get();

        return view('time-tracking.index', compact('timeTrackings'));
    }

    public function create()
    {
        return view('time-tracking.create');
    }

    public function edit($id)
    {
        $timeTracking = TimeTracking::findOrFail($id);
        return view('time-tracking.edit', compact('timeTracking'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ]);

        $timeTracking = TimeTracking::findOrFail($id);
        $timeTracking->start_time = $request->input('start_time');
        $timeTracking->end_time = $request->input('end_time');
        $timeTracking->save();

        return redirect()->route('time-tracking.index');
    }

    public function destroy(Request $request)
    {
        $id = $request->input('id');
        $timeTracking = TimeTracking::findOrFail($id);
        $timeTracking->delete();

        return redirect()->route('time-tracking.index')->with('status', 'Time tracking record deleted successfully.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'start' => 'required|string',
            'end' => 'required|string|gt:start',
        ]);

        $startTime = Carbon::createFromTimestamp(
            substr($request->input('start'), 0, -3),
            'Europe/Berlin'
        );

        $endTime = Carbon::createFromTimestamp(
            substr($request->input('end'), 0, -3),
            'Europe/Berlin'
        );

        TimeTracking::create([
            'user_id' => Auth::id(),
            'start_time' => $startTime,
            'end_time' => $endTime
        ]);

        return redirect()->route('time-tracking.index');
    }

    public function show()
    {
        $timeTrackings = TimeTracking::orderBy('start_time')->get();

        // Initialize an array to hold the summed timeTrackings by month and day
        $summedTimeTrackingsByMonthAndDay = [];

        // Loop through each timeTracking and sum the timeTrackings for that month and day
        foreach ($timeTrackings as $timeTracking) {
            $month = $timeTracking->start_time->format('m');
            $year = $timeTracking->start_time->format('Y');
            $day = $timeTracking->start_time->format('d');
            $key = $month . '-' . $year;
            if (!isset($summedTimeTrackingsByMonthAndDay[$key])) {
                $summedTimeTrackingsByMonthAndDay[$key] = [];
            }
            if (!isset($summedTimeTrackingsByMonthAndDay[$key][$day])) {
                $summedTimeTrackingsByMonthAndDay[$key][$day] = 0;
            }
            $timeSpent = $this->dateIntervalToSeconds($timeTracking->end_time->diff($timeTracking->start_time));
            $summedTimeTrackingsByMonthAndDay[$key][$day] += $timeSpent;
        }

        return view('time-tracking.show', compact('summedTimeTrackingsByMonthAndDay'));
    }

    /**
     * @param DateInterval $dateInterval
     * @return int seconds
     */
    private function dateIntervalToSeconds($dateInterval)
    {
        $reference = new DateTimeImmutable;
        return $reference->add($dateInterval)->getTimestamp();
    }

    public function export()
{
    // Abrufen der erfassten Zeiten aus der Datenbank
    $times = DB::table('time_trackings')->get();

    // Erstellen der CSV-Datei
    $csv = Writer::createFromString('');
    $csv->insertOne(['id', 'user_id', 'start_time', 'end_time']);

    foreach ($times as $time) {
        $csv->insertOne([$time->id, $time->user_id, $time->start_time, $time->end_time]);
    }

    // Herunterladen der CSV-Datei
    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => 'attachment; filename="time_trackings.csv"',
    ];

    return response($csv->getContent(), 200, $headers);
}
}
