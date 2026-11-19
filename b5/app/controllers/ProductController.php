<?php
class ProductController extends Controller
{
    private const PER_PAGE = 12;

    public const SORT_LABELS = [
        'newest' => 'Mới nhất',
        'price_asc' => 'Giá tăng dần',
        'price_desc' => 'Giá giảm dần',
        'bestseller' => 'Bán chạy'
    ];

    public function search(): void
    {
        Database::$queryCount = 0;

        $filters = [
            'q' => trim((string)($_GET['q'] ?? '')),
            'category' => $_GET['category'] ?? '',
            'brand' => $_GET['brand'] ?? [],
            'price_from' => $_GET['price_from'] ?? '',
            'price_to' => $_GET['price_to'] ?? '',
            'sort' => $_GET['sort'] ?? 'newest'
        ];

        $page = max(1, (int)($_GET['page'] ?? 1));
        $result = Product::search($filters, $page, self::PER_PAGE);

        $this->view('product/search', [
            'pageTitle' => 'Tìm kiếm sản phẩm',
            'filters' => $filters,
            'categories' => Category::activeList(),
            'brands' => Product::distinctBrands(),
            'result' => $result,
            'sortLabels' => self::SORT_LABELS
        ]);
    }
}
