<?php
include 'includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $quiz_id = intval($_POST['quiz_id']);
    $answers = $_POST['answer'] ?? [];

    // Fetch questions
    $questions = mysqli_query($conn, "SELECT * FROM questions WHERE quiz_id=$quiz_id");
    $score = 0;
    $total = mysqli_num_rows($questions);

    while ($q = mysqli_fetch_assoc($questions)) {
        $qid = $q['id'];
        if (isset($answers[$qid]) && $answers[$qid] === $q['correct_option']) {
            $score++;
        }
    }

    // Optional: Store result in database (if you have a results table)
    // mysqli_query($conn, "INSERT INTO results (quiz_id, user_id, score, total) VALUES ($quiz_id, $user_id, $score, $total)");

    // Redirect to thank-you page with score
    header("Location: thank-you.php?score=$score&total=$total");
    exit();
}
?>
