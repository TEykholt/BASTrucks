@isset($allKpis)
    <div class="d-flex">
        @foreach($allKpis as $kpi)
            <div class="card m-2" style="width: 18rem;">
                <div class="card-header">
                    {{$kpi}}
                </div>
                <div class="card-body">
                    @switch($kpi)
                        @case("Average response time")
                        <p class="card-text"><i class="fas fa-stopwatch"></i>&nbsp{{$allKpiResults["AVR"]}} min<p/>
                        @break
                        @case("Average total resolution time")
                        <p class="card-text"><i class="far fa-clock"></i>&nbsp{{$allKpiResults["AVTR"]}} min<p/>
                        @break
                        @case("Time service factor")
                        <p class="card-text"><i class="fas fa-hourglass-half"></i>&nbsp{{$allKpiResults["TSF"]}}  min<p/>
                        @break
                        @case("Average user feedbackscore")
                        <p class="card-text"><i class="far fa-star"></i>&nbsp{{$allKpiResults["AUFS"]}}<p/>
                        @break
                        @case("Customer Satisfaction")
                        <p class="card-text"><i class="far fa-thumbs-up"></i>&nbsp{{$allKpiResults["CS"]}}%<p/>
                        @break
                        @case("Status verdeling issues")
                        <p class="card-text"><i class="fas fa-clipboard-list"></i>&nbsp{{$allKpiResults["SVI"]}}<p/>
                        @break
                    @endswitch
                </div>
            </div>
        @endforeach
    </div>
@endisset
