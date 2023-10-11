@extends('time-tracking.layout')

@section('content')
    <h1>Edit Time Tracking</h1>

    <form action="{{ route('time-tracking.update', $timeTracking->id) }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="start_time">Start Time</label>
            <input type="datetime-local" name="start_time" id="start_time" class="form-control" value="{{ $timeTracking->start_time->format('Y-m-d\TH:i:s') }}" required>
        </div>
        <div class="form-group">
            <label for="end_time">End Time</label>
            <input type="datetime-local" name="end_time" id="end_time" class="form-control" value="{{ $timeTracking->end_time->format('Y-m-d\TH:i:s') }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Save</button>
    </form>
@endsection
