<div class="header px-4 bg-lightgreen noprint">
    <div class="header-left">
        <div class="menu-icon bi bi-list"></div>

    </div>
    <div class="header-right">
        <div class="dashboard-setting user-notification">
            <div class="dropdown">
                <a class="dropdown-toggle no-arrow text-dark" href="javascript:;" data-toggle="right-sidebar">
                    <i class="icon-copy dw dw-notification"></i>
                    {{-- <span class="{{ session('notification') ? 'badge notification-active' : '' }}"></span> --}}
                </a>
            </div>
        </div>

        <div
            class="user-info-dropdown {{ $title == 'Account Edit' || $title == 'Account Info' ? 'bg-lightgreen-sidebar' : '' }} mr-3 d-flex">
            <div class="dropdown">
                <a class="dropdown-profile user-icon mt-1 font-30 {{ $title == 'Account Edit' || $title == 'Account Info' ? 'text-white' : '' }}"
                    href="#" role="button" data-toggle="dropdown">

                </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list shadow-lg">
                    <div classs="p-0">
                        <div class="bg-light-secondary top-right-sidebar">
                        </div>
                        <div class="top-sidebar-right-image-wrapper">
                            <img src="{{ auth()->user()->profile !== 'default' ? asset('/storage/' . auth()->user()->profile) : asset('src/images/photo1.jpg') }}"
                                class="rounded-circle border border-success" alt="">
                            <div class="mt-1 font-weight-bold">
                                {{ auth()->user()->nama }}
                            </div>
                            <a href="/account/manage/{{ auth()->user()->kode }}"
                                class="btn btn-outline-secondary p-2 mt-3 w-75">
                                Kelola Akun anda
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="#" data-toggle="modal" data-target="#logout-modal"
                                class="btn btn-danger p-2 w-75">
                                <i class="dw dw-logout"></i>
                                Logout
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
