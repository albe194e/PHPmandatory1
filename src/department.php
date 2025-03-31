<?php

require_once 'database.php';
require_once 'logger.php';

Class Department extends Database 
{  
    /**
     * It retrieves all department from the database
     * @return An associative array with department information,
     *         or false if there was an error
     */
    function getAll(): array|false
    {
        $pdo = $this->connect();
        $sql =<<<SQL
            SELECT nDepartmentID, cName
            FROM department
            ORDER BY cName
        SQL;
        
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            Logger::logText('Error getting all departments: ', $e);
            return false;
        }
    }

    /**
     * It retrieves information regarding one department
     * @param $departmentID The ID of the department whose info to retrieve
     * @return An associative array with department information,
     *         or false if there was an error
     */
    function getByID(int $departmentID): array|false
    {
        $pdo = $this->connect();
        $sql =<<<SQL
            SELECT cName
            FROM department
            WHERE nDepartmentID = :departmentID;
        SQL;
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':departmentID', $departmentID);
            $stmt->execute();

            if ($stmt->rowCount() === 1) {
                return $stmt->fetch();
            }
            return false;
        } catch (PDOException $e) {
            Logger::logText('Error getting all departments: ', $e);
            return false;
        }
    }

    /**
     * Create a new department in the database
     * @param string $name The name of the department to create
     * @return bool True if the department was created successfully, false otherwise
     */
    public function create($name): bool
    {
        $pdo = $this->connect();
        $sql =<<<SQL
            INSERT INTO department (cName)
            VALUES (:name);
        SQL;

        try {
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':name', $name);
            return $stmt->execute();
        } catch (PDOException $e) {
            Logger::logText('Error creating department: ', $e);
            return false;
        }
    }

    /**
     * Update an existing department in the database
     * @param int $id The ID of the department to update
     * @param string $name The new name of the department
     * @return bool True if the department was updated successfully, false otherwise
     */
    public function update($id, $name): bool
    {
        $pdo = $this->connect();
        $sql =<<<SQL
            UPDATE department
            SET cName = :name
            WHERE nDepartmentID = :id;
        SQL;

        try {
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->bindValue(':name', $name);
            return $stmt->execute();
        } catch (PDOException $e) {
            Logger::logText('Error updating department: ', $e);
            return false;
        }
    }

    /**
     * Delete a department from the database
     * @param int $id The ID of the department to delete
     * @return bool True if the department was deleted successfully, false otherwise
     */
    public function delete($id): bool
    {
        $pdo = $this->connect();

        // Check if the department has employees
        $checkSql =<<<SQL
            SELECT COUNT(*) AS employeeCount
            FROM employee
            WHERE nDepartmentID = :id;
        SQL;

        try {
            $checkStmt = $pdo->prepare($checkSql);
            $checkStmt->bindValue(':id', $id, PDO::PARAM_INT);
            $checkStmt->execute();
            $result = $checkStmt->fetch();

            if ($result['employeeCount'] > 0) {
                // Department has employees, cannot delete
                return false;
            }

            // Proceed to delete the department
            $deleteSql =<<<SQL
                DELETE FROM department
                WHERE nDepartmentID = :id;
            SQL;

            $deleteStmt = $pdo->prepare($deleteSql);
            $deleteStmt->bindValue(':id', $id, PDO::PARAM_INT);
            return $deleteStmt->execute();
        } catch (PDOException $e) {
            Logger::logText('Error deleting department: ', $e);
            return false;
        }
    }

    /**
     * Search for departments in the database
     * @param string $query The search query
     * @return array|false An associative array with department information,
     *         or false if there was an error
     */
    public function search($query): array|false
    {
        $pdo = $this->connect();
        $sql =<<<SQL
            SELECT nDepartmentID, cName
            FROM department
            WHERE cName LIKE :query
            ORDER BY cName;
        SQL;

        try {
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':query', '%' . $query . '%');
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            Logger::logText('Error searching departments: ', $e);
            return false;
        }
    }
}