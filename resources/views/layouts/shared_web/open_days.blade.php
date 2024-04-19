<div class="scrolling-text mt-6">

    <span class="badge bg-success text-dark style_span text-white">Le service ROBUS est disponible ce
        {{ getAppCloseWeb()->day_of_week ?? null }} de

    {{ \Carbon\Carbon::createFromFormat('H:i:s', getAppCloseWeb()->time_start ?? null)->format('H:i') }}

    a
    {{ \Carbon\Carbon::createFromFormat('H:i:s', getAppCloseWeb()->time_end ?? null)->format('H:i') }}
    </span>
</div>
