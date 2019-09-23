@if ($errors->{$bag ?? 'default'}->any())
    <div class="field text-sm text-red-700 mt-6">
        @foreach($errors->{$bag ?? 'default'}->all() as $error)
            <p>{{$error}}</p>
        @endforeach
    </div>
@endif