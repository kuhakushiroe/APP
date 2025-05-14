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
        <div class="col-12 col-sm-4 col-md-4">
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
        </div>
        <div class="col-12 col-sm-12 col-md-6">
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
