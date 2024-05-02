<?php if ($category && $newss): ?>
  <?php $uuid = Str::uuid(); ?>
  <div class="mt-3 mb-3">
    <div class="bg-white">
      <h3 class="text-uppercase h4 fw-bold p-2 border-bottom">{{ $category['name'] ?? '' }}</h3>
      <!-- Slider main container -->
      <div id="news-list-{{ md5($uuid) }}" class="swiper-container">
          <!-- Additional required wrapper -->
          <div class="swiper-wrapper">
            <!-- Slides -->
            <?php foreach ($newss as $key => $new): ?>
            <div class="swiper-slide h-100">
              <a href="{{ $new['link'] }}" class="col-6 col-sm-4 col-md-3 bg-white text-dark h-100">
                <div class="product-item rounded-0 h-100 pt-2 pb-2">
                  <div class="ratio ratio-4x3" style="background-image: url('{{ $new['image'] }}');background-position: center;background-repeat: no-repeat;background-size: cover;"> </div>
                  <div class="card-body">
                    <p style="height: 50px;">{{ mb_strimwidth($new['title'], 0, 40, "...") }}</p>

                  </div>
                </div>
              </a>
            </div>
            <?php endforeach; ?>
          </div>
          <!-- If we need navigation buttons -->
          <div class="swiper-button-prev"></div>
          <div class="swiper-button-next"></div>
      </div>
    </div>
  </div>
  <style media="screen">
    .product-item:hover{
      box-shadow: 0px 12px 19px -7px rgba(0, 0, 0, 0.3);
    }
  </style>
  <script type="text/javascript">
  $(document).ready(function(){
    var mySwiper = new Swiper('#news-list-{{ md5($uuid) }}', {
      loop: true,
      // If we need pagination
      pagination: {
        el: '#news-list-{{ md5($uuid) }} .swiper-pagination',
      },
      navigation: {
        nextEl: '#news-list-{{ md5($uuid) }} .swiper-button-next',
        prevEl: '#news-list-{{ md5($uuid) }} .swiper-button-prev',
      },
      // Default parameters
      slidesPerView: 2,
      spaceBetween: 5,
      // Responsive breakpoints
      breakpoints: {
        // when window width is >= 480px
        480: {
          slidesPerView: 2,
          spaceBetween: 5
        },
        // when window width is >= 640px
        640: {
          slidesPerView: 3,
          spaceBetween: 5
        },
        // when window width is >= 768px
        768: {
          slidesPerView: 3,
          spaceBetween: 5
        },
        1024: {
          slidesPerView: 5,
          spaceBetween: 5
        }
      }
    });
  });
  </script>

<?php endif; ?>
