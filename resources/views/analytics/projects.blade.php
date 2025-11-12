@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50">
        <!-- 🌐 Header -->
        <header class="bg-blue-900 text-white shadow-lg mb-8">
            <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/4/4e/Nigeria_Coat_of_arms.png" class="w-10 h-10" alt="Logo">
                    <h1 class="text-2xl font-bold">Nigeria Project Analytics Dashboard</h1>
                </div>
                <p class="text-sm text-blue-100">Empowering Transparency through Data</p>
            </div>
        </header>

        <main class="max-w-7xl mx-auto p-6">
            <!-- 🔍 Candidate Filter -->
            <div class="flex flex-wrap justify-center gap-4 mb-10">
                @foreach ($analytics as $index => $candidate)
                    <button class="candidate-toggle flex items-center gap-2 px-4 py-2 rounded-full border border-gray-300 bg-white text-gray-700 hover:bg-blue-50 transition"
                            data-index="{{ $index }}">
                        <img src="{{ $candidate['image'] }}" class="w-8 h-8 rounded-full object-cover">
                        <span>{{ $candidate['candidate'] }}</span>
                    </button>
                @endforeach
                <button class="reset-view px-4 py-2 rounded-full border border-blue-400 bg-blue-50 text-blue-700 hover:bg-blue-100 transition">
                    Show All
                </button>
            </div>

            <!-- 🧠 Candidate Cards -->
            <div class="grid gap-8" id="candidate-cards">
                @foreach ($analytics as $index => $candidate)
                    <div class="candidate-card bg-white rounded-2xl shadow-lg p-6 opacity-100 transition-opacity duration-500"
                         data-index="{{ $index }}">
                        <div class="flex items-center gap-4 mb-4">
                            <img src="{{ $candidate['image'] }}" class="w-20 h-20 rounded-full object-cover border-4 border-blue-100">
                            <div>
                                <h2 class="text-2xl font-semibold text-gray-800">{{ $candidate['candidate'] }}</h2>
                                <p class="text-gray-500">Performance Overview</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                            <div class="bg-blue-100 p-4 rounded-xl text-center">
                                <h4 class="text-sm text-gray-600">Total Shortlisted</h4>
                                <p class="text-2xl font-bold text-blue-700">{{ number_format($candidate['total_shortlisted']) }}</p>
                            </div>
                            <div class="bg-green-100 p-4 rounded-xl text-center">
                                <h4 class="text-sm text-gray-600">Total Disbursed</h4>
                                <p class="text-2xl font-bold text-green-700">{{ number_format($candidate['total_disbursed']) }}</p>
                            </div>
                            <div class="bg-yellow-100 p-4 rounded-xl text-center">
                                <h4 class="text-sm text-gray-600">Success Rate</h4>
                                <p class="text-2xl font-bold text-yellow-700">{{ $candidate['success_rate'] }}%</p>
                            </div>
                        </div>

                        <canvas id="chart-{{ $index }}" height="100" class="mb-6"></canvas>
                    </div>
                @endforeach
            </div>
        </main>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const charts = [];
        @foreach ($analytics as $index => $candidate)
        const ctx{{ $index }} = document.getElementById('chart-{{ $index }}');
        const chart{{ $index }} = new Chart(ctx{{ $index }}, {
            type: 'bar',
            data: {
                labels: @json($candidate['by_arm']->keys()),
                datasets: [
                    { label: 'Shortlisted', data: @json($candidate['by_arm']->pluck('shortlisted')->values()), backgroundColor: 'rgba(54, 162, 235, 0.6)' },
                    { label: 'Disbursed', data: @json($candidate['by_arm']->pluck('disbursed')->values()), backgroundColor: 'rgba(75, 192, 192, 0.6)' }
                ]
            },
            options: { plugins: { legend: { position: 'bottom' } }, responsive: true, scales: { y: { beginAtZero: true } } }
        });
        charts.push(chart{{ $index }});
        @endforeach

        // Multi-select filter logic
        const selected = new Set();
        document.querySelectorAll('.candidate-toggle').forEach(btn => {
            btn.addEventListener('click', () => {
                const index = btn.dataset.index;
                btn.classList.toggle('bg-blue-100');
                btn.classList.toggle('border-blue-400');
                if (selected.has(index)) {
                    selected.delete(index);
                } else {
                    selected.add(index);
                }

                document.querySelectorAll('.candidate-card').forEach(card => {
                    card.style.opacity = selected.size === 0 || selected.has(card.dataset.index) ? '1' : '0';
                    card.style.display = selected.size === 0 || selected.has(card.dataset.index) ? '' : 'none';
                });
            });
        });

        document.querySelector('.reset-view').addEventListener('click', () => {
            selected.clear();
            document.querySelectorAll('.candidate-card').forEach(card => {
                card.style.display = '';
                card.style.opacity = '1';
            });
            document.querySelectorAll('.candidate-toggle').forEach(btn => {
                btn.classList.remove('bg-blue-100', 'border-blue-400');
            });
        });
    </script>
@endsection
