<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}
include 'includes/config.php';
$page_title = "Main Dashboard";
include 'includes/header.php';
?>

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
            <h2><?php echo $page_title; ?></h2>

            <div class="row mt-4">

                <?php
            // Total Users
            $total_users = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM users"))['total'];

            // Total Quizzes
            $total_quizzes = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM quizzes"))['total'];

            // Total Rounds (distinct rounds in quizzes)
            $total_rounds = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(DISTINCT round) AS total FROM quizzes"))['total'];
            ?>

                <div class="col-md-4">
                    <div class="card bg-primary text-white mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Total Users</h5>
                            <p class="card-text display-4"><?php echo $total_users; ?></p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card bg-success text-white mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Total Quizzes</h5>
                            <p class="card-text display-4"><?php echo $total_quizzes; ?></p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card bg-warning text-dark mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Total Rounds</h5>
                            <p class="card-text display-4"><?php echo $total_rounds; ?></p>
                        </div>
                    </div>
                </div>
            </div>

        </div> <!-- /.content-wrapper -->

        <?php
include 'includes/footer.php';
?>