@include('main.header', [
    'title' => 'Products',
    'styleUrls' => ['./css/products/items/overview.css', './css/products/view.css'],
])

@include('shared.heading', ['active' => 'Products'])

<div class="products-page-container">
    <div class="products-page-container__items">
        <div class="row">
            @foreach ($products as $product)
                @include('products.items.overview', ['product' => $product])
            @endforeach
        </div>
    </div>
</div>

@include('main.footer')
