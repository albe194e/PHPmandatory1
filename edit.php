<?php
require_once 'src/employee.php';
require_once 'src/department.php';

$employeeID = (int) ($_GET['id'] ?? 0);

if ($employeeID === 0) {
    header('Location: index.php');
    exit;
}

$employee = new Employee();
$department = new Department();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'first_name' => $_POST['first_name'] ?? '',
        'last_name' => $_POST['last_name'] ?? '',
        'email' => $_POST['email'] ?? '',
        'birth_date' => $_POST['birth_date'] ?? '',
        'department_id' => (int)($_POST['department_id'] ?? 0),
        'employee_id' => $employeeID
    ];
    
    if ($employee->update($data)) {
        header("Location: view.php?id=$employeeID");
        exit;
    } else {
        $errorMessage = 'Error updating employee information.';
    }
}

// Get employee data
$employeeData = $employee->getByID($employeeID);
if (!$employeeData) {
    header('Location: index.php');
    exit;
}
$employeeData = $employeeData[0];

// Get departments for dropdown
$departments = $department->getAll();

include_once 'views/header.php';
?>

<nav>
    <ul>
        <li><a href="view.php?id=<?=$employeeID ?>" title="Back to View">Back</a></li>
    </ul>
</nav>

<main>
    <h1>Edit Employee</h1>
    
    <?php if (isset($errorMessage)): ?>
        <p class="error"><?=$errorMessage ?></p>
    <?php endif; ?>

    <form method="POST">
        <div class="form-group">
            <label for="first_name">First Name:</label>
            <input type="text" id="first_name" name="first_name" value="<?=htmlspecialchars($employeeData['first_name']) ?>" required>
        </div>

        <div class="form-group">
            <label for="last_name">Last Name:</label>
            <input type="text" id="last_name" name="last_name" value="<?=htmlspecialchars($employeeData['last_name']) ?>" required>
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?=htmlspecialchars($employeeData['email']) ?>" required>
        </div>

        <div class="form-group">
            <label for="birth_date">Birth Date:</label>
            <input type="date" id="birth_date" name="birth_date" value="<?=htmlspecialchars($employeeData['birth_date']) ?>" required>
        </div>

        <div class="form-group">
            <label for="department">Department:</label>
            <select id="department" name="department_id">
                <?php foreach ($departments as $dept): ?>
                    <option value="<?=$dept['nDepartmentID'] ?>" <?=$dept['nDepartmentID'] === $employeeData['department_id'] ? 'selected' : '' ?>>
                        <?=htmlspecialchars($dept['cName']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" class="button">Update Employee</button>
    </form>
</main>

<?php include_once 'views/footer.php'; ?> 