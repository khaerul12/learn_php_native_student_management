<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'student_management');

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Fetch the student record to edit
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $student = $result->fetch_assoc();
}

// Handle updating the student record
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_student'])) {
    $name = trim($_POST['name']);
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $age = (int)$_POST['age'];

    if (empty($name) || empty($email) || $age <= 0) {
        echo "<div class='alert alert-danger'>Please fill in all fields correctly.</div>";
    } else {
        $stmt = $conn->prepare("UPDATE students SET name = ?, email = ?, age = ? WHERE id = ?");
        $stmt->bind_param("ssii", $name, $email, $age, $id);
        $stmt->execute();
        header("Location: index.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Student Management</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="index.php">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="logout.php">Logout</a>
            </li>
        </ul>
    </div>
</nav>
<div class="container mt-5">
    <h2>Edit Student</h2>
    <form method="POST">
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($student['name']); ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($student['email']); ?>" required>
        </div>
        <div class="form-group">
            <label for="age">Age</label>
            <input type="number" class="form-control" name="age" value="<?php echo htmlspecialchars($student['age']); ?>" required>
        </div>
        <button type="submit" name="update_student" class="btn btn-primary">Update Student</button>
    </form>
    <p><a href="index.php" class="btn btn-secondary">Back to Student List</a></p>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>