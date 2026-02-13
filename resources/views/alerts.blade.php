@extends('layouts.app')

@section('title', 'Alerts')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-2xl font-bold mb-6">Alerts</h2>

    <!-- Filter -->
    <div class="mb-6 flex gap-4">
        <select v-model="severityFilter" class="px-4 py-2 border rounded">
            <option value="">All Severities</option>
            <option value="critical">🔴 Critical</option>
            <option value="high">🟠 High</option>
            <option value="medium">🟡 Medium</option>
            <option value="low">🔵 Low</option>
        </select>
        <input v-model="alertSearch" type="text" placeholder="Search alerts..." class="flex-1 px-4 py-2 border rounded">
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-4 gap-4 mb-6">
        <div class="bg-red-50 p-4 rounded text-center">
            <p class="text-2xl font-bold text-red-600">@{{ alerts.filter(a => a.severity === 'critical').length }}</p>
            <p class="text-gray-600 text-sm">Critical</p>
        </div>
        <div class="bg-orange-50 p-4 rounded text-center">
            <p class="text-2xl font-bold text-orange-600">@{{ alerts.filter(a => a.severity === 'high').length }}</p>
            <p class="text-gray-600 text-sm">High</p>
        </div>
        <div class="bg-yellow-50 p-4 rounded text-center">
            <p class="text-2xl font-bold text-yellow-600">@{{ alerts.filter(a => a.severity === 'medium').length }}</p>
            <p class="text-gray-600 text-sm">Medium</p>
        </div>
        <div class="bg-blue-50 p-4 rounded text-center">
            <p class="text-2xl font-bold text-blue-600">@{{ alerts.filter(a => a.severity === 'low').length }}</p>
            <p class="text-gray-600 text-sm">Low</p>
        </div>
    </div>

    <!-- Alerts List -->
    <div class="space-y-4">
        <div v-for="alert in filteredAlerts" :key="alert.id"
             :class="{
                 'bg-red-50 border-l-4 border-red-500': alert.severity === 'critical',
                 'bg-orange-50 border-l-4 border-orange-500': alert.severity === 'high',
                 'bg-yellow-50 border-l-4 border-yellow-500': alert.severity === 'medium',
                 'bg-blue-50 border-l-4 border-blue-500': alert.severity === 'low'
             }"
             class="p-4 rounded">
            <div class="flex justify-between items-start">
                <div class="flex-1">
                    <h3 class="font-bold text-lg">@{{ alert.product.name }}</h3>
                    <p class="text-gray-700 mt-1">@{{ alert.message }}</p>
                    <p class="text-gray-500 text-sm mt-2">
                        Created: @{{ new Date(alert.created_at).toLocaleString() }}
                    </p>
                </div>
                <div class="text-right">
                    <span :class="{
                        'bg-red-100 text-red-800': alert.severity === 'critical',
                        'bg-orange-100 text-orange-800': alert.severity === 'high',
                        'bg-yellow-100 text-yellow-800': alert.severity === 'medium',
                        'bg-blue-100 text-blue-800': alert.severity === 'low'
                    }" class="px-3 py-1 rounded text-sm font-bold">
                        @{{ alert.severity.toUpperCase() }}
                    </span>
                    <button @click="dismissAlert(alert.id)" class="block mt-2 bg-gray-500 text-white px-3 py-1 rounded text-sm hover:bg-gray-600">Dismiss</button>
                </div>
            </div>
        </div>
    </div>

    <div v-if="filteredAlerts.length === 0" class="text-center py-8 text-gray-500">
        No alerts found.
    </div>
</div>
@endsection
