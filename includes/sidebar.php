<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="../styles/base.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        aside {
            position: fixed;
            top: 0;
            left: 0;
            width: 200px;
            height: 100%;
            background-color: #171c2b;
            padding: 5px;
            display: flex;
            flex-direction: column;
            z-index: 999; /* Add a higher z-index value */
        }

        aside a {
            text-decoration: none;
        }

        aside button {
            padding: 20px;
            border: none;
            text-align: left;
            width: 100%;
            font-size: 18px;
            color: white;
            border-radius: 10px;
            display: flex;
            align-items: center; /* Align the icon and text vertically */
        }

        aside button i {
            margin-right: 10px; /* Adjust the spacing between the icon and text */
        }

        aside button:hover {
            cursor: pointer;
            background-color: #3d4554;
        }

        aside hr {
            color: white;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <aside>
        <a href="../admin/dashboard.php">
            <button>
                <i class="fas fa-chart-bar"></i>
                Dashboard
            </button>
        </a>
        <hr>
        <a href="../admin/designations.php">
            <button>
                <i class="fas fa-cogs"></i>
                Designations
            </button>
        </a>
        <a href="../admin/allowances.php">
            <button>
                <i class="fas fa-money-check-alt"></i>
                Allowances
            </button>
        </a>
        <a href="../admin/employees.php">
            <button>
                <i class="fas fa-users"></i>
                Employees
            </button>
        </a>
        <a href="../admin/payroll.php">
            <button>
                <i class="fas fa-money-bill-wave"></i>
                Payrolls
            </button>
        </a>
        <hr>
        <a href="../index.php" style="margin-top: auto;">
            <button>
                <i class="fas fa-sign-out-alt"></i>
                Logout
            </button>
        </a>
    </aside>
</body>
</html>
