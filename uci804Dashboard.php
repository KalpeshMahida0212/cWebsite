<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

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

// Query to fetch applicant details
$sql = "SELECT * FROM applicant_details ORDER BY Timestamp DESC";
$result = $conn->query($sql);

// Check if the query was successful
if (!$result) {
    die("Error: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applicant Dashboard</title>
    <!-- Importing Poppins Font from Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f8ff; /* Light blue background */
            font-family: 'Poppins', sans-serif; /* Applying Poppins font to the body */
        }
        .container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #2b537c;
            font-weight: semibold; /* Making the heading bold */
        }
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #e6f7ff;
        }
        .table-striped tbody tr:nth-of-type(even) {
            background-color: #ffffff;
        }
        .table thead {
            background-color: #007bff;
            color: #ffffff;
        }
        .btn-primary {
            background-color: #2b537c;
            border-color: #2b537c;
        }
        .modal-content {
            background-color: #f0f8ff;
        }
        .btn-info {
            background-color: #17a2b8;
            border: none;
        }
        .btn-close {
            background-color: #007bff;
        }
        /* Logo Styling */
        .logo {
            max-width: 150px;
            margin: 20px 0;
        }
    </style>
</head>
<body>

<!-- Logo Section -->
<div class="text-center">
    <img src="img/Uci_Logo.png" alt="UCI Logo" class="logo">
    
    <h2 class="text-center mb-4">Applicant Dashboard</h2>
</div>

<div class="container mt-5">
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Full Name</th>
                    <th>Gender</th>
                    <th>Age</th>
                    <th>Email</th>
                    <th>Experience</th>
                    <th>Position</th>
                    <th>Resume</th>
                    <th>Actions</th>
                    <th>Application Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php $serial = 1; ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $serial++; ?></td>
                            <td><?php echo !empty($row['full_name']) ? htmlspecialchars($row['full_name']) : ''; ?></td>
                            <td><?php echo !empty($row['gender']) ? htmlspecialchars($row['gender']) : ''; ?></td>
                            <td><?php echo !empty($row['age']) ? htmlspecialchars($row['age']) : ''; ?></td>
                            <td><?php echo !empty($row['email']) ? htmlspecialchars($row['email']) : ''; ?></td>
                            <td><?php echo !empty($row['experience']) ? htmlspecialchars($row['experience']) : ''; ?></td>
                            <td><?php echo !empty($row['position']) ? htmlspecialchars($row['position']) : ''; ?></td>
                            <td>
                                <?php if (!empty($row['resume_upload'])): ?>
                                    <a href="<?php echo htmlspecialchars($row['resume_upload']); ?>" target="_blank" class="btn btn-primary btn-sm">View</a>
                                    <a href="<?php echo htmlspecialchars($row['resume_upload']); ?>" download class="btn btn-secondary btn-sm">Download</a>
                                <?php else: ?>
                                    No Resume Uploaded
                                <?php endif; ?>
                            </td>
                            <td>
                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#viewModal<?php echo $row['id']; ?>">
                                    View Application
                                </button>
                            </td>
                            <td>
                                <?php 
                                // Truncate the last 8 characters from the Timestamp
                                $timestamp = htmlspecialchars($row['Timestamp']);
                                echo !empty($timestamp) ? substr($timestamp, 0, strlen($timestamp) - 8) : ''; 
                                ?>
                            </td>
                        </tr>

                        <!-- Modal for application details -->
                        <div class="modal fade" id="viewModal<?php echo $row['id']; ?>" tabindex="-1" aria-labelledby="viewModalLabel<?php echo $row['id']; ?>" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="viewModalLabel<?php echo $row['id']; ?>">Application Details for <?php echo htmlspecialchars($row['full_name']); ?></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p><strong>Full Name:</strong> <?php echo !empty($row['full_name']) ? htmlspecialchars($row['full_name']) : ''; ?></p>
                                        <p><strong>Age:</strong> <?php echo !empty($row['age']) ? htmlspecialchars($row['age']) : ''; ?></p>
                                        <p><strong>Gender:</strong> <?php echo !empty($row['gender']) ? htmlspecialchars($row['gender']) : ''; ?></p>
                                        <p><strong>Email:</strong> <?php echo !empty($row['email']) ? htmlspecialchars($row['email']) : ''; ?></p>
                                        <p><strong>Experience:</strong> <?php echo !empty($row['experience']) ? htmlspecialchars($row['experience']) : ''; ?></p>
                                        <p><strong>Position:</strong> <?php echo !empty($row['position']) ? htmlspecialchars($row['position']) : ''; ?></p>
                                        <p><strong>How did you know about us?:</strong> <?php echo !empty($row['how_did_you_know']) ? htmlspecialchars($row['how_did_you_know']) : ''; ?></p>
                                        <p><strong>Previous Experiences:</strong> <?php echo !empty($row['previous_experiences']) ? htmlspecialchars($row['previous_experiences']) : ''; ?></p>
                                        <p><strong>Unique Trait:</strong> <?php echo !empty($row['unique_trait']) ? htmlspecialchars($row['unique_trait']) : ''; ?></p>
                                        <p><strong>Interest in Role:</strong> <?php echo !empty($row['interest_in_role']) ? htmlspecialchars($row['interest_in_role']) : ''; ?></p>
                                        <p><strong>Timestamp:</strong> <?php echo !empty($row['Timestamp']) ? htmlspecialchars($row['Timestamp']) : ''; ?></p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">No applicants found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php $conn->close(); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>