<?php

require_once 'src/project.php';

$project = new Project();

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['create'])) {
        $title = trim($_POST['title']);
        if (!empty($title)) {
            if (!$project->create($title)) {
                $errorMessage = 'Error creating project.';
            }
        }
    }

    if (isset($_POST['update'])) {
        $projectID = intval($_POST['projectID']);
        $newTitle = trim($_POST['newTitle']);
        if ($projectID > 0 && !empty($newTitle)) {
            if (!$project->update($projectID, $newTitle)) {
                $errorMessage = 'Error updating project.';
            }
        }
    }

    if (isset($_POST['delete'])) {
        $projectID = intval($_POST['projectID']);
        if ($projectID > 0) {
            if (!$project->delete($projectID)) {
                $errorMessage = 'Error deleting project.';
            }
        }
    }

    if (isset($_POST['addEmployee'])) {
        $projectID = intval($_POST['projectID']);
        $employeeID = intval($_POST['employeeID']);
        if ($projectID > 0 && $employeeID > 0) {

            
            if (!$project->addEmployee($projectID, $employeeID)) {
                $errorMessage = 'Error adding employee to project.';
            }
        }
    }

    if (isset($_POST['removeEmployee'])) {
        $projectID = intval($_POST['projectID']);
        $employeeID = intval($_POST['employeeID']);
        if ($projectID > 0 && $employeeID > 0) {
            if (!$project->removeEmployee($projectID, $employeeID)) {
                $errorMessage = 'Error removing employee from project.';
            }
        }
    }
}

// Retrieve updated list of projects
$projects = $project->getAll();

if (!$projects) {
    $errorMessage = 'There was an error while retrieving the list of projects.';
}

include_once 'views/header.php';
?>

<main>
    <h2>Projects</h2>

    <?php if (isset($errorMessage)): ?>
        <section>
            <p class="error"><?= $errorMessage ?></p>
        </section>
    <?php endif; ?>

    <section>
        <h3>Create Project</h3>
        <form method="POST">
            <input type="text" name="title" placeholder="Project Name" required>
            <button type="submit" name="create">Create</button>
        </form>
    </section>

    <section>
        <?php foreach ($projects as $proj): ?>
            <article>
                <h3><?= $proj['cName'] ?></h3>
                <p><a href="project_details.php?id=<?= $proj['nProjectID'] ?>">View details</a></p>

                <form method="POST">
                    <input type="hidden" name="projectID" value="<?= $proj['nProjectID'] ?>">
                    <input type="text" name="newTitle" placeholder="New Title" required>
                    <button type="submit" name="update">Update</button>
                </form>

                <form method="POST">
                    <input type="hidden" name="projectID" value="<?= $proj['nProjectID'] ?>">
                    <button type="submit" name="delete" onclick="return confirm('Are you sure you want to delete this project?');">Delete</button>
                </form>
            </article>
        <?php endforeach; ?>
    </section>
</main>

<?php include_once 'views/footer.php'; ?>
