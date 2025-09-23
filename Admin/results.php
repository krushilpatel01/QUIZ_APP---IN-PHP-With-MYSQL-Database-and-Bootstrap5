<?php

session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}
include 'includes/config.php';
$page_title = "Main Dashboard";
include 'includes/header.php';

// Fetch all rounds ordered
$rounds_result = mysqli_query($conn, "SELECT DISTINCT round FROM quizzes ORDER BY round ASC");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Quiz Results - QuizMaster</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@400;500;700;900&display=swap" rel="stylesheet">
    <style>
    body {
        font-family: 'Work Sans', sans-serif;
    }

    .badge-eligible {
        background-color: #198754;
    }

    .badge-not-eligible {
        background-color: #dc3545;
    }
    </style>
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
                </li>
            </ul>
        </nav>

        <!-- Main Sidebar -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <a href="index.php" class="brand-link">
                <span class="brand-text font-weight-light">Quiz Admin</span>
            </a>
            <div class="sidebar">
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview">
                        <li class="nav-item">
                            <a href="index.php" class="nav-link active">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="quizzes.php" class="nav-link">
                                <i class="nav-icon fas fa-list"></i>
                                <p>Quizzes</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="questions.php" class="nav-link">
                                <i class="nav-icon fas fa-question"></i>
                                <p>Questions</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="users.php" class="nav-link">
                                <i class="nav-icon fas fa-users"></i>
                                <p>Users</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="results.php" class="nav-link">
                                <i class="nav-icon fas fa-users"></i>
                                <p>Result</p>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>


        <!-- Content Wrapper -->
        <div class="content-wrapper p-3">
            <div class="container my-5">
                <h1 class="mb-4">Quiz Results</h1>

                <?php
        while($round_row = mysqli_fetch_assoc($rounds_result)) {
            $round_num = $round_row['round'];
            echo "<h3 class='mb-3'>Round $round_num Results</h3>";

            // Round 3: top 3 users only
            if($round_num == 3){
                $results = mysqli_query($conn, "
                    SELECT u.name, r.score, r.pass_fail, q.max_mcq
                    FROM result r
                    JOIN users u ON u.id = r.user_id
                    JOIN quizzes q ON q.id = r.quiz_id
                    WHERE q.round = $round_num
                    ORDER BY r.score DESC, r.taken_at ASC
                    LIMIT 100
                ");
            } else {
                $results = mysqli_query($conn, "
                    SELECT u.name, r.score, r.pass_fail, q.max_mcq
                    FROM result r
                    JOIN users u ON u.id = r.user_id
                    JOIN quizzes q ON q.id = r.quiz_id
                    WHERE q.round = $round_num
                    ORDER BY r.score DESC, r.taken_at ASC
                ");
            }

            if(mysqli_num_rows($results) > 0){
                echo "<table class='table table-bordered table-striped'>";
                echo "<thead class='table-dark'>
                        <tr>
                            <th>Rank</th>
                            <th>User Name</th>
                            <th>Score</th>
                            <th>Status</th>
                            <th>Eligibility</th>
                        </tr>
                      </thead>";
                echo "<tbody>";

                $rank = 1;
                while($r = mysqli_fetch_assoc($results)){
                    $status_text = ($r['pass_fail'] === 'pass') ? 'Pass' : 'Fail';
                    $eligibility_text = ($r['pass_fail'] === 'pass') ? 'Eligible for Next Round' : 'Not Eligible';
                    $badge_class = ($r['pass_fail'] === 'pass') ? 'badge-eligible' : 'badge-not-eligible';

                    echo "<tr>
                            <td>{$rank}</td>
                            <td>".htmlspecialchars($r['name'])."</td>
                            <td>{$r['score']} / {$r['max_mcq']}</td>
                            <td><span class='badge {$badge_class}'>{$status_text}</span></td>
                            <td><span class='badge {$badge_class}'>{$eligibility_text}</span></td>
                          </tr>";
                    $rank++;
                }

                echo "</tbody></table>";
            } else {
                echo "<p>No results for Round $round_num yet.</p>";
            }
        }
        ?>
            </div>

        </div>

        <?php
include 'includes/footer.php';
?>