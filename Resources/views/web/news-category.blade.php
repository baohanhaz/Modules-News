@extends('layouts.web')
<x-header/>
@section('headbottom')
<div class="pt-3" style="background-color: #EBE9E9;">
  <div class="container">
    <h3 class="text-center fw-bold m-0 text-uppercase">{{ $category['name'] ?? '' }}</h3>
    <div class="d-flex justify-content-center">{!! app('page')->getBreadCrumbs() !!}</div>
  </div>
</div>
@append
@section('content')
  <section class="pt-3 pb-3">
    <?php if ($childs): ?>
      <div class="border-bottom mb-3 pt-1 pb-1">
        <?php foreach ($childs as $child): ?>
          <a href="{{ $child['link'] }}" class="fw-bold text-dark me-2">{{ $child['name'] }}</a>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
    <div class="">
      <div class="row">
        <?php foreach ($newss as $key => $news): ?>
          <?php if ($key==0): ?>
            <div class="col-12 mb-2">
              <a href="{{ url($news->slug) }}" class="text-dark small">
                <div class="row g-0">
                  <div class="col-12 col-sm-8 pe-0">
                    <div href="{{ route('news.info', $news->slug) }}" class="ratio ratio-16x9 news-img-preview" style="background-image: url('{{ $news->image ? asset($news->image) : asset('image/icons/news-icon.png') }}')"></div>
                  </div>
                  <div class="col-12 col-sm-4 p-3" style="background-color: #e4e3e3;">
                    <h3 class="h4 fw-bold m-0">{{ $news->title  }}</h3>
                    <div class="text-secondary mt-2">{{ $news->short_description  }}</div>
                  </div>
                </div>
              </a>
            </div>
          <?php elseif ($key==1 || $key==2): ?>
            <div class="col-12 col-sm-4 col-lg-4 mb-2 p-3 {{ $key==2 ? 'border-left' : '' }}">
              <a href="{{ url($news->slug) }}" class="text-dark small">
                <h3 class="h4 fw-bold m-0">{{ $news->title  }}</h3>
                <div class="text-secondary small mt-2 h-75px">{{ $news->short_description  }}</div>
              </a>
            </div>
          <?php elseif ($key==3): ?>
            <div class="col-12 col-sm-4 col-lg-4 mb-2 p-3 border-left">
              <a href="{{ url($news->slug) }}" class="text-dark small">
                <h3 class="h4 fw-bold m-0">{{ $news->title  }}</h3>
                <div class="text-secondary small mt-2 h-75px">{{ $news->short_description  }}</div>
              </a>
            </div>
          <?php else: ?>
            <div class="col-12 mb-2">
              <a href="{{ url($news->slug) }}">
                <div class="row g-0">
                  <div class="col-12 col-sm-4 col-lg-3 pe-0">
                    <div class="news-banner" style="background-image: url('{{ $news->image ? asset($news->image) : asset('image/icons/news-icon.png') }}')"></div>
                  </div>
                  <div class="col-12 col-sm-8 col-lg-9 p-3" style="background-color: #e4e3e3;">
                    <h3 class="h4 fw-bold m-0">{{ $news->title }}</h3>
                    <div class="text-secondary mt-2">{{ $news->short_description  }}</div>
                  </div>
                </div>
              </a>
            </div>
          <?php endif; ?>
        <?php endforeach; ?>
      </div>
      <nav class="mt-4 d-flex justify-content-center">
        <ul class="pagination">
          <li class="page-item">
            <a class="page-link" href="{{ route('news.category', ['category_id' => $category->slug,'page' => max($page - 1, 1)]) }}" aria-label="Previous">
              <span aria-hidden="true">&laquo;</span>
            </a>
          </li>
          <?php if ($page >= 3): ?>
            <li class="page-item disabled"><a class="page-link">...</a></li>
          <?php endif; ?>
          <?php for ($i= max($page - 1, 1); $i <= min($page + 1, $total_page);$i++): ?>
            <?php if ($page == $i): ?>
              <li class="page-item disabled"><a class="page-link">{{ $page }}</a></li>
            <?php else: ?>
              <li class="page-item"><a class="page-link" href="{{ route('news.category', ['category_id' => $category->slug,'page' => $i]) }}">{{ $i }}</a></li>
            <?php endif; ?>
          <?php endfor; ?>
          <?php if ($page <= $total_page - 3): ?>
            <li class="page-item disabled"><a class="page-link">...</a></li>
          <?php endif; ?>
          <li class="page-item">
            <a class="page-link" href="{{ route('news.category', ['category_id' => $category->slug,'page' => min($page + 1, $total_page)]) }}" aria-label="Next">
              <span aria-hidden="true">&raquo;</span>
            </a>
          </li>
        </ul>
      </nav>
    </div>
    <style media="screen">
      .news-banner{
        padding-top: 70%;
        background-repeat: no-repeat;
        background-position: center;
        background-size: cover;
      }
      .news-img-preview{
        background-repeat: no-repeat;
        background-position: center;
        background-size: cover;
      }
    </style>
  </section>
@endsection
<x-footer/>
