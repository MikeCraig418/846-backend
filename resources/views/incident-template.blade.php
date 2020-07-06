### {{ $title }} | {{ $date }}

{{ $description }}

tags: {{ $tags }}

id: {{ $id }}

**Links**

@foreach ($links as $link)
* {{ @trim($link)  }}
@endforeach


