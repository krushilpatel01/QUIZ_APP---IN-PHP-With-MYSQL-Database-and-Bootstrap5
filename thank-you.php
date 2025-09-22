<?php
$score = isset($_GET['score']) ? intval($_GET['score']) : 0;
$total = isset($_GET['total']) ? intval($_GET['total']) : 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Quiz Complete - Thank You</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light text-dark">

    <div class="container my-5 text-center">
        <div class="card p-5">
            <h1 class="mb-4">🎉 Thank You for Completing the Quiz!</h1>
            <h3 class="mb-4">Your Score: <?php echo $score; ?> / <?php echo $total; ?></h3>
            <p class="mb-4">Great job! You can proceed to the next round or explore other quizzes.</p>
            <a href="index.php" class="btn btn-primary mb-2">Back to Home</a>
            <a href="index.php" class="btn btn-success">Next Round</a>
        </div>
    </div>

</body>

</html>