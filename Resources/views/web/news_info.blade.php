@extends('layouts.web')
<x-header/>

@section('headbottom')
<div class="pt-3 mb-4" style="background-color: #EBE9E9;">
  <div class="container">
    <h3 class="text-center fw-bold m-0 text-uppercase">{{ $info['title'] }}</h3>
    <div class="d-flex justify-content-center">{!! app('page')->getBreadCrumbs() !!}</div>
  </div>
</div>
@append
@section('content')
<section class="mt-2 mb-3" style="min-height: 40vh;">

  <div class="mt-0">
    <div class="m-auto">
      <div id="__news-info-description" class="ck-content overflow-hidden mt-4">
        <?php if (count($banners)): ?>
          <?php foreach ($banners as $banner): ?>
            <div id="banner-list-{{ $banner->id }}" class="swiper-container">
                <!-- Additional required wrapper -->
                <div class="swiper-wrapper">
                  <!-- Slides -->
                  <?php foreach ($banner->getImages() as $image): ?>
                  <div class="swiper-slide">
                    <div class="news-info-banner-item" style="background-image: url('{{ $image->image ? asset($image->image) : '' }}');"></div>
                  </div>
                  <?php endforeach; ?>
                </div>
                <!-- If we need navigation buttons -->
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
            </div>

            <script type="text/javascript">
              new Swiper('#banner-list-{{ $banner->id }}', {
                loop: true,
                // If we need pagination
                pagination: {
                  el: '#banner-list-{{ $banner->id }} .swiper-pagination',
                },
                navigation: {
                  nextEl: '#banner-list-{{ $banner->id }} .swiper-button-next',
                  prevEl: '#banner-list-{{ $banner->id }} .swiper-button-prev',
                },
              });
            </script>

          <?php endforeach; ?>
        <?php endif; ?>
        {!! $info->description !!}
      </div>
    </div>
</section>

@section('bottom')
  <?php
    $relatedData = ['news_id' =>  $info->id];
  ?>
  <div class="container">
    <x-news-related :data="$relatedData"/>
  </div>
@append
<style media="screen">
  body{
    background-color: #fff;
  }
  .news-banner{
    padding-top: 70%;
    background-repeat: no-repeat;
    background-position: center;
    background-size: cover;
  }
  .news-info-banner-item .breadcrumb-item a{
    color: #fff;
  }
  .news-info-banner-item .breadcrumb-item.active{
    color: #fff;
    font-weight: bold;
  }
</style>

<script type="text/javascript">
  $('#__news-info-description').renderIndex()
</script>
@endsection
<x-footer/>
