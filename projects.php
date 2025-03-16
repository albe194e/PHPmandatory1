<?php

require_once 'src/project.php';

$project = new Project();
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
                <p class="error"><?=$errorMessage ?></p>
            </section>
        <?php else: ?>
            <section>
                <?php foreach ($projects as $proj): ?>
                    <article>
                        <h3><?=$proj['cName'] ?></h3>
                        <p><a href="project_details.php?id=<?=$proj['nProjectID'] ?>">View details</a></p>
                    </article>
                <?php endforeach; ?>
            </section>
        <?php endif; ?>
    </main>
<?php include_once 'views/footer.php'; ?> 