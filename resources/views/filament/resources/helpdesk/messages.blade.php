<div class="space-y-4">
    <div class="flex justify-between items-center">
        <h2 class="text-lg font-bold">Historia konwersacji</h2>
    </div>

    <div class="space-y-4">
        @foreach($messages as $message)
            <div class="p-4 bg-gray-800 rounded-lg shadow {{ $message->direction === 'incoming' ? 'ml-4' : 'mr-4' }}">
                <div class="flex justify-between items-start">
                    <div class="text-sm text-gray-300">
                        {{ $message->email }}
                    </div>
                    <div class="text-sm text-gray-400">
                        {{ $message->created_at->format('d.m.Y H:i') }}
                    </div>
                </div>
                <div class="mt-2 text-gray-100">
                    {{ $message->content }}
                </div>
            </div>
        @endforeach
    </div>
</div>
