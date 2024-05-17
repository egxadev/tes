<div class="section-header">
    <h1>{{ end($breadcrumb)->name }}</h1>
    <div class="section-header-breadcrumb">
        @foreach ($breadcrumb as $item)
            @if (!$loop->last)
                <div class="breadcrumb-item "><a href="{{ $item->link }}">{{ $item->name }}</a></div>
            @else
                <div class="breadcrumb-item">{{ $item->name }}</div>
            @endif
        @endforeach
    </div>
</div>
