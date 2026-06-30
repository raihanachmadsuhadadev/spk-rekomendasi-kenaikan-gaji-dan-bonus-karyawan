@extends('layouts.app')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        @php
            $periodeText = ($bulanList[$bulan] ?? $bulan) . ' ' . $tahun;
            $summary = $hrSummary ?? [];
            $kpiUmumStatus = $summary['kpiUmumStatus'] ?? [];
            $distributionStatus = $summary['kpiDivisiDistributionStatus'] ?? [];
            $kpiDivisiRealizationStatus = $summary['kpiDivisiRealizationStatus'] ?? [];
            $peerAssessment = $summary['peerAssessment'] ?? ['total' => 0, 'submitted' => 0];
            $statCards = [
                [
                    'label' => 'Total Karyawan',
                    'value' => $summary['totalKaryawan'] ?? 0,
                    'icon' => 'bx-group',
                    'tone' => 'primary',
                ],
                [
                    'label' => 'Total Divisi',
                    'value' => $summary['totalDivisi'] ?? 0,
                    'icon' => 'bx-building-house',
                    'tone' => 'info',
                ],
                [
                    'label' => 'KPI Umum',
                    'value' => $summary['totalKpiUmum'] ?? 0,
                    'icon' => 'bx-data',
                    'tone' => 'success',
                ],
                [
                    'label' => 'KPI Divisi',
                    'value' => $summary['totalKpiDivisi'] ?? 0,
                    'icon' => 'bx-task',
                    'tone' => 'warning',
                ],
            ];
            $shortcuts = [
                ['label' => 'Data Divisi', 'route' => route('divisions.index'), 'icon' => 'bx-building-house'],
                ['label' => 'Data User', 'route' => route('users.index'), 'icon' => 'bx-group'],
                ['label' => 'KPI Umum', 'route' => route('kpi-umum.index'), 'icon' => 'bx-data'],
                ['label' => 'KPI Divisi', 'route' => route('kpi-divisi.index'), 'icon' => 'bx-task'],
                ['label' => 'Bobot KPI Umum', 'route' => route('ahp.kpi-umum.index'), 'icon' => 'bx-analyse'],
                ['label' => 'Bobot KPI Divisi', 'route' => route('ahp.kpi-divisi.index'), 'icon' => 'bx-line-chart'],
                ['label' => 'Peer Assessment', 'route' => route('peer.admin.index'), 'icon' => 'bx-check-circle'],
                ['label' => 'Leaderboard', 'route' => route('leaderboard.bulanan.index'), 'icon' => 'bx-trophy'],
                ['label' => 'Rekomendasi Bonus', 'route' => route('bonus.rekomendasi.index'), 'icon' => 'bx-gift'],
                ['label' => 'Kenaikan Gaji', 'route' => route('salary.raise.index'), 'icon' => 'bx-money'],
            ];
        @endphp

        @if (($me->role ?? null) === 'hr')
            <div class="row g-4 mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body d-flex flex-column flex-lg-row justify-content-between gap-3">
                            <div>
                                <div class="text-muted small mb-1">Dashboard HR</div>
                                <h4 class="mb-1">Ringkasan Kinerja dan Data SPK</h4>
                                <p class="mb-0 text-muted">
                                    Pantau kesiapan data KPI, realisasi, peer assessment, dan leaderboard untuk periode aktif.
                                </p>
                            </div>
                            <form method="GET" action="{{ url()->current() }}" class="d-flex align-items-center gap-2">
                                <select name="bulan" class="form-select form-select-sm w-auto">
                                    @foreach ($bulanList as $num => $label)
                                        <option value="{{ $num }}" {{ (int) $bulan === (int) $num ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                <input type="number" name="tahun" value="{{ $tahun }}"
                                    class="form-control form-control-sm w-auto" style="width:90px" min="2000" max="2100">
                                <button class="btn btn-sm btn-primary">
                                    <i class="bx bx-filter me-1"></i> Filter
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                @foreach ($statCards as $card)
                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="card h-100">
                            <div class="card-body d-flex align-items-center gap-3">
                                <span class="avatar-initial rounded bg-label-{{ $card['tone'] }} p-3">
                                    <i class="bx {{ $card['icon'] }} fs-4"></i>
                                </span>
                                <div>
                                    <div class="text-muted small">{{ $card['label'] }}</div>
                                    <h4 class="mb-0">{{ number_format($card['value']) }}</h4>
                                    <small class="text-muted">{{ $periodeText }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="row g-4 mb-4">
                <div class="col-12 col-xl-4">
                    <div class="card h-100">
                        <div class="card-header">
                            <h6 class="card-title mb-0"><i class="bx bx-task me-1"></i>Status KPI Umum</h6>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Submitted</span>
                                <span class="fw-semibold">{{ number_format($kpiUmumStatus['submitted'] ?? 0) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Approved</span>
                                <span class="fw-semibold text-success">{{ number_format($kpiUmumStatus['approved'] ?? 0) }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Rejected</span>
                                <span class="fw-semibold text-danger">{{ number_format($kpiUmumStatus['rejected'] ?? 0) }}</span>
                            </div>
                        </div>
                        <div class="card-footer small text-muted">Periode: {{ $periodeText }}</div>
                    </div>
                </div>

                <div class="col-12 col-xl-4">
                    <div class="card h-100">
                        <div class="card-header">
                            <h6 class="card-title mb-0"><i class="bx bx-transfer me-1"></i>Distribusi KPI Divisi</h6>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Submitted</span>
                                <span class="fw-semibold">{{ number_format($distributionStatus['submitted'] ?? 0) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Approved</span>
                                <span class="fw-semibold text-success">{{ number_format($distributionStatus['approved'] ?? 0) }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Rejected</span>
                                <span class="fw-semibold text-danger">{{ number_format($distributionStatus['rejected'] ?? 0) }}</span>
                            </div>
                        </div>
                        <div class="card-footer small text-muted">Periode: {{ $periodeText }}</div>
                    </div>
                </div>

                <div class="col-12 col-xl-4">
                    <div class="card h-100">
                        <div class="card-header">
                            <h6 class="card-title mb-0"><i class="bx bx-check-circle me-1"></i>Peer Assessment</h6>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Total assessment</span>
                                <span class="fw-semibold">{{ number_format($peerAssessment['total'] ?? 0) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Sudah submit</span>
                                <span class="fw-semibold text-success">{{ number_format($peerAssessment['submitted'] ?? 0) }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Belum submit</span>
                                <span class="fw-semibold text-warning">
                                    {{ number_format(max(0, ($peerAssessment['total'] ?? 0) - ($peerAssessment['submitted'] ?? 0))) }}
                                </span>
                            </div>
                        </div>
                        <div class="card-footer small text-muted">Periode: {{ $periodeText }}</div>
                    </div>
                </div>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-12 col-xl-7">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="card-title mb-0"><i class="bx bx-bar-chart-alt me-1"></i>Status Realisasi KPI Divisi</h6>
                            <span class="badge bg-label-secondary">{{ $periodeText }}</span>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table-sm mb-0 table align-middle">
                                    <thead>
                                        <tr class="text-muted text-uppercase small">
                                            <th>Tipe</th>
                                            <th class="text-end">Submitted</th>
                                            <th class="text-end">Approved</th>
                                            <th class="text-end">Rejected</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach (['kuantitatif' => 'Kuantitatif', 'kualitatif' => 'Kualitatif', 'response' => 'Response', 'persentase' => 'Persentase'] as $key => $label)
                                            @php $status = $kpiDivisiRealizationStatus[$key] ?? []; @endphp
                                            <tr>
                                                <td class="fw-semibold">{{ $label }}</td>
                                                <td class="text-end">{{ number_format($status['submitted'] ?? 0) }}</td>
                                                <td class="text-end text-success">{{ number_format($status['approved'] ?? 0) }}</td>
                                                <td class="text-end text-danger">{{ number_format($status['rejected'] ?? 0) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-xl-5">
                    <div class="card h-100">
                        <div class="card-header">
                            <h6 class="card-title mb-0"><i class="bx bx-link-alt me-1"></i>Aksi Cepat HR</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-2">
                                @foreach ($shortcuts as $shortcut)
                                    <div class="col-12 col-sm-6">
                                        <a href="{{ $shortcut['route'] }}"
                                            class="btn btn-outline-secondary btn-sm w-100 d-flex align-items-center justify-content-start">
                                            <i class="bx {{ $shortcut['icon'] }} me-2"></i>
                                            <span class="text-truncate">{{ $shortcut['label'] }}</span>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <h5 class="mb-1">Ringkasan Leaderboard</h5>
                <div class="text-muted small">Data ranking periode {{ $periodeText }}</div>
            </div>
        </div>

        {{-- Row 1: 2 Card --}}
        <div class="row g-4">
            {{-- Card 1: Top 5 Global --}}
            <div class="col-12 col-lg-6">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div class="me-2">
                            <h6 class="card-title d-flex align-items-center mb-1"><i class="bx bx-trophy me-1"></i> Top 5
                                Karyawan Global</h6>
                        </div>
                        <form method="GET" action="{{ url()->current() }}" class="d-flex align-items-center gap-2">
                            <select name="bulan" class="form-select form-select-sm w-auto">
                                @foreach ($bulanList as $num => $label)
                                    <option value="{{ $num }}" {{ (int) $bulan === (int) $num ? 'selected' : '' }}>
                                        {{ $label }}</option>
                                @endforeach
                            </select>
                            <input type="number" name="tahun" value="{{ $tahun }}"
                                class="form-control form-control-sm w-auto" style="width:90px" min="2000"
                                max="2100">
                            <button class="btn btn-sm btn-outline-secondary"><i class="bx bx-filter"></i></button>
                        </form>
                    </div>
                    <div class="card-body pb-2">
                        <div class="overflow-auto" style="max-height:260px;">
                            <table class="table-sm mb-0 table align-middle">
                                <thead>
                                    <tr class="text-muted text-uppercase small">
                                        <th style="width:56px;">Rank</th>
                                        <th>Nama</th>
                                        <th>Divisi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($topGlobal as $row)
                                        <tr>
                                            <td class="fw-semibold">{{ $row['rank'] }}</td>
                                            <td class="fw-semibold">{{ $row['name'] }}</td>
                                            <td>{{ $row['division'] }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-muted py-4 text-center">Belum ada data.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer small text-muted">Periode: {{ $periodeText }}</div>
                </div>
            </div>

            {{-- Card 2: Top 5 Karyawan di Divisi --}}
            <div class="col-12 col-lg-6">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div class="me-2">
                            <h6 class="card-title d-flex align-items-center mb-1"><i class="bx bx-target-lock me-1"></i> Top
                                5 Karyawan di Divisi</h6>
                        </div>
                        <form method="GET" action="{{ url()->current() }}" class="d-flex align-items-center gap-2">
                            <select name="division_id" class="form-select form-select-sm w-120px" style="min-width:100px;">
                                @foreach ($divisions as $d)
                                    <option value="{{ $d->id }}"
                                        {{ (int) $divisionId === (int) $d->id ? 'selected' : '' }}>{{ $d->name }}
                                    </option>
                                @endforeach
                            </select>
                            <select name="bulan" class="form-select form-select-sm w-auto">
                                @foreach ($bulanList as $num => $label)
                                    <option value="{{ $num }}"
                                        {{ (int) $bulan === (int) $num ? 'selected' : '' }}>
                                        {{ $label }}</option>
                                @endforeach
                            </select>
                            <input type="number" name="tahun" value="{{ $tahun }}"
                                class="form-control form-control-sm w-auto" style="width:90px" min="2000"
                                max="2100">
                            <button class="btn btn-sm btn-outline-secondary"><i class="bx bx-filter"></i></button>
                        </form>
                    </div>
                    <div class="card-body pb-2">
                        @if (empty($divisionId))
                            <div class="text-muted py-5 text-center">Silakan pilih divisi terlebih dahulu.</div>
                        @else
                            <div class="overflow-auto" style="max-height:260px;">
                                <table class="table-sm mb-0 table align-middle">
                                    <thead>
                                        <tr class="text-muted text-uppercase small">
                                            <th style="width:56px;">Rank</th>
                                            <th>Nama</th>
                                            <th class="text-end" style="width:90px;">Skor</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($topInDivisi as $row)
                                            <tr>
                                                <td class="fw-semibold">{{ $row['rank'] }}</td>
                                                <td class="fw-semibold">{{ $row['name'] }}</td>
                                                <td class="text-end">{{ number_format($row['score'], 2) }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-muted py-4 text-center">Belum ada data.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                    <div class="card-footer small text-muted">Periode: {{ $periodeText }}</div>
                </div>
            </div>
        </div>

        {{-- Row 2: 1 Card --}}
        <div class="row g-4 mt-1">
            <div class="col-12 col-lg-6">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div class="me-2">
                            <h6 class="card-title d-flex align-items-center mb-1"><i class="bx bx-building-house me-1"></i>
                                Top 5 Divisi (KPI Divisi)</h6>
                        </div>
                        <form method="GET" action="{{ url()->current() }}" class="d-flex align-items-center gap-2">
                            <select name="bulan" class="form-select form-select-sm w-auto">
                                @foreach ($bulanList as $num => $label)
                                    <option value="{{ $num }}"
                                        {{ (int) $bulan === (int) $num ? 'selected' : '' }}>
                                        {{ $label }}</option>
                                @endforeach
                            </select>
                            <input type="number" name="tahun" value="{{ $tahun }}"
                                class="form-control form-control-sm w-auto" style="width:90px" min="2000"
                                max="2100">
                            <button class="btn btn-sm btn-outline-secondary"><i class="bx bx-filter"></i></button>
                        </form>
                    </div>
                    <div class="card-body pb-2">
                        <div class="overflow-auto" style="max-height:260px;">
                            <table class="table-sm mb-0 table align-middle">
                                <thead>
                                    <tr class="text-muted text-uppercase small">
                                        <th style="width:56px;">Rank</th>
                                        <th>Divisi</th>
                                        <th class="text-end" style="width:90px;">Avg</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($topDivisi as $row)
                                        <tr>
                                            <td class="fw-semibold">{{ $row['rank'] }}</td>
                                            <td class="fw-semibold">{{ $row['division'] }}</td>
                                            <td class="text-end">{{ number_format($row['score'], 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-muted py-4 text-center">Belum ada data.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer small text-muted">Periode: {{ $periodeText }}</div>
                </div>
            </div>
        </div>

    </div>
@endsection
