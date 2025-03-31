<?php

require_once 'src/project.php';

// Get project ID from URL parameter
$projectID = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($projectID <= 0) {
    die('Invalid project ID.');
}

$project = new Project();

// Handle form submissions for adding and removing employees
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['addEmployee'])) {
        $employeeID = intval($_POST['employeeID']);
        if ($employeeID > 0) {
            if (!$project->addEmployee($projectID, $employeeID)) {
                $errorMessage = 'Error adding employee to project.';
            }
        }
    }

    if (isset($_POST['removeEmployee'])) {
        $employeeID = intval($_POST['employeeID']);
        if ($employeeID > 0) {
            if (!$project->removeEmployee($projectID, $employeeID)) {
                $errorMessage = 'Error removing employee from project.';
            }
        }
    }
}

// Fetch project details
$projectDetails = $project->get($projectID);
if (!$projectDetails) {
    die('Project not found.');
}

// Fetch employees assigned to the project
$employees = $project->listEmployees($projectID);
// Fetch available employees not assigned to the project
$availableEmployees = $project->getAvailableEmployees($projectID);

include_once 'views/header.php';
?>

<main>
    <h2>Project: <?= htmlspecialchars($projectDetails['cName']) ?></h2>
    
    <?php if (isset($errorMessage)): ?>
        <p class="error"><?= htmlspecialchars($errorMessage) ?></p>
    <?php endif; ?>

    <h3>Assigned Employees</h3>
    <?php if (empty($employees)): ?>
        <p>No employees assigned to this project.</p>
    <?php else: ?>
        <ul class="employee-list">
            <?php foreach ($employees as $emp): ?>
                <li>
                    <?= htmlspecialchars($emp['fullName']) ?>
                    <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to remove this employee from the project?');">
                        <input type="hidden" name="employeeID" value="<?= $emp['nEmployeeID'] ?>">
                        <button type="submit" name="removeEmployee" class="button delete">Remove</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <!-- Add Employee Form -->
    <h3>Add Employee to Project</h3>
    <?php if (empty($availableEmployees)): ?>
        <p>No available employees to add to this project.</p>
    <?php else: ?>
        <form method="POST" class="add-employee-form">
            <select name="employeeID" required>
                <option value="">Select an employee...</option>
                <?php foreach ($availableEmployees as $emp): ?>
                    <option value="<?= $emp['nEmployeeID'] ?>"><?= htmlspecialchars($emp['fullName']) ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" name="addEmployee" class="button">Add Employee</button>
        </form>
    <?php endif; ?>

    <p><a href="projects.php" class="button">Back to Projects</a></p>
</main>

<style>
    .employee-list {
        list-style: none;
        padding: 0;
    }
    .employee-list li {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px;
        border-bottom: 1px solid #eee;
    }
    .add-employee-form {
        display: flex;
        gap: 10px;
        align-items: center;
        margin-bottom: 20px;
    }
    .add-employee-form select {
        flex: 1;
        padding: 8px;
    }
    .button.delete {
        background-color: #dc3545;
        color: white;
        border: none;
        padding: 5px 10px;
        border-radius: 4px;
        cursor: pointer;
    }
    .button.delete:hover {
        background-color: #c82333;
    }
</style>

<?php include_once 'views/footer.php'; ?>
