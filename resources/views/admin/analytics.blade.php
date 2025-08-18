<x-admin.layout title="Analytics">
    <div class="content-card card">
        <div class="heading">
            <h2>Analytics Dashboard</h2>
            <p>Overview of your visitors and traffic</p>
            <form method="GET" action="{{ route('admin.analytics') }}" class="filters">
                <label for="range">Range</label>
                <select id="range" name="range" onchange="this.form.submit()">
                    <option value="7" {{ $range == 7 ? 'selected' : '' }}>Last 7 days</option>
                    <option value="30" {{ $range == 30 ? 'selected' : '' }}>Last 30 days</option>
                    <option value="90" {{ $range == 90 ? 'selected' : '' }}>Last 90 days</option>
                </select>
            </form>
        </div>

        <div class="grid grid-4">
            <div class="stat-card">
                <div class="stat-title">Total Visitors</div>
                <div class="stat-value">{{ number_format($totalVisitors) }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-title">Unique Visitors</div>
                <div class="stat-value">{{ number_format($uniqueVisitors) }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-title">Live Users (5m)</div>
                <div class="stat-value" id="liveUsers">—</div>
            </div>
            <div class="stat-card">
                <div class="stat-title">Downloads Today</div>
                <div class="stat-value" id="downloadsToday">—</div>
            </div>
            <div class="stat-card">
                <div class="stat-title">New vs Returning</div>
                <div class="chart-container"><canvas id="newReturningChart"></canvas></div>
            </div>
        </div>

        <div class="grid grid-2">
            <div class="card-inner">
                <div class="card-title">Traffic Trends</div>
                <div class="chart-container"><canvas id="trafficChart"></canvas></div>
            </div>
            <div class="card-inner">
                <div class="card-title">Downloads Trend</div>
                <div class="chart-container"><canvas id="downloadsChart"></canvas></div>
            </div>
            <div class="card-inner">
                <div class="card-title">Geography</div>
                <div id="worldMap" class="map"></div>
                <div class="top-countries">
                    <div class="top-title">Top Countries</div>
                    <ul>
                        @foreach($countries as $country)
                            <li><span>{{ $country['name'] }}</span><span>{{ number_format($country['visitors']) }}</span></li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <div class="grid grid-2">
            <div class="card-inner">
                <div class="card-title">Devices</div>
                <div class="chart-container"><canvas id="deviceChart"></canvas></div>
            </div>
            <div class="card-inner">
                <div class="card-title">Browsers</div>
                <div class="chart-container"><canvas id="browserChart"></canvas></div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/jsvectormap/dist/css/jsvectormap.min.css" rel="stylesheet"/>
        <script src="https://cdn.jsdelivr.net/npm/jsvectormap"></script>
        <script src="https://cdn.jsdelivr.net/npm/jsvectormap/dist/maps/world.js"></script>
        <script>
            const dates = @json($dates);
            const dailyVisitors = @json($dailyVisitors);
            const newUsers = {{ $newUsers }};
            const returningUsers = {{ $returningUsers }};
            const countries = @json($countries);
            const deviceBreakdown = @json($deviceBreakdown);
            const browserBreakdown = @json($browserBreakdown);
            const dailyDownloads = @json($dailyDownloads);

            const brandColor = getComputedStyle(document.documentElement).getPropertyValue('--primary') || '#4f46e5';

            new Chart(document.getElementById('trafficChart'), {
                type: 'line',
                data: {
                    labels: dates,
                    datasets: [{
                        label: 'Visitors',
                        data: dailyVisitors,
                        borderColor: brandColor.trim(),
                        backgroundColor: 'rgba(79,70,229,0.15)',
                        fill: true,
                        tension: 0.35,
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true } }
                }
            });

            new Chart(document.getElementById('downloadsChart'), {
                type: 'line',
                data: {
                    labels: dates,
                    datasets: [{
                        label: 'Downloads',
                        data: dailyDownloads,
                        borderColor: '#059669',
                        backgroundColor: 'rgba(5,150,105,0.15)',
                        fill: true,
                        tension: 0.35,
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true } }
                }
            });

            new Chart(document.getElementById('newReturningChart'), {
                type: 'doughnut',
                data: {
                    labels: ['New', 'Returning'],
                    datasets: [{
                        data: [newUsers, returningUsers],
                        backgroundColor: ['#10b981', '#f59e0b'],
                        hoverOffset: 8,
                    }]
                },
                options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
            });

            new Chart(document.getElementById('deviceChart'), {
                type: 'pie',
                data: {
                    labels: Object.keys(deviceBreakdown),
                    datasets: [{
                        data: Object.values(deviceBreakdown),
                        backgroundColor: ['#6366f1', '#22c55e', '#f97316']
                    }]
                },
                options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
            });

            new Chart(document.getElementById('browserChart'), {
                type: 'bar',
                data: {
                    labels: Object.keys(browserBreakdown),
                    datasets: [{
                        label: 'Usage %',
                        data: Object.values(browserBreakdown),
                        backgroundColor: '#60a5fa'
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true, max: 100 } }
                }
            });

            const mapData = countries.reduce((acc, c) => { acc[c.code] = c.visitors; return acc; }, {});
            const map = new jsVectorMap({
                selector: '#worldMap',
                map: 'world',
                zoomOnScroll: false,
                regionStyle: { initial: { fill: '#e5e7eb' }, hovered: { fill: '#93c5fd' } },
                series: { regions: [{ scale: ['#dbeafe', '#1d4ed8'], values: mapData }] }
            });
            async function pollLive() {
                try {
                    const res = await fetch('{{ route('admin.analytics.live') }}');
                    if (!res.ok) return;
                    const data = await res.json();
                    document.getElementById('liveUsers').textContent = data.live_users ?? '0';
                    document.getElementById('downloadsToday').textContent = data.downloads_today ?? '0';
                } catch (e) {}
            }
            pollLive();
            setInterval(pollLive, 5000);
        </script>
    @endpush

    @push('styles')
        <style>
            .grid { display: grid; gap: 1.25rem; }
            .grid-2 { grid-template-columns: repeat(1, minmax(0, 1fr)); }
            .grid-3 { grid-template-columns: repeat(1, minmax(0, 1fr)); }
            .grid-4 { grid-template-columns: repeat(1, minmax(0, 1fr)); }
            @media (min-width: 900px) { .grid-2 { grid-template-columns: repeat(2, 1fr); } }
            @media (min-width: 1200px) { .grid-3 { grid-template-columns: repeat(3, 1fr); } .grid-4 { grid-template-columns: repeat(4, 1fr); } }
            .stat-card, .card-inner { background: linear-gradient(180deg,#ffffff 0%,#f8fafc 100%); border-radius: 14px; box-shadow: 0 10px 24px rgba(15,23,42,0.06); padding: 18px; transition: transform .18s ease, box-shadow .18s ease; border: 1px solid #eef2f7; }
            .stat-card:hover, .card-inner:hover { transform: translateY(-2px); box-shadow: 0 12px 28px rgba(0,0,0,0.1); }
            .stat-title { color: #6b7280; font-size: 12px; text-transform: uppercase; letter-spacing: .08em; margin-bottom: 6px; }
            .stat-value { font-size: 28px; font-weight: 800; color: #0f172a; }
            .card-title { font-weight: 700; margin-bottom: 10px; color: #0b1324; }
            .chart-container { position: relative; height: 260px; }
            .map { height: 320px; border-radius: 10px; overflow: hidden; }
            .top-countries { margin-top: 12px; }
            .top-countries .top-title { font-weight: 600; margin-bottom: 6px; }
            .top-countries ul { list-style: none; padding: 0; margin: 0; }
            .top-countries li { display: flex; justify-content: space-between; padding: 6px 8px; border-bottom: 1px solid #f1f5f9; }
        </style>
    @endpush
</x-admin.layout>


