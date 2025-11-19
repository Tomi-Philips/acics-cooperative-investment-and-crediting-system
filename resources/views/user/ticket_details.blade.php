@extends('layouts.user')

@section('title', 'Support Ticket Details')

@section('content')
    <div class="container px-4 py-6 mx-auto space-y-6">
        <div class="flex items-center space-x-2 text-sm text-gray-500">
            <a href="{{ route('user.dashboard') }}" class="hover:text-green-600">Dashboard</a>
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
            <a href="{{ route('user.support') }}" class="hover:text-green-600">Support</a>
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
            <span class="text-gray-700">Ticket #{{ $ticket->ticket_number }}</span>
        </div>

        <div class="overflow-hidden bg-white shadow-sm rounded-xl">
            <div class="p-5 border-b border-gray-100">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800">{{ $ticket->subject }}</h2>
                        <div class="flex items-center gap-3 mt-1 text-sm text-gray-500">
                            <span>{{ $ticket->ticket_number }}</span>
                            <span>•</span>
                            <span>{{ $ticket->created_at->format('M d, Y h:i A') }}</span>
                            <span>•</span>
                            <span class="capitalize">{{ $ticket->category }}</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        @if($ticket->status === 'open')
                            <span class="px-3 py-1 text-sm font-medium text-yellow-800 bg-yellow-100 rounded-full">Open</span>
                            <form action="{{ route('user.support.close', $ticket->id) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="flex items-center gap-1 text-sm text-gray-500 hover:text-red-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Close Ticket
                                </button>
                            </form>
                        @elseif($ticket->status === 'closed')
                            <span class="px-3 py-1 text-sm font-medium text-gray-800 bg-gray-100 rounded-full">Closed</span>
                            <span class="text-sm text-gray-500">{{ $ticket->closed_at->format('M d, Y h:i A') }}</span>
                        @elseif($ticket->status === 'pending')
                            <span class="px-3 py-1 text-sm font-medium text-blue-800 bg-blue-100 rounded-full">Pending</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="overflow-hidden bg-white shadow-sm rounded-xl">
            <div class="p-5 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-800">Conversation</h3>
            </div>
            <div class="divide-y divide-gray-100">
                <div class="p-5">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center w-10 h-10 font-semibold text-gray-600 bg-gray-200 rounded-full">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <h4 class="text-sm font-medium text-gray-900">{{ $user->name }}</h4>
                                <span class="text-xs text-gray-500">{{ $ticket->created_at->format('M d, Y h:i A') }}</span>
                            </div>
                            <div class="mt-2 space-y-2 text-sm text-gray-700">
                                <p>{{ $ticket->message }}</p>
                            </div>
                            @if($ticket->attachment)
                                <div class="mt-3">
                                    <a href="{{ Storage::url($ticket->attachment) }}" target="_blank" class="inline-flex items-center gap-1 text-sm text-green-600 hover:text-green-800">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                        </svg>
                                        Attachment
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                @foreach($replies as $reply)
                    <div class="p-5 {{ $reply->is_admin ? 'bg-green-50' : '' }}">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 rounded-full {{ $reply->is_admin ? 'bg-green-200 text-green-800' : 'bg-gray-200 text-gray-600' }} flex items-center justify-center font-semibold">
                                    {{ strtoupper(substr($reply->user->name, 0, 1)) }}
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <h4 class="text-sm font-medium text-gray-900">
                                        {{ $reply->user->name }}
                                        @if($reply->is_admin)
                                            <span class="ml-2 px-2 py-0.5 rounded text-xs bg-green-100 text-green-800">Staff</span>
                                        @endif
                                    </h4>
                                    <span class="text-xs text-gray-500">{{ $reply->created_at->format('M d, Y h:i A') }}</span>
                                </div>
                                <div class="mt-2 space-y-2 text-sm text-gray-700">
                                    <p>{{ $reply->message }}</p>
                                </div>
                                @if($reply->attachment)
                                    <div class="mt-3">
                                        <a href="{{ Storage::url($reply->attachment) }}" target="_blank" class="inline-flex items-center gap-1 text-sm text-green-600 hover:text-green-800">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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

            @if($ticket->status === 'open')
                <div class="p-5 bg-gray-50">
                    <h4 class="mb-3 text-sm font-medium text-gray-700">Add Reply</h4>

                    @if(session('success'))
                        <div class="p-3 mb-4 text-green-800 bg-green-100 rounded-lg">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="p-3 mb-4 text-red-800 bg-red-100 rounded-lg">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('user.support.reply', $ticket->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div>
                            <textarea name="message" rows="3" class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('message') border-red-500 @enderror" placeholder="Type your reply here..." required>{{ old('message') }}</textarea>
                            @error('message')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="attachment" class="block mb-1 text-sm text-gray-600">Attachment (Optional)</label>
                            <input type="file" id="attachment" name="attachment" class="w-full border border-gray-300 rounded-lg p-2 text-sm @error('attachment') border-red-500 @enderror">
                            <p class="mt-1 text-xs text-gray-500">Accepted file types: PDF, DOC, DOCX, JPG, JPEG, PNG (max 2MB)</p>
                            @error('attachment')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="px-4 py-2 text-sm font-medium text-white transition-colors bg-green-600 rounded-lg hover:bg-green-700">
                                Send Reply
                            </button>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    </div>
@endsection