<?php

require_once 'database.php';
require_once 'department.php';
require_once 'logger.php';

Class Project extends Database
{
    
    /**
     * It retrieves all projects from the database
     * @return An associative array with project information,
     *         or false if there was an error
     */
    function getAll(): array|false
    {
        $pdo = $this->connect();
        $sql =<<<SQL
            SELECT nProjectID, cName
            FROM project
            ORDER BY cName;
        SQL;

        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute();

            return $stmt->fetchAll();
        } catch (PDOException $e) {
            Logger::logText('Error getting all projects: ', $e);
            return false;
        }
    }

    /**
     * It retrieves a project by its ID from the database
     * @param int $projectID The ID of the project to retrieve
     * @return An associative array with project information,
     *         or false if there was an error
     */
    function getByID(int $projectID): array|false
    {
        $pdo = $this->connect();
        $sql =<<<SQL
            SELECT nProjectID, cName
            FROM project
            WHERE nProjectID = :projectID;
        SQL;

        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute();

            return $stmt->fetchAll();
        } catch (PDOException $e) {
            Logger::logText('Error getting project by ID: ', $e);
            return false;
        }
    }
}
