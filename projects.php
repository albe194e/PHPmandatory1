<?php

require_once 'src/project.php';

$project = new Project();
$errorMessage = null;
$searchQuery = '';

// Handle AJAX request for search suggestions
if (isset($_GET['ajaxSearch']) && isset($_GET['query'])) {
    $searchQuery = $_GET['query'];
    $projects = $project->search($searchQuery);
    header('Content-Type: application/json');
    echo json_encode($projects);
    exit;
}

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

    if (isset($_POST['search'])) {
        $searchQuery = $_POST['searchQuery'];
    }
}

// Retrieve projects, filtered by search query if provided
if (!empty($searchQuery)) {
    $projects = $project->search($searchQuery);
} else {
    $projects = $project->getAll();
}

if (!$projects && !$errorMessage) {
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

    <!-- Search Bar -->
    <section>
        <h3>Search Projects</h3>
        <form method="POST" action="projects.php">
            <input type="text" id="searchQuery" name="searchQuery" placeholder="Search by name..." value="<?=htmlspecialchars($searchQuery) ?>">
            <button type="submit" name="search">Search</button>
        </form>
        <ul id="suggestions" class="suggestions"></ul>
    </section>

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

<!-- Search Suggestions Styles -->
<style>
    .suggestions {
        list-style-type: none;
        padding: 0;
        margin: 0;
        border: 1px solid #ccc;
        max-height: 150px;
        overflow-y: auto;
        background-color: #fff;
        position: absolute;
        width: 300px;
    }

    .suggestions li {
        padding: 8px;
        cursor: pointer;
    }

    .suggestions li:hover {
        background-color: #f0f0f0;
    }
</style>

<!-- AJAX Script for Search Suggestions -->
<script>
    const searchInput = document.getElementById('searchQuery');
    const suggestionsList = document.getElementById('suggestions');

    searchInput.addEventListener('input', function () {
        const query = searchInput.value;

        if (query.length > 0) {
            fetch(`projects.php?ajaxSearch=true&query=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    suggestionsList.innerHTML = '';
                    data.forEach(project => {
                        const li = document.createElement('li');
                        li.textContent = project.cName;
                        li.addEventListener('click', () => {
                            searchInput.value = project.cName;
                            suggestionsList.innerHTML = '';
                        });
                        suggestionsList.appendChild(li);
                    });
                });
        } else {
            suggestionsList.innerHTML = '';
        }
    });

    document.addEventListener('click', function (event) {
        if (!suggestionsList.contains(event.target) && event.target !== searchInput) {
            suggestionsList.innerHTML = '';
        }
    });
</script>

<?php include_once 'views/footer.php'; ?>
