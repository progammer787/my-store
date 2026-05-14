@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-6xl">

    {{-- Header --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Global Settings</h1>
            <p class="text-sm text-gray-500 mt-1">Manage system configurations and shop details.</p>
        </div>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
    <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">
        ✅ {{ session('success') }}
    </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
        <form action="{{ route('admin.settings.save') }}" method="POST">
            @csrf

            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-gray-500 text-sm font-semibold">
                        <th class="py-4 px-6 w-1/3">Configuration Key</th>
                        <th class="py-4 px-6 w-2/3">Value</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-600 text-sm">
                    @forelse($settings as $setting)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="py-4 px-6 font-mono text-xs font-semibold text-gray-700">
                            {{ strtoupper(str_replace('_', ' ', $setting->key)) }}
                            <span class="block text-[10px] text-gray-400 font-normal lowercase">
                                {{ $setting->key }}
                            </span>
                        </td>
                        <td class="py-4 px-6">
                            <input type="text"
                                   name="settings[{{ $setting->key }}]"
                                   value="{{ $setting->value }}"
                                   class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-1.5 text-sm text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all">
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="2" class="py-8 text-center text-gray-400">
                            No settings configured yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            @if($settings->count() > 0)
            <div class="p-4 bg-gray-50 border-t border-gray-100 flex justify-end">
                <button type="submit"
                        class="bg-emerald-600 hover:bg-emerald-700 text-white font-medium text-sm px-5 py-2 rounded-lg shadow-sm transition-colors">
                    💾 Save All Changes
                </button>
            </div>
            @endif

        </form>
    </div>
</div>
@endsection