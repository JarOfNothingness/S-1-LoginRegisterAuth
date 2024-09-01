<?php
session_start();
include("connection.php");

// Check if the form was submitted
if (isset($_POST["submit"])) {
    $fullname = $_POST["name"];
    $address = $_POST["address"];
    $role = $_POST["role"];
    $username = $_POST["username"];
    $password = bin2hex(random_bytes(8)); // Generate a temporary password

    // Validate if all required fields are filled
    if (empty($fullname) || empty($address) || empty($role) || empty($username)) {
        $error_msg = "Error: Please fill in all required fields.";
    } else {
        // Insert new user with temporary password and status 'pending'
        $sql_create = "INSERT INTO user (name, address, password, username, role, status) VALUES (?, ?, ?, ?, ?, 'pending')";
        $statement = mysqli_stmt_init($connection);
        if (mysqli_stmt_prepare($statement, $sql_create)) {
            mysqli_stmt_bind_param($statement, "sssss", $fullname, $address, $password, $username, $role);
            if (mysqli_stmt_execute($statement)) {
                // Store the user ID in the session for later use
                $_SESSION['userid'] = mysqli_insert_id($connection);
                header("Location: loading.php"); // Redirect to loading page
                exit();
            } else {
                $error_msg = "Error: Could not execute the statement.";
            }
            mysqli_stmt_close($statement);
        } else {
            $error_msg = "Error: Could not prepare the statement.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }
        .navbar {
            background-color: #007bff;
            padding: 10px;
            color: white;
            text-align: center;
        }
        .register-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        h1 {
            margin-top: 0;
        }
        input, select {
            width: calc(100% - 22px);
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }
        .error-msg {
            color: red;
        }
        .success-msg {
            color: green;
        }
        .btn-success {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="../home/login.php" style="color: white; text-decoration: none;">Back to Login</a>
    </div>

    <div class="register-container">
        <h1>Create Account</h1>
        <form method="POST">
            <?php if (isset($error_msg)): ?>
                <p class="error-msg"><?php echo $error_msg; ?></p>
            <?php endif; ?>
            <input type="text" name="name" placeholder="Full Name" value="<?php echo isset($_POST["name"]) ? htmlspecialchars($_POST["name"]) : ''; ?>" required>
            <input type="email" name="address" placeholder="Email Address" value="<?php echo isset($_POST["address"]) ? htmlspecialchars($_POST["address"]) : ''; ?>" required>
            <select name="role" id="role" required>
                <option value="">Select Role</option>              
                <option value="Teacher" <?php echo isset($_POST["role"]) && $_POST["role"] === "Teacher" ? 'selected' : ''; ?>>Teacher</option>
            </select>
            <input type="text" name="username" placeholder="Username" minlength="5" maxlength="20" value="<?php echo isset($_POST["username"]) ? htmlspecialchars($_POST["username"]) : ''; ?>" required>
            <input type="submit" name="submit" value="Submit" class="btn-success">
        </form>
    </div>
</body>
</html>