<?php if (!empty($news)): ?>
<?php $uuid = Str::uuid(); ?>
<div class="">
  <div class="container p-4">
    <div class="h3 module-title fw-bold mb-3 text-uppercase d-flex flex-wrap justify-content-center align-items-center">
      <span>{{ $name ?? '' }}</span>
      <div class="input-group ms-2" style="width: 200px;height: 40px;">
        <div class="">
          <span class="input-group-text"><i class="fa fa-search"></i></span>
        </div>
        <input type="text" name="key" class="form-control" placeholder="Your question?" autocomplete="off">
      </div>
    </div>
    <p class="fw-bold text-center mb-3">{{ $description ?? '' }}</p>
    <!-- Slider main container -->
    <div id="news-list-{{ md5($uuid) }}" class="swiper-container">
        <!-- Additional required wrapper -->
        <div class="swiper-wrapper">
          <!-- Slides -->
          <?php foreach ($news as $new): ?>
          <div class="swiper-slide">
            <div class="card">
              <a href="{{ route('news.info',$new->slug) }}">
                <img class="card-img-top" src="{{ $new->image ?? asset('image/icons/news-icon.png') }}">
                <div class="card-body">
                  <p class="fw-bold" style="height: 40px;">{{ mb_strimwidth($new->title, 0, 40, "...")  }}</p>
                  <p class="small" style="height: 50px;">{{ mb_strimwidth($new->short_description, 0, 90, "...")  }}</p>
                </div>
              </a>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
        <!-- If we need navigation buttons -->
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
        <div class="text-center mt-2">
          <a href="{{ route('news') }}" class="btn btn-outline-primary">@lang('See more')</a>
        </div>
    </div>
  </div>

</div>


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
});
</script>
<script type="text/javascript">
  $('input[name="key"]').keypress(function (e) {
    if (e.which == 13) {
      setTimeout(function(){
        let url = 'http://google.com/search?q={{ config('app.url') }}+' + encodeURIComponent($('input[name="key"]').val());
        console.log(url);
        window.location = url;
      },10)
      return false;
    }
  });
</script>
<?php endif; ?>
