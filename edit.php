<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'student_management');

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if ($_SESSION['role'] !== 'user') {
    echo "Access denied!";
    exit();
}

// Handle update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $age = $_POST['age'];

    $stmt = $conn->prepare("UPDATE students SET name = ?, email = ?, age = ? WHERE id = ?");
    $stmt->bind_param("ssii", $name, $email, $age, $id);
    $stmt->execute();
    header("Location: index.php");
    exit();
}

// Get student data for editing
$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Student</title>
</head>
<body>
    <h1>Edit Student</h1>
    <form method="POST">
        <input type="hidden" name="id" value="<?php echo $student['id']; ?>">
        Name: <input type="text" name="name" value="<?php echo $student['name']; ?>" required>
        Email: <input type="email" name="email" value="<?php echo $student['email']; ?>" required>
        Age: <input type="number" name="age" value="<?php echo $student['age']; ?>" required>
        <button type="submit">Update Student</button>
    </form>
    <p><a href="index.php">Back to Student List</a></p>
</body>
</html>

<?php
$conn->close();
?>