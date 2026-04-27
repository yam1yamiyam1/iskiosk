
<header class="navbar-expand-md">
    <div class="collapse navbar-collapse" id="navbar-menu">
        <div class="navbar">
            <div class="container-xl">
                <ul class="navbar-nav">
                    <li class="nav-item {{ request()->is('dashboard*') ? 'active' : null }}">
                        <a class="nav-link" href="{{ route('dashboard') }}" >
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <i class="fa-solid fa-chart-simple" style="{{ request()->is('dashboard*') ? 'color: #ff0d0b;' : 'color: #720100;' }}"></i>
                            </span>
                            <span class="nav-link-title">
                                {{ __('Dashboard') }}
                            </span>
                        </a>
                    </li>

                    <li class="nav-item {{ request()->is('documents*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('documents.index') }}">
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <i class="fa-solid fa-file-lines"
                                style="color: {{ request()->is('documents*') ? '#ff0d0b' : '#720100' }};">
                                </i>
                            </span>
                            <span class="nav-link-title">
                                {{ __('Documents') }}
                            </span>
                        </a>
                    </li>

                    <li class="nav-item {{ request()->is('records*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('records.index') }}">
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <i class="fa-solid fa-folder-open"
                                style="color: {{ request()->is('records*') ? '#ff0d0b' : '#720100' }};">
                                </i>
                            </span>
                            <span class="nav-link-title">
                                {{ __('Records') }}
                            </span>
                        </a>
                    </li>

                    <li class="nav-item {{ request()->is('students*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('students.index') }}">
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <i class="fa-solid fa-user-graduate"
                                style="color: {{ request()->is('students*') ? '#ff0d0b' : '#720100' }};">
                                </i>
                            </span>
                            <span class="nav-link-title">
                                {{ __('Students') }}
                            </span>
                        </a>
                    </li>

                    @if(Auth::user()->roles[0]->role != 0)

                        <li class="nav-item {{ request()->is('types*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('types.index') }}">
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <i class="fa-solid fa-layer-group"
                                    style="color: {{ request()->is('types*') ? '#ff0d0b' : '#720100' }};">
                                    </i>
                                </span>
                                <span class="nav-link-title">
                                    {{ __('Types / Categories') }}
                                </span>
                            </a>
                        </li>

                        <li class="nav-item {{ request()->is('departments*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('departments.index') }}">
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <i class="fa-solid fa-building"
                                    style="color: {{ request()->is('departments*') ? '#ff0d0b' : '#720100' }};">
                                    </i>
                                </span>
                                <span class="nav-link-title">
                                    {{ __('Departments') }}
                                </span>
                            </a>
                        </li>

                        <li class="nav-item dropdown {{ request()->is('users*') ? 'active' : null }}">
                            <a class="nav-link" href="{{ route('users.index') }}">
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <i class="fa-solid fa-users-gear"
                                    style="{{ request()->is('users*') ? 'color: #ff0d0b;' : 'color: #720100;' }}">
                                    </i>
                                </span>
                                <span class="nav-link-title">
                                    {{ __('Users') }}
                                </span>
                            </a>
                        </li>

                        <li class="nav-item {{ request()->is('activity-logs*') ? 'active' : null }}">
                            <a class="nav-link" href="{{ route('activity-logs.index') }}">
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <i class="fa-solid fa-clock-rotate-left"
                                    style="{{ request()->is('activity-logs*') ? 'color: #ff0d0b;' : 'color: #720100;' }}">
                                    </i>
                                </span>
                                <span class="nav-link-title">
                                    {{ __('Activity Logs') }}
                                </span>
                            </a>
                        </li>

                    @endif

                </ul>
            </div>
        </div>
    </div>
</header>
