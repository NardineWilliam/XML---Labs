<?php

function loadEmployeeData()
{
    return simplexml_load_file('Employees.xml');
}

function handleFormSubmission($action, $index, $data)
{
    switch ($action) {
        case 'insert':
            $newEmployee = $data->addChild('employee');
            $newEmployee->addChild('name', $_POST['name']);
            $newEmployee->addChild('phone', $_POST['phone']);
            $newEmployee->addChild('address', $_POST['address']);
            $newEmployee->addChild('email', $_POST['email']);
            break;
        case 'update':
            if (isset($data->employee[$index])) {
                $employee = $data->employee[$index];
                $employee->name = $_POST['name'];
                $employee->phone = $_POST['phone'];
                $employee->address = $_POST['address'];
                $employee->email = $_POST['email'];
            }
            break;
        case 'delete':
            if (isset($data->employee[$index])) {
                unset($data->employee[$index]);
            }
            break;
        case 'search':
            $searchResults = [];
            $searchTerm = $_POST['search_term'];
            foreach ($data->employee as $employee) {
                if (stripos($employee->name, $searchTerm) !== false) {
                    $searchResults[] = $employee;
                }
            }
            return $searchResults;
        case 'next':
            $index++;
            break;
        case 'prev':
            $index--;
            break;
    }
    saveEmployeeData($data);
    return $index;
}


function saveEmployeeData($data)
{
    $data->asXML('Employees.xml');
}

function displayEmployeeDetails($employee)
{
    $details = "<h2>Employee Details</h2>";
    $details .= "<p><strong>Name:</strong> {$employee->name}</p>";
    $details .= "<p><strong>Phone:</strong> {$employee->phone}</p>";
    $details .= "<p><strong>Address:</strong> {$employee->address}</p>";
    $details .= "<p><strong>Email:</strong> {$employee->email}</p>";
    echo $details;
}

    $action = $_SERVER['REQUEST_METHOD'] === 'POST' ? $_POST['action'] : null;
    $index = isset($_POST['index']) ? (int)$_POST['index'] : (isset($_GET['index']) ? (int)$_GET['index'] : 0);
    $data = loadEmployeeData();
    $totalEmployees = count($data->employee);
    $employee = isset($data->employee[$index]) ? $data->employee[$index] : null;
    if ($action) {
        $index = handleFormSubmission($action, $index, $data);
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Employee Management</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f0f0f0;
            }
            .container {
                margin: 20px auto;
                padding: 20px;
                background-color: #8a2be2;
                border-radius: 10px;
                box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.5);
                width: 70%; 
                max-width: 500px; 
            }
            h2, p { 
                color: #fff;
            }
            form {
                margin-top: 20px;
            }
            label {
                color: #fff;
                font-weight: bold;
                display: block;
                margin-bottom: 5px;
            }
            input[type="text"] {
                width: 95%;
                padding: 10px;
                margin-bottom: 10px;
                border: none;
                border-radius: 5px;
            }
            input[type="submit"], button {
                background-color: #6a1c9a; 
                color: #fff; 
                border: none;
                border-radius: 5px;
                padding: 10px 20px;
                cursor: pointer;
                margin-right: 10px;
                transition: background-color 0.3s;
            }
            input[type="submit"]:hover, button:hover {
                background-color: #9F9F9F;
            }
            button:disabled {
                background-color: #646464;
                cursor: not-allowed;
            }
            .search-form {
                margin-top: 20px;
            }
            .search-form button {
                background-color: #6a1c9a; 
            }
            .search-form button:hover{
                background-color: #9F9F9F;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h2>Employee Management</h2>
            <form id="employeeForm" method="post">
                <input type="hidden" name="index" value="<?php echo $index; ?>">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" placeholder="Enter name" value="<?php echo $employee ? $employee->name : ''; ?>"><br>
                <label for="phone">Phone:</label>
                <input type="text" id="phone" name="phone" placeholder="Enter phone" value="<?php echo $employee ? $employee->phone : ''; ?>"><br>
                <label for="address">Address:</label>
                <input type="text" id="address" name="address" placeholder="Enter address" value="<?php echo $employee ? $employee->address : ''; ?>"><br>
                <label for="email">Email:</label>
                <input type="text" id="email" name="email" placeholder="Enter email" value="<?php echo $employee ? $employee->email : ''; ?>"><br>
                <input type="submit" name="action" value="insert">
                <input type="submit" name="action" value="update">
                <input type="submit" name="action" value="delete">
                <button type="submit" name="action" value="prev" <?php echo $index == 0 || $totalEmployees == 0 ? 'disabled' : ''; ?>>Prev</button>
                <button type="submit" name="action" value="next" <?php echo $index == $totalEmployees - 1 || $totalEmployees == 0 ? 'disabled' : ''; ?>>Next</button>
            </form>

            <form class="search-form" method="post">
                <label for="search_term">Search by Name:</label>
                <input type="text" id="search_term" name="search_term" placeholder="Enter name">
                <button type="submit" name="action" value="search">Search</button>
            </form>

            <?php
                if ($action === 'search') {
                    $searchResults = handleFormSubmission($action, $index, $data);
                    if (!empty($searchResults)) {
                        echo "<h2>Search Results</h2>";
                        foreach ($searchResults as $result) {
                            displayEmployeeDetails($result);
                            echo "<br>";
                        }
                    } else {
                        echo "<p>No employee found matching the search term.</p>";
                    }
                } else {
                    if ($employee) {
                        displayEmployeeDetails($employee);
                    } else {
                        echo "<p>No employee found.</p>";
                    }
                }
            ?>
        </div>
    </body>
</html>
