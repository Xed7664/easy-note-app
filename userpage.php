<?php
session_start();
include 'dashboard.php';
include 'includes/db_conn.php'; 

$userID = ""; // Initialize userID
$userName = ""; // Initialize userName
$email = ""; // Initialize email
$userImage = ""; // Initialize userImage variable
$userPass = ""; // Initialize userImage variable

if (isset($_SESSION['user_name'])) {
    $name = $_SESSION['user_name']; 
    
    $select = "SELECT id, name, email, imgpath,password FROM user_form WHERE name = '$name'";
    $result = mysqli_query($conn, $select);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $userID = $row['id']; 
        $userName = $row['name']; 
        $email = $row['email'];
        $userImage = $row['imgpath']; 
         $userPass = $row['password']; 
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" type="text/css" href="css/userpage.css">
    <style>
    body, html {
    margin: 0;
    padding: 0;
    height: 100%; /* Set the height of the body and html elements to 100% */
}

#mySidebar {
    height: 100%; /* Set the height of the sidebar to 100% */
}

#main {
    height: 100%; /* Set the height of the user page content to 100% */
}


.user-container .user-label{
    margin-top: 0;
}
.user-container {
    display: flex;
    flex-direction: column;
    align-items: flex-start; /* Align items to the left */
    padding: 20px;
    margin: 0;
    margin-top: 0; /* Ensure no margin at the top */
}
.user-details h1 {
    font-size: 24px; /* Adjust the font size as needed */
}
        .user-label {
            font-size: 14px;
            margin-bottom: 10px;
        }
        .user-details-container {
            display: flex;
            flex-direction: row;
            flex-wrap: nowrap;
            justify-content: space-between;
            width: 100%;
            background-color:transparent;
            border-bottom: 1px solid #ccc;
            padding: 0px 0px 16px;
            height: 150px; /* Adjust the height as needed */
            align-items: center; /* Center the content vertically */
}

        .user-details {
            display: flex;
            width: inherit;
            padding: 0px 0px 16px; 
            flex-direction: column;
            flex-wrap: nowrap;
            justify-content: space-between;
            min-width: 0;
            min-height: 90px; /* Set a minimum height */
        }

        .user-label {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
            margin-top: 20px; 
            height: 20px; /* Set a fixed height */
            line-height: 20px; /* Set line height equal to the height */
        }

        .user-label {
            display: flex;
            align-items: center;
        }
        .user-label label{
            font-size:14px;
        }
        .user-details p {
            font-size: 14px;
            margin-bottom: 10px;
            line-height: 3;
        }
        .user-details-container button {
            background-color:#40576D12;
             height: 40px;
             color: rgb(0, 0, 0);
            padding: 0px 8px;
            margin: 32px 0px 0px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .user-details-container button:hover {
            background-color: rgba(64, 87, 109, 0.12);
        }
        .profile-pic {
            display: flex;
            align-items: center;
            justify-content: space-between; /* Adjusts spacing between image and buttons */
        }
        /* .profile-pic img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-right: 20px;
            margin-left:0;
        } */
        /* .profile-button-container {
            column-gap: 16px;
            display: flex;
            flex-direction: row;
        } */
        /* .profile-button-container button {
            margin-bottom: 10px;
            padding: 0px 8px;
            height: 40px;
        } */
        .profile-button-container span{
            font-size: 14px;
            margin: 9px 0px;
            padding:0px 8px;
        }
        .profile-button-container button.remove-pic {
    margin-bottom: 10px;
    padding: 0px 8px;
    height: 40px;
    background-color: transparent; /* Set background color to transparent */
}
/* Styles for the image container */
.image-container {
    position: relative;
    display: inline-block;

}

/* Styles for the profile pic image */
.profile-pic img {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    margin-right: 20px;
   
}

/* Styles for the button container */
.profile-button-container {
    display: flex;
    flex-direction: row;
    justify-content: center;
    margin-bottom: 20px;
}

/* Styles for the buttons */
.profile-button-container button {
    padding: 0 8px;
    height: 40px;
    margin-bottom: 10px;
}

/* Styles for the camera overlay */
.camera-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    transition: opacity 0.3s ease;
    display: flex;
    justify-content: center;
    align-items: center;
    cursor:pointer;

}

/* Styles for the camera icon */
.camera-overlay i {
    color: white;
    cursor:pointer;
    margin-right:20px;
}

/* Hover effect for the camera overlay */
.profile-img-wrapper:hover .camera-overlay {
    opacity: 1;
}

/* Dim the image on hover */
.profile-img-wrapper:hover img {
    filter: brightness(70%);
}

#editForm, #passwordEditForm, #emaileditForm {
    display: none;
}

#editForm .edit-container,#passwordEditForm .edit-container, #emaileditForm .edit-container {
    display: flex;
    flex-direction: row;
    /* flex-wrap: nowrap; */
    /* justify-content: space-between; */
    /* align-items: center; */
    width: 100%;
    /* background-color: #f7f7f7; */
    /* border-bottom: 1px solid #ccc; */
    /* padding: 0px 0px 16px; */
    
}

/* CSS for username edit form */
#editForm input[type="text"], #passwordEditForm input[type="password"], #emaileditForm input[type="text"] {
    width: calc(100% - 80px); /* Adjust the width of the input field */
    margin-right: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
    height:40px;
    margin-bottom: 12px;
    
}

#editForm .button-container, #passwordEditForm .button-container, #emaileditForm .button-container {
    display: flex;
    flex-direction: row;
    margin-top: -30px;
}

#editForm button, #passwordEditForm button,  #emaileditForm button {
    background-color: #40576D12;
    color: rgb(0, 0, 0);
    padding: 0px 8px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

#editForm button:hover, #passwordEditForm button:hover,  #emaileditForm button:hover {
    background-color: rgba(64, 87, 109, 0.12);
}

#editForm button#cancelButton, #passwordEditForm button#passwordCancelButton,  #emaileditForm button#emailcancelButton {
    margin-right: 8px; /* Add margin to the right of the Cancel button */
    background-color: #40576D12;
}

#editForm button#saveButton, #passwordEditForm button#passwordSaveButton, #emaileditForm button#emailsaveButton {
    margin-right: 8px; /* Add margin to the right of the Cancel button */
    background-color: #007bff;
    color: white;
}

#loadingMessage,
#successMessage,
#errorMessage,
#emailLoadingMessage,
#emailSuccessMessage,
#emailErrorMessage,
#emailStatus::before,
#passwordLoadingMessage,
#passwordSuccessMessage {
    display: none;
    font-size: 14px;
    color: #28a745;
    margin-left: 10px;
}

#loadingMessage::before,
#successMessage::before,
#errorMessage::before,
#emailLoadingMessage::before,
#emailSuccessMessage::before,
#emailErrorMessage::before,
#emailStatus::before,
#passwordLoadingMessage::before,
#passwordSuccessMessage::before {
    display: inline-block;
    width: 20px;
    height: 20px;
    margin-right: 5px;
}

#loadingMessage::before,
#emailLoadingMessage::before,
#passwordLoadingMessage::before {
    /* Use Font Awesome loading icon */
    content: "\f110"; /* Unicode for Font Awesome spinner icon */
    font-family: FontAwesome; /* Use Font Awesome font family */
    animation: spin 2s linear infinite; /* Apply animation */
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

#successMessage::before, 
#emailSuccessMessage::before,
#passwordSuccessMessage::before {
    /* Use Font Awesome check icon */
    content: "\f00c"; /* Unicode for Font Awesome check icon */
    font-family: FontAwesome; /* Use Font Awesome font family */
}

    </style>
</head>
<body>
    <div id="main" class="user-container">
   
      <div class="user-details"><h1>Your Account</h1></div>
        <div class="user-details-container">
            <div class="user-details">
                <div class="user-label">Profile Photo</div>
                <div class="profile-pic">
                    <div class="image-container">
                    <?php if (!empty($userImage)) : ?>
                        <label for="imageInput" class="profile-img-wrapper">
                            <img src="<?php echo htmlspecialchars($userImage); ?>" alt="User Image">
                            <div class="camera-overlay">
                                <i class="fas fa-camera"></i>
                            </div>
                        </label>
                    <?php else : ?>
                        <label for="imageInput" class="profile-img-wrapper">
                            <img src="images/user.png" alt="Default User Image">
                            <div class="camera-overlay">
                                <i class="fas fa-camera"></i>
                            </div>
                        </label>
                    <?php endif; ?>
                    <input type="file" id="imageInput" style="display: none;">
                    </div>
                    <div class="profile-button-container">
                        <button id="removePhoto" class="remove-pic"><span>Remove photo</span></button>
                        <button id="changePicButton" class="change-pic"><span>Change photo</span></button>
                    </div>
                </div>
            </div>
        </div>
        <div class="user-details-container">
    <div class="user-details">
        <div class="user-label">
            <label>Account Name</label>
            <!-- Loading message -->
            <div id="loadingMessage"><i></i> Saving update...</div>
            <!-- Success message -->
            <div id="successMessage"><i class="fa fa-check"></i> Update successful</div>
            <!-- Error message -->
            <div id="errorMessage" style="display:none; color:red;"><i class="fa fa-times"></i> Username already exists</div>
            <div id="usernameStatus" style="margin-left:10px;"></div>
        </div>
        <div class="edit-container">
            <p id="username"><?php echo isset($userName) ? $userName : ""; ?></p>
            <div id="editForm" style="display:none;">
                <div class="edit-container">
                    <input type="text" id="usernameInput" value="<?php echo isset($userName) ? $userName : ""; ?>">
                    <div class="button-container">
                        <button id="cancelButton" style="display:none;">Cancel</button>
                        <button id="saveButton" style="display:none;">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <button id="editButton">Edit</button>
</div>





<div class="user-details-container">
    <div class="user-details">
        <div class="user-label">
            <label>Email Address</label>
               <!-- Loading message -->
               <div id="emailLoadingMessage"><i></i> Saving update...</div>
            <!-- Success message -->
            <div id="emailSuccessMessage" ><i class="fa fa-check"></i> Update successful</div>
            <div id="emailErrorMessage" style="display:none; color:red;"><i class="fa fa-times"></i> Email already exists</div>
                     <div id="emailStatus" style="margin-left:10px;"></div>
        </div>
        <div class="edit-container">
            <p id="email"><?php echo isset($email) ? $email : ""; ?></p>
            <div id="emaileditForm" style="display:none;">
                <div class="edit-container">
                    <input type="text" id="emailInput" value="<?php echo isset($email) ? $email : ""; ?>">

           
                    <div class="button-container">
                        <button id="emailcancelButton" style="display:none;">Cancel</button>
                        <button id="emailsaveButton" style="display:none;">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <button id="emaileditButton">Edit</button>
</div>

<div class="user-details-container">
    <div class="user-details">
        <!-- Password Label and Messages -->
        <div class="user-label">
            <label for="password">Password</label>
            <!-- Loading message -->
            <div id="passwordLoadingMessage" style="display:none;">
                <i></i> Saving update...
            </div>
            <!-- Success message -->
            <div id="passwordSuccessMessage" style="display:none;">
                <i class="fa fa-check"></i> Update successful
            </div>
            <!-- Error message -->
            <div id="passwordErrorMessage" style="display:none; color:red;">
                <i class="fa fa-times"></i> Password update failed
            </div>
            <!-- Additional Status -->
            <div id="passwordStatus" style="margin-left:10px;"></div>
        </div>
        <!-- Password Edit Form -->
        <div class="edit-container">
            <!-- Initial Password Display -->
            <p id="password">Change Password Here...</p>
            <!-- Edit Form -->
            <div id="passwordEditForm" style="display:none;">
                <div class="edit-container">
                    <!-- Old Password Input -->
                    <input type="password" id="oldPasswordInput" placeholder="Type your old password...">
                    <!-- New Password Input -->
                    <input type="password" id="newPasswordInput" placeholder="Type your new password...">
                    <!-- Confirm New Password Input -->
                    <input type="password" id="confirmPasswordInput" placeholder="Confirm your new password...">
                    <!-- Button Container -->
                    <div class="button-container">
                        <!-- Cancel Button -->
                        <button id="passwordCancelButton" style="display:none;">Cancel</button>
                        <!-- Save Button -->
                        <button id="passwordSaveButton" style="display:none;">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Password Edit Button -->
    <button id="passwordEditButton">Edit</button>
</div>






       
    </div>

   

    <!-- Hidden input field to select image file -->
    <input type="file" id="imageInput" style="display: none;">

    <script>

 document.getElementById('changePicButton').addEventListener('click', function() {
        document.getElementById('imageInput').click();
    });

       document.getElementById('imageInput').addEventListener('change', function() {
        var file = this.files[0];
        if (file) {
            var formData = new FormData();
            formData.append('file', file);

            fetch('upload_image.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Image uploaded:', data.imageUrl);
                    document.querySelector('.profile-img-wrapper img').src = data.imageUrl;
                    location.reload();
                } else {
                    console.error('Image upload failed:', data.error);
                }
            })
            .catch(error => console.error('Error:', error));
        }
    });

         document.getElementById('removePhoto').addEventListener('click', function() {
        fetch('upload_image.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'delete_image=true'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log(data.message);
                location.reload();
            } else {
                console.error('Error:', data.error);
            }
        })
        .catch(error => console.error('Error:', error));
    });

document.getElementById("editButton").addEventListener("click", function() {
    // Hide the username paragraph and edit button
    document.getElementById("username").style.display = "none";
    document.getElementById("editButton").style.display = "none";

    // Show the edit form and buttons
    document.getElementById("editForm").style.display = "block";
    document.getElementById("saveButton").style.display = "block";
    document.getElementById("cancelButton").style.display = "block";

    // Fill the input field with the current username
    document.getElementById("usernameInput").value = document.getElementById("username").textContent.trim();
});

// Function to update the username asynchronously
function updateUsername(newName) {
    // Display loading message
    document.getElementById("loadingMessage").style.display = "block";
    document.getElementById("usernameStatus").style.display = "none";
    
    // Set a timeout to wait for 1 second before sending the AJAX request
    setTimeout(function() {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "checkusername.php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                var response = xhr.responseText;
                var usernameStatus = document.getElementById("usernameStatus");
                
                // Clear previous styles and content
                usernameStatus.className = "";
                usernameStatus.innerHTML = "";
                
                // Set appropriate icon and message based on response
                if (response.includes("Invalid")) {
                    usernameStatus.className = "invalid";
                    usernameStatus.innerHTML = '<i class="fa fa-times" style="color: red;"></i> ' + response;
                } else if (response.includes("exists")) {
                    usernameStatus.className = "exists";
                    usernameStatus.innerHTML = '<i class="fa fa-times" style="color: red;"></i> ' + response;
                } else if (response.includes("valid")) {
                    usernameStatus.className = "valid";
                    usernameStatus.innerHTML = '<i class="fa fa-check" style="color: green;"></i> ' + response;
                    // Update the displayed username
                    document.getElementById("username").textContent = newName;
                }
                
                // Hide the loading message and display the username status
                document.getElementById("loadingMessage").style.display = "none";
                usernameStatus.style.display = "block";
                
                // Hide the message after 2 seconds
                setTimeout(function() {
                    usernameStatus.innerHTML = "";
                    document.getElementById("editForm").style.display = "none";
                    document.getElementById("saveButton").style.display = "none";
                    document.getElementById("cancelButton").style.display = "none";
                    document.getElementById("editButton").style.display = "block";
                    document.getElementById("username").style.display = "block";
                }, 2000);
            }
        };
        xhr.send("new_name=" + encodeURIComponent(newName));
    }, 1000); // Wait for 1 second before sending the AJAX request
}

// Event listener for saving the updated username
document.getElementById("saveButton").addEventListener("click", function() {
    var newName = document.getElementById("usernameInput").value.trim();
    // Update the username asynchronously
    updateUsername(newName);
});



document.getElementById("passwordEditButton").addEventListener("click", function() {
    // Hide the password paragraph and edit button
    document.getElementById("password").style.display = "none";
    document.getElementById("passwordEditButton").style.display = "none";

    // Show the edit form and buttons
    document.getElementById("passwordEditForm").style.display = "block";
     document.getElementById("passwordSaveButton").style.display = "block";
    document.getElementById("passwordCancelButton").style.display = "block";
});

document.getElementById("passwordCancelButton").addEventListener("click", function() {
    // Hide the password edit form and buttons
    document.getElementById("passwordEditForm").style.display = "none";
    document.getElementById("passwordSaveButton").style.display = "none";
    document.getElementById("passwordCancelButton").style.display = "none";
    document.getElementById("passwordEditButton").style.display = "block";
    document.getElementById("password").style.display = "block";

    // Clear previous status messages and input fields
    document.getElementById("passwordLoadingMessage").style.display = "none";
    document.getElementById("passwordSuccessMessage").style.display = "none";
    document.getElementById("passwordErrorMessage").style.display = "none";
    document.getElementById("oldPasswordInput").value = "";
    document.getElementById("newPasswordInput").value = "";
    document.getElementById("confirmPasswordInput").value = "";
});



document.getElementById("passwordSaveButton").addEventListener("click", function() {
    var oldPassword = document.getElementById("oldPasswordInput").value.trim();
    var newPassword = document.getElementById("newPasswordInput").value.trim();
    var confirmPassword = document.getElementById("confirmPasswordInput").value.trim();

    // Validate input
    // if (newPassword !== confirmPassword) {
    //     alert("New password and confirm password must match.");
    //     return;
    // }

    // Display loading message
    document.getElementById("passwordLoadingMessage").style.display = "block";
    document.getElementById("passwordSuccessMessage").style.display = "none";
    document.getElementById("passwordErrorMessage").style.display = "none";

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "changepass.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var response = xhr.responseText;
            var passwordStatus = document.getElementById("passwordStatus");

            // Clear previous styles and content
            passwordStatus.className = "";
            passwordStatus.innerHTML = "";

            // Set appropriate icon and message based on response
            if (response.includes("Incorrect")) {
                passwordStatus.className = "invalid";
                passwordStatus.innerHTML = '<i class="fa fa-times" style="color: red;"></i> ' + response;
            } else if (response.includes("match")) {
                passwordStatus.className = "invalid";
                passwordStatus.innerHTML = '<i class="fa fa-times" style="color: red;"></i> ' + response;
            } else if (response.includes("success")) {
                passwordStatus.className = "valid";
                passwordStatus.innerHTML = '<i class="fa fa-check" style="color: green;"></i> ' + response;
                // You may choose to update any UI elements here if necessary
            } else {
                // Show error message
                passwordStatus.className = "invalid";
                passwordStatus.innerHTML = '<i class="fa fa-times" style="color: red;"></i> ' + response;
            }

            // Hide the loading message after processing the response
            document.getElementById("passwordLoadingMessage").style.display = "none";
            passwordStatus.style.display = "block";

            // Hide the message after 2 seconds
            setTimeout(function() {
                passwordStatus.innerHTML = "";
            }, 2000);
        }
    };
    xhr.send("old_password=" + encodeURIComponent(oldPassword) + "&new_password=" + encodeURIComponent(newPassword));
});





// <________________________________________________________________________________>


document.getElementById("emaileditButton").addEventListener("click", function() {
    // Hide the email paragraph and edit button
    document.getElementById("email").style.display = "none";
    document.getElementById("emaileditButton").style.display = "none";

    // Show the edit form and buttons
    document.getElementById("emaileditForm").style.display = "block";
    document.getElementById("emailsaveButton").style.display = "block";
    document.getElementById("emailcancelButton").style.display = "block";

    // Fill the input field with the current email
    document.getElementById("emailInput").value = document.getElementById("email").textContent.trim();
});

// Function to update the email asynchronously
function updateEmail(newEmail) {
    // Display loading message
    document.getElementById("emailLoadingMessage").style.display = "block";
    document.getElementById("emailStatus").style.display = "none";
    
    // Set a timeout to wait for 1 second before sending the AJAX request
    setTimeout(function() {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "checkemail.php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                var response = xhr.responseText;
                var emailStatus = document.getElementById("emailStatus");
                
                // Clear previous styles and content
                emailStatus.className = "";
                emailStatus.innerHTML = "";
                
                // Set appropriate icon and message based on response
                if (response.includes("Invalid")) {
                    emailStatus.className = "invalid";
                    emailStatus.innerHTML = '<i class="fa fa-times" style="color: red;"></i> ' + response;
                } else if (response.includes("exists")) {
                    emailStatus.className = "exists";
                    emailStatus.innerHTML = '<i class="fa fa-times" style="color: red;"></i> ' + response;
                } else if (response.includes("valid")) {
                    emailStatus.className = "valid";
                    emailStatus.innerHTML = '<i class="fa fa-check" style="color: green;"></i> ' + response;
                    // Update the displayed email
                    document.getElementById("email").textContent = newEmail;
                }
                
                // Hide the loading message and display the email status
                document.getElementById("emailLoadingMessage").style.display = "none";
                emailStatus.style.display = "block";
                
                // Hide the message after 2 seconds
                setTimeout(function() {
                    emailStatus.innerHTML = "";
                    document.getElementById("emaileditForm").style.display = "none";
                    document.getElementById("emailsaveButton").style.display = "none";
                    document.getElementById("emailcancelButton").style.display = "none";
                    document.getElementById("emaileditButton").style.display = "block";
                    document.getElementById("email").style.display = "block";
                }, 2000);
            }
        };
        xhr.send("new_email=" + encodeURIComponent(newEmail));
    }, 1000); // Wait for 1 second before sending the AJAX request
}

// Event listener for saving the updated email
document.getElementById("emailsaveButton").addEventListener("click", function() {
    var newEmail = document.getElementById("emailInput").value.trim();
    // Update the email asynchronously
    updateEmail(newEmail);
});


document.getElementById("emailcancelButton").addEventListener("click", function() {
    // Show the email paragraph and edit button
    document.getElementById("email").style.display = "block";
    document.getElementById("emaileditButton").style.display = "block";

    // Hide the edit form and buttons
    document.getElementById("emaileditForm").style.display = "none";
    document.getElementById("emailsaveButton").style.display = "none";
    document.getElementById("emailcancelButton").style.display = "none";
});

// <________________________________________________________________________________>
// document.getElementById('imageInput').addEventListener('change', function() {
//     var file = this.files[0];
//     if (file) {
//         var formData = new FormData();
//         formData.append('file', file);
        
//         var xhr = new XMLHttpRequest();
//         xhr.open('POST', 'upload_image.php', true);
//         xhr.onload = function() {
//             if (xhr.status === 200) {
//                 var imagePath = xhr.responseText;
//                 // Update the displayed profile image with the new path
//                 document.querySelector('.profile-img-wrapper img').src = imagePath;

//                 // Update the profile image icon in the header
//                 document.getElementById('profileImageIcon').src = imagePath;

//                 // Update all associated images with the new path
//                 var associatedImages = document.querySelectorAll('.associated-image');
//                 associatedImages.forEach(function(image) {
//                     image.src = imagePath; 
//                     location.reload();
//                 });
//             }
//         };
//         xhr.send(formData);
//     }
// });





   
    </script>
</body>
</html>
