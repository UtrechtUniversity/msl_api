{{-- 

--}}


<div class="
    group
    h-fit
    max-w-60
    relative
    hover:overflow-visible
">
    <div class="
        word-card
        truncate
    ">
    {{  substr($word, 0, 40)  }}</div>
    <div class="
        word-card
        hover-neutral
        hidden
        group-hover:block
        w-fit
        group-hover:wrap-anywhere
        group-hover:absolute
        group-hover:top-0
        group-hover:left-0
        group-hover:z-10
    ">
        {{ $word }}
    </div>
</div>