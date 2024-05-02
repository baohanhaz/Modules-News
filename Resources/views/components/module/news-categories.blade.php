<div class="container mt-3 mb-2">
  <div class="row">
      <?php if (!empty($categories)): ?>
        <?php foreach ($categories as $category): ?>
          <div class="col-12 col-sm-4">
              <h3 class="section-title text-uppercase h4 fw-bold p-2 border-bottom border-2 border-dark mb-3">{{ $category['name'] }}</h3>
              <?php if (!empty($category['news'])): ?>
                <div class="mb-2">
                  <?php foreach ($category['news'] as $new_key => $news): ?>
                    <div class="row">
                      <div class="col-5">
                        <a href="{{$news['link']}}"><div style="background-image: url('{{ $news['image'] ?? '' }}');padding-top: 75%;background-size: cover;background-position: center;" class="w-100"></div></a>
                      </div>
                      <div class="col-7">
                        <a href="{{$news['link']}}" class="text-dark"><p class="fw-bold mb-2">{{ mb_strimwidth($news['title'], 0, 90, "...") }}</p></a>
                        <p>{{ mb_strimwidth($news['short_description'], 0, 120, "...") }}</p>
                      </div>
                    </div>
                  <?php endforeach; ?>
                </div>
              <?php endif; ?>
            </div>
        <?php endforeach; ?>
      <?php endif; ?>
  </div>
</div>
