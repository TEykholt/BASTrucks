@include("incl/header")
@isset($userData)
    <h1 class="mt-3">Welkom {{$userData[0]['name']}}</h1>
    <p>Select the KPI's you want to see on the dashboard.</p>
    @isset($kpiData)
    <form method="POST" action="/userPreference/{{$userData[0]['name']}}">
        @csrf
        <div class="form-group">
            <select name="kpi[]" class="form-control" multiple>
                    @foreach($kpiData as $kpi)
                        <option selected>{{$kpi['kpi']}}</option>
                    @endforeach
            </select>
        </div>
        <input type="submit" class="btn btn-primary"/>
    </form>
    @else
        <p>KPI data not loaded correctly</p>
    @endisset
@endisset
