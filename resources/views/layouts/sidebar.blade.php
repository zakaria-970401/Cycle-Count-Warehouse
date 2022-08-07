@php
    $permission = DB::table('auth_group_permission')
                ->join('auth_permission', 'auth_permission.id', '=', 'auth_group_permission.permission_id')
                ->where('auth_group_permission.group_id', Auth::user()->auth_group)
                ->pluck('auth_permission.name')
                ->toArray();

    $revisi = DB::table('cycle_count')
                ->where('status', 3)
                ->where('count_by', Auth::user()->name)
                ->count();
                // dd($revisi);
@endphp

@if (in_array('menu_admin', $permission))
<div data-kt-menu-trigger="click" data-kt-menu-placement="right-start"
    class="menu-item here {{ request()->is('cycle-count/admin/*') ? 'show' : '' }} py-3">
    <span class="menu-link menu-center" title="MENU ADMIN" data-bs-toggle="tooltip" data-bs-trigger="hover"
        data-bs-dismiss="click" data-bs-placement="right">
        <span class="menu-icon me-0">
            <i class="fas fa-desktop fs-2"></i>
        </span>
    </span>
    <div class="menu-sub menu-sub-dropdown w-225px w-lg-275px px-1 py-4">
        <div class="menu-item">
            <a class="{{ request()->is('cycle-count/admin/upload') ? 'menu-link active' : 'menu-link' }}"
                href="{{ url('cycle-count/admin/upload') }}">
                <span class="menu-bullet">
                    <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">Upload Excel</span>
            </a>
        </div>
        <div class="menu-item">
            <a class="{{ request()->is('cycle-count/aktifitas') ? 'menu-link active' : 'menu-link' }}"
                href="{{ url('cycle-count/aktifitas') }}">
                <span class="menu-bullet">
                    <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">Aktifitas Cycle Count</span>
            </a>
        </div>
        <div class="menu-item">
            <a class="{{ request()->is('cycle-count/jadwal') ? 'menu-link active' : 'menu-link' }}"
                href="{{ url('cycle-count/jadwal') }}">
                <span class="menu-bullet">
                    <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">Jadwal Cycle Count</span>
            </a>
        </div>
    </div>
</div>
@endif

@if (in_array('menu_superadmin', $permission))
<div data-kt-menu-trigger="click" data-kt-menu-placement="right-start"
    class="menu-item here {{ request()->is('cycle-count/superadmin/*') ? 'show' : '' }} py-3">
    <span class="menu-link menu-center" title="MANAGEMENT USER" data-bs-toggle="tooltip" data-bs-trigger="hover"
        data-bs-dismiss="click" data-bs-placement="right">
        <span class="menu-icon me-0">
            <i class="fas fa-users fs-2"></i>
        </span>
    </span>
    <div class="menu-sub menu-sub-dropdown w-225px w-lg-275px px-1 py-4">
        <div class="menu-item">
            <a class="{{ request()->is('cycle-count/superadmin/user') ? 'menu-link active' : 'menu-link' }}"
                href="{{ url('cycle-count/superadmin/user') }}">
                <span class="menu-bullet">
                    <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">Master User</span>
            </a>
        </div>
        <div class="menu-item">
            <a class="{{ request()->is('cycle-count/superadmin/menu') ? 'menu-link active' : 'menu-link' }}"
                href="{{ url('cycle-count/superadmin/menu') }}">
                <span class="menu-bullet">
                    <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">Akses Menu</span>
            </a>
        </div>
    </div>
</div>
@endif

@if(in_array('menu_gudang', $permission))
<div data-kt-menu-trigger="click" data-kt-menu-placement="right-start"
    class="menu-item here {{ request()->is('cycle-count/gudang/*') ? 'show' : '' }} py-3">
    <span class="menu-link menu-center" title="MENU GUDANG" data-bs-toggle="tooltip" data-bs-trigger="hover"
        data-bs-dismiss="click" data-bs-placement="right">
        @if ($revisi > 0)
            <span class="badge badge-danger ml-2">{{ $revisi }}</span>
        @endif
        <span class="menu-icon me-0">
            <i class="fas fa-warehouse fs-2"></i>
        </span>
    </span>
    <div class="menu-sub menu-sub-dropdown w-225px w-lg-275px px-1 py-4">
        <div class="menu-item">
            <a class="{{ request()->is('cycle-count/gudang/hitung') ? 'menu-link active' : 'menu-link' }}"
                href="{{ url('cycle-count/gudang/hitung') }}">
                <span class="menu-bullet">
                    <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">Hitung Cycle Count</span>
            </a>
        </div>
        <div class="menu-item">
            <a class="{{ request()->is('cycle-count/gudang/revisiCycleCount') ? 'menu-link active' : 'menu-link' }}"
                href="{{ url('cycle-count/gudang/revisiCycleCount') }}">
                <span class="menu-bullet">
                    <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">Revisi Cycle Count</span>
                @if ($revisi > 0)
                    <span class="badge badge-danger ml-2">{{ $revisi }}</span>
                @endif
            </a>
        </div>
        <div class="menu-item">
            <a class="{{ request()->is('cycle-count/aktifitas') ? 'menu-link active' : 'menu-link' }}"
                href="{{ url('cycle-count/aktifitas') }}">
                <span class="menu-bullet">
                    <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">Aktifitas Cycle Count</span>
            </a>
        </div>
    </div>
</div>
@endif

@if(in_array('generate_excel', $permission))
<div data-kt-menu-trigger="click" data-kt-menu-placement="right-start"
    class="menu-item here {{ request()->is('cycle-count/excel/*') ? 'show' : '' }} py-3">
    <span class="menu-link menu-center" title="GENERATE EXCEL" data-bs-toggle="tooltip" data-bs-trigger="hover"
        data-bs-dismiss="click" data-bs-placement="right">
        <span class="menu-icon me-0">
            <i class="fas fa-file-excel fs-2"></i>
        </span>
    </span>
    <div class="menu-sub menu-sub-dropdown w-225px w-lg-275px px-1 py-4">
        <div class="menu-item">
            <a class="{{ request()->is('cycle-count/generateExcel') ? 'menu-link active' : 'menu-link' }}"
                href="{{ url('cycle-count/generateExcel') }}">
                <span class="menu-bullet">
                    <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">Generate Excel</span>
            </a>
        </div>
    </div>
</div>
@endif

@if(in_array('report', $permission))
<div data-kt-menu-trigger="click" data-kt-menu-placement="right-start"
    class="menu-item here {{ request()->is('cycle-count/report/*') ? 'show' : '' }} py-3">
    <span class="menu-link menu-center" title="MENU REPORT" data-bs-toggle="tooltip" data-bs-trigger="hover"
        data-bs-dismiss="click" data-bs-placement="right">
        <span class="menu-icon me-0">
            <i class="fas fa-chart-line fs-2"></i>
        </span>
    </span>
    <div class="menu-sub menu-sub-dropdown w-225px w-lg-275px px-1 py-4">
        <div class="menu-item">
            <a class="{{ request()->is('cycle-count/report') ? 'menu-link active' : 'menu-link' }}"
                href="{{ url('cycle-count/report') }}">
                <span class="menu-bullet">
                    <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">Menu Report</span>
            </a>
        </div>
    </div>
</div>
@endif

<div data-kt-menu-trigger="click" data-kt-menu-placement="right-start" class="menu-item py-3">
    <span class="menu-link menu-center" title="Logout" onclick="postLogout()" data-bs-trigger="hover"
        data-bs-dismiss="click" data-bs-placement="right">
        <span class="menu-icon me-0">
            <i class="fas fa-power-off fs-2"></i>
        </span>
    </span>
</div>
