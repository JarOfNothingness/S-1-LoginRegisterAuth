<?php
session_start();
include("connection.php");

if (!isset($_SESSION['userid'])) {
    header("Location: ../Home/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loading</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8f9fa;
            margin: 0;
        }
        .loading-container {
            text-align: center;
        }
        .spinner {
            border: 8px solid #f3f3f3;
            border-radius: 50%;
            border-top: 8px solid #007bff;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
    <script>
        function checkStatus() {
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "check_status.php", true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var response = JSON.parse(xhr.responseText);
                    var status = response.status;
                    var userid = <?php echo json_encode($_SESSION['userid']); ?>;

                    if (status === 'approved') {
                        window.location.href = "../home/setup_password.php?userid=" + userid;
                    } else if (status === 'disapproved') {
                        window.location.href = "../Home/login.php?error=disapproved";
                    }
                }
            };
            xhr.send();
        }

        // Check status every 5 seconds
        setInterval(checkStatus, 5000);
    </script>
</head>
<body>
    <div class="loading-container">
        <div class="spinner"></div>
        <p>Please wait while your request is being processed...</p>
    </div>
</body>
</html>