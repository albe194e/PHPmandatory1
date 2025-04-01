<?php

require_once 'src/employee.php';

$employee = new Employee();
$errorMessage = null;
$searchQuery = '';

// Handle AJAX request for search suggestions
if (isset($_GET['ajaxSearch']) && isset($_GET['query'])) {
    $searchQuery = $_GET['query'];
    $employees = $employee->search($searchQuery);
    header('Content-Type: application/json');
    echo json_encode($employees);
    exit;
}

// Handle form submissions for Create, Update, Delete, and Search
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['search'])) {
        $searchQuery = $_POST['searchQuery'];
    }
}

// Retrieve employees, filtered by search query if provided
if (!empty($searchQuery)) {
    $employees = $employee->search($searchQuery);
} else {
    $employees = $employee->getAll();
}

if (!$employees && !$errorMessage) {
    $errorMessage = 'There was an error while retrieving the list of employees.';
}

include_once 'views/header.php';
?>
<main>
    <h2>Employees</h2>
    <?php if (isset($errorMessage)): ?>
        <section>
            <p class="error"><?=$errorMessage ?></p>
        </section>
    <?php endif; ?>

    <!-- Search Bar -->
    <section>
        <h3>Search Employees</h3>
        <form method="POST" action="employees.php">
            <input type="text" id="searchQuery" name="searchQuery" placeholder="Search by name..." value="<?=htmlspecialchars($searchQuery) ?>">
            <button type="submit" name="search">Search</button>
        </form>
        <ul id="suggestions" class="suggestions"></ul>
    </section>

    <!-- Create Employee Button -->
    <section>
        <h3>Create Employee</h3>
        <a href="new.php" title="Create new employee" class="button">Add employee</a>
    </section>

    <!-- List of Employees -->
    <section>
        <h3>Existing Employees</h3>
        <?php if ($employees): ?>
            <?php foreach ($employees as $emp): ?>
                <article>
                    <h4><?=$emp['cFirstName'] ?> <?=$emp['cLastName'] ?></h4>
                    <p><strong>Birth date: </strong><?=$emp['dBirth'] ?></p>
                    <p><a href="view.php?id=<?=$emp['nEmployeeID'] ?>">View details</a></p>
                </article>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No employees found.</p>
        <?php endif; ?>
    </section>
</main>

<!-- AJAX Script for Search Suggestions
<script>
    const searchInput = document.getElementById('searchQuery');
    const suggestionsList = document.getElementById('suggestions');

    searchInput.addEventListener('input', function () {
        const query = searchInput.value;

        if (query.length > 0) {
            fetch(`employees.php?ajaxSearch=true&query=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    suggestionsList.innerHTML = '';
                    data.forEach(employee => {
                        const li = document.createElement('li');
                        li.textContent = `${employee.cFirstName} ${employee.cLastName}`;
                        li.addEventListener('click', () => {
                            searchInput.value = `${employee.cFirstName} ${employee.cLastName}`;
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
-->

<?php include_once 'views/footer.php'; ?> 