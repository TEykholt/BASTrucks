@include("incl/header")
        <h1 class="mt-2">Feedback</h1>
        <form method="POST" id="input_form" action="/Feedback/new" enctype="multipart/form-data">
            @csrf
        <div class="Filter-Item ">
            <label for="pros" class="Filter-Label">Pro's</label>
            <textarea name="pros" type="text" class="Filter-Value VerticalResize DisplayBlock"cols="40" rows="5" value=""></textarea>
        </div>
        <div class="Filter-Item">
            <label for="cons" class="Filter-Label">Con's</label>
            <textarea name="cons" type="text" class="Filter-Value VerticalResize DisplayBlock"cols="40" rows="5" value=""></textarea>
        </div>

        <select class = "Score" name="Score">
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
            <option value="6">6</option>
            <option value="7">7</option>
            <option value="8">8</option>
            <option value="9">9</option>
            <option value="10">10</option>
        </select>
        <Input value="{{$ticket_id}}" Name="ticket_id"></Input>
        <Button type='submit' name='SubmitFeedback'> Submit </Button>
        </form>
    </div>
    </body>
</html>
