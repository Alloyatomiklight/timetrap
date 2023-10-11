@extends('time-tracking.layout')

@section('content')
<h1>Time Tracking</h1>

<a href="{{ route('time-tracking.create') }}" class="btn btn-primary mb-3">Add Time Tracking</a>

<table class="table">
    <thead>
        <tr>
            <th>Start Time</th>
            <th>End Time</th>
            <th>Duration</th>
            <th>Edit</th>
            <th>Delete</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($timeTrackings as $timeTracking)
        <tr class="{{$timeTracking->id}}">
            <td>{{ $timeTracking->start_time }}</td>
            <td>{{ $timeTracking->end_time }}</td>
            <td>{{ $timeTracking->end_time->diff($timeTracking->start_time)->format('%H:%I:%S') }}</td>
            <td>
                <form action="{{ route('time-tracking.edit', $timeTracking->id) }}" method="GET">
                    @csrf
                    <button type="submit" class="btn btn-primary">Edit</button>
                </form>
            </td>
            <td>
                <form action="{{ route('time-tracking.destroy') }}" method="POST">
                    @csrf
                    <input type="hidden" name="_method" value="DELETE">
                    <input type="number" name="id" value="{{$timeTracking->id}}" hidden>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
