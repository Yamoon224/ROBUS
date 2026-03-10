@extends('layouts.master')

@section('title', 'Notifications')

@section('content')

<div class="w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <h2 class="text-2xl md:text-3xl font-bold text-gray-800 dark:text-gray-200 flex items-center">
            <span class="mr-2">🔔</span> Notifications
            @if($admin->unreadNotifications->count() > 0)
            <span class="ml-3 inline-flex items-center px-3 py-1 text-sm font-medium rounded-full bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-200">
                {{ $admin->unreadNotifications->count() }} non lue(s)
            </span>
            @endif
        </h2>

        <div class="mt-4 sm:mt-0 flex space-x-3">
            @if ($admin->unreadNotifications->count())
            <a href="{{ route('admin.make.makeReadAll') }}"
                class="btn btn-primary shadow-md mr-2">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 19v-8.93a2 2 0 01.89-1.664l7-4.666a2 2 0 012.22 0l7 4.666A2 2 0 0121 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2M3 19l6.75-4.5M21 19l-6.75-4.5M3 10l6.75 4.5M21 10l-6.75 4.5m0 0l-1.14.76a2 2 0 01-2.22 0l-1.14-.76" />
                </svg>
                Tout marquer comme lu
            </a>
            @endif
        </div>
    </div>

    @if($admin->unreadNotifications->count() > 0)
    <div class="grid grid-cols-12 gap-6 mt-5">
        @foreach ($admin->unreadNotifications as $notif)

        <div class="intro-y col-span-12 md:col-span-6 lg:col-span-4">
            <div class="box">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border border-gray-100 dark:border-gray-700 overflow-hidden group">

                    <div class="px-5 py-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 border-b border-gray-200 dark:border-gray-600">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-yellow-400 to-orange-500 flex items-center justify-center shadow-lg flex-shrink-0">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <h3 class="font-semibold text-gray-800 dark:text-white truncate">
                                        {{ $notif->data['title'] ?? 'Notification' }}
                                    </h3>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $notif->created_at ? $notif->created_at->diffForHumans() : 'Date inconnue' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="p-5">
                        <div class="flex items-start">
                            <div class="flex-1 min-w-0">
                                <p class="text-gray-600 dark:text-gray-300 text-sm leading-relaxed line-clamp-3">
                                    {{ $notif->data['message'] ?? 'Aucun message disponible' }}
                                </p>

                                @if(isset($notif->data['type']) || isset($notif->data['priority']))
                                <div class="mt-3 flex flex-wrap gap-2">
                                    @if(isset($notif->data['type']))
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-200">
                                        {{ $notif->data['type'] }}
                                    </span>
                                    @endif
                                    @if(isset($notif->data['priority']))
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium {{ $notif->data['priority'] == 'haute' ? 'bg-red-50 text-red-700 dark:bg-red-900/30 dark:text-red-200' : 'bg-green-50 text-green-700 dark:bg-green-900/30 dark:text-green-200' }}">
                                        Priorité {{ $notif->data['priority'] }}
                                    </span>
                                    @endif
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="px-5 py-4 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-100 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <a href="{{ route('admin.make.makeReadSingle', $notif->id) }}"
                                class="btn btn-primary mr-1 mb-2  btn-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                Lire la notification
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        @endforeach
    </div>

    @if(method_exists($admin->unreadNotifications, 'links'))
    <div class="mt-8">
        {{ $admin->unreadNotifications->links() }}
    </div>
    @endif
    @else
    <div class="text-center py-16 bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
        <div class="mb-6 flex justify-center">
            <div class="w-24 h-24 bg-gradient-to-br from-blue-100 to-indigo-100 dark:from-gray-700 dark:to-gray-600 rounded-full flex items-center justify-center">
                <svg class="w-12 h-12 text-blue-500 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                </svg>
            </div>
        </div>
        <h3 class="text-xl font-medium text-gray-900 dark:text-white mb-2">Aucune notification</h3>
        <p class="text-gray-500 dark:text-gray-400 max-w-sm mx-auto px-4">
            Vous n'avez pas de nouvelles notifications pour le moment. Revenez plus tard !
        </p>
    </div>
    @endif
</div>
@endsection