<div class="card card-stats pt-2 pb-2">
  <div class="card-header card-header-default card-header-icon bg-white border-0">
    <p class="card-category">@lang('News Total')</p>
    <h3 class="card-title">{{ $total }}</h3>
  </div>
  <div class="card-footer">
    <div class="stats w-100 d-flex justify-content-between">
      <i class="material-icons fa fa-newspaper"></i>
      <a href="{{ $see_more }}">@lang('See more')</a>
    </div>
  </div>
</div>
