<?php
include_once 'views/header.php';
?>
    <main>
        <h2>Welcome to Company Management System</h2>
        <p>Use the navigation bar above to access different sections of the application.</p>
        
        <div class="dashboard">
            <div class="dashboard-item">
                <h3>Departments</h3>
                <p>View and manage company departments</p>
                <a href="departments.php" class="button">Go to Departments</a>
            </div>
            
            <div class="dashboard-item">
                <h3>Employees</h3>
                <p>View and manage employee information</p>
                <a href="employees.php" class="button">Go to Employees</a>
            </div>
            
            <div class="dashboard-item">
                <h3>Projects</h3>
                <p>View and manage company projects</p>
                <a href="projects.php" class="button">Go to Projects</a>
            </div>
        </div>
    </main>
<?php include_once 'views/footer.php'; ?>