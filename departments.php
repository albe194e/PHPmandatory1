<?php

require_once 'src/department.php';

$department = new Department();
$departments = $department->getAll();

if (!$departments) {
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
        <?php else: ?>
            <section>
                <?php foreach ($departments as $dept): ?>
                    <article>
                        <h3><?=$dept['cName'] ?></h3>
                        <p><strong>Department ID: </strong><?=$dept['nDepartmentID'] ?></p>
                        <p><a href="department_details.php?id=<?=$dept['nDepartmentID'] ?>">View details</a></p>
                    </article>
                <?php endforeach; ?>
            </section>
        <?php endif; ?>
    </main>
<?php include_once 'views/footer.php'; ?> 