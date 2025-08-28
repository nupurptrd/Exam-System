<?php
session_start();
require_once '../config/db_connect.php';

function isAdmin() {
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        header("Location: ../index.php");
        exit;
    }
}
isAdmin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <main class="container mx-auto p-4" role="main">
        <section aria-labelledby="admin-dashboard-heading">
            <h2 id="admin-dashboard-heading" class="text-xl font-bold">Admin Dashboard</h2>
            <p>Welcome, Admin!</p>
            <ul class="list-disc pl-5">
                <li><a href="manage_users.php" class="text-blue-600 underline">Manage Users</a></li>
                <li><a href="manage_quizzes.php" class="text-blue-600 underline">Manage Quizzes</a></li>
            </ul>
        </section>
    </main>
</body>
</html>