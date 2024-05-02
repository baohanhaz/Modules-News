<h3 class="text-uppercase h6 m-0 fw-bold p-1">{{ $name ?? '' }}</h3>

<ul class="hot-lst fw-bold">
  <?php foreach ($hashtags as $key => $hashtag): ?>
    <li>
        <a href="{{ route('hashtag.info', $hashtag->slug) }}" target="_blank">{{ $hashtag->title }}</a>
    </li>
  <?php endforeach; ?>
</ul>

<style media="screen">
  .hot-lst {
    position: relative;
    overflow: hidden;
    list-style: none;
    padding: 10px 0px 10px 5px;
    border: 1px solid #ccc;
    background-color: #fff;
  }
  .hot-lst li a {
    overflow: visible;
    background: #0078d7;
    float: left;
    position: relative;
    color: #fff;
    font-size: 11px;
    margin: 2px 5px 7px 12px;
    padding: 0 15px 0 12px;
    height: 24px;
    line-height: 24px;
    border-top-right-radius: 4px;
    border-bottom-right-radius: 4px;
    text-decoration: none;
  }
  .hot-lst li a:before {
    content: "";
    float: left;
    position: absolute;
    top: 0;
    left: -12px;
    width: 0;
    height: 0;
    border-color: transparent #0078d7 transparent transparent;
    border-style: solid;
    border-width: 12px 12px 12px 0;
  }
  .hot-lst li a:after {
    content: "";
    position: absolute;
    top: 10px;
    left: 0;
    float: left;
    width: 4px;
    height: 4px;
    -moz-border-radius: 2px;
    -webkit-border-radius: 2px;
    border-radius: 2px;
    background: #fff;
    -moz-box-shadow: -1px -1px 2px #004977;
    -webkit-box-shadow: -1px -1px 2px #004977;
    box-shadow: -1px -1px 2px #004977;
  }
</style>
