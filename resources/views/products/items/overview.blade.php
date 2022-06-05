<div class="product-item-container">
    <a class="list-group-item clearfix product-item" href="/product/{{ $product->id }}/edit">
        <div class="pull-left product-item__detail">
            <h4 class="list-group-item-heading">{{ $product->name }}</h4>
            <p class="list-group-item-text">
                @if (empty($product->description))
                    <i>No Description</i>
                @else
                    {{ $product->description }}
                @endif
            </p>
            @if (!empty($product->tags))
                <p class="list-group-item-text">
                    <i>Tag: {{ $product->tags }}</i>
                </p>
            @endif
        </div>
        @if ($product->image_url)
            <span class="pull-right product-item__image">
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="img-responsive" />
            </span>
        @endif
    </a>
</div>
