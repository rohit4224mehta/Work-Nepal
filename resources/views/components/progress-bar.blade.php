<div class="relative pt-1">
    <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-gray-200 dark:bg-gray-700">
        <div style="width: {{ $percentage }}%"
             class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center 
                    {{ $color === 'red' ? 'bg-red-600' : 'bg-blue-600' }}">
        </div>
    </div>
    <div class="text-right">
        <span class="text-xs font-semibold inline-block text-gray-600 dark:text-gray-400">
            {{ $percentage }}%
        </span>
    </div>
</div>