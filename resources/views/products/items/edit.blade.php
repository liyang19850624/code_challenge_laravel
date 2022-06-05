@include('main.header', ['title' => 'Products', 'styleUrls' => ['/css/products/items/edit.css'], 'scripts' => ['/js/products/items/edit.js']])

@include('shared.heading', ['active' => 'Products'])
<div class="products-page-container">
    <h1 class="products-page-container__header">{{ $product ? 'Update' : 'Create' }} Product</h1>
    <div class="products-page-container__form">
        <div class="products-page-container__form-result" id="result-message">
        </div>
        <form onSubmit="return false">
            <div class="form-group">
                <label for="name">Product Name</label>
                <div class="form-control">
                    <input type="text" id="name" value="{{ $product ? $product->name : '' }}" aria-describedby="nameWarnHelpblock">
                    <span id="nameWarnHelpblock" class="help-block text-danger warnHelpblock"></span>
                </div>
            </div>
            <div class="form-group">
                <label for="name">Description</label>
                <div class="form-control">
                    <textarea rows="4" cols="70" id="description"  aria-describedby="descriptionWarnHelpblock">{{ $product ? $product->description : '' }}</textarea>
                    <span id="descriptionWarnHelpblock" class="help-block text-danger warnHelpblock"></span>
                </div>
            </div>
            <div class="form-group">
                <label for="name">Tags (split by ,)</label>
                <div class="form-control">
                    <input type="text" id="tags" value="{{ $product ? $product->tags : '' }}" size="70"  aria-describedby="tagsWarnHelpblock">
                    <span id="tagsWarnHelpblock" class="help-block text-danger warnHelpblock"></span>
                </div>
            </div>
            <div class="form-group">
                <label for="name">Image Url</label>
                <div class="form-control">
                    <input type="text" id="image_url" value="{{ $product ? $product->image_url : '' }}" size="70"  aria-describedby="image_urlWarnHelpblock">
                    <span id="image_urlWarnHelpblock" class="help-block text-danger warnHelpblock"></span>
                </div>
            </div>
            <div class="row">
                <div class="products-page-container__form-action-button">
                    @if($product)
                        <button class="btn btn-success" type="button" onClick="updateProduct('{{$product->id}}')">Update</button>
                        <button class="btn btn-danger" type="button" onClick="deleteProduct('{{$product->id}}')">Delete</button>
                    @else
                        <button class="btn btn-success" type="button" onClick="createProduct()">Create</button>
                    @endif
                </div>
            </div>

        </form>
    </div>
</div>

@include('main.footer')
