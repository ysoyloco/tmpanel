<div class="space-y-4 p-4">
    @foreach($messages as $message)
        <div @class([
            'p-4 rounded-lg shadow-sm',
            'ml-12 bg-blue-100 dark:bg-blue-950/50' => $message->direction === 'incoming',
            'mr-12 bg-emerald-100 dark:bg-emerald-950/50' => $message->direction === 'outgoing',
        ])>
            <div class="flex justify-between items-start">
                <div @class([
                    'font-medium',
                    'text-blue-700 dark:text-blue-400' => $message->direction === 'incoming',
                    'text-emerald-700 dark:text-emerald-400' => $message->direction === 'outgoing',
                ])>
                    {{ $message->email }}
                </div>
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    {{ $message->created_at->format('d.m.Y H:i') }}
                </div>
            </div>
            <div class="mt-2">
                {{ $message->content }}
            </div>
        </div>
    @endforeach
</div>
