<link href="/css/shared/heading.css" rel="stylesheet">
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Food by us</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a @class(['nav-link', 'active' => !isset($active) || $active == 'Home']) aria-current="page" href="/">Home</a>
                </li>
                <li class="nav-item">
                    <a @class([
                        'nav-link',
                        'active' => isset($active) && $active == 'About us',
                    ]) href="/about-us">About us</a>
                </li>
                <li class="nav-item dropdown">
                    <a @class([
                        'nav-link',
                        'dropdown-toggle',
                        'active' => isset($active) && $active == 'Products',
                    ]) href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        Products
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="/products">View</a></li>
                        <li><a class="dropdown-item" href="/product/new">Create</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
