<?php
session_start();
include 'includes/db_conn.php';

// Function to get the base URL dynamically
function getBaseUrl() {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
    $host = $_SERVER['HTTP_HOST'];
    return $protocol . "://" . $host;
}

// Set the upload directory relative to the script
$uploadDirectory = __DIR__ . '/uploads/';

// Ensure the upload directory exists
if (!file_exists($uploadDirectory)) {
    mkdir($uploadDirectory, 0755, true);
}

if (isset($_SESSION['user_name'])) {
    $name = $_SESSION['user_name'];

    // Function to delete the user image
    function deleteUserImage($conn, $name)
    {
        $update = "UPDATE user_form SET imgpath = 'uploads/user1.png' WHERE name = ?";
        $stmt = $conn->prepare($update);
        $stmt->bind_param("s", $name);
        return $stmt->execute();
    }

    if (isset($_POST['delete_image'])) {
        // Call the function to delete the image
        if (deleteUserImage($conn, $name)) {
            echo json_encode(['success' => true, 'message' => 'Image deleted successfully.']);
        } else {
            echo json_encode(['error' => 'Unable to delete image.']);
        }
        exit(); // Exit the script after deleting the image
    }

    if (isset($_FILES['file'])) {
        $file = $_FILES['file'];
        $fileName = $file['name'];
        $fileTmpName = $file['tmp_name'];
        $fileError = $file['error'];

        if ($fileError === UPLOAD_ERR_OK) {
            // Generate a unique filename
            $prefix = 'SHESH-' . $name . '-';
            $newFileName = uniqid($prefix, true) . '_' . $fileName;
            $relativePath = 'uploads/' . $newFileName;
            $targetPath = $uploadDirectory . $newFileName;

            // Get user's ID from user_form table
            $select = "SELECT id FROM user_form WHERE name = ?";
            $stmt = $conn->prepare($select);
            $stmt->bind_param("s", $name);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $userID = $row['id'];

                // Update or insert image path into user_form table
                $update = "UPDATE user_form SET imgpath = ? WHERE id = ?";
                $stmt = $conn->prepare($update);
                $stmt->bind_param("si", $relativePath, $userID);
                $stmt->execute();

                // Move uploaded file to target directory
                if (move_uploaded_file($fileTmpName, $targetPath)) {
                    $imageUrl = getBaseUrl() . '/' . $relativePath;
                    echo json_encode(['success' => true, 'imageUrl' => $imageUrl]);
                } else {
                    echo json_encode(['error' => 'Failed to move uploaded file.']);
                }
            } else {
                // Handle the case where user does not exist
                echo json_encode(['error' => 'User not found.']);
            }
        } else {
            echo json_encode(['error' => 'File upload failed.']);
        }
    }
} else {
    echo json_encode(['error' => 'User not logged in.']);
}
?>
