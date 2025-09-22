<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}
include 'includes/config.php';
$page_title = "Welcome To Quizzes Page";
include 'includes/header.php';

// Handle add quiz
if (isset($_POST['add_quiz'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    // Handle image upload
    $image = '';
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "uploads/"; // create this folder in your project root
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $image_name = time() . '_' . basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image_name;
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image = $image_name;
        }
    }

    mysqli_query($conn, "INSERT INTO quizzes (title, description, image) 
                         VALUES ('$title', '$description', '$image')");
    header("Location: quizzes.php");
    exit();
}

// Handle delete quiz
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    // Optional: delete image from folder too
    $img_q = mysqli_query($conn, "SELECT image FROM quizzes WHERE id=$id");
    $img_row = mysqli_fetch_assoc($img_q);
    if (!empty($img_row['image']) && file_exists("uploads/".$img_row['image'])) {
        unlink("uploads/".$img_row['image']);
    }
    mysqli_query($conn, "DELETE FROM quizzes WHERE id=$id");
    header("Location: quizzes.php");
    exit();
}

// Fetch quizzes
$quizzes = mysqli_query($conn, "SELECT * FROM quizzes ORDER BY id DESC");
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
                            <a href="index.php" class="nav-link">
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
                    </ul>
                </nav>
            </div>
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper p-3">
            <h1 class="mb-5"><?php echo $page_title; ?></h1>
            <section class="content">
                <div class="container-fluid">

                    <!-- Add Quiz Form -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h3 class="card-title">Add New Quiz</h3>
                        </div>
                        <div class="card-body">
                            <form method="post" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label>Quiz Title</label>
                                    <input type="text" name="title" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label>Description</label>
                                    <textarea name="description" class="form-control" rows="3" required></textarea>
                                </div>
                                <div class="mb-3">
                                    <label>Quiz Image</label>
                                    <input type="file" name="image" class="form-control">
                                </div>
                                <button type="submit" name="add_quiz" class="btn btn-primary">Add Quiz</button>
                            </form>
                        </div>
                    </div>

                    <!-- Quizzes Table -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">All Quizzes</h3>
                        </div>
                        <div class="card-body table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Image</th>
                                        <th>Title</th>
                                        <th>Description</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($row = mysqli_fetch_assoc($quizzes)) { ?>
                                    <tr>
                                        <td><?php echo $row['id']; ?></td>
                                        <td>
                                            <?php if(!empty($row['image'])) { ?>
                                            <img src="uploads/<?php echo $row['image']; ?>" width="60" height="60"
                                                style="object-fit:cover;">
                                            <?php } else { ?>
                                            <span class="text-muted">No image</span>
                                            <?php } ?>
                                        </td>
                                        <td><?php echo $row['title']; ?></td>
                                        <td><?php echo $row['description']; ?></td>
                                        <td>
                                            <a href="questions.php?quiz_id=<?php echo $row['id']; ?>"
                                                class="btn btn-sm btn-success mb-3">Manage Questions</a>
                                            <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger w-100"
                                                onclick="return confirm('Delete this quiz?')">Delete</a>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </section>
        </div>

        <?php

include 'includes/footer.php';
?>