@extends('layouts.app')

@section('content')
    @php
        $periodeText = ($bulanList[$bulan] ?? $bulan) . ' ' . $tahun;
        $summary = $leaderSummary ?? [];
        $division = $summary['division'] ?? $me->division ?? null;
        $divisionName = $division?->name ?? 'Belum ada divisi';
        $distributionStatus = $summary['distributionStatus'] ?? [];
        $kpiUmumStatus = $summary['kpiUmumStatus'] ?? [];
        $kpiDivisiStatus = $summary['kpiDivisiStatus'] ?? [];
        $peerAssessment = $summary['peerAssessment'] ?? ['total' => 0, 'submitted' => 0];
        $statCards = [
            [
                'label' => 'Anggota Divisi',
                'value' => $summary['memberCount'] ?? 0,
                'icon' => 'bx-group',
                'tone' => 'primary',
            ],
            [
                'label' => 'KPI Divisi',
                'value' => $summary['kpiDivisiCount'] ?? 0,
                'icon' => 'bx-data',
                'tone' => 'info',
            ],
            [
                'label' => 'Realisasi KPI Umum',
                'value' => $summary['kpiUmumRealizationCount'] ?? 0,
                'icon' => 'bx-task',
                'tone' => 'success',
            ],
            [
                'label' => 'Realisasi KPI Divisi',
                'value' => $summary['kpiDivisiRealizationCount'] ?? 0,
                'icon' => 'bx-bar-chart-alt',
                'tone' => 'warning',
            ],
        ];
        $kpiChecks = [
            [
                'label' => 'KPI divisi tersedia',
                'count' => $summary['kpiDivisiCount'] ?? 0,
            ],
            [
                'label' => 'Distribusi target tersedia',
                'count' => array_sum($distributionStatus),
            ],
            [
                'label' => 'Realisasi kuantitatif tersedia',
                'count' => array_sum($kpiDivisiStatus['kuantitatif'] ?? []),
            ],
            [
                'label' => 'Realisasi kualitatif tersedia',
                'count' => array_sum($kpiDivisiStatus['kualitatif'] ?? []),
            ],
            [
                'label' => 'Realisasi response tersedia',
                'count' => array_sum($kpiDivisiStatus['response'] ?? []),
            ],
            [
                'label' => 'Realisasi persentase tersedia',
                'count' => array_sum($kpiDivisiStatus['persentase'] ?? []),
            ],
        ];
        $shortcuts = [
            ['label' => 'Realisasi KPI Umum', 'route' => route('realisasi-kpi-umum.index'), 'icon' => 'bx-task'],
            ['label' => 'Distribusi Target', 'route' => route('distribusi-kpi-divisi.index'), 'icon' => 'bx-transfer'],
            ['label' => 'KPI Kuantitatif', 'route' => route('realisasi-kpi-divisi-kuantitatif.index'), 'icon' => 'bx-bar-chart'],
            ['label' => 'KPI Kualitatif', 'route' => route('realisasi-kpi-divisi-kualitatif.index'), 'icon' => 'bx-message-square-check'],
            ['label' => 'KPI Response', 'route' => route('realisasi-kpi-divisi-response.index'), 'icon' => 'bx-timer'],
            ['label' => 'KPI Persentase', 'route' => route('realisasi-kpi-divisi-persentase.index'), 'icon' => 'bx-pie-chart-alt'],
            ['label' => 'Leaderboard Divisi', 'route' => route('leaderboard.divisi.index', ['division_id' => $divisionId, 'bulan' => $bulan, 'tahun' => $tahun]), 'icon' => 'bx-trophy'],
            ['label' => 'Data Anggota', 'route' => route('users.index'), 'icon' => 'bx-group'],
        ];
    @endphp

    @if (($me->role ?? null) === 'leader')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row g-4 mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body d-flex flex-column flex-lg-row justify-content-between gap-3">
                        <div>
                            <div class="text-muted small mb-1">Dashboard Kepala Divisi</div>
                            <h4 class="mb-1">{{ $divisionName }}</h4>
                            <p class="mb-0 text-muted">
                                {{ $me->full_name }} memantau progress KPI, realisasi, dan performa anggota untuk periode {{ $periodeText }}.
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
            <div class="col-12 col-xl-8">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div>
                            <h6 class="card-title mb-1"><i class="bx bx-medal me-1"></i>Top Anggota Divisi</h6>
                            <small class="text-muted">Skor gabungan dalam {{ $divisionName }}</small>
                        </div>
                        <a href="{{ route('leaderboard.divisi.index', ['division_id' => $divisionId, 'bulan' => $bulan, 'tahun' => $tahun]) }}"
                            class="btn btn-sm btn-outline-primary">
                            <i class="bx bx-right-arrow-alt me-1"></i> Detail
                        </a>
                    </div>
                    <div class="card-body pb-2">
                        <div class="table-responsive">
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
                                            <td colspan="3" class="text-muted py-4 text-center">
                                                Belum ada data pada periode ini.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h6 class="card-title mb-0"><i class="bx bx-check-shield me-1"></i>Status Data Divisi</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">KPI umum approved</span>
                            <span class="fw-semibold text-success">{{ number_format($kpiUmumStatus['approved'] ?? 0) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Distribusi approved</span>
                            <span class="fw-semibold text-success">{{ number_format($distributionStatus['approved'] ?? 0) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Peer submitted</span>
                            <span class="fw-semibold text-success">{{ number_format($peerAssessment['submitted'] ?? 0) }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Peer total</span>
                            <span class="fw-semibold">{{ number_format($peerAssessment['total'] ?? 0) }}</span>
                        </div>
                    </div>
                    <div class="card-footer small text-muted">Periode: {{ $periodeText }}</div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-12 col-xl-7">
                <div class="card h-100">
                    <div class="card-header">
                        <h6 class="card-title mb-0"><i class="bx bx-list-check me-1"></i>Status KPI Divisi</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            @foreach ($kpiChecks as $check)
                                @php $available = ($check['count'] ?? 0) > 0; @endphp
                                <div class="col-12 col-md-6">
                                    <div class="d-flex align-items-center justify-content-between rounded border p-3">
                                        <div>
                                            <div class="fw-semibold">{{ $check['label'] }}</div>
                                            <small class="text-muted">{{ number_format($check['count'] ?? 0) }} data</small>
                                        </div>
                                        <span class="badge bg-label-{{ $available ? 'success' : 'secondary' }}">
                                            {{ $available ? 'Tersedia' : 'Belum tersedia' }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-5">
                <div class="card h-100">
                    <div class="card-header">
                        <h6 class="card-title mb-0"><i class="bx bx-link-alt me-1"></i>Aksi Cepat Leader</h6>
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
    </div>
    @else
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row g-4">
                <div class="col-12 col-lg-6">
                    <div class="card h-100">
                        <div class="card-header">
                            <h6 class="card-title d-flex align-items-center mb-1">
                                <i class="bx bx-trophy me-1"></i> Top 5 Karyawan Global
                            </h6>
                            <small class="text-muted">Periode {{ $periodeText }}</small>
                        </div>
                        <div class="card-body pb-2">
                            <div class="table-responsive">
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
                                                <td colspan="3" class="text-muted py-4 text-center">
                                                    Belum ada data pada periode ini.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-6">
                    <div class="card h-100">
                        <div class="card-header">
                            <h6 class="card-title d-flex align-items-center mb-1">
                                <i class="bx bx-target-lock me-1"></i> Top 5 Karyawan di Divisi
                            </h6>
                            <small class="text-muted">Periode {{ $periodeText }}</small>
                        </div>
                        <div class="card-body pb-2">
                            <div class="table-responsive">
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
                                                <td colspan="3" class="text-muted py-4 text-center">
                                                    Belum ada data pada periode ini.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
