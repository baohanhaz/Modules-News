<?php $uuid = Str::uuid(); ?>
<?php if ($newss): ?>
  <?php if (!empty($position) && !in_array($position, ['left','right'])): ?>
    <h2>  Tin tức mới  </h2>
    <div id="swiper-{{ $uuid }}" class="swiper overflow-hidden">
      <div class="swiper-wrapper">
        <?php foreach ($newss as $news): ?>
          <div class="swiper-slide">
            <a href="{{ $news['link'] }}" class="text-dark">
              <div class="">
                <div data-src="{{ $news['thumbnail'] ?? asset('image/icons/news-icon.png') }}" class="lazyload news-img"></div>
                <div class="des-container p-3">
                  <p class="text-uppercase">{{ mb_strimwidth($news['title'], 0, 80, "...") }}</p>
                  <p class="short-des">{{ mb_strimwidth($news['short_description'], 0, 400, "...") }}</p>
                </div>
              </div>
            </a>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
    <style media="screen">
      .__component--NewsLastest h2{
        text-align: center;
      }
      .__component--NewsLastest a{
        text-decoration: none;
        font-size: 12px;
      }
      .__component--NewsLastest .news-img{
        padding-top: 65%;
        background-position: center;
        background-size: cover;
        background-repeat: no-repeat;
      }
      .__component--NewsLastest .short-des{
        font-style: italic;
      }
    </style>
    <script>
      var swiper = new Swiper("#swiper-{{ $uuid }}", {
        loop: true,
        slidesPerView: 3,
        spaceBetween: 10,
      });
    </script>
  <?php else: ?>
    <!-- Show as column -->
    <h3 class="text-uppercase h6 m-0 fw-bold p-1 bg-white border-bottom">{{ $name ?? __('Latest News') }}</h3>
    <div class="mb-2">
    <?php foreach ($newss as $news): ?>
    <a href="{{ $news['link'] }}">
      <div class="d-flex bg-white mb-1 text-dark w-100">
        <div class="row w-100">
          <div class="col-4 col-sm-4 col-lg-3 col-xl-3 pe-0">
            <div class="ratio ratio-4x3 w-100" style="background-image: url('{{ $news['thumbnail'] ?? asset('image/icons/news-icon.png') }}');background-position: center;background-repeat: no-repeat;background-size: cover;">

            </div>
          </div>
          <div class="col-8 col-sm-8 col-lg-9 col-xl-9">
            <div class="small">{{ mb_strimwidth($news['title'], 0, 160, "...") }}</div>
          </div>
        </div>
      </div>
    </a>
    <hr class="m-0">
    <?php endforeach; ?>
    </div>
    <style media="screen">
      .__component--NewsLastest a{
        text-decoration: none;
      }
      .__component--NewsLastest .img-container{
        width: 35%;
      }
      .__component--NewsLastest .des-container{
        width: 65%;
      }
    </style>
  <?php endif; ?>
<?php endif; ?>
