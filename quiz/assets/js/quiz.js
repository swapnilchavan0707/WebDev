let timerElement = document.getElementById('time-display');
let quizForm = document.getElementById('quizForm');

let countdownInterval;

function updateTimer() {

    if (!timerElement || !quizForm) return;

    let minutes = Math.floor(totalSeconds / 60);
    let seconds = totalSeconds % 60;

    minutes = minutes < 10 ? '0' + minutes : minutes;
    seconds = seconds < 10 ? '0' + seconds : seconds;

    timerElement.textContent = minutes + ':' + seconds;

    if (totalSeconds <= 0) {

        clearInterval(countdownInterval);

        alert('Time is up! Your quiz will be submitted automatically.');

        quizForm.submit();
        return;
    }

    totalSeconds--;
}


// start timer only if elements exist
if (timerElement && quizForm) {
    countdownInterval = setInterval(updateTimer, 1000);
}


// QUESTION SWITCH
function changeQuestion(targetIndex) {

    let blocks = document.getElementsByClassName('question-block');

    for (let i = 0; i < blocks.length; i++) {
        blocks[i].style.display = 'none';
    }

    let target = document.getElementById('q-block-' + targetIndex);

    if (target) {
        target.style.display = 'block';
    }
}


// MANUAL SUBMIT
function confirmManualSubmit(event) {

    event.preventDefault();

    if (confirm("Are you sure you want to finish and submit your answers?")) {

        clearInterval(countdownInterval);

        quizForm.submit();
    }
}