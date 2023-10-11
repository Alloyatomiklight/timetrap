@extends('time-tracking.layout')

@section('content')
<h1 class="mt-5">Time Trackings</h1>
@foreach ($summedTimeTrackingsByMonthAndDay as $month => $summedTimeTrackingsByDay)
<table class="table table-striped">
    <thead>
        <tr>
            <th>Month: {{ $month }}</th>
            <th>Total Time: {{ gmdate('H:i:s', array_sum($summedTimeTrackingsByDay)) }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($summedTimeTrackingsByDay as $day => $time)
        <tr>
            <td>{{ $day }}</td>
            <td>{{ gmdate('H:i:s', $time) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endforeach
@endsection
