<div class="overflow-hidden rounded-lg border border-gray-200 bg-white">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            {{ $head ?? '' }}
        </thead>
        <tbody class="bg-white divide-y divide-gray-100">
            {{ $slot }}
        </tbody>
    </table>
</div>
