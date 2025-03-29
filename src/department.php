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
        $sql =<<<SQL
            DELETE FROM department
            WHERE nDepartmentID = :id;
        SQL;

        try {
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            Logger::logText('Error deleting department: ', $e);
            return false;
        }
    }
}