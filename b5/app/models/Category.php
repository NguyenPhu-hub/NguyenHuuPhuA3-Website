<?php
class Category extends Model
{
    public static function activeList(): array
    {
        $sql = "SELECT id, name FROM categories WHERE status = 'active' ORDER BY name ASC";
        return self::db()->query($sql)->fetchAll();
    }
}
