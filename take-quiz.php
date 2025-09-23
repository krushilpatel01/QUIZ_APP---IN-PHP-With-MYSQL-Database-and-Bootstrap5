<?php
session_start();
include 'includes/config.php';

// check if quiz_id is provided in URL
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$quiz_id = intval($_GET['id']);

// Fetch quiz questions for display
$questions_result = mysqli_query($conn, "SELECT * FROM questions WHERE quiz_id=$quiz_id ORDER BY RAND()");

// Fetch quiz info
$quiz_row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM quizzes WHERE id=$quiz_id"));
$quiz_title = $quiz_row['title'];
$quiz_description = $quiz_row['description'];

$time_limit = intval($quiz_row['time']); // in minutes
$time_seconds = $time_limit * 60; // convert to seconds

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title><?php echo htmlspecialchars($quiz_title); ?> - QuizMaster</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@400;500;700;900&display=swap" rel="stylesheet">
    <style>
    body {
        font-family: 'Work Sans', sans-serif;
        background: #f8f9fa;
    }

    .quiz-card {
        margin-bottom: 25px;
        border-radius: .5rem;
    }

    .quiz-card h5 {
        font-weight: 600;
    }

    .quiz-card .form-check-label {
        font-size: 1rem;
    }

    .submit-btn {
        margin-top: 20px;
    }

    .timer-bar {
        height: 25px;
    }
    </style>
</head>

<body class="bg-light text-dark">

    <!-- Header -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
        <div class="container d-flex justify-content-between">
            <a class="navbar-brand d-flex align-items-center mx-auto gap-2" href="#">
                <svg class="text-primary" width="24" height="24" fill="currentColor" viewBox="0 0 48 48">
                    <path d="M6 6H42L36 24L42 42H6L12 24L6 6Z"></path>
                </svg>
                <span class="fw-bold">QuizMaster</span>
            </a>
        </div>
    </nav>

    <!-- Quiz Section -->
    <div class="container my-5">
        <h1 class="mb-3"><?php echo htmlspecialchars($quiz_title); ?></h1>
        <p class="mb-4 text-muted"><?php echo htmlspecialchars($quiz_description); ?></p>

        <!-- Timer -->
        <div class="mb-3">
            <label>Time Remaining:</label>
            <div class="progress timer-bar">
                <div id="timerProgress" class="progress-bar bg-danger" role="progressbar" style="width:100%"></div>
            </div>
            <div class="mt-1" id="timerText"><?php echo gmdate("i:s", $time_seconds); ?></div>
        </div>


        <form method="post" action="submit-quiz.php" id="quizForm">
            <?php $qnum = 1; mysqli_data_seek($questions_result, 0); 
        while($q = mysqli_fetch_assoc($questions_result)) { ?>
            <div class="card p-3 mb-3">
                <h5>Q<?php echo $qnum++; ?>: <?php echo htmlspecialchars($q['question']); ?></h5>
                <div class="form-check mt-2">
                    <input class="form-check-input" type="radio" name="answer[<?php echo $q['id']; ?>]" value="A"
                        id="q<?php echo $q['id']; ?>a" required>
                    <label class="form-check-label"
                        for="q<?php echo $q['id']; ?>a"><?php echo htmlspecialchars($q['option_a']); ?></label>
                </div>
                <div class="form-check mt-2">
                    <input class="form-check-input" type="radio" name="answer[<?php echo $q['id']; ?>]" value="B"
                        id="q<?php echo $q['id']; ?>b">
                    <label class="form-check-label"
                        for="q<?php echo $q['id']; ?>b"><?php echo htmlspecialchars($q['option_b']); ?></label>
                </div>
                <div class="form-check mt-2">
                    <input class="form-check-input" type="radio" name="answer[<?php echo $q['id']; ?>]" value="C"
                        id="q<?php echo $q['id']; ?>c">
                    <label class="form-check-label"
                        for="q<?php echo $q['id']; ?>c"><?php echo htmlspecialchars($q['option_c']); ?></label>
                </div>
                <div class="form-check mt-2">
                    <input class="form-check-input" type="radio" name="answer[<?php echo $q['id']; ?>]" value="D"
                        id="q<?php echo $q['id']; ?>d">
                    <label class="form-check-label"
                        for="q<?php echo $q['id']; ?>d"><?php echo htmlspecialchars($q['option_d']); ?></label>
                </div>
            </div>
            <?php } ?>

            <input type="hidden" name="quiz_id" value="<?php echo $quiz_id; ?>">
            <button type="submit" class="btn btn-primary" id="submitBtn">Submit Quiz</button>
        </form>
    </div>

    <!-- Footer -->
    <footer class="bg-light border-top py-4 mb-auto">
        <div class="container text-center">
            <p class="text-muted small mb-0">© 2025 QuizMaster, Design & Develop by RAJHANS DIGITAL</p>
        </div>
    </footer>


    <!-- Script -->
    <script>
    let totalTime = <?php echo $time_seconds; ?>; // total seconds
    let timerProgress = document.getElementById('timerProgress');
    let timerText = document.getElementById('timerText');
    let quizForm = document.getElementById('quizForm');

    function updateTimer() {
        if (totalTime <= 0) {
            quizForm.submit();
            return;
        }

        let minutes = Math.floor(totalTime / 60);
        let seconds = totalTime % 60;
        timerText.textContent = minutes.toString().padStart(2, '0') + ":" + seconds.toString().padStart(2, '0');

        // Update progress bar
        let percent = (totalTime / <?php echo $time_seconds; ?>) * 100;
        timerProgress.style.width = percent + "%";

        totalTime--;
    }

    updateTimer(); // ← run once at load
    setInterval(updateTimer, 1000);
    </script>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>