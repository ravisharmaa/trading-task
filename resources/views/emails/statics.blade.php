<x-mail::message>
# From {{$start_date}} to {{$end_date}}
@component('mail::table')
|Date | Open          | High          | Close     | Low     | Volume|
| ------------- | ------------- |:-------------:| --------:| --------:| --------:|
@foreach($prices as $data )
|{{$data['date'] }}| {{$data['open']}}      | {{$data['high']}}      | {{$data['close']}}      | {{$data['low']}} | {{$data['volume']}}
@endforeach

@endcomponent
Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
