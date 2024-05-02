<div class=""  style="background: #043265;">
  <div class="container p-4">
    <div class="text-center h3 text-white fw-bold mb-3">GÓC CHIA SẺ</div>
    <p class="text-white fw-bold text-center mb-3">Cập nhật những từ vựng thông dụng và chia sẻ các bí quyết học tiếng anh có một không hai…</p>
    <!-- Slider main container -->
    <div id="new-lastest-list" class="swiper-container">
        <!-- Additional required wrapper -->
        <div class="swiper-wrapper">
          <!-- Slides -->
          <?php for ($i=1;$i<6;$i++): ?>
          <div class="swiper-slide">
            <div class="card">
              <a href="#"><img class="card-img-top" src="https://onelifeenglish.edu.vn/wp-content/uploads/2020/06/8252d519c43a3f64662b-295x400.png"></a>
            </div>
          </div>
          <?php endfor; ?>
        </div>
        <!-- If we need navigation buttons -->
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
    </div>
  </div>
</div>
<script type="text/javascript">
  $(document).ready(function(){
    var mySwiper = new Swiper('#new-lastest-list', {
      loop: true,
      // If we need pagination
      pagination: {
        el: '#new-lastest-list .swiper-pagination',
      },
      navigation: {
        nextEl: '#new-lastest-list .swiper-button-next',
        prevEl: '#new-lastest-list .swiper-button-prev',
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
  })
</script>
