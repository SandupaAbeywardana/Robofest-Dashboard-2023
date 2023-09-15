<!DOCTYPE html>
<html>

<head>
    <title>Robofest 2023</title>
    <link rel="stylesheet" type="text/css" href="index.css">
</head>

<body>
    <h1>Robofest 2023</h1>
    <br />

    <form method="post" action="save_timer.php" onsubmit="updateFormFields()">

        <!-- Team Name Input -->
        <div class="team-input">
            <div class="input-group">
                <label for="team-name">Team Name:</label>
                <input type="text" id="team-name" name="team_name" required>
            </div>
            <div class="input-group">
                <label for="visited-cells">Visited Cells:</label>
                <input type="number" id="visited-cells" name="visited_cells" required>
            </div>
            <div class="input-group">
                <label for="restart-attempts">Restart Attempts:</label>
                <input type="number" id="restart-attempts" name="restart_attempts" required>
            </div>
            <div class="input-group">
                <label for="touches">Touches:</label>
                <input type="number" id="touches" name="touches" required>
            </div>
            <input type="hidden" id="timer-8min1" name="timer_8min">
            <input type="hidden" id="timer-5min1" name="timer_5min">
            <input type="hidden" id="timer-3min1" name="timer_3min">
            <input type="hidden" id="lap-times-5min1" name="lap_times_5min">
            <input type="hidden" id="lap-times-3min1" name="lap_times_3min">
            <button type="submit">Submit</button>
        </div>

    </form>

    <!-- Timers -->


    <div class="timer-column">
        <div class="timer-container">
            <h2 class="timer-label" style="margin-top: 70px;">8-Minute Timer:</h2>
            <span class="timer-value" id="timer-8min">08:00:000</span>
            <div>
                <button class="timer-button" id="start-8min" style="visibility: hidden;">Start</button>
                <button class="timer-button" id="stop-8min" style="visibility: hidden;">Stop</button>
                <button class="timer-button" id="lap-button-8min" style="visibility: hidden;">Lap</button>
            </div>
        </div>
        <!-- 5-Minute Timer -->
        <div class="timer-container">
            <h2 class="timer-label" style="margin-top: 50px;">5-Minute Timer:</h2>
            <span class="timer-value" id="timer-5min" style="margin-bottom: 30px;">05:00:000</span>
            <div>
                <button class="timer-button" id="start-5min">Start</button>
                <button class="timer-button" id="stop-5min">Stop</button>
                <button class="timer-button" id="lap-button-5min">Lap</button>
            </div>
        </div>

        <!-- 3-Minute Timer -->
        <div class="timer-container">
            <h2 class="timer-label">3-Minute Timer:</h2>
            <span class="timer-value" id="timer-3min">03:00:000</span>
            <span>LapTimer<span class="lap-timer-value" id="lap-timer-3min">00:00:000</span></span>
            <div>
                <button class="timer-button" id="start-3min">Start</button>
                <button class="timer-button" id="stop-3min">Stop</button>
                <!-- <button class="timer-button" id="lap-button-3min">Lap</button> -->
                <button class="timer-button" id="lap-start-button-3min">Lap Start</button>
                <button class="timer-button" id="lap-end-button-3min">Lap End</button>
            </div>
        </div>
    </div>

    <!-- Lap Times for 5-Min Timer -->
    <div class="lap-table-container">
        <table class="lap-table" id="lap-table-5min">
            <thead>
                <tr>
                    <th colspan="2">5 Minute Timer</th>
                </tr>
                <tr>
                    <th>Lap</th>
                    <th>Time</th>
                </tr>
            </thead>
            <tbody>
                <!-- Lap Times for 5-Min Timer content -->
            </tbody>
        </table>
    </div>

    <!-- Lap Times for 3-Min Timer -->
    <div class="lap-table-container">
        <table class="lap-table" id="lap-table-3min">
            <thead>
                <tr>
                    <th colspan="2">3 Minute Timer</th>
                </tr>
                <tr>
                    <th>Lap</th>
                    <th>Time</th>
                </tr>
            </thead>
            <tbody>
                <!-- Lap Times for 3-Min Timer content -->
            </tbody>
        </table>
    </div>



    <!-- JavaScript -->
    <script src="timer.js"></script>
    <script>
        function updateFormFields() {
            // Retrieve timer values
            const timer8minValue = document.getElementById("timer-8min").textContent;
            const timer5minValue = document.getElementById("timer-5min").textContent;
            const timer3minValue = document.getElementById("timer-3min").textContent;

            // Retrieve lap times from respective tables
            const lapTimes5min = [];
            const lapTimes3min = [];

            const lapTable5min = document.getElementById("lap-table-5min").getElementsByTagName("tbody")[0].getElementsByTagName("tr");
            const lapTable3min = document.getElementById("lap-table-3min").getElementsByTagName("tbody")[0].getElementsByTagName("tr");

            for (let i = 0; i < lapTable5min.length; i++) {
                const lapTime = lapTable5min[i].getElementsByTagName("td")[1].textContent;
                lapTimes5min.push(lapTime);
            }

            for (let i = 0; i < lapTable3min.length; i++) {
                const lapTime = lapTable3min[i].getElementsByTagName("td")[1].textContent;
                lapTimes3min.push(lapTime);
            }

            // Set the values in the form fields
            document.getElementById("timer-8min1").value = timer8minValue;
            document.getElementById("timer-5min1").value = timer5minValue;
            document.getElementById("timer-3min1").value = timer3minValue;
            document.getElementById("lap-times-5min1").value = lapTimes5min.join(",");
            document.getElementById("lap-times-3min1").value = lapTimes3min.join(",");
        }
    </script>
</body>

</html>