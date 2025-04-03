
@extends('layouts.admin')

@section('title', 'Dashboard')

@section('page-title', 'Panel de Control')

@section('content')

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="bg-blue-100 rounded-full p-3 text-blue-600">
                    <i class="fas fa-users"></i>
                </div>
                <div class="ml-4">
                    <h4 class="text-gray-500 text-sm">Usuarios</h4>
                    <div class="font-semibold text-xl">{{ number_format($stats['total_users']) }}</div>
                    <div class="text-xs text-gray-500">{{ number_format($stats['active_users']) }} activos esta semana</div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="bg-green-100 rounded-full p-3 text-green-600">
                    <i class="fas fa-link"></i>
                </div>
                <div class="ml-4">
                    <h4 class="text-gray-500 text-sm">Enlaces</h4>
                    <div class="font-semibold text-xl">{{ number_format($stats['total_links']) }}</div>
                    <div class="text-xs text-gray-500">{{ number_format($stats['published_links']) }} publicados</div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="bg-yellow-100 rounded-full p-3 text-yellow-600">
                    <i class="fas fa-comment"></i>
                </div>
                <div class="ml-4">
                    <h4 class="text-gray-500 text-sm">Comentarios</h4>
                    <div class="font-semibold text-xl">{{ number_format($stats['total_comments']) }}</div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="bg-purple-100 rounded-full p-3 text-purple-600">
                    <i class="fas fa-tag"></i>
                </div>
                <div class="ml-4">
                    <h4 class="text-gray-500 text-sm">Categorías</h4>
                    <div class="font-semibold text-xl">{{ number_format($stats['total_tags']) }}</div>
                </div>
            </div>
        </div>
    </div>


    <div class="bg-white rounded-lg shadow mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="font-medium">Actividad de la última semana</h3>
        </div>
        <div class="p-6">
            <canvas id="activityChart" height="100"></canvas>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <div class="lg:col-span-8">

            <div class="bg-white rounded-lg shadow mb-6">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="font-medium">Enlaces recientes</h3>
                    <a href="{{ route('admin.links.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        Ver todos
                    </a>
                </div>
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                            <tr>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Título</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuario</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                            </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($recentLinks as $link)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900 truncate max-w-xs">
                                            <a href="{{ route('admin.links.edit', $link->id) }}" class="hover:text-blue-600">
                                                {{ $link->title }}
                                            </a>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $link->user->username }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($link->status === 'published')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    Publicado
                                                </span>
                                        @elseif($link->status === 'pending')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    Pendiente
                                                </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    Descartado
                                                </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $link->created_at->format('d/m/Y H:i') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                        No hay enlaces recientes
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


            <div class="bg-white rounded-lg shadow mb-6">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="font-medium">Comentarios recientes</h3>
                    <a href="{{ route('admin.comments.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        Ver todos
                    </a>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @forelse($recentComments as $comment)
                            <div class="border-b border-gray-200 pb-4 last:border-0 last:pb-0">
                                <div class="flex justify-between">
                                    <div class="text-sm font-medium text-gray-900">{{ $comment->user->username }}</div>
                                    <div class="text-sm text-gray-500">{{ $comment->created_at->format('d/m/Y H:i') }}</div>
                                </div>
                                <div class="mt-1 text-sm text-gray-600 line-clamp-2">{{ $comment->content }}</div>
                                <div class="mt-1 text-xs text-gray-500">
                                    En: <a href="{{ route('admin.links.edit', $comment->link_id) }}" class="text-blue-600 hover:text-blue-800">{{ $comment->link->title }}</a>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-gray-500 py-4">
                                No hay comentarios recientes
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-4">

            <div class="bg-white rounded-lg shadow mb-6">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="font-medium">Nuevos usuarios</h3>
                    <a href="{{ route('admin.users.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        Ver todos
                    </a>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @forelse($newUsers as $user)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                                        {{ strtoupper(substr($user->username, 0, 1)) }}
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">{{ $user->username }}</div>
                                        <div class="text-xs text-gray-500">{{ $user->created_at->format('d/m/Y') }}</div>
                                    </div>
                                </div>
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                            </div>
                        @empty
                            <div class="text-center text-gray-500 py-4">
                                No hay usuarios nuevos
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>


            <div class="bg-white rounded-lg shadow mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="font-medium">Categorías populares</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-2">
                        @foreach($popularTags as $tag)
                            <div class="flex items-center justify-between">
                                <div class="text-sm font-medium text-gray-900">{{ $tag->name }}</div>
                                <div class="text-sm text-gray-500">{{ $tag->links_count }} enlaces</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>


            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="font-medium">Acciones rápidas</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        <a href="{{ route('admin.links.create') }}" class="w-full flex justify-between items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-md text-left">
                            <span class="text-sm font-medium text-gray-700">Crear nuevo enlace</span>
                            <i class="fas fa-plus text-gray-500"></i>
                        </a>
                        <a href="{{ route('admin.tags.create') }}" class="w-full flex justify-between items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-md text-left">
                            <span class="text-sm font-medium text-gray-700">Crear nueva categoría</span>
                            <i class="fas fa-tag text-gray-500"></i>
                        </a>
                        <a href="{{ route('admin.imports.index') }}" class="w-full flex justify-between items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-md text-left">
                            <span class="text-sm font-medium text-gray-700">Importar contenido</span>
                            <i class="fas fa-file-import text-gray-500"></i>
                        </a>
                        <form action="#" method="POST" class="w-full"><!-- route('admin.links.promote') -->
                            @csrf
                            <button type="submit" class="w-full flex justify-between items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-md text-left">
                                <span class="text-sm font-medium text-gray-700">Promover enlaces pendientes</span>
                                <i class="fas fa-arrow-circle-up text-gray-500"></i>
                            </button>
                        </form>
                        <a href="{{ route('admin.activitypub.index') }}" class="w-full flex justify-between items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-md text-left">
                            <span class="text-sm font-medium text-gray-700">Configurar ActivityPub</span>
                            <i class="fas fa-globe text-gray-500"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Datos para el gráfico de actividad
            const activityData = @json($lastWeekStats);

            // Configurar el gráfico
            const ctx = document.getElementById('activityChart').getContext('2d');
            const chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: activityData.map(item => item.date),
                    datasets: [
                        {
                            label: 'Usuarios',
                            data: activityData.map(item => item.users),
                            borderColor: 'rgb(59, 130, 246)',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            tension: 0.3,
                            fill: true
                        },
                        {
                            label: 'Enlaces',
                            data: activityData.map(item => item.links),
                            borderColor: 'rgb(16, 185, 129)',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            tension: 0.3,
                            fill: true
                        },
                        {
                            label: 'Comentarios',
                            data: activityData.map(item => item.comments),
                            borderColor: 'rgb(245, 158, 11)',
                            backgroundColor: 'rgba(245, 158, 11, 0.1)',
                            tension: 0.3,
                            fill: true
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    },
                    interaction: {
                        mode: 'nearest',
                        axis: 'x',
                        intersect: false
                    }
                }
            });
        });
    </script>
@endpush
