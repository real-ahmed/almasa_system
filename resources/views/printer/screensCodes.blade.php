@foreach($repair->screens as $screen)

        <div style="display: flex; border: #0a0c0d 2px solid; padding: 10px; justify-content: center;height :60vh; ">
            {{$repair->reference_number}}
            <br>
            {{ $screen->code }}
        </div>
        <br>

@endforeach
