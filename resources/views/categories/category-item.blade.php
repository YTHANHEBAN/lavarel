<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" id="category{{ $category->id }}" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        {{ $category->name }}
    </a>
    @if ($category->children->isNotEmpty())
        <ul class="dropdown-menu">
            @foreach ($category->children as $child)
                @include('categories.category-item', ['category' => $child])
            @endforeach
        </ul>
    @endif
</li>
