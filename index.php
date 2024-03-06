<?php include 'scripts/connectdb.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <title>PHP Payroll</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="styles/button-8.css">
    <link rel="stylesheet" href="styles/modal.css">
    <link rel="stylesheet" href="styles/scrollbars.css">
    <link rel="stylesheet" href="styles/forms.css">
    <link rel="stylesheet" href="styles/base.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
    </style>
</head>
<body>
    <section id="login-section">
        <h1>Payroll Management System</h1>
        <br>
        <h3>Admin Login</h3>
        <hr>
        <form id="admin-form" method="post">
            <table>
                <tr>
                    <td>Username</td>
                    <td><input type="text" name="username"></td>
                </tr>
                <tr>
                    <td>Password</td>
                    <td><input type="password" name="password"></td>
                </tr>
            </table>
            <br>
            <button name="login" type="submit" class="button-8">Login</button>
        </form>
		<br>
        <p id="message" style="height: 20px; color: red; text-align: right;"></p>
    </section>

    <!-- Add the following script -->
    <script>
        const form = document.querySelector('#admin-form');
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            const formData = new FormData(form);
            fetch('scripts/verify-login.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    window.location.href = 'admin/dashboard.php';
                } else {
                    document.querySelector('#message').textContent = data.message;
                }
            });
        });
    </script>
</body>
</html>

