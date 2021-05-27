@include("incl/header")
@isset($userData)
    <h1 class="mt-3">Welcome {{$userData[0]['name']}}</h1>
    <p>Select the KPI's you want to see on the dashboard.</p>
    @isset($kpiData)
            <form method="POST" action="/userPreference/{{$userData[0]['username']}}">
                @foreach($kpiData as $kpi)

                    <p class="font-weight-bold">{{$kpi['kpi']}}</p>
                    @csrf
                        <div class="form-check">
                            <input class="form-check-input" type="radio"  id="yes{{$kpi['id']}}" name="kpi{{$kpi['id']}}" value="yes">
                            <label class="form-check-label" for="yes">Yes</label><br>
                            <input class="form-check-input" type="radio" id="no{{$kpi['id']}}" name="kpi{{$kpi['id']}}" value="no">
                            <label class="form-check-label" for="no">No</label><br>
                        </div>
                    <input type="text" value="{{$kpi['kpi']}}" hidden />

                    @foreach($personSetting as $setting)
                        @if($setting['preferd_kpi'] == $kpi['id'])
                            <script>
                                $( "#yes{{$kpi['id']}}").prop('checked', true);
                            </script>
                            @break;
                        @else
                            <script>
                                $( "#no{{$kpi['id']}}").prop('checked', true);
                            </script>
                        @endif
                    @endforeach
                @endforeach
                <input type="submit" class="btn btn-primary"/>
            </form>

    @else
        <p>KPI data not loaded correctly</p>
    @endisset
@endisset
