<?php
session_start();
include 'includes/db_conn.php';

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
            echo 'Image deleted successfully.';
        } else {
            echo 'Error: Unable to delete image.';
        }
        exit(); // Exit the script after deleting the image
    }

    if (isset($_FILES['file'])) {
        $file = $_FILES['file'];
        $fileName = $file['name'];
        $fileTmpName = $file['tmp_name'];
        $fileError = $file['error'];
        $fileSize = $file['size'];
        
        // Restrict file types (e.g., only allow JPG, PNG, etc.)
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $fileType = mime_content_type($fileTmpName);

        if (!in_array($fileType, $allowedTypes)) {
            echo 'Error: Only JPG, PNG, and GIF files are allowed.';
            exit();
        }

        if ($fileError === UPLOAD_ERR_OK) {
            $uploadDirectory = __DIR__ . '/uploads/'; // Use absolute path for better security

            if (!is_dir($uploadDirectory)) {
                mkdir($uploadDirectory, 0777, true); // Ensure the uploads directory exists
            }

            // Generate a unique filename
            $prefix = 'SHESH-' . $name . '-';
            $newFileName = uniqid($prefix, true) . '.' . pathinfo($fileName, PATHINFO_EXTENSION);
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
                $stmt->bind_param("si", $newFileName, $userID);
                $stmt->execute();
            } else {
                // Handle the case where user does not exist
                echo 'Error: User not found.';
                exit(); // Exit script
            }

            // Move uploaded file to target directory
            if (move_uploaded_file($fileTmpName, $targetPath)) {
                echo 'uploads/' . $newFileName; // Return the path of the uploaded file
            } else {
                echo 'Error: Unable to move the file.';
            }
        } else {
            echo 'Error: File upload failed.';
        }
    }
}
?>
