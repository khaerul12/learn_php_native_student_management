<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'student_management');

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if ($_SESSION['role'] !== 'user') {
    echo "<div class='alert alert-danger'>Access denied!</div>";
    exit();
}

// Handle adding a student
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_student'])) {
    $name = trim($_POST['name']);
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $age = (int)$_POST['age'];

    if (empty($name) || empty($email) || $age <= 0) {
        echo "<div class='alert alert-danger'>Please fill in all fields correctly.</div>";
    } else {
        $stmt = $conn->prepare("INSERT INTO students (name, email, age) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $name, $email, $age);
        $stmt->execute();
        echo "<div class='alert alert-success'>Student added successfully!</div>";
    }
}

// Handle deleting a student
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM students WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

// Fetch all students
$result = $conn->query("SELECT * FROM students");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management</title>
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
    <h2 class="mb-4">Student Management</h2>

    <!-- Add Student Form -->
    <form method="POST" class="mb-4">
        <div class="form-row">
            <div class="col">
                <input type="text" class="form-control" name="name" placeholder="Name" required>
            </div>
            <div class="col">
                <input type="email" class="form-control" name="email" placeholder="Email" required>
            </div>
            <div class="col">
                <input type="number" class="form-control" name="age" placeholder="Age" required>
            </div>
            <div class="col">
                <button type="submit" name="add_student" class="btn btn-primary">Add Student</button>
            </div>
        </div>
    </form>

    <!-- Student List -->
    <h3>Student List</h3>
    <table class="table table-striped">
        <thead class="thead-dark">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Age</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['age']; ?></td>
                    <td>
                        <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?');">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <p><a href="logout.php" class="btn btn-secondary">Logout</a></p>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>