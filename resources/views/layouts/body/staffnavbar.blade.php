
<header class="navbar-expand-md">
    <div class="collapse navbar-collapse" id="navbar-menu">
        <div class="navbar">
            <div class="container-xl">
                <ul class="navbar-nav">
                    <li class="nav-item {{ request()->is('home*') ? 'active' : null }}">
                        <a class="nav-link" href="{{ route('home') }}" >
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <i class="fa-solid fa-house" style="{{ request()->is('home*') ? 'color: #ff0d0b;' : 'color: #720100;' }}"></i>
                            </span>
                            <span class="nav-link-title">
                                {{ __('Home') }}
                            </span>
                        </a>
                    </li>

                    <li class="nav-item {{ request()->is('daily-report*') ? 'active' : null }}">
                        <a class="nav-link" href="{{ route('daily-report.index') }}" >
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <i class="fa-solid fa-clipboard-list" style="{{ request()->is('daily-report*') ? 'color: #ff0d0b;' : 'color: #720100;' }}"></i>
                            </span>
                            <span class="nav-link-title">
                                {{ __('Daily Reports') }}
                            </span>
                        </a>
                    </li>
                    
                    <li class="nav-item {{ request()->is('products*') ? 'active' : null }}">
                        <a class="nav-link" href="{{ route('staff.products.index') }}" >
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <i class="fa-solid fa-boxes-stacked" style="{{ request()->is('products*') ? 'color: #ff0d0b;' : 'color: #720100;' }}"></i>
                            </span>
                            <span class="nav-link-title">
                                {{ __('Products') }}
                            </span>
                        </a>
                    </li>
                  
                </ul>
            </div>
        </div>
    </div>
</header>
