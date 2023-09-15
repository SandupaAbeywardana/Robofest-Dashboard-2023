<?php

include_once "config.php";

// Initialize variables
$teamName = $timer8min = $timer5min = $timer3min = $lapTimes5min = $lapTimes3min = "";

// Retrieve data from the form if available
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $teamName = isset($_POST["team_name"]) ? $_POST["team_name"] : "";
    $timer8min = isset($_POST["timer_8min"]) ? $_POST["timer_8min"] : "";
    $timer5min = isset($_POST["timer_5min"]) ? $_POST["timer_5min"] : "";
    $timer3min = isset($_POST["timer_3min"]) ? $_POST["timer_3min"] : "";
    $lapTimes5min = isset($_POST["lap_times_5min"]) ? $_POST["lap_times_5min"] : "";
    $lapTimes3min = isset($_POST["lap_times_3min"]) ? $_POST["lap_times_3min"] : "";
    $visitedCells = isset($_POST["visited_cells"]) ? $_POST["visited_cells"] : "";
    $restartAttempts = isset($_POST["restart_attempts"]) ? $_POST["restart_attempts"] : "";
    $touches = isset($_POST["touches"]) ? $_POST["touches"] : "";


    // Prepare and execute SQL query to insert data into the database
    $sql = "INSERT INTO timer_data (team_name, timer_8min, timer_5min, timer_3min, lap_times_5min, lap_times_3min, visited_cells, restart_attempts, touches) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssss", $teamName, $timer8min, $timer5min, $timer3min, $lapTimes5min, $lapTimes3min, $visitedCells, $restartAttempts, $touches);

    if ($stmt->execute()) {
        echo "Data saved successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close the database connection
    $stmt->close();
    $conn->close();
}
