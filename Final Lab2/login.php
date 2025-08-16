<?php
// Function to clean input safely
function clean_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Get form inputs safely
$name = clean_input($_POST['name'] ?? '');
$email = clean_input($_POST['email'] ?? '');
$comment = clean_input($_POST['comment'] ?? '');
$gender = $_POST['gender'] ?? '';
$file = $_FILES['file'] ?? null;

$errors = [];

// Validate name
if (empty($name)) {
    $errors[] = "Name is required.";
}

// Validate email
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "A valid email is required.";
}

// Validate comment
if (empty($comment)) {
    $errors[] = "Comment cannot be empty.";
}

// Validate gender
if (empty($gender)) {
    $errors[] = "Please select your gender.";
}

// Validate file
if ($file && $file['error'] == 0) {
    $allowed_types = ['image/jpeg', 'image/png', 'application/pdf'];
    if (!in_array($file['type'], $allowed_types)) {
        $errors[] = "Only JPG, PNG, or PDF files are allowed.";
    }
    if ($file['size'] > 2 * 1024 * 1024) { // 2MB
        $errors[] = "File must be less than 2MB.";
    }
} else {
    $errors[] = "Please upload a file.";
}

// Show errors or success
if (!empty($errors)) {
    echo "<h3 style='color:red;'>Form Errors:</h3><ul>";
    foreach ($errors as $error) {
        echo "<li>" . $error . "</li>";
    }
    echo "</ul><a href='form.html'>Go Back</a>";
} else {
    echo "<h2>Form Submitted Successfully ✅</h2>";
    echo "<p><strong>Name:</strong> $name</p>";
    echo "<p><strong>Email:</strong> $email</p>";
    echo "<p><strong>Comment:</strong> $comment</p>";
    echo "<p><strong>Gender:</strong> $gender</p>";

    // Save file in uploads/ folder
    $upload_dir = "uploads/";
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    $file_path = $upload_dir . basename($file['name']);
    move_uploaded_file($file['tmp_name'], $file_path);

    echo "<p><strong>File Uploaded:</strong> " . htmlspecialchars($file['name']) . "</p>";
}
?>
