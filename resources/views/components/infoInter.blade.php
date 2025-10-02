<div class="transaction-alert">
    <div class="transaction-alert__content">
        <div class="transaction-alert__icon">
            <svg fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                    clip-rule="evenodd" />
            </svg>
        </div>
        <span class="transaction-alert__text">INFO TRANSACTION</span>
    </div>
    <div class="transaction-alert__date">
        <span class="transaction-alert__date-day">
            {{ $item->created_at ? $item->created_at->format('d/m/Y') : '—' }}
        </span>
        <span class="transaction-alert__date-time">
            {{ $item->created_at ? $item->created_at->format('H:i') : '—' }}
        </span>
    </div>
  
</div>
  <div class="px-3 py-2 text-dark bg-blue-900 dark:bg-darkmode-400 dark:text-slate-300 rounded font-medium mb-2">
        <div class="fc-event-title-container" style="color: white;">
            <div class="fc-event-title fc-sticky">
                {{ $item->country->name ?? null }}
            </div>
        </div>
    </div>
