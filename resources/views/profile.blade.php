@include("incl/header")
@isset($userData)
    <h1 class="mt-3">Welcome {{ Auth::user()->username }}</h1>
    <p>Select the KPI's you want to see on the dashboard.</p>
    <div class="row">
        <div class="col-lg-5">
            @isset($kpiData)
                <form method="POST" action="/userPreference">
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
                    <input type="submit" class="btn btn-primary" value="Submit"/>
                </form>

            @else
                <p>KPI data not loaded correctly</p>
            @endisset
        </div>
        <div class="col-lg-6">
            <form method="POST" action="/userEdit">
                @csrf
                <div class="form-group">
                    <label for="firstname">Email</label>
                    <input required type="text" class="form-control" id="email" name="email" placeholder="{{$userData[0]['email']}}">
                </div>
                <div class="form-group">
                    <label for="firstname">First name</label>
                    <input required type="text" class="form-control" id="firstname" name="firstname" placeholder="{{$userData[0]['name']}}">
                </div>
                <div class="form-group">
                    <label for="lastname">Last name</label>
                    <input required type="text" class="form-control" id="lastname" name="lastname" placeholder="{{$userData[0]['name']}}">
                </div>
                <div class="form-group">
                    <label for="tell">Telephone  number</label>
                    <input required type="text" class="form-control" id="tell" name="tell" placeholder="{{$userData[0]['tell']}}">
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1">New password</label>
                    <input required type="password" class="form-control" id="exampleInputPassword1" name="password" >
                </div>
                <input required type="submit" class="btn btn-primary" value="Submit"/>
            </form>
        </div>
    </div>
@endisset
