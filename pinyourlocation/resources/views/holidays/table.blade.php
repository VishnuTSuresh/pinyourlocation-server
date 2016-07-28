<table class="table table-responsive" id="holidays-table">
    <thead>
        <th>Name</th>
        <th>Date</th>
        <th colspan="3">Action</th>
    </thead>
    <tbody>
    @foreach($holidays as $holiday)
        <tr>
            <td>{!! $holiday->name !!}</td>
            <td>{!! $holiday->date !!}</td>
            <td>
                {!! Form::open(['route' => ['holidays.destroy', $holiday->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('holidays.show', [$holiday->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('holidays.edit', [$holiday->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
