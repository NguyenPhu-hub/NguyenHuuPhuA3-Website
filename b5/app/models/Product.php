<?php
class Product extends Model
{
    protected static string $table = 'products';

    // Chỉ những cột này mới được phép dùng trong ORDER BY.
    public const SORT_OPTIONS = [
        'newest' => 'p.created_at DESC',
        'price_asc' => 'p.price ASC',
        'price_desc' => 'p.price DESC',
        'bestseller' => 'p.sold_count DESC'
    ];

    public static function search(array $filters, int $page = 1, int $perPage = 12): array
    {
        $where = ["p.status = 'active'"];
        $params = [];

        $keyword = trim((string)($filters['q'] ?? ''));
        if ($keyword !== '') {
            $where[] = '(p.name LIKE :kw1 OR p.sku LIKE :kw2 OR p.description LIKE :kw3)';
            $params['kw1'] = '%' . $keyword . '%';
            $params['kw2'] = '%' . $keyword . '%';
            $params['kw3'] = '%' . $keyword . '%';
        }

        $categoryId = (int)($filters['category'] ?? 0);
        if ($categoryId > 0) {
            $where[] = 'p.category_id = :category_id';
            $params['category_id'] = $categoryId;
        }

        $brands = array_filter((array)($filters['brand'] ?? []), fn($b) => trim((string)$b) !== '');
        if (!empty($brands)) {
            $placeholders = [];
            foreach (array_values($brands) as $i => $brand) {
                $key = 'brand' . $i;
                $placeholders[] = ':' . $key;
                $params[$key] = $brand;
            }
            $where[] = 'p.brand IN (' . implode(',', $placeholders) . ')';
        }

        if (($filters['price_from'] ?? '') !== '' && is_numeric($filters['price_from'])) {
            $where[] = 'p.price >= :price_from';
            $params['price_from'] = (float)$filters['price_from'];
        }

        if (($filters['price_to'] ?? '') !== '' && is_numeric($filters['price_to'])) {
            $where[] = 'p.price <= :price_to';
            $params['price_to'] = (float)$filters['price_to'];
        }

        $whereSql = implode(' AND ', $where);

        // Không đưa trực tiếp dữ liệu từ URL vào SQL.
        $sortKey = (string)($filters['sort'] ?? 'newest');
        $orderBy = self::SORT_OPTIONS[$sortKey] ?? self::SORT_OPTIONS['newest'];

        $page = max(1, $page);
        $perPage = max(1, $perPage);
        $offset = ($page - 1) * $perPage;

        $countSql = "SELECT COUNT(*) FROM products p WHERE {$whereSql}";
        $countStmt = self::db()->prepare($countSql);
        $countStmt->execute($params);
        Database::$queryCount++;
        $total = (int)$countStmt->fetchColumn();

        // LEFT JOIN lấy ảnh đại diện trong cùng truy vấn, tránh N+1.
        $sql = "SELECT p.*, pi.file_name AS image
                FROM products p
                LEFT JOIN product_images pi
                    ON pi.product_id = p.id AND pi.is_primary = 1
                WHERE {$whereSql}
                ORDER BY {$orderBy}
                LIMIT :limit OFFSET :offset";

        $stmt = self::db()->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        Database::$queryCount++;
        $items = $stmt->fetchAll();

        return [
            'items' => $items,
            'total' => $total,
            'page' => $page,
            'perPage' => $perPage,
            'totalPages' => $total > 0 ? (int)ceil($total / $perPage) : 1
        ];
    }

    public static function distinctBrands(): array
    {
        $sql = "SELECT DISTINCT brand FROM products
                WHERE status = 'active' AND brand IS NOT NULL AND brand != ''
                ORDER BY brand ASC";
        return self::db()->query($sql)->fetchAll(PDO::FETCH_COLUMN);
    }
}
