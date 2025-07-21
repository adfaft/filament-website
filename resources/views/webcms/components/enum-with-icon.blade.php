<div class="flex rounded-md relative align-middle">
    <div class="flex mt-1">
        <div class="px-2 py-2">
            <div class="h-3 w-4">
                {{-- <img src="{{ url('/storage/'.$image.'') }}" alt="{{ $name }}" role="img" class="h-full w-full rounded-full overflow-hidden shadow object-cover" /> --}}
                <x-filament::icon icon="{{ $item->getIcon() }}" 
                    class="{{ $item instanceof \Filament\Support\Contracts\HasColor ? 'text-custom-500' : '' }}"
                    style='{{ $item instanceof \Filament\Support\Contracts\HasColor ? "--c-500:var(--{$item->getColor()}-500)" : "" }}' />
            </div>
        </div>
 
        <div class="flex flex-col justify-center">
            <p class="text-sm font-bold pb-1">{{ $item->name }}</p>
        </div>
    </div>
</div>