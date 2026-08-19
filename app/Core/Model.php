<?php

namespace App\Core;

use PDO;

/**
 * Base Model
 *
 * Provides common database operations for all models.
 * Each child model defines its own $table and $fillable.
 */
abstract class Model
{
    protected string $table = '';
    protected string $primaryKey = 'id';

    /**
     * Get the PDO connection.
     */
    protected function db(): PDO
    {
        return Database::getConnection();
    }

    /**
     * Find a record by primary key.
     */
    public function find(int $id): ?array
    {
        $stmt = $this->db()->prepare(
            "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ?"
        );
        $stmt->execute([$id]);
        $result = $stmt->fetch();

        return $result ?: null;
    }

    /**
     * Get all records.
     */
    public function all(string $orderBy = 'id DESC'): array
    {
        return $this->db()->query(
            "SELECT * FROM {$this->table} ORDER BY {$orderBy}"
        )->fetchAll();
    }

    /**
     * Find records by a column value.
     */
    public function where(string $column, mixed $value): array
    {
        $stmt = $this->db()->prepare(
            "SELECT * FROM {$this->table} WHERE {$column} = ?"
        );
        $stmt->execute([$value]);
        return $stmt->fetchAll();
    }

    /**
     * Find a single record by column value.
     */
    public function findBy(string $column, mixed $value): ?array
    {
        $stmt = $this->db()->prepare(
            "SELECT * FROM {$this->table} WHERE {$column} = ? LIMIT 1"
        );
        $stmt->execute([$value]);
        $result = $stmt->fetch();

        return $result ?: null;
    }

    /**
     * Insert a new record.
     *
     * @return int The inserted ID
     */
    public function create(array $data): int
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));

        $stmt = $this->db()->prepare(
            "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})"
        );
        $stmt->execute(array_values($data));

        return (int)$this->db()->lastInsertId();
    }

    /**
     * Update a record by primary key.
     */
    public function update(int $id, array $data): bool
    {
        $set = implode(' = ?, ', array_keys($data)) . ' = ?';

        $stmt = $this->db()->prepare(
            "UPDATE {$this->table} SET {$set} WHERE {$this->primaryKey} = ?"
        );

        $values = array_values($data);
        $values[] = $id;

        return $stmt->execute($values);
    }

    /**
     * Delete a record by primary key.
     */
    public function delete(int $id): bool
    {
        $stmt = $this->db()->prepare(
            "DELETE FROM {$this->table} WHERE {$this->primaryKey} = ?"
        );
        return $stmt->execute([$id]);
    }

    /**
     * Count records, optionally with a condition.
     */
    public function count(?string $condition = null, array $params = []): int
    {
        $sql = "SELECT COUNT(*) FROM {$this->table}";
        if ($condition) {
            $sql .= " WHERE {$condition}";
        }

        $stmt = $this->db()->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    }
}
