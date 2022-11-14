<!doctype html>
<html lang="{{ app()->getLocale() }}">
    @section('htmlheader')
    @include('public.partials.htmlheader')
    @show

    <body id="user_view" class="login-reg">
        @section('topmenu')
        @include('public.partials.topmenu')
        @show
        
      @if(\Auth::user())
        <section class="reg-info non-mobile">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-5">
                        <p>Registrering for: <span class="text-white">{{$event['name']}}</span><a href="/" class="reg-info_change">(change)</a></p>
                    </div>
                    <div class="col-3 align-items-center">
                        <p>Exhibitor Type: <span class="text-white">{{$exhibitor_type}}</span><a href="/" class="reg-info_change">(change)</a></p>
                    </div>
                    <div class="col align-items-center">
                        <a href="@if(!(empty($event['tc']) || is_null($event['tc']))){{asset('storage/'.$event['tc'])}} @else {{asset('storage/Exhibitor_Manual.pdf')}} @endif" target="_blank" download="">Download Exhibitor Manual</a>
                    </div>
                </div>
            </div>
        </section>
      @endif
        
        <section class="top">
            <div class="container">
                <div class="row">
                    <div class="exhibitor_account col-10 col-md-9">
                        <form method="POST" action="{{ route('register') }}" class="form-account">
                            {{ csrf_field() }}
                            <h4>Create an exhibitor account</h4>
                            <span class="booking-details_line"></span>
                            <div class="form-group">
                                <input type="email" data-validation="regemail" id="regemail" name="regemail" class="form-control" placeholder="Email" required>
                                @if ($errors->has('regemail'))
                                    <span class="help-block" style="color: red;">
                                        <strong>{{ $errors->first('regemail') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <input type="password" data-validation="length" data-validation-length="min8" data-validation-error-msg="Your password is shorter than 8 characters" id="regpassword" class="form-control" placeholder="Create a Password"  name="password" required>
                            </div>
                            <input type="hidden"  id="exhibitor_type" name="exhibitor_type" value="{{$exhibitor_type_id}}">
                            <button type="submit" class="btn">Create</button>
                        </form>
                        <form  method="POST" action="{{ route('login') }}" class="form-account">
                            {{ csrf_field() }}
                            <h4>Login to my exhibitor account</h4>
                            <span class="booking-details_line"></span>
                            <div class="form-group">
                                <input type="email" data-validation="email" id="loginemail" name="email" class="form-control" placeholder="Email" required>
                                @if ($errors->has('email'))
                                    <span class="help-block" style="color: red;">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <input type="password" id="loginpassword" class="form-control" placeholder="Password"  name="password" required>
                                @if ($errors->has('password'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                                @endif
                            </div>
                            <button type="submit" class="btn">Login</button>
                            <a class="btn btn-forget" href="{{ route('password.request') }}">Forgot Your Password?</a>
                        </form>
                    </div>
                    <div class="booking-details col non-mobile">
                        <h5>Your Booking Details</h5>
                        <div class="booking-details_block">
                            <div class="booking-details_items"> 
                                <div class="booking-details_item">
                                    <?= $bookingTables ?>
                                </div>
                                <?php 
                                  foreach ($eventaddons as $eventaddon){
                                    if( $eventaddon->amount >0 ){
                                      ?><span class="booking-details_line"></span><?php
                                      break;
                                    }
                                  }
                                ?>
                                @foreach ($eventaddons as $eventaddon)
                                @if( $eventaddon->amount >0 )
                                <div class="booking-details_item">
                                    <p class="booking-details_item-name">{{$eventaddon->name}} <span class="text-white">x {{$eventaddon->amount}}</span></p>
                                    <p class="booking-details_item-value">${{formatSum($eventaddon->price)}} + HST</p>
                                </div>
                                @endif
                                @endforeach

                                <?php 
                                  foreach ($eventmarketings as $eventmarketing){
                                    if( $eventmarketing->amount >0 ){
                                      ?><span class="booking-details_line"></span><?php
                                      break;
                                    }
                                  }
                                ?>
                                @foreach ($eventmarketings as $eventmarketing)
                                @if( $eventmarketing->amount >0 )
                                <div class="booking-details_item">
                                    <p class="booking-details_item-name">{{$eventmarketing->name}} <span class="text-white">x {{$eventmarketing->amount}}</span></p>
                                    <p class="booking-details_item-value">${{formatSum($eventmarketing->price)}} + HST</p>
                                </div>
                                @endif
                                @endforeach
                                <div class="booking-details_total">
                                    <p class="booking-details_total-hst">HST ${{formatSum($totalhst)}}</p>
                                    <p class="booking-details_total-value">TOTAL ${{formatSum($total)}}</p>
                                </div>                            
                                <p class="booking-details_text">Create an account or login to continue.</p>
                            </div>
                        </div>
                    </div>
                </div>
              </div>
        </section>
        <script>
            $(document).ready(function () {
                $.validate();
            });
        </script>

        @section('footer')
        @include('public.partials.footer')
        @show

    </body>
</html>