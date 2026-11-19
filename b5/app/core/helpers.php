<?php
function base_url(string $path = ''): string
{
    return rtrim(BASE_URL, '/') . '/' . ltrim($path, '/');
}

function e($value): string
{
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}

// Tạo URL mới nhưng vẫn giữ các điều kiện lọc đang có.
function query_url(array $override = []): string
{
    $params = array_merge($_GET, $override);
    $params = array_filter($params, function ($value) {
        return $value !== '' && $value !== null && $value !== [];
    });

    $query = http_build_query($params);
    return base_url('product/search') . ($query !== '' ? '?' . $query : '');
}

// Tạo thanh phân trang Bootstrap.
function pagination_html(int $page, int $totalPages): string
{
    if ($totalPages <= 1) {
        return '';
    }

    $html = '<nav aria-label="Phân trang"><ul class="pagination justify-content-center flex-wrap">';
    $prevDisabled = $page <= 1 ? ' disabled' : '';
    $prevUrl = e(query_url(['page' => max(1, $page - 1)]));
    $html .= "<li class=\"page-item{$prevDisabled}\"><a class=\"page-link\" href=\"{$prevUrl}\">Trước</a></li>";

    for ($i = 1; $i <= $totalPages; $i++) {
        $active = $i === $page ? ' active' : '';
        $url = e(query_url(['page' => $i]));
        $html .= "<li class=\"page-item{$active}\"><a class=\"page-link\" href=\"{$url}\">{$i}</a></li>";
    }

    $nextDisabled = $page >= $totalPages ? ' disabled' : '';
    $nextUrl = e(query_url(['page' => min($totalPages, $page + 1)]));
    $html .= "<li class=\"page-item{$nextDisabled}\"><a class=\"page-link\" href=\"{$nextUrl}\">Sau</a></li>";
    $html .= '</ul></nav>';

    return $html;
}

function product_card_html(array $product): string
{
    $name = e($product['name']);
    $price = number_format((float)$product['price'], 0, ',', '.') . ' đ';
    $image = trim((string)($product['image'] ?? ''));
    $imageUrl = $image !== '' ? base_url('uploads/' . rawurlencode($image)) : base_url('assets/img/no-image.svg');

    return '<div class="col">
        <div class="card product-card h-100">
            <img src="' . e($imageUrl) . '" class="card-img-top product-image" alt="' . $name . '"
                 onerror="this.src=\'' . e(base_url('assets/img/no-image.svg')) . '\'">
            <div class="card-body d-flex flex-column">
                <h3 class="h6">' . $name . '</h3>
                <p class="text-muted small mb-2">SKU: ' . e($product['sku']) . '</p>
                <p class="price mt-auto mb-0">' . e($price) . '</p>
                <p class="small text-muted mb-0">Thương hiệu: ' . e($product['brand']) . '</p>
            </div>
        </div>
    </div>';
}
