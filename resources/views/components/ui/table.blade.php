<style>
    .ui-table-fixed {
        table-layout: fixed;
        width: 100%;
    }

    .ui-table-fixed th,
    .ui-table-fixed td {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .ui-role-badge {
        padding: 4px 10px;
        border-radius: 9999px;
        font-size: 11px;
        display: inline-block;
    }
</style>

<div class="overflow-x-auto rounded-lg border border-gray-200 bg-white">
    <table class="min-w-full ui-table-fixed divide-y divide-gray-200">
        <thead class="bg-gray-50">
            {{ $head ?? '' }}
        </thead>
        <tbody class="bg-white divide-y divide-gray-100">
            {{ $slot }}
        </tbody>
    </table>
</div>
