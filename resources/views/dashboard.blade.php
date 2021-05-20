@include("incl/header")
<div class="d-flex pt-3 pb-3">
    <h1 class="mt-4 align-middle">Dashboard</h1>
    <div class="Filter-List ml-auto">
        <div class="filter-item-big mb-2 d-flex">
            <div class="Filter-Item ml-auto">
            <!--Filter-->
                <label class="Filter-Label">
                    Search:
                </label>
                <input name="subject" class="Filter-Value" oninput="filterChangedText(this, this.value)"></input>
                <label class="Filter-Label">
                    On:
                </label>
                <select class="Filter-Value" onchange="changeFilterName(this, this.value, this.parentNode.getElementsByTagName('INPUT')[0], filterChangedText)">
                    <option selected value="subject">Subject</option>
                    <option value="person_name">Ticket holder</option>
                </select>
            </div>
        </div>
        <div class="items">
        @if (isset($departments))
            @if (count($departments) > 0)

                     <div class="Filter-Item">
                         <!--Filter-->
                         <label for="department_name" class="Filter-Label">
                             department:
                         </label>
                         <select name="department_name" class="Filter-Value" onchange="filterChanged(this, this.value)">
                             <option selected value="None">None</option>
                                 @foreach($departments as $department)
                                     <option value="{{$department['name']}}">{{$department['name']}}</option>
                                 @endforeach
                         </select>
                     </div>
            @endif
        @endif

        <div class="Filter-Item">
            <!--Filter-->
            <label for="Type" class="Filter-Label">
                Type:
            </label>
            <select name="type" class="Filter-Value" onchange="filterChanged(this, this.value)">
                <option selected value="None">None</option>
                @foreach($types as $type)
                    <option value="{{$type['name']}}">{{$type['name']}}</option>
                @endforeach
            </select>
        </div>
        <div class="Filter-Item">
            <!--Filter-->
            <label for="Status" class="Filter-Label">
                Status:
            </label>
            <select name="status" class="Filter-Value" onchange="filterChanged(this, this.value)">
                <!--ToDo fill up with different status types-->
                <option selected value="None">None</option>
                @foreach($statuses as $status)
                    <option value="{{$status['status']}}">{{$status['status']}}</option>
                @endforeach
            </select>
        </div>
        </div>
    </div>
</div>


<table id="tickets-table" class="table">
    <tr name="head" class="thead">
        <th>Id</th>
        <th>ticket holder</th>
        <th>type</th>
        <th>subject</th>
        <th>message</th>
        <th>department</th>
        <th>status</th>
    </tr>

    @foreach($results as $result)
    <tr class="trow" onclick="ToTicketViewer(event);">
        <td name="id">{{$result['id']}}</td>
        <td name="person_name">{{$result['person_name']}}</td>
        <td name="type">{{$result['type']}}</td>
        <td name="subject">{{$result['subject']}}</td>
        <td name="message">{{ \Illuminate\Support\Str::limit($result['message'], $limit = 75, $end = '...')}}</td>
        <td name="department_name">{{$result['department_name']}}</td>
        <td name="status">{{$result['status']}}</td>

        <form name="toticketviewer" action="\ticketviewer" method="POST" style="display: none;">
            @csrf
            <input hidden name="id" value="{{$result['id']}}">
        </form>
    </tr>
    @endforeach


</table>
</div>
<script>
    function ToTicketViewer(event) {
        var TrChildren = event.target.parentNode.children;

        var toticketviewerForm = null;
        for (let index = 0; index < TrChildren.length; index++) {
            const element = TrChildren[index];
            if (element.nodeName == "FORM") {
                toticketviewerForm = element;
                break;
            }
        }
        if (toticketviewerForm) {
            toticketviewerForm.submit();
        }
    }

    //
    // Filter
    //
    var filterOptions = {};
    function changeFilterName(Element, newValue, ToChangeElement, filterUpdateFunction){
        if (ToChangeElement) {
            removeFilter(ToChangeElement.getAttribute("name"));
            ToChangeElement.setAttribute("name", newValue);
            if (filterUpdateFunction) {
                filterUpdateFunction(ToChangeElement, ToChangeElement.value);
            }
        }
    }

    function filterChanged(Element, Value, ValidationFunction) {
        var Name = Element.getAttribute("name");
        if (!Name) {
            return null;
        }

        ColumnNames = Name.split("/")
        for (let index = 0; index < ColumnNames.length; index++) {
            const Col = ColumnNames[index];
            addFilter(Col, Value, ValidationFunction);
        }
        filterTickets();
    }

    function filterChangedText(Element, Value) {
        filterChanged(Element, Value, (CellName, CellValue, FilterValue) => {
            if (FilterValue == null) {
                return true;
            }
            if (FilterValue.trim() == "") {
                return true;
            }

            return CellValue.trim().includes(FilterValue.trim());
        });
    }
    function addFilter(Name, Value, ValidationFunction = null) {
        var newFilter = {
            name: Name.toLowerCase(),
            value: Value.toLowerCase(),
            validationFunction: ValidationFunction
        };
        filterOptions[Name] = newFilter;
        return newFilter;
    }
    function removeFilter(Name) {
        if (filterOptions[Name]) {
            delete filterOptions[Name];
        }
    }

    function adheresToFilter(Row) {
        var Cells = Row.children
        var Result = true;
        for (let index = 0; index < Cells.length; index++) {
            const Cell = Cells[index];
            for (let key in filterOptions) {
                if (hasName(Cell, filterOptions[key]["name"])) {
                    var Adheres = Cell.textContent.toLowerCase() == filterOptions[key]["value"].toLowerCase() || filterOptions[key]["value"].toLowerCase() == "none" ;

                    if (filterOptions[key]["validationFunction"]) {
                        Adheres = filterOptions[key]["validationFunction"](filterOptions[key]["name"].toLowerCase(), Cell.textContent.toLowerCase(), filterOptions[key]["value"].toLowerCase())
                    }

                    if (Adheres) {
                        //Adheres
                    } else {
                        return false;
                    }
                }
            }
        }

        return Result;
    }

    function hasName(Element, Name) {
        return (Element.getAttribute("name") == Name);
    }

    function filterTickets() {
        // Debug: Print the filter variables
        // for (let index = 0; index < FilterItems.length; index++) {
        //     const element = FilterItems[index];
        //     console.log(element["value"]);
        //     console.log(element["column"]);
        // }

        //Hide non matching items
        var Table = document.getElementById("tickets-table");
        if (!Table) {
            return null;
        }

        var TableBody = Table.firstElementChild;
        var TableRows = TableBody.getElementsByClassName("trow");
        for (let index = 0; index < TableRows.length; index++) {
            const Row = TableRows[index];

            var RowAdherestToFilter = adheresToFilter(Row);
            if (!RowAdherestToFilter) {
                Row.setAttribute("hidden", "");
            } else {
                Row.removeAttribute("hidden");
            }
        }
    }
</script>
</body>

</html>
