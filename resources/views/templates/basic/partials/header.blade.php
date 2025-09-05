<header class="header-section header-section-two">
    <div class="header">
        <div class="header-bottom-area">
            <div class="container-fluid">
                <div class="header-menu-content">
                    <nav class="navbar navbar-expand-lg p-0">
                        <a class="site-logo site-title" href="{{ route('home') }}"><img
                                src="{{ getImage(getFilePath('logoIcon') . '/logo.png') }}" alt="logo"></a>
                                @if ($general->multi_language)
                                <div class="d-block d-lg-none ml-auto">
                                <select class="langSel form-control">
                                    @foreach($language as $item)
                                        <option value="{{ $item->code }}" @if(session('lang')==$item->code) selected @endif>
                                            {{ __($item->name) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        <button class="navbar-toggler ml-auto collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <i class="las la-bars"></i>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav main-menu mx-auto justify-content-center">
                                <li class="{{ menuActive('home') }}"><a href="{{ route('home') }}">@lang('Home')</a></li>
                                <li class="{{ menuActive('doctors.all') }}"><a href="{{ route('doctors.all') }}">@lang('Doctors')</a></li>

                                @php
                                    $pages = App\Models\Page::where('tempname', $activeTemplate)
                                        ->where('is_default', 0)
                                        ->get();
                                @endphp
                                @foreach ($pages as $k => $data)
                                    <li><a href="{{ route('pages', [$data->slug]) }}">{{ __($data->name) }}</a></li>
                                @endforeach

                                <li class="{{ menuActive('blogs') }}"><a
                                        href="{{ route('blogs') }}">@lang('Blogs')</a>
                                </li>
                                <li class="{{ menuActive('contact') }}"><a
                                        href="{{ route('contact') }}">@lang('Contact')</a></li>
                            </ul>
                            <div class="header-bottom-action">
                                <a href="{{ route('doctors.all') }}" class="cmn-btn">@lang('Book Now')</a>
                            </div>
                            <div class="header-bottom-action">
                                <a href="{{ route('login') }}" class="cmn-btn">@lang('Login')</a>
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</header>
<!-- header-section end -->
