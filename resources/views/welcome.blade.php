
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Hotel packages</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  </head>
  <body>
    <div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-white border-bottom shadow-sm">
  <h5 class="my-0 mr-md-auto font-weight-normal"> {{ config('app.name', 'Laravel') }}</h5>
  <nav class="my-2 my-md-0 mr-md-3">
    <a class="p-2 text-dark" href="/">Home</a>
  </nav>
            @guest
                <a class="btn btn-outline-primary" href="/login">Login</a>
            @else
                Welcome {{ Auth::user()->name }}&nbsp;&nbsp;&nbsp;
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        {{ __('Logout') }}
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
            @endguest
</div>

<div class="pricing-header px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">
  <h2 class="display-4">Latest Packages</h2>
</div>

<div class="container">
    <div class="row mb-2">
          @if($res)
            @foreach ($res as $item)
            <div class="col-md-4">
              <div class="card mb-4 shadow-sm">
                <img class="bd-placeholder-img card-img-top" width="100%" height="225" src="{{ asset('img/hotel/'.$item->photo) }}" height="100px">
                <div class="card-body">
                  <h4 class="mb-0">{{ucfirst($item->hotel_name)}}</h4>
                      <p class="card-text mb-auto">
                        <b>Package Validity </b>{{$item->package_validity}}
                        <br/>
                        {{$item->description}}
                      </p>
                        
                  <div class="d-flex justify-content-between align-items-center">
                    <div class="btn-group">
                      {{$item->duration}}
                    </div>
                    <div class="mb-1 text-muted">RM {{$item->price}}</div>
                  </div>
                </div>
              </div>
            </div>
            @endforeach

        @endif
    </div>
  <footer class="pt-4 my-md-5 pt-md-5 border-top">
    <div class="row">
      <div class="col-12 col-md">
        <small class="d-block mb-3 text-muted">&copy; 2017-2020</small>
      </div>
      <div class="col-6 col-md">
        <h5>About</h5>
        <ul class="list-unstyled text-small">
          <li><a class="text-muted" href="#">Team</a></li>
          <li><a class="text-muted" href="#">Privacy</a></li>
          <li><a class="text-muted" href="#">Terms</a></li>
        </ul>
      </div>
      <div class="col-6 col-md">
        <h5>Newsletter</h5>
        <div>
            Newsletter block
        </div>   
      </div>
      <div class="col-6 col-md">
        <h5>Contact Us</h5>
        <div>
            Email : xyz@xyz.com
            <br/>
            Phone : +603784545
            <br/>
            Address
            <br/>
            XYZ.com
            Jalan ss77/26, Pj

        </div>
      </div>
      
    </div>
  </footer>
</div>
</body>
</html>
