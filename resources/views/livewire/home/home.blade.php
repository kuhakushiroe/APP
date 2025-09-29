<div>
    <div class="row">
        <!-- /.col -->
        @if (auth()->user()->role !== 'admin')

            <div class="col-12 col-sm-4 col-md-4">
                <div class="info-box">
                    <span class="info-box-icon text-bg-primary shadow-sm">
                        <i class="bi bi-people-fill"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">Karyawan</span>
                        <span class="info-box-number">{{ $jumlahKaryawan }}</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            {{-- STATUS UMUM: AKTIF / NON AKTIF --}}
            @forelse ($finalKaryawanCounts as $datakaryawan)
                <div class="col-12 col-sm-4 col-md-4 mb-3">
                    <div class="info-box shadow-sm">
                        <span class="info-box-icon {{ $datakaryawan['color'] }}">
                            <i class="bi bi-person-fill-check"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text text-capitalize">{{ $datakaryawan['status'] }}</span>
                            <span class="info-box-number">{{ $datakaryawan['total'] }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-warning text-center">Tidak ada data status umum</div>
                </div>
            @endforelse

            {{-- STATUS_KARYAWAN DETAIL --}}
            @forelse ($finalDetailStatusKaryawan as $item)
                <div class="col-12 col-sm-4 col-md-4 mb-3">
                    <div class="info-box shadow-sm border-start border-4">
                        <span class="info-box-icon">
                            <i class="bi bi-people-fill"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text text-uppercase">
                                @if ($item['status_karyawan'] == 'TEMPORARY')
                                    TEMPORARY / NEW HIRE
                                @else
                                    {{ $item['status_karyawan'] }}
                                @endif
                            </span>
                            <div class="d-flex justify-content-between">
                                <small class="text-success">Aktif:</small>
                                <small>{{ $item['aktif'] }}</small>
                            </div>
                            <div class="d-flex justify-content-between">
                                <small class="text-danger">Non Aktif:</small>
                                <small>{{ $item['non_aktif'] }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-warning text-center">Tidak ada data status_karyawan</div>
                </div>
            @endforelse


            <div class="col-12 col-sm-12 col-md-6">
                <div class="card mb-4 collapsed-card">
                    <div class="card-header">
                        <h3 class="card-title">Karyawan Aktif</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse">
                                <i data-lte-icon="collapse" class="bi bi-dash-lg"></i>
                                <i data-lte-icon="expand" class="bi bi-plus-lg"></i>
                            </button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <!--begin::Row-->
                        <div class="row">
                            <div class="col-12">
                                <div id="pie-chart-aktif"></div>
                            </div>
                            <!-- /.col -->
                        </div>
                        <!--end::Row-->
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-12 col-md-6">
                <div class="card mb-4 collapsed-card">
                    <div class="card-header">
                        <h3 class="card-title">Karyawan Non Aktif</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse">
                                <i data-lte-icon="collapse" class="bi bi-dash-lg"></i>
                                <i data-lte-icon="expand" class="bi bi-plus-lg"></i>
                            </button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <!--begin::Row-->
                        <div class="row">
                            <div class="col-12">
                                <div id="pie-chart-non-aktif"></div>
                            </div>
                            <!-- /.col -->
                        </div>
                        <!--end::Row-->
                    </div>
                </div>
            </div>
        @endif
        <!-- expMCUKaryawan -->

        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header bg-secondary text-white">
                    <h5 class="card-title">DATA KARYAWAN BELUM MEMILIKI MCU / EXP MCU
                        ({{ auth()->user()->subrole ?? '' }})</h5>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse">
                            <i data-lte-icon="expand" class="bi bi-plus-lg"></i>
                            <i data-lte-icon="collapse" class="bi bi-dash-lg"></i>
                        </button>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive" style="max-height: 300px; overflow-y: auto;">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Departemen</th>
                                <th>Jabatan</th>
                                <th>Exp MCU</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($expmcukaryawan as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item['nama'] }}</td>
                                    <td>{{ $item['dept'] }}</td>
                                    <td>{{ $item['jabatan'] }}</td>
                                    <td>{{ $item['exp_mcu'] ?? '-Tidak Memiliki MCU-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Tidak ada data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- ./card-body -->

                <!-- /.card-footer -->
            </div>
        </div>

        <div class="col-md-9">
            <div class="card mb-4">
                <div class="card-header bg-secondary text-white">
                    <h5 class="card-title">Verifikator MCU {{ auth()->user()->subrole ?? '' }}</h5>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse">
                            <i data-lte-icon="expand" class="bi bi-plus-lg"></i>
                            <i data-lte-icon="collapse" class="bi bi-dash-lg"></i>
                        </button>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <form> <!-- Ganti dengan nama route yang sesuai -->
                                <div class="form-group">
                                    <label for="tanggal">Pilih Tanggal:</label>
                                    <input type="date" name="tanggal" wire:model="tanggal"
                                        wire:change="onchangeTanggal" id="tanggal"
                                        class="form-control d-inline-block w-auto">
                                </div>
                            </form>
                            @php
                                $carijumlahmcuharian = 0;
                                foreach ($verifikators as $dataverifikator) {
                                    $carijumlahmcuharian += $dataverifikator['status_total'];
                                }
                                $jumlahmcuharian = $mcuNoAcc + $carijumlahmcuharian;
                            @endphp
                            {{-- <pre>{{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y') }}</pre> --}}
                            <pre>MCU Harian : {{ $carijumlahmcuharian . '/' . $jumlahmcuharian }}</pre>
                        </div>

                        {{-- @forelse ($verifikators as $dataverifikator)
                            <div class="col-12 col-sm-4 col-md-6">
                                <div class="info-box">
                                    <span class="info-box-icon text-bg-info shadow-sm">
                                        <i class="bi bi bi-person-workspace"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">{{ $dataverifikator['nama'] }}</span>
                                        <span class="info-box-number">Fit : {{ $dataverifikator['status_fit'] }}</span>
                                        <span class="info-box-number">Follow Up :
                                            {{ $dataverifikator['status_follow_up'] }}</span>
                                        <span class="info-box-number">Temporary Unfit :
                                            {{ $dataverifikator['status_temporary_unfit'] }}</span>
                                        <span class="info-box-number">Unfit :
                                            {{ $dataverifikator['status_unfit'] }}</span>
                                        <span class="info-box-number">
                                            Total Verify :
                                            {{ $dataverifikator['status_total'] }}
                                        </span>
                                    </div>
                                    <!-- /.info-box-content -->
                                </div>
                                <!-- /.info-box -->
                            </div>
                        @empty
                            -
                        @endforelse --}}
                        @forelse ($verifikators as $dataverifikator)
                            <div class="col-md-6">
                                <p class="text-center"><strong>{{ $dataverifikator['nama'] }}</strong></p>

                                {{-- FIT --}}
                                <div class="progress-group">
                                    FIT
                                    <span class="float-end">
                                        <b>{{ $dataverifikator['status_fit'] }}</b>/{{ $jumlahmcuharian }}
                                    </span>
                                    @php
                                        $persenFit =
                                            $jumlahmcuharian > 0
                                                ? ($dataverifikator['status_fit'] / $jumlahmcuharian) * 100
                                                : 0;
                                    @endphp
                                    <div class="progress progress-sm" style="height: 20px">
                                        <div class="progress-bar text-bg-success"
                                            style="width: {{ $persenFit }}%">
                                            {{ number_format($persenFit, 2) }} %
                                        </div>
                                    </div>
                                </div>

                                {{-- FOLLOW UP --}}
                                <div class="progress-group">
                                    FOLLOW UP
                                    <span class="float-end">
                                        <b>{{ $dataverifikator['status_follow_up'] }}</b>/{{ $jumlahmcuharian }}
                                    </span>
                                    @php
                                        $persenFollowUp =
                                            $jumlahmcuharian > 0
                                                ? ($dataverifikator['status_follow_up'] / $jumlahmcuharian) * 100
                                                : 0;
                                    @endphp
                                    <div class="progress progress-sm" style="height: 20px">
                                        <div class="progress-bar text-bg-primary"
                                            style="width: {{ $persenFollowUp }}%">
                                            {{ number_format($persenFollowUp, 2) }} %
                                        </div>
                                    </div>
                                </div>

                                {{-- TEMPORARY UNFIT --}}
                                <div class="progress-group">
                                    TEMPORARY UNFIT
                                    <span class="float-end">
                                        <b>{{ $dataverifikator['status_temporary_unfit'] }}</b>/{{ $jumlahmcuharian }}
                                    </span>
                                    @php
                                        $persenTemporaryUnfit =
                                            $jumlahmcuharian > 0
                                                ? ($dataverifikator['status_temporary_unfit'] / $jumlahmcuharian) * 100
                                                : 0;
                                    @endphp
                                    <div class="progress progress-sm" style="height: 20px">
                                        <div class="progress-bar text-bg-warning"
                                            style="width: {{ $persenTemporaryUnfit }}%">
                                            {{ number_format($persenTemporaryUnfit, 2) }} %
                                        </div>
                                    </div>
                                </div>

                                {{-- UNFIT --}}
                                <div class="progress-group">
                                    UNFIT
                                    <span class="float-end">
                                        <b>{{ $dataverifikator['status_unfit'] }}</b>/{{ $jumlahmcuharian }}
                                    </span>
                                    @php
                                        $persenUnfit =
                                            $jumlahmcuharian > 0
                                                ? ($dataverifikator['status_unfit'] / $jumlahmcuharian) * 100
                                                : 0;
                                    @endphp
                                    <div class="progress progress-sm" style="height: 20px">
                                        <div class="progress-bar text-bg-danger" style="width: {{ $persenUnfit }}%">
                                            {{ number_format($persenUnfit, 2) }} %
                                        </div>
                                    </div>
                                </div>

                                {{-- Total Verifikasi --}}
                                <div class="progress-group">
                                    Total Verifikasi
                                    <span class="float-end">
                                        <b>{{ $dataverifikator['status_total'] }}</b>/{{ $jumlahmcuharian }}
                                    </span>
                                    @php
                                        $persenTotal =
                                            $jumlahmcuharian > 0
                                                ? ($dataverifikator['status_total'] / $jumlahmcuharian) * 100
                                                : 0;
                                    @endphp
                                    <div class="progress progress-sm" style="height: 20px">
                                        <div class="progress-bar text-bg-secondary"
                                            style="width: {{ $persenTotal }}%">
                                            {{ number_format($persenTotal, 2) }} %
                                        </div>
                                    </div>
                                </div>


                            </div>
                        @empty
                            -
                        @endforelse
                        <div class="col-md-12 text-center">
                            <p class="text-center"><strong>Total Verifikasi</strong></p>

                            {{-- FIT --}}
                            <div class="progress-group">
                                <span class="float-end">
                                    <b>{{ $totalSemuaStatus }}</b>/{{ $jumlahmcuharian }}
                                </span>
                                @php
                                    $persenFit =
                                        $jumlahmcuharian > 0 ? ($totalSemuaStatus / $jumlahmcuharian) * 100 : 0;
                                @endphp
                                <div class="progress progress-sm" style="height: 20px">
                                    <div class="progress-bar text-bg-success" style="width: {{ $persenFit }}%">
                                        {{ number_format($persenFit, 2) }} %
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>

                </div>
                <!-- ./card-body -->

                <!-- /.card-footer -->
            </div>
            <!-- /.card -->
        </div>

        <div class="col-md-3">
            <div class="card mb-4">
                <div class="card-header bg-secondary text-white">
                    <h5 class="card-title">Temuan MCU</h5>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse">
                            <i data-lte-icon="expand" class="bi bi-plus-lg"></i>
                            <i data-lte-icon="collapse" class="bi bi-dash-lg"></i>
                        </button>

                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="row">
                        {{-- <div class="col-12">
                            <div class="info-box">
                                <span class="info-box-icon shadow-sm">
                                    <i class="bi bi-person-fill-check"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Gula Normal</span>
                                    <span class="info-box-number">{{ $gulanormal }}</span>
                                </div>
                            </div>
                        </div> --}}
                        <div class="col-12">
                            <div class="info-box">
                                <span class="info-box-icon shadow-sm">
                                    <i class="bi bi-person-fill-check"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Prediabetes</span>
                                    <span class="info-box-number">{{ $prediabetes }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="info-box">
                                <span class="info-box-icon shadow-sm">
                                    <i class="bi bi-person-fill-check"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Diabetes</span>
                                    <span class="info-box-number">{{ $diabetes }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ./card-body -->

                <!-- /.card-footer -->
            </div>
            <!-- /.card -->
        </div>


        <!-- /.col -->
    </div>

    <script>
        const departments = @json($departments->pluck('name_department'));
        const colors = @json($assignedColors);
        const seriesAktif = @json($employeeCountsAktif);
        const seriesNonAktif = @json($employeeCountsNonAktif);

        let pie_chart_aktif = null;
        let pie_chart_non_aktif = null;

        function getLabelColor(theme) {
            return theme === "dark" ? "#ffffff" : "#000000";
        }

        function renderPieCharts(theme) {
            const labelColor = getLabelColor(theme);

            const pie_chart_options_aktif = {
                series: seriesAktif,
                chart: {
                    type: 'pie',
                },
                labels: departments,
                dataLabels: {
                    enabled: false,
                },
                colors: colors,
                legend: {
                    labels: {
                        colors: labelColor
                    }
                }
            };

            const pie_chart_options_non_aktif = {
                series: seriesNonAktif,
                chart: {
                    type: 'pie',
                },
                labels: departments,
                dataLabels: {
                    enabled: false,
                },
                colors: colors,
                legend: {
                    labels: {
                        colors: labelColor
                    }
                }
            };

            // Destroy charts if already rendered
            if (pie_chart_aktif) pie_chart_aktif.destroy();
            if (pie_chart_non_aktif) pie_chart_non_aktif.destroy();

            // Render charts
            pie_chart_aktif = new ApexCharts(document.querySelector('#pie-chart-aktif'), pie_chart_options_aktif);
            pie_chart_aktif.render();

            pie_chart_non_aktif = new ApexCharts(document.querySelector('#pie-chart-non-aktif'),
                pie_chart_options_non_aktif);
            pie_chart_non_aktif.render();
        }

        // Initial render with current theme
        const currentTheme = document.documentElement.getAttribute("data-bs-theme") || "light";
        renderPieCharts(currentTheme);

        // Re-render charts when theme changes
        const observer = new MutationObserver((mutations) => {
            mutations.forEach(mutation => {
                if (mutation.attributeName === "data-bs-theme") {
                    const newTheme = document.documentElement.getAttribute("data-bs-theme");
                    renderPieCharts(newTheme);
                }
            });
        });

        observer.observe(document.documentElement, {
            attributes: true
        });
    </script>

</div>
