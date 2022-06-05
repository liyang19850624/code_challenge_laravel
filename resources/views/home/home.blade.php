@include('main.header', ['title' => 'Home', 'styleUrls' => ['./css/home/home.css']])

@include('shared.heading', ['active' => 'Home'])

<div class="home-page-container">
    <div class="home-page-container__content">
        <div class="home-page-container__content-article">
            <div class="home-page-container__content-article-paragraph">
                home information
            </div>
        </div>
        <div class="home-page-container__content-label">Home</div>
    </div>
</div>

@include('main.footer')
