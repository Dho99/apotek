<div class="left-side-bar sidebar-light shadow bg-lightgreen">
    <div class="brand-logo border-0 shadow-0">
        <a href="#" class="light-logo m-0 p-0">
            <img src="{{ asset('src/images/logo-pharmapal.png') }}" style="min-width: 248px; height: 72px;" alt="">
        </a>
        <div class="close-sidebar" data-toggle="left-sidebar-close">
            <i class="ion-close-round"></i>
        </div>
    </div>
    @if (auth()->user()->role->roleName === 'Dokter')
        <div class="menu-block customscroll mt-4">
            <div class="sidebar-menu">
                <ul id="accordion-menu">
                    <li>
                        <a href="/dokter/dashboard"
                            class="dropdown-toggle no-arrow {{ $title == 'Dashboard' ? 'active ' : '' }}">
                            <span class="micon icon-copy dw dw-home"></span><span class="mtext">Dashboard</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    @endif

    @if (auth()->user()->role->roleName === 'Administrator')
        <div class="menu-block customscroll mt-4">
            <div class="sidebar-menu">
                <ul id="accordion-menu">
                    <li class="drowpdown">
                        <a href="/administrator/dashboard"
                            class="dropdown-toggle no-arrow {{ $title == 'Dashboard' ? 'active' : '' }}">
                            <span class="micon icon-copy dw dw-house"></span><span class="mtext">Dashboard</span>
                        </a>
                    </li>
                    <li class="dropdown">
                        <a href="javascript:;"
                            class="dropdown-toggle {{ $title == 'Daftar Pasien' || $title == 'Tambah Pasien' ? 'active ' : '' }}">
                            <span class="micon icon-copy dw dw-file-22"></span><span class="mtext">Pasien</span>
                        </a>
                        <ul class="submenu">
                            <li><a href="{{route('pasien.index')}}"
                                    class="{{ $title == 'Daftar Pasien' ? 'active' : '' }}">Daftar
                                    Pasien</a></li>
                            <li><a href="{{route('pasien.create')}}"
                                    class="{{ $title === 'Tambah Pasien' ? 'active' : '' }}">Tambah
                                    Pasien</a></li>

                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="javascript:;"
                            class="dropdown-toggle {{ $title == 'Tambah Dokter' || $title == 'Daftar Dokter' ? 'active ' : '' }}">
                            <span class="micon icon-copy dw dw-human-resources"></span><span class="mtext">Dokter</span>
                        </a>
                        <ul class="submenu">
                            <li><a href="/administrator/manage/dokter"
                                    class="{{ $title == 'Daftar Dokter' ? 'active' : '' }}">Daftar
                                    Dokter</a></li>
                            <li><a href="{{route('dokter.create')}}"
                                    class="{{ $title === 'Tambah Dokter' ? 'active' : '' }}">Tambah
                                    Dokter</a></li>

                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="javascript:;"
                            class="dropdown-toggle {{ $title == 'Tambah data Kasir' || $title == 'Daftar Kasir' ? 'active ' : '' }}">
                            <span class="micon icon-copy dw dw-user-12"></span><span class="mtext">Kasir</span>
                        </a>
                        <ul class="submenu">
                            <li><a href="/administrator/manage/kasir"
                                    class="{{ $title == 'Daftar Kasir' ? 'active' : '' }}">Daftar
                                    Kasir</a></li>
                            <li><a href="{{route('kasir.create')}}"
                                    class="{{ $title === 'Tambah data Kasir' ? 'active' : '' }}">Tambah
                                    Kasir</a></li>

                        </ul>
                    </li>

                    <li class="dropdown">
                        <a href="javascript:;"
                            class="dropdown-toggle {{ $title == 'Daftar Kunjungan' || $title == 'Buat Data Kunjungan' ? 'active ' : '' }}">
                            <span class="micon icon-copy dw dw-file-22"></span><span class="mtext">Kunjungan</span>
                        </a>
                        <ul class="submenu">
                            <li><a href="{{route('kunjungan.index')}}"
                                    class="{{ $title == 'Daftar Kunjungan' ? 'active' : '' }}">Daftar
                                    Kunjungan</a></li>
                            <li><a href="{{route('kunjungan.create')}}"
                                    class="{{ $title === 'Buat Data Kunjungan' ? 'active' : '' }}">Tambah
                                    Kunjungan</a></li>

                        </ul>
                    </li>

                    <li class="dropdown">
                        <a href="javascript:;"
                            class="dropdown-toggle {{ $title == 'Laporan Penjualan' || $title == 'Laporan Kunjungan' ? 'active' : '' }}">
                            <span class="micon icon-copy dw dw-file"></span><span class="mtext">Laporan</span>
                        </a>
                        <ul class="submenu">
                            <li>
                                <a href="{{route('laporanKunjungan')}}"
                                    class="{{ $title == 'Laporan Kunjungan' ? 'active' : '' }}">Kunjungan</a>
                            </li>
                            <li>
                                <a href="{{route('laporanPenjualan')}}"
                                    class="{{ $title == 'Laporan Penjualan' ? 'active' : '' }}">Penjualan</a>
                            </li>
                        </ul>
                    </li>


                    <li class="dropdown">
                        <a href="javascript:;"
                            class="dropdown-toggle {{ $title == 'Daftar Produk' || $title == 'Tambah Produk' || $title == 'Produk Kadaluarsa' ? 'active' : '' }}">
                            <span class="micon icon-copy dw dw-folder"></span><span class="mtext">Produk</span>
                        </a>
                        <ul class="submenu">
                            <li><a href="{{route('products.index')}}"
                                    class="{{ $title == 'Daftar Produk' ? 'active' : '' }}">Daftar Produk</a></li>
                            <li><a href="{{route('products.create')}}"
                                    class="{{ $title == 'Tambah Produk' ? 'active' : '' }}">Tambah Produk</a></li>
                            <li><a href="{{route('products.create')}}"
                                    class="{{ $title == 'Produk Kadaluarsa' ? 'active' : '' }}">Produk Kadaluarsa</a></li>

                        </ul>
                    </li>

                    <li class="dropdown">
                        <a href="javascript:;"
                            class="dropdown-toggle {{ $title == 'Supplier Produk' || $title == 'Kategori Produk' ? 'active' : '' }}">
                            <span class="micon icon-copy dw dw-folder"></span><span class="mtext">Lainnya</span>
                        </a>
                        <ul class="submenu">
                            <li><a href="{{route('supplier.index')}}"
                                    class="{{ $title == 'Supplier Produk' ? 'active' : '' }}">Supplier Produk</a></li>
                            <li><a href="{{route('kategori.index')}}"
                                    class="{{ $title == 'Kategori Produk' ? 'active' : '' }}">Kategori Produk</a></li>

                        </ul>
                    </li>

                </ul>
            </div>
        </div>
    @endif

    @if (auth()->user()->role->roleName === 'Kasir')
        <div class="menu-block customscroll mt-4">
            <div class="sidebar-menu">
                <ul id="accordion-menu">
                    <li>
                        <a href="/kasir/dashboard"
                            class="dropdown-toggle no-arrow {{ $title == 'Dashboard' ? 'active ' : '' }}">
                            <span class="micon icon-copy dw dw-home"></span><span class="mtext">Dashboard</span>
                        </a>
                    </li>

                    <li class="dropdown">
                        <a href="javascript:;"
                            class="dropdown-toggle {{ $title == 'Daftar Produk' || $title == 'Produk Kadaluarsa' ? 'active' : '' }}">
                            <span class="micon icon-copy dw dw-folder"></span><span class="mtext">Produk</span>
                        </a>
                        <ul class="submenu">
                            <li><a href="{{route('products.index')}}"
                                    class="{{ $title == 'Daftar Produk' ? 'active' : '' }}">Daftar Produk</a></li>
                            <li><a href="{{route('products.kadaluarsa')}}"
                                    class="{{ $title == 'Produk Kadaluarsa' ? 'active' : '' }}">Produk Kadaluarsa</a></li>

                        </ul>
                    </li>

                    <li class="dropdown">
                        <a href="javascript:;"
                            class="dropdown-toggle {{ $title == 'Tambah Pasien' || $title == 'Daftar Pasien' ? 'active' : '' }}">
                            <span class="micon icon-copy dw dw-group"></span><span class="mtext">Pasien</span>
                        </a>
                        <ul class="submenu">
                            <li><a href="/kasir/pasien/list"
                                    class="{{ $title == 'Daftar Pasien' ? 'active' : '' }}">Daftar Pasien</a></li>
                        </ul>
                    </li>

                    <li class="dropdown">
                        <a href="javascript:;"
                            class="dropdown-toggle {{ $title == 'Tambah Pasien' || $title == 'Daftar Pasien' ? 'active' : '' }}">
                            <span class="micon icon-copy dw dw-group"></span><span class="mtext">Penjualan</span>
                        </a>
                        <ul class="submenu">
                            <li><a href="/kasir/pasien/list"
                                    class="{{ $title == 'Daftar Pasien' ? 'active' : '' }}">Riwayat Penjualan</a></li>
                        </ul>
                    </li>

                </ul>
            </div>
        </div>
    @endif
</div>
