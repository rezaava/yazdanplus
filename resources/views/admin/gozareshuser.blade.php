<html>
    <body>
        <table>
            <tr>
                <th>
                    name
                </th>
                <th>
                    mobile
                </th>
                <th>
                    خرید
                </th>
            </tr>
            @foreach($users as $user)
                <tr>
                    <td>{{$user->name}}{{$user->family}}</td>
                    <td>{{$user->mobile}}</td>
                    <td>{{$user['jam']}}</td>
                </tr>
            
            @endforeach
        </table>
    </body>
</html>