@extends('time-tracking.layout')

@section('content')
    <div class="container">
        <h1>Time Tracking</h1>

        <form action="{{ route('time-tracking.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Timer</label>
                <div id="timer" class="form-control">00:00:00</div>
                <input type="number" name="start" id="start" hidden value="0" />
                <input type="number" name="end" id="end" hidden value="0"/>


                <input onclick="stopTimer()" id="stop-btn" type="submit" class="col-md-3 btn offset-md-4 btn-primary" value="Stop">

            </div>
        </form>
        <button onclick="startTimer()" id="start-btn" class="col-md-3 btn offset-md-4 btn-primary">Start</button>

    </div>
@endsection

@section('scripts')
    <script>
        // Get the timer element
        const timerEl = document.getElementById('timer');

        // Get the start-stop button element
        const startBtn = document.getElementById('start-btn');
        const stopBtn = document.getElementById('stop-btn');

        // Get the start and end time elements
        const startTimeEl = document.getElementById('start');
        const endTimeEl = document.getElementById('end');


        // Hide the stop button
        stopBtn.style.display = "none";

        // Get the start time from local storage
        const startTime = localStorage.getItem('startTime');

        // Initialize the timer
        let timer = null;

        // If start time exists, start the timer
        if (startTime) {
            startTimer();
        }

        // Function to start the timer
        function startTimer() {
            // Get the current time
            const now = new Date().getTime();


            // If start time does not exist, set the start time to current time
            if (!startTime) {
                localStorage.setItem('startTime', now);
                startTimeEl.setAttribute("value",now);
            }else{
                startTimeEl.setAttribute("value",startTime);
            }

            // Set the timer interval to 1 second
            timer = setInterval(function() {
                // Get the elapsed time in milliseconds
                const elapsed = new Date().getTime() - parseInt(localStorage.getItem('startTime'));

                const now = new Date().getTime();
                endTimeEl.setAttribute("value",now);

                // Convert elapsed time to HH:MM:SS format
                const hours = Math.floor(elapsed / (1000 * 60 * 60));
                const minutes = Math.floor((elapsed % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((elapsed % (1000 * 60)) / 1000);

                // Update the timer element with the elapsed time
                timerEl.textContent = `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            }, 1000);

            startBtn.style.display = "none";
            stopBtn.style.display = "block";
        }

        // Function to stop the timer
        function stopTimer() {

            // Clear the timer interval
            clearInterval(timer);

            // Remove the start time from local storage
            localStorage.removeItem('startTime');

            // Reset the timer element to "00:00:00"
            timerEl.textContent = '00:00:00';

            // Set the timer variable to null
            timer = null;

            startBtn.style.display = "block";
            stopBtn.style.display = "none";
        }
    </script>
@endsection
