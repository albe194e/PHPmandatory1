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

include_once 'views/header.php';
?>

<main>
    <h2>Project: <?= htmlspecialchars($projectDetails['cName']) ?></h2>
    
    <h3>Assigned Employees</h3>
    <?php if (empty($employees)): ?>
        <p>No employees assigned to this project.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($employees as $emp): ?>
                <li><?= htmlspecialchars($emp['fullName']) ?> (ID: <?= $emp['nEmployeeID'] ?>)</li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <!-- Add Employee Form -->
    <h3>Add Employee to Project</h3>
    <form method="POST">
        <input type="number" name="employeeID" placeholder="Employee ID" required>
        <button type="submit" name="addEmployee">Add Employee</button>
    </form>

    <!-- Remove Employee Form -->
    <h3>Remove Employee from Project</h3>
    <form method="POST">
        <input type="number" name="employeeID" placeholder="Employee ID" required>
        <button type="submit" name="removeEmployee">Remove Employee</button>
    </form>

    <p><a href="projects.php">Back to Projects</a></p>
</main>

<?php include_once 'views/footer.php'; ?>
