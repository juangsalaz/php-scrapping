<table>
    <thead>
    <tr>
        <th>Zip</th>
        <th>City / State</th>
        <th>Fiber</th>
        <th>Cable</th>
        <th>DSL</th>
        <th>Wired</th>
        <th>Providers</th>
    </tr>
    </thead>
    <tbody>
    @foreach($result as $row)
        <tr>
            <td>{{ $row['zip'] }}</td>
            <td>{{ $row['city'] }}</td>
            <td>{{ $row['fiber'] }}</td>
            <td>{{ $row['cable'] }}</td>
            <td>{{ $row['dsl'] }}</td>
            <td>{{ $row['wired'] }}</td>
            <td>{{ $row['providers'] }}</td>
        </tr>
    @endforeach
    </tbody>
</table>