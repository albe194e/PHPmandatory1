<?php

$employeeID = (int) ($_GET['id'] ?? 0);

if ($employeeID === 0) {
    header('Location: index.php');
    exit;
}

require_once 'src/employee.php';

$employee = new Employee();

// Handle delete action
if (isset($_POST['action']) && $_POST['action'] === 'delete') {
    if ($employee->delete($employeeID)) {
        header('Location: index.php');
        exit;
    } else {
        $errorMessage = 'Error deleting employee.';
    }
}

$employee = $employee->getByID($employeeID);

if (!$employee) {
    $errorMessage = 'There was an error retrieving employee information.';
} else {
    $employee = $employee[0];
}

include_once 'views/header.php';

?>

    <nav>
        <ul>
            <li><a href="index.php" title="Homepage">Back</a></li>
        </ul>
    </nav>
    <main>
        <?php if (isset($errorMessage)): ?>
            <section>
                <p class="error"><?=$errorMessage ?></p>
            </section>
        <?php else: ?>
            <p><strong>First name: </strong><?=$employee['first_name'] ?></p>
            <p><strong>Last name: </strong><?=$employee['last_name'] ?></p>
            <p><strong>Email: </strong><?=$employee['email'] ?></p>
            <p><strong>Birth date: </strong><?=$employee['birth_date'] ?></p>
            <p><strong>Department: </strong><?=$employee['department_name'] ?></p>
            
            <div class="actions">
                <a href="edit.php?id=<?=$employeeID ?>" class="button">Edit Employee</a>
                
                <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this employee?');">
                    <input type="hidden" name="action" value="delete">
                    <button type="submit" class="button delete">Delete Employee</button>
                </form>
            </div>
        <?php endif; ?>
    </main>

<?php include_once 'views/footer.php'; ?>