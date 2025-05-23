<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Form</title>
    <!-- Add SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">
    <!-- Add Google Fonts for Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>
<body>

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

$showAlert = false; // Initialize variable to control SweetAlert display

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $full_name = $_POST['full_name'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $email = $_POST['email'];
    $years_experience = $_POST['years_experience'];
    $position = $_POST['position'];
    $how_did_you_know = $_POST['how_did_you_know'];
    $previous_experiences = $_POST['previous_experiences'];
    $unique_trait = $_POST['unique_trait'];
    $interest_in_role = $_POST['interest_in_role'];

    // Validate email
    if (!validateEmail($email)) {
        echo "<script>alert('Enter a valid Email Address');</script>";
        exit();
    }

    // Define the upload directory
    $resumeDirectory = 'Resume/' . preg_replace('/[^A-Za-z0-9]/', '_', $full_name);

    // Create the directory if it doesn't exist
    if (!is_dir($resumeDirectory)) {
        mkdir($resumeDirectory, 0777, true);
    }

    // Handle file upload for resume
    if (isset($_FILES['resume_upload']) && $_FILES['resume_upload']['error'] == 0) {
        // Set the target file path
        $resumePath = $resumeDirectory . '/' . basename($_FILES['resume_upload']['name']);

        // Move the uploaded file to the target directory
        if (move_uploaded_file($_FILES['resume_upload']['tmp_name'], $resumePath)) {
            // Resume uploaded successfully
        } else {
            echo "<script>alert('Failed to upload resume');</script>";
            exit();
        }
    } else {
        echo "<script>alert('Error uploading resume');</script>";
        exit();
    }

    // Database connection
    $servername = "localhost";
    $username = "UciIndia01";
    $password = "UciIndia@803";
    $dbname = "uciIndia";
    
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO applicant_details (full_name, age, gender, experience, email, position, how_did_you_know, previous_experiences, unique_trait, interest_in_role, resume_upload) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sisssssssss", $full_name, $age, $gender, $years_experience, $email, $position, $how_did_you_know, $previous_experiences, $unique_trait, $interest_in_role, $resumePath);
    // Execute and check
    if ($stmt->execute()) {
        $showAlert = true; // Set variable to show SweetAlert
    } else {
        echo "<script>alert('Error submitting application: " . addslashes($stmt->error) . "');</script>";
    }

    // Close connections
    $stmt->close();
    $conn->close();
}
?>

<!-- Add SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.all.min.js"></script>

<?php if ($showAlert): ?>
<script>
    Swal.fire({
        title: 'Application Submitted',
        text: 'We have received your application. Congratulations on taking the first step in your application process! We appreciate your interest in our company and will carefully consider your qualifications. Stay tuned!',
        icon: 'success',
        confirmButtonText: 'Close'
    }).then(() => {
        // Redirect to index.html when the modal is closed
        window.location.href = 'index.html';
    });
</script>
<?php endif; ?>

</body>
</html>