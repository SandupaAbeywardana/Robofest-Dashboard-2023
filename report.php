<!DOCTYPE html>
<html>

<head>
    <title>Robofest 2023 - Score Board</title>
    <link rel="stylesheet" type="text/css" href="report.css">
    <style>
        .min-time {
            background-color: yellow;
            /* Highlight color for minimum time */
        }
    </style>
</head>

<body>
    <h1>Robofest 2023 - Score Board</h1>

    <input type="text" id="teamSearch" placeholder="Search by Team Name">

    <?php
    include_once "config.php";


    // Retrieve data from the database
    $sql = "SELECT * FROM timer_data";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Output table headers
        echo "<table>";
        echo "<thead><tr><th>Team Name</th><th>8-Min Timer</th><th>5-Min Timer</th><th>3-Min Timer</th><th>Visited Cells</th><th>Restart Attempts</th><th>Touches</th><th>Lap Times for 5-Min Timer</th><th>Lap Times for 3-Min Timer</th><th>Score</th></tr></thead>";
        echo "<tbody id='tableBody'>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row["team_name"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["timer_8min"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["timer_5min"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["timer_3min"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["visited_cells"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["restart_attempts"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["touches"]) . "</td>";
            echo "<td>";
            $lapTimes5min = explode(',', $row["lap_times_5min"]);
            $minTime5min = min($lapTimes5min); // Find the minimum lap time
            foreach ($lapTimes5min as $index => $lapTime) {
                $lapNumber = $index + 1;
                if ($lapTime === $minTime5min) {
                    echo "<span>" . "Lap $lapNumber: " . htmlspecialchars($lapTime) . "</span><br>";
                } else {
                    echo "Lap $lapNumber: " . htmlspecialchars($lapTime) . "<br>";
                }
            }
            echo "</td>";
            echo "<td>";
            $lapTimes3min = explode(',', $row["lap_times_3min"]);
            $minTime3min = min($lapTimes3min); // Find the minimum lap time
            foreach ($lapTimes3min as $index => $lapTime) {
                $lapNumber = $index + 1;
                if ($lapTime === $minTime3min) {
                    echo "<span class='min-time'>" . "Lap $lapNumber: " . htmlspecialchars($lapTime) . "</span><br>";
                } else {
                    echo "Lap $lapNumber: " . htmlspecialchars($lapTime) . "<br>";
                }
            }
            echo "</td>";

            // Convert minTime3min to seconds
            list($minutes, $seconds, $milliseconds) = sscanf($minTime3min, "%d:%d.%d");
            $minTime3minInSeconds = ($minutes * 60) + $seconds + ($milliseconds / 1000);

            // Calculate the score
            $visitedCells = floatval($row["visited_cells"]);
            $restartAttempts = floatval($row["restart_attempts"]);
            $touches = floatval($row["touches"]);

            $score = ((180 - $minTime3minInSeconds) + ($visitedCells * 0.5)) - (($restartAttempts * 20) + ($touches * 3));

            echo "<td>" . number_format(
                $score,
                2
            ) . "</td>";

            echo "</tr>";
        }


        echo "</tbody>";
        echo "</table>";
    } else {
        echo "<div>No data found</div>";
    }


    // Close the database connection
    $conn->close();
    ?>

    <script>
        const teamSearch = document.getElementById("teamSearch");
        const tableBody = document.getElementById("tableBody");

        teamSearch.addEventListener("input", () => {
            const searchTerm = teamSearch.value.toLowerCase();
            const rows = tableBody.getElementsByTagName("tr");

            for (let i = 0; i < rows.length; i++) {
                const teamName = rows[i].getElementsByTagName("td")[0];
                if (teamName) {
                    const text = teamName.textContent || teamName.innerText;
                    if (text.toLowerCase().indexOf(searchTerm) > -1) {
                        rows[i].style.display = "";
                    } else {
                        rows[i].style.display = "none";
                    }
                }
            }
        });

        function sortTable() {
            const rows = Array.from(tableBody.getElementsByTagName("tr"));
            rows.shift(); // Remove the header row

            // Sort rows based on the score column
            rows.sort((a, b) => {
                const scoreA = parseFloat(a.lastElementChild.textContent);
                const scoreB = parseFloat(b.lastElementChild.textContent);
                return scoreA - scoreB;
            });

            // Reorder the rows in the table body
            for (let i = 0; i < rows.length; i++) {
                tableBody.appendChild(rows[i]);
            }
        }

        // Sort the table when the page loads
        window.addEventListener("load", sortTable);
    </script>

</body>

</html>