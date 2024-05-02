<nav class="navbar navbar-expand-lg ncmenu-navbar">
  <div class="container">
    <ul class="navbar-nav w-100">
      <li class="nav-item text-nowrap">
        <a class="nav-link p-2 m-0" href="{{ route('home') }}"><i class="fa fa-home"></i></a>
      </li>
      <?php foreach ($categories as $category): ?>
        <li class="nav-item text-nowrap">
          <a class="nav-link p-2 m-0" href="{{ route('news.category', $category->slug) }}">{{ $category->name }}</a>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
</nav>
<style media="screen">
  .ncmenu-navbar .navbar-nav{
    flex-direction: unset;
    overflow: hidden;
    flex-direction: unset;
    overflow-x: auto;
  }
  /* Hide scrollbar for Chrome, Safari and Opera */
  .ncmenu-navbar .navbar-nav::-webkit-scrollbar {
    display: none;
  }

  /* Hide scrollbar for IE, Edge and Firefox */
  .ncmenu-navbar .navbar-nav {
    -ms-overflow-style: none;  /* IE and Edge */
    scrollbar-width: none;  /* Firefox */
  }

  .ncmenu-navbar .nav-item{
    width: fit-content;
  }
</style>
