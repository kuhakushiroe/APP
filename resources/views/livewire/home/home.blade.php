<div>
    <div class="row">
        <!-- /.col -->
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
        @forelse ($karyawanCounts as $datakaryawan)
            <div class="col-12 col-sm-4 col-md-4">
                <div class="info-box">
                    <span class="info-box-icon {{ $datakaryawan['color'] }} shadow-sm">
                        <i class="bi bi-person-fill-check"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">{{ $datakaryawan['status'] }}</span>
                        <span class="info-box-number">{{ $datakaryawan['total'] }}</span>
                    </div>
                </div>
            </div>
        @empty
            <p>Tidak ada data MCU.</p>
        @endforelse
        {{-- <div class="col-12 col-sm-4 col-md-4">
            <div class="info-box">
                <span class="info-box-icon text-bg-success shadow-sm">
                    <i class="bi bi-people-fill"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Karyawan Aktif</span>
                    <span class="info-box-number">{{ $jumlahKaryawanAktif }}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <div class="col-12 col-sm-4 col-md-4">
            <div class="info-box">

                <span class="info-box-icon text-bg-secondary shadow-sm">
                    <i class="bi bi-people-fill"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Karyawan Non Aktif</span>
                    <span class="info-box-number">{{ $jumlahKaryawanNonAktif }}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div> --}}

        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header bg-secondary text-white">
                    <h5 class="card-title">Laporan MCU</h5>
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
                        @forelse ($mcuCounts as $datamcu)
                            <div class="col-12 col-sm-4 col-md-3">
                                <div class="info-box">
                                    <span class="info-box-icon {{ $datamcu['color'] }} shadow-sm">
                                        <i class="bi bi-person-fill-check"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">{{ $datamcu['status'] }}</span>
                                        <span class="info-box-number">{{ $datamcu['total'] }}</span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p>Tidak ada data MCU.</p>
                        @endforelse

                        {{-- <div class="col-12 col-sm-4 col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon text-bg-success shadow-sm">
                                    <i class="bi bi-person-fill-check"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Karyawan MCU Fit</span>
                                    <span class="info-box-number">{{ $jumlahMCUFit }}</span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <div class="col-12 col-sm-4 col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon text-bg-info shadow-sm">
                                    <i class="bi bi bi-person-fill-add"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Karyawan MCU Fit Whit Note</span>
                                    <span class="info-box-number">{{ $jumlahMCUFitWithNote }}</span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <div class="col-12 col-sm-4 col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon text-bg-warning shadow-sm">
                                    <i class="bi bi-person-exclamation"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Karyawan MCU Follow Up</span>
                                    <span class="info-box-number">{{ $jumlahMCUFollowUp }}</span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <div class="col-12 col-sm-4 col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon text-bg-danger shadow-sm">
                                    <i class="bi bi-person-fill-x"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Karyawan MCU Unfit</span>
                                    <span class="info-box-number">{{ $jumlahMCUnfit }}</span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div> --}}
                    </div>
                </div>
                <!-- ./card-body -->

                <!-- /.card-footer -->
            </div>
            <!-- /.card -->
        </div>

        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header bg-secondary text-white">
                    <h5 class="card-title">Verifikator MCU</h5>
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
                        @forelse ($verifikators as $dataverifikator)
                            <div class="col-12 col-sm-4 col-md-6">
                                <div class="info-box">
                                    <span class="info-box-icon text-bg-info shadow-sm">
                                        <i class="bi bi bi-person-workspace"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">{{ $dataverifikator['nama'] }}</span>
                                        <span class="info-box-number">{{ $dataverifikator['jumlah_mcu'] }}</span>
                                    </div>
                                    <!-- /.info-box-content -->
                                </div>
                                <!-- /.info-box -->
                            </div>
                        @empty
                            -
                        @endforelse

                        {{-- <div class="col-12 col-sm-4 col-md-6">
                            <div class="info-box">
                                <span class="info-box-icon text-bg-info shadow-sm">
                                    <i class="bi bi bi-person-workspace"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Dokter A</span>
                                    <span class="info-box-number">{{ $dokter }}</span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <div class="col-12 col-sm-4 col-md-6">
                            <div class="info-box">
                                <span class="info-box-icon text-bg-info shadow-sm">
                                    <i class="bi bi bi-person-workspace"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Dokter B</span>
                                    <span class="info-box-number">{{ $dokter2 }}</span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div> --}}
                    </div>


                </div>
                <!-- ./card-body -->

                <!-- /.card-footer -->
            </div>
            <!-- /.card -->
        </div>

        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header bg-secondary text-white">
                    <h5 class="card-title">Temuan Pemeriksaan Kesehatan Karyawan</h5>
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

                        <div class="col-12 col-sm-4 col-md-4">
                            <div class="info-box">
                                <span class="info-box-icon shadow-sm">
                                    <i class="bi bi-person-fill-check"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Gula Normal</span>
                                    <span class="info-box-number">{{$gulanormal}}</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-sm-4 col-md-4">
                            <div class="info-box">
                                <span class="info-box-icon shadow-sm">
                                    <i class="bi bi-person-fill-check"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Prediabetes</span>
                                    <span class="info-box-number">{{$prediabetes}}</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-sm-4 col-md-4">
                            <div class="info-box">
                                <span class="info-box-icon shadow-sm">
                                    <i class="bi bi-person-fill-check"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Diabetes</span>
                                    <span class="info-box-number">{{$diabetes}}</span>
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


        <div class="col-12
                                        col-sm-12 col-md-6">
            <div class="card mb-4">
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
            <div class="card mb-4">
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
