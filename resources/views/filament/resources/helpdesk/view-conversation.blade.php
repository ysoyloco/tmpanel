<x-filament::page>
    <div class="space-y-4">
        <div class="p-4 bg-white rounded-lg shadow">
            <h2 class="text-lg font-medium">{{ $record->subject }}</h2>
            <p class="text-sm text-gray-500">ID sprawy: {{ $record->ticket_id }}</p>
        </div>

        <div class="space-y-4">
            @foreach($record->conversation as $message)
                <div class="p-4 bg-white rounded-lg shadow">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <p class="text-sm text-gray-900">{{ $message['content'] }}</p>
                        </div>
                        <span class="text-xs text-gray-500">{{ $message['date'] }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-filament::page>

HELPDESK_EMAIL=biuro@telemedia.pl
IMAP_HOST=your-imap-host
IMAP_PORT=993
IMAP_USERNAME=biuro@telemedia.pl
IMAP_PASSWORD=your-password