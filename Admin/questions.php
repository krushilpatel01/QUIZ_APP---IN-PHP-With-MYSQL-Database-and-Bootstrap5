<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}
include 'includes/config.php';
$page_title = "Welcome To Question Page";
include 'includes/header.php';

// Get quiz_id
$quiz_id = isset($_GET['quiz_id']) ? intval($_GET['quiz_id']) : 0;

// Handle add question
if (isset($_POST['add_question'])) {
    $question = mysqli_real_escape_string($conn, $_POST['question']);
    $option_a = mysqli_real_escape_string($conn, $_POST['option_a']);
    $option_b = mysqli_real_escape_string($conn, $_POST['option_b']);
    $option_c = mysqli_real_escape_string($conn, $_POST['option_c']);
    $option_d = mysqli_real_escape_string($conn, $_POST['option_d']);
    $correct_option = $_POST['correct_option'];

    mysqli_query($conn, "INSERT INTO questions 
        (quiz_id, question, option_a, option_b, option_c, option_d, correct_option) 
        VALUES 
        ($quiz_id, '$question', '$option_a', '$option_b', '$option_c', '$option_d', '$correct_option')");
    header("Location: questions.php?quiz_id=$quiz_id");
}

// Handle delete question
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM questions WHERE id=$id");
    header("Location: questions.php?quiz_id=$quiz_id");
}

// Fetch questions
$questions = mysqli_query($conn, "SELECT * FROM questions WHERE quiz_id=$quiz_id ORDER BY id DESC");

// Fetch quiz title
$quiz_title = mysqli_fetch_assoc(mysqli_query($conn, "SELECT title FROM quizzes WHERE id=$quiz_id"))['title'];
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

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper p-3">
            <h1 class="mb-5">Questions for Quiz: <?php echo htmlspecialchars($quiz_title); ?></h1>

            <section class="content">
                <div class="container-fluid">

                    <!-- Add Question Form -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h3 class="card-title">Add New Question</h3>
                        </div>
                        <div class="card-body">
                            <form method="post">
                                <div class="mb-3">
                                    <label>Question</label>
                                    <textarea name="question" class="form-control" rows="2" required></textarea>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label>Option A</label>
                                        <input type="text" name="option_a" class="form-control" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Option B</label>
                                        <input type="text" name="option_b" class="form-control" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Option C</label>
                                        <input type="text" name="option_c" class="form-control" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Option D</label>
                                        <input type="text" name="option_d" class="form-control" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label>Correct Option</label>
                                    <select name="correct_option" class="form-control" required>
                                        <option value="A">A</option>
                                        <option value="B">B</option>
                                        <option value="C">C</option>
                                        <option value="D">D</option>
                                    </select>
                                </div>
                                <button type="submit" name="add_question" class="btn btn-primary">Add Question</button>
                            </form>
                        </div>
                    </div>

                    <!-- Questions Table -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">All Questions</h3>
                        </div>
                        <div class="card-body table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Question</th>
                                        <th>A</th>
                                        <th>B</th>
                                        <th>C</th>
                                        <th>D</th>
                                        <th>Correct</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($row = mysqli_fetch_assoc($questions)) { ?>
                                    <tr>
                                        <td><?php echo $row['id']; ?></td>
                                        <td><?php echo $row['question']; ?></td>
                                        <td><?php echo $row['option_a']; ?></td>
                                        <td><?php echo $row['option_b']; ?></td>
                                        <td><?php echo $row['option_c']; ?></td>
                                        <td><?php echo $row['option_d']; ?></td>
                                        <td><?php echo $row['correct_option']; ?></td>
                                        <td>
                                            <a href="?delete=<?php echo $row['id']; ?>&quiz_id=<?php echo $quiz_id; ?>"
                                                class="btn btn-sm btn-danger"
                                                onclick="return confirm('Delete this question?')">Delete</a>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </section>

            <?php

include 'includes/footer.php';
?>