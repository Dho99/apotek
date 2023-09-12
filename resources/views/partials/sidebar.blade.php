<div class="left-side-bar sidebar-light shadow">
    <div class="brand-logo border-0 shadow-0">
        <a href="index.html" class="light-logo">
            {{-- <h2 class="dark-logo">Modernics</h2> --}}
            <h2 class="font-green">PharmaPal</h2>
        </a>
        <div class="close-sidebar" data-toggle="left-sidebar-close">
            <i class="ion-close-round"></i>
        </div>
    </div>
    @if (auth()->user()->level === 'Dokter')
        <div class="menu-block customscroll">
            <div class="sidebar-menu">
                <ul id="accordion-menu">
                    <li class="drowpdown">
                        <a href="/dokter/dashboard"
                            class="dropdown-toggle no-arrow {{ $title == 'Dashboard' ? 'active' : '' }}">
                            <span class="micon icon-copy dw dw-house"></span><span
                                class="mtext">Dashboard</span>
                        </a>
                    </li>
                    <li>
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
                    </li>
                </ul>
            </div>
        </div>
    @endif

    @if (auth()->user()->level === 'Apoteker')
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
                            class="dropdown-toggle {{ $title == 'Tambah Obat' || $title == 'Daftar Obat' || $title == 'Transaksi Obat' || $title == 'Stock-in Obat' || $title == 'Edit Data Obat' ? 'active ' : '' }}">
                            <span class="micon icon-copy dw dw-folder"></span><span class="mtext">Obat</span>
                        </a>
                        <ul class="submenu">
                            <li><a href="/apoteker/obat/create" class="{{ $title === 'Tambah Data Obat' ? 'active' : '' }}">Tambah
                                    Obat</a></li>
                            <li><a href="/apoteker/obat/list" class="{{ $title == 'Daftar Obat' ? 'active' : '' }}">Daftar
                                    Obat</a></li>
                            <li><a href="/apoteker/obat/stock-in" class="{{ $title == 'Stock-in Obat' ? 'active' : '' }}">Stock-in
                                    Obat</a></li>
                        </ul>
                    </li>
                    <li class="drowpdown">
                        <a href="/apoteker/resep/antrian"
                            class="dropdown-toggle no-arrow {{ $title == 'Kasir' ? 'active' : '' }}">
                            <span class="micon icon-copy dw dw-shopping-cart-1"></span><span
                                class="mtext">Kasir</span>
                        </a>
                    </li>
                    {{-- <li class="drowpdown">
                        <a href="/apoteker/dokter/list"
                            class="dropdown-toggle no-arrow {{ $title == 'Daftar Dokter' ? 'active' : '' }}">
                            <span class="micon icon-copy dw dw-user"></span><span
                                class="mtext">Dokter</span>
                        </a>
                    </li> --}}
                    <li class="drowpdown">
                        <a href="/apoteker/user/list"
                            class="dropdown-toggle no-arrow {{ $title == 'Daftar User' ? 'active' : '' }}">
                            <span class="micon icon-copy fa fa-address-book-o"></span><span
                                class="mtext">User</span>
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
                        <a href="javascript:;" class="dropdown-toggle {{ $title == 'Transaksi' || $title == 'Produk' || $title == 'Struk' || $title == 'Penerimaan Barang' || $title == 'Resep' ? 'active' : '' }}">
                            <span class="micon icon-copy dw dw-file"></span><span class="mtext">Laporan</span>
                        </a>
                        <ul class="submenu">
                            <li>
                                <a href="/apoteker/laporan/penjualan" class="{{ $title == 'Laporan Penjualan' ? 'active' : '' }}">Penjualan</a>
                            </li>
                            <li>
                                <a href="basic-table.html" class="{{ $title == 'Laporan Keuangan' ? 'active' : '' }}">Keuangan</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    @endif

    @if (auth()->user()->level === 'Kasir')
        <div class="menu-block customscroll">
            <div class="sidebar-menu">
                <ul id="accordion-menu">
                    <li>
                        <a href="/kasir/dashboard"
                            class="dropdown-toggle no-arrow {{ $title == 'Dashboard' ? 'active ' : '' }}">
                            <span class="micon icon-copy dw dw-house"></span><span
                                class="mtext text-light">Dashboard</span>
                        </a>
                    </li>
                    <li class="dropdown">
                        <a href="javascript:;" class="dropdown-toggle {{ $title == 'Tambah Transaksi' || $title == 'Daftar Transaksi' ? 'active' : '' }}">
                            <span class="micon icon-copy dw dw-folder-40"></span><span class="mtext">Transaksi</span>
                        </a>
                        <ul class="submenu">
                            <li><a href="index.html" class="{{ $title == 'Tambah Transaksi' ? 'active' : ''}}">Tambah Transaksi</a></li>
                            <li><a href="index2.html" class="{{ $title == 'Daftar Transaksi' ? 'active' : ''}}">Daftar Transaksi</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="javascript:;" class="dropdown-toggle {{ $title == 'Daftar Obat' ? 'active' : '' }}">
                            <span class="micon icon-copy dw dw-first-aid-kit"></span><span class="mtext">Obat</span>
                        </a>
                        <ul class="submenu">
                            <li><a href="form-basic.html" class="{{$title=='Daftar Obat' ? 'active' : ''}}">Daftar Obat</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="javascript:;" class="dropdown-toggle {{$title == 'Tambah Pasien' || $title == 'Daftar Pasien' ? 'active' : '' }}">
                            <span class="micon icon-copy dw dw-group"></span><span class="mtext">Pasien</span>
                        </a>
                        <ul class="submenu">
                            <li><a href="basic-table.html" class="{{ $title == 'Tambah Pasien' ? 'active' : '' }}">Tambah Pasien</a></li>
                            <li><a href="datatable.html" class="{{ $title == 'Daftar Pasien' ? 'active' : '' }}">Daftar Pasien</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="calendar.html" class="dropdown-toggle no-arrow {{ $title == 'Rekap Transaksi' ? 'active' : '' }}">
                            <span class="micon icon-copy dw dw-file"></span><span class="mtext">Rekap
                                Transaksi</span>
                        </a>
                    </li>

                </ul>
            </div>
        </div>
    @endif
</div>
