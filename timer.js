// Timer variables
let timer8min, timer5min, timer3min;
let lapCounter5min = 1;
let lapCounter3min = 1;

// Initialize timers
function initializeTimers() {
  timer8min = createTimer("timer-8min", 8 * 60 * 1000); // 8 minutes in milliseconds
  timer5min = createTimer("timer-5min", 10 * 60 * 1000); // 5 minutes in milliseconds
  timer3min = createTimer("timer-3min", 10 * 60 * 1000); // 3 minutes in milliseconds
}

// Create a timer
function createTimer(timerId, duration) {
  return {
    element: document.getElementById(timerId),
    duration: duration,
    intervalId: null,
    isRunning: false,
    lapStartTime: null,
    lapEndTime: null,
    lapCounter: timerId === "timer-5min" ? lapCounter5min : lapCounter3min,
  };
}

// Update timer display with milliseconds
function updateTimerDisplay(timer) {
  const minutes = Math.floor(timer.duration / 60000);
  const seconds = Math.floor((timer.duration % 60000) / 1000);
  const milliseconds = (timer.duration % 1000).toString().padStart(3, "0");
  timer.element.textContent = `${minutes.toString().padStart(2, "0")}:${seconds
    .toString()
    .padStart(2, "0")}.${milliseconds}`;
}

// Start timer
function startTimer(timer) {
  if (!timer.isRunning) {
    timer.intervalId = setInterval(() => {
      timer.duration -= 10; // Subtract 10 milliseconds
      updateTimerDisplay(timer);

      if (timer.duration <= 0) {
        clearInterval(timer.intervalId);
        timer.isRunning = false;
        if (timer === timer5min) {
          startTimer(timer3min); // Start the 3-minute timer when the 5-minute timer reaches 0
        }

        if (timer3min.duration <= 0) {
          stopTimer(timer8min);
        }
      }
    }, 10); // Update every 10 milliseconds

    if (timer.lapStartTime === null) {
      timer.lapStartTime = new Date().getTime();
    }

    timer.isRunning = true;
  }
}

// Stop timer
function stopTimer(timer) {
  if (timer.isRunning) {
    clearInterval(timer.intervalId);
    timer.isRunning = false;
  }
}

function handleLap(timer, currentTime, tableId) {
  if (timer.lapStartTime !== null) {
    timer.lapEndTime = currentTime;
    const lapDuration = timer.lapEndTime - timer.lapStartTime;
    const minutes = Math.floor(lapDuration / 60000);
    const seconds = Math.floor((lapDuration % 60000) / 1000);
    const milliseconds = (lapDuration % 1000).toString().padStart(3, "0");

    const lapTable = document
      .getElementById(tableId)
      .getElementsByTagName("tbody")[0];
    const newRow = lapTable.insertRow();
    const lapCell = newRow.insertCell(0);
    const timeCell = newRow.insertCell(1);

    lapCell.innerHTML = timer.lapCounter; // Use the lapCounter from the timer object
    timeCell.innerHTML = `${minutes.toString().padStart(2, "0")}:${seconds
      .toString()
      .padStart(2, "0")}.${milliseconds}`;

    timer.lapCounter++; // Increment the lap counter on the timer object
    timer.lapStartTime = timer.lapEndTime;
  }
}

// Lap button click handler
function handleLapButton(timerId) {
  return () => {
    const currentTime = new Date().getTime();

    if (timerId === "timer-5min") {
      handleLap(timer5min, currentTime, "lap-table-5min");
    } else if (timerId === "timer-3min") {
      handleLap(timer3min, currentTime, "lap-table-3min");
    }
  };
}

// Start/Stop buttons click handlers
document.getElementById("start-5min").addEventListener("click", () => {
  startTimer(timer5min);
  startTimer(timer8min);
});

document
  .getElementById("stop-5min")
  .addEventListener("click", () => stopTimer(timer5min));
document.getElementById("start-3min").addEventListener("click", () => {
  startTimer(timer3min);
  stopTimer(timer5min); // Stop the 5-minute timer when starting the 3-minute timer
});
document.getElementById("stop-3min").addEventListener("click", () => {
  stopTimer(timer3min);
  stopTimer(timer8min);
});

// Lap buttons click handlers
document
  .getElementById("lap-button-5min")
  .addEventListener("click", handleLapButton("timer-5min"));
document
  .getElementById("lap-button-3min")
  .addEventListener("click", handleLapButton("timer-3min"));

// Initialize timers when the page loads
initializeTimers();
