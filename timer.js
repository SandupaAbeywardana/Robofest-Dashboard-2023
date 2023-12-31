// Timer variables
let timer8min, timer5min, timer3min;
let lapCounter5min = 1;
let lapCounter3min = 1;

// Timer for lap timer (3-minute lap timer)
let lapTimer3min = 0; // Initialize lap timer for 3-minute timer to 0
let lapTimerInterval3min = null; // Variable to hold the lap timer interval ID

// Initialize timers
function initializeTimers() {
  timer8min = createTimer("timer-8min", 8 * 60 * 1000); // 8 minutes in milliseconds
  timer5min = createTimer("timer-5min", 5 * 60 * 1000); // 5 minutes in milliseconds
  timer3min = createTimer("timer-3min", 3 * 60 * 1000); // 3 minutes in milliseconds
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
    // Reset lap timer
    lapTimer3min = 0;
    updateLapTimerDisplay();

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

// Function to update and display the lap timer
function updateLapTimerDisplay() {
  const minutes = Math.floor(lapTimer3min / 60000);
  const seconds = Math.floor((lapTimer3min % 60000) / 1000);
  const milliseconds = (lapTimer3min % 1000).toString().padStart(3, "0");
  document.getElementById("lap-timer-3min").textContent = `${minutes
    .toString()
    .padStart(2, "0")}:${seconds.toString().padStart(2, "0")}.${milliseconds}`;
}

// Lap start button click handler for the 3-minute timer
document
  .getElementById("lap-start-button-3min")
  .addEventListener("click", () => {
    if (timer3min.isRunning) {
      // Reset lap timer
      lapTimer3min = 0;
      updateLapTimerDisplay();

      lapTimerInterval3min = setInterval(() => {
        lapTimer3min += 10; // Increment lap timer by 10 milliseconds
        updateLapTimerDisplay();
      }, 10);
    }
  });

// Lap end button click handler for the 3-minute timer
document.getElementById("lap-end-button-3min").addEventListener("click", () => {
  if (lapTimerInterval3min !== null) {
    clearInterval(lapTimerInterval3min);
    lapTimerInterval3min = null;

    // Record lap timer to the 3-minute table
    const minutes = Math.floor(lapTimer3min / 60000);
    const seconds = Math.floor((lapTimer3min % 60000) / 1000);
    const milliseconds = (lapTimer3min % 1000).toString().padStart(3, "0");

    const lapTable = document
      .getElementById("lap-table-3min")
      .getElementsByTagName("tbody")[0];
    const newRow = lapTable.insertRow();
    const lapCell = newRow.insertCell(0);
    const timeCell = newRow.insertCell(1);

    lapCell.innerHTML = lapCounter3min; // Use the lapCounter for 3-minute timer
    timeCell.innerHTML = `${minutes.toString().padStart(2, "0")}:${seconds
      .toString()
      .padStart(2, "0")}.${milliseconds}`;

    lapCounter3min++; // Increment the lap counter for 3-minute timer
  }
});

// Lap button click handler for the 5-minute timer (old logic)
document.getElementById("lap-button-5min").addEventListener("click", () => {
  const currentTime = new Date().getTime();

  if (timer5min.lapStartTime !== null) {
    timer5min.lapEndTime = currentTime;
    const lapDuration = timer5min.lapEndTime - timer5min.lapStartTime;
    const minutes = Math.floor(lapDuration / 60000);
    const seconds = Math.floor((lapDuration % 60000) / 1000);
    const milliseconds = (lapDuration % 1000).toString().padStart(3, "0");

    const lapTable = document
      .getElementById("lap-table-5min")
      .getElementsByTagName("tbody")[0];
    const newRow = lapTable.insertRow();
    const lapCell = newRow.insertCell(0);
    const timeCell = newRow.insertCell(1);

    lapCell.innerHTML = timer5min.lapCounter; // Use the lapCounter from the timer object
    timeCell.innerHTML = `${minutes.toString().padStart(2, "0")}:${seconds
      .toString()
      .padStart(2, "0")}.${milliseconds}`;

    timer5min.lapCounter++; // Increment the lap counter on the timer object
    timer5min.lapStartTime = timer5min.lapEndTime;
  }
});

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

// Initialize timers when the page loads
initializeTimers();
