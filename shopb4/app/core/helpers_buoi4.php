<?php
// Copy ham ben duoi vao cuoi app/core/helpers.php cua du an Buoi 1-3.
// Ham nay can e(), base_url(), product_image_url() va format_vnd().

if (!function_exists('product_card_html')) {
    function product_card_html(array $product): string
    {
        $url = e(base_url('product/detail/' . $product['id']));
        $img = e(product_image_url($product['image'] ?? null, 'thumb'));
        $name = e($product['name']);
        $price = format_vnd($product['price']);

        return <<<HTML
        <div class="col">
            <a href="{$url}" class="text-decoration-none text-dark">
                <div class="card h-100 shadow-sm">
                    <img src="{$img}" class="card-img-top" loading="lazy" alt="{$name}">
                    <div class="card-body d-flex flex-column">
                        <h3 class="h6 card-title">{$name}</h3>
                        <p class="text-danger fw-bold mt-auto mb-0">{$price}</p>
                    </div>
                </div>
            </a>
        </div>
HTML;
    }
}
