@if (!empty($newss))
<?php $uuid = Str::uuid(); ?>
<div class="mt-3 mb-3">
  <h4 class="fw-bold mb-2 h1">Tin tức liên quan</h4>
  <div id="news-list-{{ $uuid }}" class="swiper-container">
      <!-- Additional required wrapper -->
      <div class="swiper-wrapper">
        <!-- Slides -->
        <?php foreach ($newss as $news): ?>
        <div class="swiper-slide">
          <div class="card">
            <a href="{{ $news['link'] }}" class="text-dark">
              <div class="news-banner" style="background-image: url('{{ $news['image'] ? $news['image'] : asset('image/icons/news-icon.png') }}');"></div>
              <div class="card-body">
                <p class="fw-bold">{{ $news['title']  }}</p>
                <p class="small">{{ mb_strimwidth($news['short_description'], 0, 90, "...")  }}</p>
              </div>
            </a>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <!-- If we need navigation buttons -->
      <div class="swiper-button-prev"></div>
      <div class="swiper-button-next"></div>
  </div>
</div>
<script type="text/javascript">
  var mySwiper = new Swiper('#news-list-{{ $uuid }}', {
    loop: true,
    // If we need pagination
    pagination: {
      el: '#news-list-{{ $uuid }} .swiper-pagination',
    },
    navigation: {
      nextEl: '#news-list-{{ $uuid }} .swiper-button-next',
      prevEl: '#news-list-{{ $uuid }} .swiper-button-prev',
    },
    // Default parameters
    slidesPerView: 1,
    spaceBetween: 10,
    // Responsive breakpoints
    breakpoints: {
      // when window width is >= 480px
      480: {
        slidesPerView: 1,
        spaceBetween: 10
      },
      // when window width is >= 640px
      640: {
        slidesPerView: 2,
        spaceBetween: 20
      },
      // when window width is >= 768px
      768: {
        slidesPerView: 3,
        spaceBetween: 30
      },
      1024: {
        slidesPerView: 4,
        spaceBetween: 30
      }
    }
  });
</script>
@endif