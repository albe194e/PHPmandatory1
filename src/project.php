<?php

require_once 'database.php';
require_once 'department.php';
require_once 'logger.php';

Class Project extends Database
{
    public function getAll(): array|false {
        $pdo = $this->connect();
        $stmt = $pdo->query("SELECT nProjectID, cName FROM project");
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: false;
    }

    public function get(int $id): array|false {
        $pdo = $this->connect();
        // Prepare a statement to fetch a project by its ID
        $stmt = $pdo->prepare("SELECT nProjectID, cName FROM project WHERE nProjectID = :id");
        $stmt->execute([':id' => $id]);
    
        // Fetch and return the result as an associative array
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    

    public function create(string $title): bool {
        $pdo = $this->connect();
        $stmt = $pdo->prepare("INSERT INTO project (cName) VALUES (:title)");
        return $stmt->execute([':title' => $title]);
    }

    public function update(int $projectID, string $newTitle): bool {
        $pdo = $this->connect();
        $stmt = $pdo->prepare("UPDATE project SET cName = :newTitle WHERE nProjectID = :projectID");
        return $stmt->execute([':newTitle' => $newTitle, ':projectID' => $projectID]);
    }

    public function delete(int $projectID): bool {
        $pdo = $this->connect();
        try {
            $pdo->beginTransaction();
            $pdo->prepare("DELETE FROM emp_proy WHERE nProjectID = :projectID")->execute([':projectID' => $projectID]);
            $pdo->prepare("DELETE FROM project WHERE nProjectID = :projectID")->execute([':projectID' => $projectID]);
            $pdo->commit();
            return true;
        } catch (PDOException $e) {
            $pdo->rollBack();
            error_log('Delete error: ' . $e->getMessage());
            return false;
        }
    }

    public function addEmployee(int $projectID, int $employeeID): bool {
        $pdo = $this->connect();
        $stmt = $pdo->prepare("INSERT INTO emp_proy (nProjectID, nEmployeeID) VALUES (:projectID, :employeeID)");
        return $stmt->execute([':projectID' => $projectID, ':employeeID' => $employeeID]);
    }

    public function removeEmployee(int $projectID, int $employeeID): bool {
        $pdo = $this->connect();
        $stmt = $pdo->prepare("DELETE FROM emp_proy WHERE nProjectID = :projectID AND nEmployeeID = :employeeID");
        return $stmt->execute([':projectID' => $projectID, ':employeeID' => $employeeID]);
    }

    public function listEmployees(int $projectID): array {
        $pdo = $this->connect();
        $stmt = $pdo->prepare("
            SELECT e.nEmployeeID, CONCAT(e.cFirstName, ' ', e.cLastName) AS fullName
            FROM employee e
            JOIN emp_proy pe ON e.nEmployeeID = pe.nEmployeeID
            WHERE pe.nProjectID = :projectID
        ");
        $stmt->execute([':projectID' => $projectID]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
}
