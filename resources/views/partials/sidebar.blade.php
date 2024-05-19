<div class="left-side-bar sidebar-light shadow bg-lightgreen" style="z-index: 10000">
    <div class="brand-logo border-0 shadow-0">
        <a href="#" class="light-logo m-0 p-0">
            <img src="{{asset('src/images/logo-pharmapal.png')}}" style="min-width: 248px; height: 72px;" alt="">
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
                    {{-- <li>
                        <a href="/dokter/resep/create"
                            class="dropdown-toggle no-arrow {{ $title == 'Buat Resep' ? 'active ' : '' }}">
                            <span class="micon icon-copy dw dw-edit-file"></span><span class="mtext">Buat Resep</span>
                        </a>
                    </li>
                    <li>
                        <a href="/dokter/resep/list"
                            class="dropdown-toggle no-arrow {{ $title == 'Rekap Resep' ? 'active ' : '' }}">
                            <span class="micon icon-copy dw dw-file"></span><span class="mtext">Rekap Resep</span>
                        </a>
                    </li> --}}
                </ul>
            </div>
        </div>
    @endif

    @if (auth()->user()->role->roleName === 'Administrator')
        <div class="menu-block customscroll mt-4">
            <div class="sidebar-menu">
                <ul id="accordion-menu">
                    <li class="drowpdown">
                        <a href="/apoteker/dashboard"
                            class="dropdown-toggle no-arrow {{ $title == 'Dashboard' ? 'active' : '' }}">
                            <span class="micon icon-copy dw dw-house"></span><span
                                class="mtext">Dashboard</span>
                        </a>
                    </li>
                    <li class="dropdown">
                        <a href="javascript:;"
                            class="dropdown-toggle {{ $title == 'Tambah Obat' || $title == 'Daftar Obat' || $title == 'Transaksi Obat' || $title == 'Stock-in Obat' || $title == 'Detail Data Obat' || $title == 'Kategori Obat' || $title == 'Obat Kadaluarsa' ? 'active ' : '' }}">
                            <span class="micon icon-copy dw dw-folder"></span><span class="mtext">Obat</span>
                        </a>
                        <ul class="submenu">
                            <li><a href="/apoteker/obat/create" class="{{ $title === 'Tambah Data Obat' ? 'active' : '' }}">Tambah
                                    Obat</a></li>
                            <li><a href="/apoteker/obat/list" class="{{ $title == 'Daftar Obat' || $title == 'Detail Data Obat' ? 'active' : '' }}">Daftar
                                    Obat</a></li>
                            <li><a href="/apoteker/obat/stock-in" class="{{ $title == 'Stock-in Obat' ? 'active' : '' }}">Stock-in
                                    Obat</a></li>
                            <li><a href="/obat/kategori" class="{{ $title == 'Kategori Obat' ? 'active' : '' }}">Kategori Obat</a></li>
                            <li><a href="/apoteker/obat/kadaluarsa" class="{{ $title == 'Obat Kadaluarsa' ? 'active' : '' }}">Obat Kadaluarsa</a></li>
                        </ul>
                    </li>
                    <li class="drowpdown">
                        <a href="/apoteker/resep/antrian"
                            class="dropdown-toggle no-arrow {{ $title == 'Kasir' ? 'active' : '' }}">
                            <span class="micon icon-copy dw dw-shopping-cart-1"></span><span
                                class="mtext">Transaksi</span>
                        </a>
                    </li>
                    <li class="drowpdown">
                        <a href="/apoteker/resep/list"
                            class="dropdown-toggle no-arrow {{ $title == 'Daftar Resep Masuk' || $title == 'Buat Resep' ? 'active' : '' }}">
                            <span class="micon icon-copy dw dw-newspaper"></span><span
                                class="mtext">Resep</span>
                        </a>
                    </li>
                    <li class="drowpdown">
                        <a href="/apoteker/user/list"
                            class="dropdown-toggle no-arrow {{ $title == 'Daftar Anggota' ? 'active' : '' }}">
                            <span class="micon icon-copy fa fa-address-book-o"></span><span
                                class="mtext">Anggota</span>
                        </a>
                    </li>
                    <li class="drowpdown">
                        <a href="/apoteker/pemasok/list"
                            class="dropdown-toggle no-arrow {{ $title == 'Daftar Pemasok' ? 'active' : '' }}">
                            <span class="micon icon-copy dw dw-delivery-truck-2"></span><span
                                class="mtext">Pemasok</span>
                        </a>
                    </li>
                    <li class="dropdown">
                        <a href="javascript:;" class="dropdown-toggle {{ $title == 'Laporan Penjualan' || $title == 'Laporan Keuangan' ? 'active' : '' }}">
                            <span class="micon icon-copy dw dw-file"></span><span class="mtext">Laporan</span>
                        </a>
                        <ul class="submenu">
                            <li>
                                <a href="/apoteker/laporan/penjualan" class="{{ $title == 'Laporan Penjualan' ? 'active' : '' }}">Penjualan</a>
                            </li>
                            <li>
                                <a href="/apoteker/laporan/keuangan" class="{{ $title == 'Laporan Keuangan' ? 'active' : '' }}">Keuangan</a>
                            </li>
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
                    <li class="dropdown">
                        <a href="javascript:;" class="dropdown-toggle {{$title == 'Tanpa Resep' || $title == 'Dengan Resep' ? 'active' : '' }}">
                            <span class="micon icon-copy dw dw-folder"></span><span class="mtext">Pasien</span>
                        </a>
                        <ul class="submenu">
                            <li><a href="/kasir/transaksi/nonresep" class="{{ $title == 'Tanpa Resep' ? 'active' : '' }}">Tanpa Resep</a></li>
                            <li><a href="/kasir/transaksi/resep" class="{{ $title == 'Dengan Resep' ? 'active' : '' }}">Dengan Resep</a></li>
                        </ul>
                    </li>

                    <li class="dropdown">
                        <a href="javascript:;" class="dropdown-toggle {{$title == 'Tambah Pasien' || $title == 'Daftar Pasien' ? 'active' : '' }}">
                            <span class="micon icon-copy dw dw-group"></span><span class="mtext">Pasien</span>
                        </a>
                        <ul class="submenu">
                            <li><a href="/kasir/pasien/list" class="{{ $title == 'Daftar Pasien' ? 'active' : '' }}">Daftar Pasien</a></li>
                        </ul>
                    </li>

                </ul>
            </div>
        </div>
    @endif
</div>


