<?php
session_start();

$score   = isset($_GET['score']) ? intval($_GET['score']) : 0;
$total   = isset($_GET['total']) ? intval($_GET['total']) : 0;
$quiz_id = isset($_GET['quiz_id']) ? intval($_GET['quiz_id']) : 0;

// Pass if score >= 50% of total questions
$pass_percentage = 50; 
$percentage = ($total > 0) ? ($score / $total * 100) : 0;
$status = ($percentage >= $pass_percentage) ? 'pass' : 'fail';
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Quiz Complete - Thank You</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    body {
        font-family: 'Work Sans', sans-serif;
        background: #f8f9fa;
    }

    .card {
        border-radius: 1rem;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }

    .btn-primary {
        background: #0d6efd;
        border: none;
    }

    .btn-success {
        background: #198754;
        border: none;
    }

    .btn-warning {
        background: #ffc107;
        border: none;
        color: #212529;
    }

    .status-pass {
        color: #198754;
    }

    .status-fail {
        color: #198754;
    }
    </style>

    <?php if ($status === 'pass' && $quiz_id) { ?>
    <!-- Auto redirect to next round after 5 seconds -->
    <meta http-equiv="refresh" content="5;url=take-quiz.php?id=<?php echo $quiz_id; ?>">
    <?php } ?>
</head>

<body>

    <div class="container my-5 d-flex justify-content-center">
        <div class="card p-5 text-center" style="max-width:500px;">
            <h1 class="mb-4">
                <?php echo ($status === 'pass') ? '🎉 Well Done!' : '😔 Quiz Completed'; ?>
            </h1>

            <h3 class="mb-4">Your Score: <?php echo $score; ?> / <?php echo $total; ?></h3>

            <p class="mb-4 <?php echo ($status === 'pass') ? 'status-pass' : 'status-fail'; ?>">
                <?php
    if ($status === 'pass') {
        echo "Great job! You passed this round and can move to the next.";
    } else {
        echo "You did not pass this round. Try again!";
    }
    ?>
            </p>

            <div class="d-flex justify-content-center gap-2">
                <a href="index.php" class="btn btn-primary">Back to Home</a>
            </div>

        </div>
    </div>

</body>

</html>