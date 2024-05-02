<div class="">
  <p class="fw-bold mb-3 module-title">Xem nhiều nhất</p>
  <?php foreach ($newss as $news): ?>
    <a href="{{ route('news.info',$news->slug) }}" class="text-dark">
      <div class="ratio ratio-16x9">
        <img src="{{ $news->image ?? asset('image/icons/news-icon.png') }}" class="">
      </div>
      <div class="mt-2">
        {{ mb_strimwidth($news->title, 0, 50, "...") }}
      </div>
    </a>
    <hr>
  <?php endforeach; ?>
</div>
