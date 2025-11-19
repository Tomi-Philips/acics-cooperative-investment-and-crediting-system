@extends('layouts.admin')

@section('content')
<div class="max-w-4xl p-6 mx-auto overflow-hidden bg-white rounded-lg shadow-lg">
    <div class="flex items-start justify-between mb-6">
        <div class="flex items-center space-x-3">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="text-green-600 w-7 h-7">
                <path fill-rule="evenodd" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.125a7.5 7.5 0 0114.998 0 .75.75 0 01-.017.138A9 9 0 0110.999 21.75c-2.679 0-5.023-.737-6.32-1.637a.75.75 0 01-.018-.138z" clip-rule="evenodd" />
            </svg>
            <h2 class="text-2xl font-bold tracking-tight text-gray-900">Reply to Ticket</h2>
        </div>
        <a href="{{ route('admin.tickets.open') }}" class="inline-flex items-center text-sm font-medium text-green-600 hover:text-green-900">
            <svg class="mr-1.5 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18" />
            </svg>
            Back to Tickets
        </a>
    </div>

    <div class="p-4 mb-6 border border-green-100 rounded-lg bg-green-50">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <h3 class="flex items-center mb-2 font-semibold text-gray-900">
                    <span class="mr-2">Ticket Details</span>
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">{{ $ticket->ticket_number }}</span>
                </h3>
                <p class="mb-1 text-sm text-gray-700"><span class="font-medium">Subject:</span> {{ $ticket->subject }}</p>
                <p class="mb-1 text-sm text-gray-700"><span class="font-medium">Category:</span> {{ $ticket->category_name }}</p>
                <p class="text-sm text-gray-700"><span class="font-medium">User:</span> {{ $ticket->user->name }} (<a href="mailto:{{ $ticket->user->email }}" class="text-green-600 hover:underline">{{ $ticket->user->email }}</a>)</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <div class="flex items-center text-sm">
                    <span class="font-medium mr-1.5">Status:</span>
                    @if($ticket->status == 'open')
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                        <span class="w-2 h-2 mr-1 bg-yellow-500 rounded-full"></span> Open
                    </span>
                    @elseif($ticket->status == 'closed')
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                        <span class="w-2 h-2 mr-1 bg-gray-500 rounded-full"></span> Closed
                    </span>
                    @elseif($ticket->status == 'pending')
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        <span class="w-2 h-2 mr-1 bg-blue-500 rounded-full"></span> Pending
                    </span>
                    @endif
                </div>
                <div class="flex items-center text-sm text-gray-700">
                    <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Created {{ $ticket->created_at->diffForHumans() }}
                </div>
            </div>
        </div>
    </div>

    <div class="mb-8">
        <h3 class="flex items-center mb-3 font-semibold text-gray-900">
            <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
            </svg>
            Conversation History
        </h3>
        <div class="space-y-4">
            <div class="flex space-x-3">
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-center w-8 h-8 font-medium text-gray-600 bg-gray-300 rounded-full">
                        {{ substr($ticket->user->name, 0, 1) }}
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="p-4 bg-gray-100 rounded-lg">
                        <div class="flex items-baseline justify-between mb-1">
                            <p class="text-sm font-medium text-gray-900">{{ $ticket->user->name }}</p>
                            <p class="text-xs text-gray-500">{{ $ticket->created_at->diffForHumans() }}</p>
                        </div>
                        <p class="text-sm text-gray-700">{{ $ticket->message }}</p>
                        @if($ticket->attachment)
                        <div class="pt-2 mt-2 border-t border-gray-200">
                            <a href="{{ Storage::url($ticket->attachment) }}" target="_blank" class="inline-flex items-center text-xs text-blue-600 hover:text-blue-800">
                                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                </svg>
                                Attachment
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @foreach($ticket->replies as $reply)
            <div class="flex space-x-3">
                <div class="flex-shrink-0">
                    @if($reply->is_admin)
                    <div class="flex items-center justify-center w-8 h-8 font-medium text-green-800 bg-green-100 rounded-full">
                        A
                    </div>
                    @else
                    <div class="flex items-center justify-center w-8 h-8 font-medium text-gray-600 bg-gray-300 rounded-full">
                        {{ substr($reply->user->name, 0, 1) }}
                    </div>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <div class="{{ $reply->is_admin ? 'bg-green-50 border border-green-100' : 'bg-gray-100' }} rounded-lg p-4">
                        <div class="flex items-baseline justify-between mb-1">
                            <p class="text-sm font-medium {{ $reply->is_admin ? 'text-green-700' : 'text-gray-900' }}">
                                {{ $reply->is_admin ? 'Admin' : $reply->user->name }}
                            </p>
                            <p class="text-xs {{ $reply->is_admin ? 'text-green-500' : 'text-gray-500' }}">{{ $reply->created_at->diffForHumans() }}</p>
                        </div>
                        <p class="text-sm text-gray-700">{{ $reply->message }}</p>
                        @if($reply->attachment)
                        <div class="pt-2 mt-2 border-t border-gray-200">
                            <a href="{{ Storage::url($reply->attachment) }}" target="_blank" class="inline-flex items-center text-xs text-blue-600 hover:text-blue-800">
                                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                </svg>
                                Attachment
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <div>
        <h3 class="flex items-center mb-3 font-semibold text-gray-900">
            <svg class="w-5 h-5 mr-2 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
            Compose Reply
        </h3>
        <form action="{{ route('admin.tickets.store_reply', $ticket->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label for="message" class="sr-only">Reply Message</label>
                <textarea id="message" name="message" rows="6" class="block w-full border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm" placeholder="Type your response here..." required></textarea>
            </div>
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <label class="inline-flex items-center text-sm text-gray-600 cursor-pointer hover:text-gray-900">
                        <svg class="mr-1.5 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                        </svg>
                        <span>Attach File</span>
                        <input type="file" name="attachment" class="hidden">
                    </label>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.tickets.open') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        Cancel
                    </a>
                    <button type="submit" name="close_ticket" value="1" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <svg class="w-5 h-5 mr-2 -ml-1 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Reply & Close
                    </button>
                    <button type="submit" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <svg class="w-5 h-5 mr-2 -ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        Send Reply
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection