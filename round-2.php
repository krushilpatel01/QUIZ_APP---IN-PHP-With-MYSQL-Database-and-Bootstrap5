<?php
include 'includes/config.php';

$quiz_id = isset($_GET['quiz_id']) ? intval($_GET['quiz_id']) : 0;

// get current quiz round
$currentQuiz = mysqli_fetch_assoc(mysqli_query($conn, "SELECT round FROM quizzes WHERE id=$quiz_id"));
$currentRound = $currentQuiz ? intval($currentQuiz['round']) : 1;

// force next round to be round 2 only
$nextRound = 2;

// fetch a quiz from round 2 only
$nextQuiz = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM quizzes WHERE round=$nextRound LIMIT 1"));

if ($nextQuiz) {
    // redirect user to take-quiz page with next round quiz id
    header("Location: take-quiz.php?id=" . $nextQuiz['id']);
    exit();
} else {
    // no round 2 quiz exists
    header("Location: no-more-rounds.php");
    exit();
}
?>