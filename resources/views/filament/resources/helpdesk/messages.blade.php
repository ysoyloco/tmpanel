<div class="space-y-4 p-4">
    @foreach($messages as $message)
        <div class="flex {{ $message->direction === 'incoming' ? 'justify-end' : 'justify-start' }}">
            <div class="max-w-[75%] p-4 rounded-lg shadow-sm {{ $message->direction === 'incoming' ? 'bg-gray-100 dark:bg-gray-800' : 'bg-gray-50 dark:bg-gray-900' }}">
                <div class="flex justify-between items-start gap-4">
                    <div class="font-medium dark:text-gray-200">
                        {{ $message->email }}
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        {{ $message->created_at->format('d.m.Y H:i') }}
                    </div>
                </div>
                <div class="mt-2 dark:text-gray-300">
                    {{ $message->content }}
                </div>
            </div>
        </div>
    @endforeach

    <div class="mt-4">
        {{ $messages->links() }}
    </div>
</div>
