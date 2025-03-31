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
            <input type="text" id="searchQuery" name="searchQuery" placeholder="Search by name..." value="<?=htmlspecialchars($searchQuery) ?>">
            <button type="submit" name="search">Search</button>
        </form>
        <ul id="suggestions" class="suggestions"></ul>
    </section>

    <!-- Create Department Button -->
    <section>
        <h3>Create Department</h3>
        <button id="openCreateModal">Create Department</button>
    </section>

    <!-- Create Department Modal -->
    <div id="createModal" class="modal">
        <div class="modal-content">
            <span class="close" id="closeCreateModal">&times;</span>
            <h3>Create Department</h3>
            <form method="POST" action="departments.php">
                <label for="name">Department Name:</label>
                <input type="text" id="name" name="name" required>
                <button type="submit" name="create">Create</button>
            </form>
        </div>
    </div>

    <!-- List of Departments -->
    <section>
        <h3>Existing Departments</h3>
        <?php if ($departments): ?>
            <?php foreach ($departments as $dept): ?>
                <article>
                    <h4><?=$dept['cName'] ?></h4>
                    <p><strong>Department ID: </strong><?=$dept['nDepartmentID'] ?></p>
                    
                    <!-- Update Department Button -->
                    <button class="openUpdateModal" data-id="<?=$dept['nDepartmentID'] ?>" data-name="<?=$dept['cName'] ?>">Update</button>

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

<!-- Update Department Modal -->
<div id="updateModal" class="modal">
    <div class="modal-content">
        <span class="close" id="closeUpdateModal">&times;</span>
        <h3>Update Department</h3>
        <form method="POST" action="departments.php">
            <input type="hidden" id="update-id" name="id">
            <label for="update-name">New Department Name:</label>
            <input type="text" id="update-name" name="name" required>
            <button type="submit" name="update">Update</button>
        </form>
    </div>
</div>

<!-- Modal Styles -->
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

    .modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.4);
    }

    .modal-content {
        background-color: #fff;
        margin: 15% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 30%;
        border-radius: 8px;
    }

    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
    }
</style>

<!-- AJAX Script for Search Suggestions -->
<script>
    const searchInput = document.getElementById('searchQuery');
    const suggestionsList = document.getElementById('suggestions');

    searchInput.addEventListener('input', function () {
        const query = searchInput.value;

        if (query.length > 0) {
            fetch(`departments.php?ajaxSearch=true&query=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    suggestionsList.innerHTML = '';
                    data.forEach(department => {
                        const li = document.createElement('li');
                        li.textContent = department.cName;
                        li.addEventListener('click', () => {
                            searchInput.value = department.cName;
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

    // Create Modal
    const createModal = document.getElementById('createModal');
    const openCreateModal = document.getElementById('openCreateModal');
    const closeCreateModal = document.getElementById('closeCreateModal');

    openCreateModal.onclick = function () {
        createModal.style.display = 'block';
    };

    closeCreateModal.onclick = function () {
        createModal.style.display = 'none';
    };

    window.onclick = function (event) {
        if (event.target === createModal) {
            createModal.style.display = 'none';
        }
    };

    // Update Modal
    const updateModal = document.getElementById('updateModal');
    const openUpdateButtons = document.querySelectorAll('.openUpdateModal');
    const closeUpdateModal = document.getElementById('closeUpdateModal');
    const updateIdInput = document.getElementById('update-id');
    const updateNameInput = document.getElementById('update-name');

    openUpdateButtons.forEach(button => {
        button.onclick = function () {
            const id = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');

            updateIdInput.value = id;
            updateNameInput.value = name;

            updateModal.style.display = 'block';
        };
    });

    closeUpdateModal.onclick = function () {
        updateModal.style.display = 'none';
    };

    window.onclick = function (event) {
        if (event.target === updateModal) {
            updateModal.style.display = 'none';
        }
    };
</script>

<?php include_once 'views/footer.php'; ?>