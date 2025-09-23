<?php
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

include 'includes/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit();
}

$quiz_id = intval($_POST['quiz_id']);
$answers = $_POST['answer'] ?? [];

// Get quiz info
$quiz_sql = mysqli_query($conn, "SELECT * FROM quizzes WHERE id=$quiz_id");
$quiz = mysqli_fetch_assoc($quiz_sql);
$current_round = intval($quiz['round']);
$next_round = $current_round + 1;

// Fetch questions and calculate score
$qres = mysqli_query($conn, "SELECT id, correct_option FROM questions WHERE quiz_id=$quiz_id");
$total = mysqli_num_rows($qres);
$score = 0;

while ($q = mysqli_fetch_assoc($qres)) {
    $qid = $q['id'];
    $correct = strtoupper($q['correct_option']);
    $selected = isset($answers[$qid]) ? strtoupper($answers[$qid]) : '';
    if ($selected === $correct) {
        $score++;
    }
}

// Determine pass/fail
$pass_percentage = 50;
$status_passfail = ($total > 0 && ($score / $total * 100) >= $pass_percentage) ? 'pass' : 'fail';

// Store result in database including pass/fail
$sql = "INSERT INTO result (user_id, quiz_id, score, pass_fail) VALUES ($user_id, $quiz_id, $score, '$status_passfail')";
mysqli_query($conn, $sql) or die(mysqli_error($conn));

// Determine qualification based on round
$qualify_limit = 0;
if ($current_round == 1) $qualify_limit = 25;
elseif ($current_round == 2) $qualify_limit = 15;
elseif ($current_round == 3) $qualify_limit = 3; // finalists

// Get top N users for current quiz
$top_users = mysqli_query($conn, "SELECT user_id FROM result WHERE quiz_id=$quiz_id ORDER BY score DESC, taken_at ASC LIMIT $qualify_limit");

// Check if current user qualifies
$qualified = false;
while ($row = mysqli_fetch_assoc($top_users)) {
    if ($row['user_id'] == $user_id) {
        $qualified = true;
        break;
    }
}

// Redirect based on round and qualification
if ($current_round < 3 && $qualified) {
    // Get next quiz id
    $next_quiz = mysqli_query($conn, "SELECT id FROM quizzes WHERE round=$next_round LIMIT 1");
    $next_quiz_id = mysqli_fetch_assoc($next_quiz)['id'];
    header("Location: thank-you.php?score=$score&total=$total&status=pass&quiz_id=$next_quiz_id");
    exit();
} else {
    // Last round or not qualified
    if ($current_round == 3 && $qualified) {
        $status = 'finalist'; // top 3
    } elseif ($qualified) {
        $status = 'pass';
    } else {
        $status = 'fail';
    }
    header("Location: thank-you.php?score=$score&total=$total&status=$status&quiz_id=$quiz_id");
    exit();
}
?>
