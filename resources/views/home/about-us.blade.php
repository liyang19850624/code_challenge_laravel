@include('main.header', ['title' => 'Home', 'styleUrls' => ['./css/home/about-us.css']])

@include('shared.heading', ['active' => 'About us'])

<div class="home-page-container">
    <div class="home-page-container__content">
        <div class="home-page-container__content-label">About Us</div>
        <div class="home-page-container__content-article">
            <div class="home-page-container__content-article-paragraph">
                about us information
            </div>
        </div>
    </div>
</div>

@include('main.footer')
