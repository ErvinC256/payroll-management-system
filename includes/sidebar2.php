<head>
    <link rel="stylesheet" href="../styles/base.css">
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
        }
        aside button:hover {
            cursor: pointer;
            background-color: #3d4554;
        }
    </style>
</head>
<body>
    <aside>
        <a href="../admin/dashboard.php"><button>Dashboard</button></a>
        <hr style="color: white;">
        <a href="../admin/designations.php"><button>Designations</button></a>
        <a href="../admin/allowances.php"><button>Allowances</button></a>
        <a href="../admin/employees.php"><button>Employees</button></a>
        <a href="../admin/payroll.php"><button>Payrolls</button></a>
        <hr style="color: white; margin-top: auto;">
        <a href="../scripts/logout.php" style="margin-top: auto;"><button>Logout</button></a>
    </aside>
</body>