<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <header>
        <h1><i class="fas fa-building"></i> Company Management</h1>
    </header>
    <nav class="main-nav">
        <ul>
            <li><a href="index.php" id="nav-home"><i class="fas fa-home"></i> Home</a></li>
            <li><a href="departments.php" id="nav-departments"><i class="fas fa-sitemap"></i> Departments</a></li>
            <li><a href="employees.php" id="nav-employees"><i class="fas fa-users"></i> Employees</a></li>
            <li><a href="projects.php" id="nav-projects"><i class="fas fa-project-diagram"></i> Projects</a></li>
        </ul>
    </nav>
    <script>
        // Highlight the active page in the navigation
        document.addEventListener('DOMContentLoaded', function() {
            const currentPage = window.location.pathname.split('/').pop();
            
            switch(currentPage) {
                case 'index.php':
                case '':
                    document.getElementById('nav-home').classList.add('active');
                    break;
                case 'departments.php':
                    document.getElementById('nav-departments').classList.add('active');
                    break;
                case 'employees.php':
                    document.getElementById('nav-employees').classList.add('active');
                    break;
                case 'projects.php':
                    document.getElementById('nav-projects').classList.add('active');
                    break;
                default:
                    // If on a detail page, try to determine the parent section
                    if (currentPage.includes('department')) {
                        document.getElementById('nav-departments').classList.add('active');
                    } else if (currentPage.includes('employee') || currentPage === 'view.php' || currentPage === 'new.php' || currentPage === 'edit.php') {
                        document.getElementById('nav-employees').classList.add('active');
                    } else if (currentPage.includes('project')) {
                        document.getElementById('nav-projects').classList.add('active');
                    }
            }
        });
    </script>