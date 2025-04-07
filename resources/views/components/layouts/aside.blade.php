@php
    $routeName = request()->route()->getName();

    function iconActive($match, $icon)
    {
        return request()->routeIs($match) ? "bi-{$icon}-fill" : "bi-{$icon}";
    }
@endphp
<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <!--begin::Sidebar Brand-->
    <div class="sidebar-brand">
        <!--begin::Brand Link-->
        <a href="/" class="brand-link">
            <!--begin::Brand Image-->
            <img src="{{ asset('storage/LOGO SHE-G.png') }}" class="rounded brand-image"
                style="background-color: white; padding: 5px;" />

            <!--end::Brand Image-->
            <!--begin::Brand Text-->
            <span class="brand-text fw-light">SHE-G</span>
            <!--end::Brand Text-->
        </a>
        <!--end::Brand Link-->
    </div>
    <!--end::Sidebar Brand-->
    <!--begin::Sidebar Wrapper-->
    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <!--begin::Sidebar Menu-->
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="/" class="nav-link @if ($routeName == 'home') active @endif">
                        <i class="nav-icon bi bi-speedometer"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                @hasAnyRole(['superadmin', 'admin', 'dokter'])

                    {{-- MASTER DATA --}}
                    @hasAnyRole(['superadmin', 'admin'])
                        @php
                            $isMasterActive = Str::startsWith($routeName, [
                                'users',
                                'departments',
                                'jabatan',
                                'perusahaan',
                                'versatility',
                                'karyawan',
                            ]);
                        @endphp
                        <li class="nav-item {{ $isMasterActive ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ $isMasterActive ? 'active' : '' }}">
                                <i class="nav-icon bi {{ $isMasterActive ? 'bi-folder-fill' : 'bi-folder' }}"></i>
                                <p>
                                    Master Data
                                    <i class="nav-arrow bi bi-chevron-right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                @hasAnyRole(['superadmin'])
                                    <li class="nav-item">
                                        <a href="/users" class="nav-link @if ($routeName == 'users') active @endif">
                                            <i class="nav-icon bi {{ iconActive('users', 'circle') }}"></i>
                                            <p>Users</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="/departments" class="nav-link @if ($routeName == 'departments') active @endif">
                                            <i class="nav-icon bi {{ iconActive('departments', 'circle') }}"></i>
                                            <p>Departments</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="/jabatan" class="nav-link @if ($routeName == 'jabatan') active @endif">
                                            <i class="nav-icon bi {{ iconActive('jabatan', 'circle') }}"></i>
                                            <p>Jabatan</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="/perusahaan" class="nav-link @if ($routeName == 'perusahaan') active @endif">
                                            <i class="nav-icon bi {{ iconActive('perusahaan', 'circle') }}"></i>
                                            <p>Perusahaan</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="/versatility" class="nav-link @if ($routeName == 'versatility') active @endif">
                                            <i class="nav-icon bi {{ iconActive('versatility', 'circle') }}"></i>
                                            <p>Versatility</p>
                                        </a>
                                    </li>
                                @endhasAnyRole
                                @hasAnyRole(['superadmin', 'admin'])
                                    <li class="nav-item">
                                        <a href="/karyawan" class="nav-link @if ($routeName == 'karyawan') active @endif">
                                            <i class="nav-icon bi {{ iconActive('karyawan', 'circle') }}"></i>
                                            <p>Karyawan</p>
                                        </a>
                                    </li>
                                @endhasAnyRole
                            </ul>
                        </li>
                    @endhasAnyRole

                    {{-- PENGAJUAN --}}
                    @php
                        $isPengajuanActive = Str::startsWith($routeName, ['mcu', 'id', 'kimper']);
                    @endphp
                    <li class="nav-item {{ $isPengajuanActive ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ $isPengajuanActive ? 'active' : '' }}">
                            <i class="nav-icon bi {{ $isPengajuanActive ? 'bi-send-plus-fill' : 'bi-send-plus' }}"></i>
                            <p>
                                Pengajuan
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="mcu" class="nav-link @if ($routeName == 'mcu') active @endif">
                                    <i class="nav-icon bi {{ iconActive('mcu', 'circle') }}"></i>
                                    <p>MCU</p>
                                </a>
                            </li>
                            @hasAnyRole(['admin', 'superadmin'])
                                <li class="nav-item">
                                    <a href="id" class="nav-link @if ($routeName == 'id') active @endif">
                                        <i class="nav-icon bi {{ iconActive('id', 'circle') }}"></i>
                                        <p>ID</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="kimper" class="nav-link @if ($routeName == 'kimper') active @endif">
                                        <i class="nav-icon bi {{ iconActive('kimper', 'circle') }}"></i>
                                        <p>KIMPER</p>
                                    </a>
                                </li>
                            @endhasAnyRole
                        </ul>
                    </li>

                    {{-- HISTORI --}}
                    @php
                        $isHistoriActive = Str::startsWith($routeName, ['histori-mcu']);
                    @endphp
                    <li class="nav-item {{ $isHistoriActive ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ $isHistoriActive ? 'active' : '' }}">
                            <i class="nav-icon bi {{ $isHistoriActive ? 'bi-clock-fill' : 'bi-clock-history' }}"></i>
                            <p>
                                Histori
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="histori-mcu" class="nav-link @if ($routeName == 'histori-mcu') active @endif">
                                    <i class="nav-icon bi {{ iconActive('histori-mcu', 'circle') }}"></i>
                                    <p>MCU</p>
                                </a>
                            </li>
                            @hasAnyRole(['admin', 'superadmin'])
                                <li class="nav-item">
                                    <a href="id" class="nav-link @if ($routeName == 'id') active @endif">
                                        <i class="nav-icon bi {{ iconActive('id', 'circle') }}"></i>
                                        <p>ID</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="kimper" class="nav-link @if ($routeName == 'kimper') active @endif">
                                        <i class="nav-icon bi {{ iconActive('kimper', 'circle') }}"></i>
                                        <p>KIMPER</p>
                                    </a>
                                </li>
                            @endhasAnyRole
                        </ul>
                    </li>

                    {{-- CETAK --}}
                    @hasAnyRole(['superadmin'])
                        @php
                            $isCetakActive = Str::startsWith($routeName, ['cetak']);
                        @endphp
                        <li class="nav-item {{ $isCetakActive ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ $isCetakActive ? 'active' : '' }}">
                                <i class="nav-icon bi {{ $isCetakActive ? 'bi-printer-fill' : 'bi-printer' }}"></i>
                                <p>
                                    Cetak
                                    <i class="nav-arrow bi bi-chevron-right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="karyawan" class="nav-link @if ($routeName == 'karyawan') active @endif">
                                        <i class="nav-icon bi {{ iconActive('karyawan', 'circle') }}"></i>
                                        <p>ID</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="karyawan" class="nav-link @if ($routeName == 'karyawan') active @endif">
                                        <i class="nav-icon bi {{ iconActive('karyawan', 'circle') }}"></i>
                                        <p>KIMPER</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endhasAnyRole

                @endhasAnyRole
            </ul>
            <!--end::Sidebar Menu-->
        </nav>
    </div>
    <!--end::Sidebar Wrapper-->
</aside>
