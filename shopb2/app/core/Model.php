<?php
class Model
{
    protected static string $table = '';

    protected static function db(): PDO
    {
        return Database::connect();
    }

    public static function find(int $id): ?array
    {
        $stmt = self::db()->prepare('SELECT * FROM ' . static::$table . ' WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function all(string $orderBy = 'id DESC'): array
    {
        // orderBy chỉ nhận chuỗi nội bộ từ controller, không nhận trực tiếp từ người dùng.
        return self::db()->query('SELECT * FROM ' . static::$table . ' ORDER BY ' . $orderBy)->fetchAll();
    }

    public static function insert(array $data): int
    {
        $columns = array_keys($data);
        $sql = 'INSERT INTO ' . static::$table . ' (' . implode(', ', $columns) . ')
                VALUES (:' . implode(', :', $columns) . ')';
        $stmt = self::db()->prepare($sql);
        $stmt->execute($data);
        return (int)self::db()->lastInsertId();
    }

    public static function update(int $id, array $data): bool
    {
        if (!$data) return false;
        $sets = [];
        foreach ($data as $column => $value) {
            $sets[] = "$column = :$column";
        }
        $data['id'] = $id;
        $stmt = self::db()->prepare(
            'UPDATE ' . static::$table . ' SET ' . implode(', ', $sets) . ' WHERE id = :id'
        );
        return $stmt->execute($data);
    }
}
