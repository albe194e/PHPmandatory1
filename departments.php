<?php

require_once 'src/department.php';

$department = new Department();
$errorMessage = null;
$searchQuery = '';

// Handle AJAX request for search suggestions
if (isset($_GET['ajaxSearch']) && isset($_GET['query'])) {
    $searchQuery = $_GET['query'];
    $departments = $department->search($searchQuery);
    header('Content-Type: application/json');
    echo json_encode($departments);
    exit;
}

// Handle form submissions for Create, Update, Delete, and Search
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['create'])) {
        $name = $_POST['name'];
        if ($department->create($name)) {
            header('Location: departments.php');
            exit;
        } else {
            $errorMessage = 'Failed to create department.';
        }
    } elseif (isset($_POST['update'])) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        if ($department->update($id, $name)) {
            header('Location: departments.php');
            exit;
        } else {
            $errorMessage = 'Failed to update department.';
        }
    } elseif (isset($_POST['delete'])) {
        $id = $_POST['id'];
        if ($department->delete($id)) {
            header('Location: departments.php');
            exit;
        } else {
            $errorMessage = 'Failed to delete department. Ensure it has no employees.';
        }
    } elseif (isset($_POST['search'])) {
        $searchQuery = $_POST['searchQuery'];
    }
}

// Retrieve departments, filtered by search query if provided
if (!empty($searchQuery)) {
    $departments = $department->search($searchQuery);
} else {
    $departments = $department->getAll();
}

if (!$departments && !$errorMessage) {
    $errorMessage = 'There was an error while retrieving the list of departments.';
}

include_once 'views/header.php';
?>
<main>
    <h2>Departments</h2>
    <?php if (isset($errorMessage)): ?>
        <section>
            <p class="error"><?=$errorMessage ?></p>
        </section>
    <?php endif; ?>

    <!-- Search Bar -->
    <section>
        <h3>Search Departments</h3>
        <form method="POST" action="departments.php">
            <input type="text" name="searchQuery" placeholder="Search by name..." value="<?=htmlspecialchars($searchQuery) ?>">
            <button type="submit" name="search">Search</button>
        </form>
    </section>

    <!-- Create Department Form -->
    <section>
        <h3>Create Department</h3>
        <form method="POST" action="departments.php">
            <label for="name">Department Name:</label>
            <input type="text" id="name" name="name" required>
            <button type="submit" name="create">Create</button>
        </form>
    </section>

    <!-- List of Departments -->
    <section>
        <h3>Existing Departments</h3>
        <?php if ($departments): ?>
            <?php foreach ($departments as $dept): ?>
                <article>
                    <h4><?=$dept['cName'] ?></h4>
                    <p><strong>Department ID: </strong><?=$dept['nDepartmentID'] ?></p>
                    
                    <!-- Update Department Form -->
                    <form method="POST" action="departments.php" style="display: inline;">
                        <input type="hidden" name="id" value="<?=$dept['nDepartmentID'] ?>">
                        <input type="text" name="name" value="<?=htmlspecialchars($dept['cName']) ?>" required>
                        <button type="submit" name="update">Update</button>
                    </form>

                    <!-- Delete Department Form -->
                    <form method="POST" action="departments.php" style="display: inline;">
                        <input type="hidden" name="id" value="<?=$dept['nDepartmentID'] ?>">
                        <button type="submit" name="delete" onclick="return confirm('Are you sure you want to delete this department?')">Delete</button>
                    </form>
                </article>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No departments found.</p>
        <?php endif; ?>
    </section>
</main>

<?php include_once 'views/footer.php'; ?>