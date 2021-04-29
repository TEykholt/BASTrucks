<h3>{{$mailinfo['title']}}</h3>
<p>
    {{$mailinfo['body']}}
</p>
<p>
    Kind regards,

    BAS Trucks
</p>
<img src="{{ $message->embed(public_path() . '/img/logo.png') }}" alt="" />
