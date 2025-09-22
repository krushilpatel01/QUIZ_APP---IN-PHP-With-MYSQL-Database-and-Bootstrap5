<?php
include 'includes/config.php';

// fetch latest quizzes
$quizzes = mysqli_query($conn, "SELECT * FROM quizzes ORDER BY id ASC LIMIT 3");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Quiz Design By RAJHANS DIGITAL</title>
    <link href="data:image/x-icon;base64," rel="icon" type="image/x-icon" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@400;500;700;900&display=swap" rel="stylesheet">
    <style>
    body {
        font-family: 'Work Sans', sans-serif;
    }

    .hero {
        position: relative;
        background-size: cover;
        background-position: center;
        min-height: 100vh;
        color: #fff;
    }

    .hero::before {
        content: "";
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, .8);
    }

    .hero-content {
        position: relative;
        z-index: 2;
    }

    .quiz-card-img {
        width: 100%;
        aspect-ratio: 1/1;
        background-size: cover;
        background-position: center;
        border-radius: .5rem;
    }
    </style>
</head>

<body class="bg-light text-dark">

    <!-- Header -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
        <div class="container d-flex justify-content-between">
            <a class="navbar-brand d-flex align-items-center gap-2" href="#">
                <svg class="text-primary" width="24" height="24" fill="currentColor" viewBox="0 0 48 48">
                    <path d="M6 6H42L36 24L42 42H6L12 24L6 6Z"></path>
                </svg>
                <span class="fw-bold">QuizMaster</span>
            </a>

                <div class="d-flex gap-2">
                    <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#loginModal">Log
                        In</button>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#registerModal">Sign
                        Up</button>
                </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero d-flex align-items-center justify-content-center text-center"
        style="background-image:url('../Admin/uploads/hero-banner.jpg'); background-position:center; background-size: cover; background-repeat: no-repeat;">
        <div class="container hero-content">
            <h1 class="display-4 fw-bold">Test Your Knowledge</h1>
            <p class="lead mb-4">Explore a wide range of quizzes on various topics. Join our community and compete with
                others on the leaderboard.</p>
            <a href="#" class="btn btn-primary btn-lg">Take Your Quiz</a>
        </div>
    </section>

    <div class="container my-5">

        <section class="mb-5">
            <h2 class="h3 fw-bold mb-4">Popular Quizzes</h2>
            <div class="row g-3">
                <?php while($quiz = mysqli_fetch_assoc($quizzes)) { ?>
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card h-100">
                        <div class="quiz-card-img card-img-top"
                            style="background-image: url('../Admin/uploads/<?php echo !empty($quiz['image']) ? $quiz['image'] : "default.jpg"; ?>'); height:200px; background-size:cover; background-position:center;">
                        </div>
                        <div class="card-body">
                            <h2 class="fw-bold mb-1"><?php echo htmlspecialchars($quiz['title']); ?></h2>
                            <p class="text-muted small"><?php echo htmlspecialchars($quiz['description']); ?></p>
                            <a href="quiz.php?id=<?php echo $quiz['id']; ?>" class="btn btn-sm btn-primary mt-2">Take
                                Quiz</a>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </section>



    </div>

    <!-- Footer -->
    <footer class="bg-light border-top py-4 mt-auto">
        <div class="container text-center">
            <div class="mb-3">
                <a class="text-muted small mx-2" href="#">Contact</a>
                <a class="text-muted small mx-2" href="#">Privacy Policy</a>
                <a class="text-muted small mx-2" href="#">Terms of Service</a>
            </div>
            <p class="text-muted small mb-0">© 2025 QuizMaster, Design & Develop by RAJHANS DIGITAL - All rights
                reserved.</p>
        </div>
    </footer>

    <!-- Login Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Log In</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="login.php" method="post">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-between">
                        <span class="small">Don’t have an account? <a href="#" data-bs-toggle="modal"
                                data-bs-target="#registerModal" data-bs-dismiss="modal">Register</a></span>
                        <button type="submit" class="btn btn-primary">Log In</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Register Modal -->
    <div class="modal fade" id="registerModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Sign Up</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="register.php" method="post">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-between">
                        <span class="small">Already have an account? <a href="#" data-bs-toggle="modal"
                                data-bs-target="#loginModal" data-bs-dismiss="modal">Log In</a></span>
                        <button type="submit" class="btn btn-primary">Sign Up</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>