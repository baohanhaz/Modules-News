@extends('layouts.web')
<x-header/>

@section('content')
<h2 class="mt-3">@lang('News')</h2>
<?php if ($newss): ?>
<section id="news-list" style="min-height: 40vh;">
  <div class="container">
    <div class="">
      <div class="row">
        <?php foreach ($newss as $key => $news): ?>
          <?php if ($key==0): ?>
            <div class="col-12 mb-2">
              <div class="row g-0">
                <div class="col-12 col-sm-8 pe-0">
                  <a href="{{ url($news['slug']) }}" class="">
                    <div class="ratio ratio-16x9 new-img" style="background-image: url('{{ $news['image'] ?? asset('image/icons/news-icon.png') }}');">

                    </div>
                  </a>
                </div>
                <div class="col-12 col-sm-4 p-3" style="background-color: #e4e3e3;">
                  <a href="{{ url($news['slug']) }}" class="text-dark small"><h3 class="h4 fw-bold m-0">{{ mb_strimwidth($news['title'], 0, 35, "...")  }}</h3></a>
                  <div class="text-secondary mt-2">{{ mb_strimwidth($news['short_description'], 0, 110, " [...]")  }}</div>
                </div>
              </div>
            </div>
          <?php elseif ($key==1 || $key==2): ?>
            <div class="col-12 col-sm-4 col-lg-4 mb-2 p-3 {{ $key==2 ? 'border-left' : '' }}">
              <a href="{{ url($news['slug']) }}" class="text-dark small h-45px"><h3 class="h4 fw-bold m-0">{{ mb_strimwidth($news['title'], 0, 40, "...")  }}</h3></a>
              <div class="text-secondary small mt-2 h-75px">{{ mb_strimwidth($news['short_description'], 0, 140, " [...]")  }}</div>
            </div>
          <?php elseif ($key==3): ?>
            <div class="col-12 col-sm-4 col-lg-4 mb-2 p-3 border-left">
              <a href="{{ url($news['slug']) }}" class="text-dark small h-45px"><h3 class="h4 fw-bold m-0">{{ mb_strimwidth($news['title'], 0, 40, "...")  }}</h3></a>
              <div class="text-secondary small mt-2 h-75px">{{ mb_strimwidth($news['short_description'], 0, 140, " [...]")  }}</div>
            </div>
          <?php else: ?>
            <div class="col-12 mb-2">
              <div class="row g-0">
                <div class="col-12 col-sm-4 col-lg-3 pe-0">
                  <a href="{{ url($news['slug']) }}">
                    <div class="news-banner" style="background-image: url('{{ $news['image'] ?? asset('image/icons/news-icon.png') }}');"></div>
                  </a>
                </div>
                <div class="col-12 col-sm-8 col-lg-9 p-3" style="background-color: #e4e3e3;">
                  <a href="{{ url($news['slug']) }}" class="text-dark small"><h3 class="h4 fw-bold m-0">{{ mb_strimwidth($news['title'], 0, 35, "...")  }}</h3></a>
                  <div class="text-secondary mt-2">{{ mb_strimwidth($news['short_description'], 0, 110, " [...]")  }}</div>
                </div>
              </div>
            </div>
          <?php endif; ?>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
  <hr>
</section>
<?php endif; ?>
<style media="screen">
  .timepost .user-info {
    float: left;
    width: auto;
    margin-right: 10px;
  }
  .h-45px{
    height: 45px;
    overflow: hidden;
  }
  .h-75px{
    height: 75px;
    overflow: hidden;
  }
  #news-list a{
    text-decoration: none;
  }
  #news-list .news-banner{
    padding-top: 70%;
    background-position: center;
    background-size: cover;
    background-repeat: no-repeat;
  }
  .new-img{
    background-size: cover;
    background-repeat: no-repeat;
  }
  @media (max-width: 767px){
    .h-45px, .h-75px{
      height: unset;
    }
  }
</style>
@endsection
<x-footer/>
