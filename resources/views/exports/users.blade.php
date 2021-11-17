<table style="border-collapse: collapse;border: 1px solid grey;">
    <thead>
    <tr>
        <th rowspan="2" style="border: 1px solid grey;padding: 5px;">ФИО</th>
        <th rowspan="2" style="border: 1px solid grey;padding: 5px;">специализация</th>
        <th rowspan="2" style="border: 1px solid grey;padding: 5px;">город</th>
        <th rowspan="2" style="border: 1px solid grey;padding: 5px;">место работы</th>
        <th rowspan="2" style="border: 1px solid grey;padding: 5px;">номер телефона</th>
        <th rowspan="2" style="border: 1px solid grey;padding: 5px;">всего баллов</th>
        <th rowspan="2" style="border: 1px solid grey;padding: 5px;">всего баллов. (статьи)</th>
        <th rowspan="2" style="border: 1px solid grey;padding: 5px;">всего баллов (тесты)</th>
        <th rowspan="2" style="border: 1px solid grey;padding: 5px;">переведено баллов</th>
        <th rowspan="2" style="border: 1px solid grey;padding: 5px;">тип обмена</th>
        <th colspan="4" style="border: 1px solid grey;padding: 5px;">тест 1.</th>
        <th colspan="4" style="border: 1px solid grey;padding: 5px;">тест 1.</th>
        <th colspan="4" style="border: 1px solid grey;padding: 5px;">тест 1.</th>
        <th colspan="4" style="border: 1px solid grey;padding: 5px;">статья 1.</th>
    </tr>
    <tr>
        <th style="border: 1px solid grey;padding: 5px;">время старта</th>
        <th style="border: 1px solid grey;padding: 5px;">время прохождения</th>
        <th style="border: 1px solid grey;padding: 5px;">провал</th>
        <th style="border: 1px solid grey;padding: 5px;">полученные балы</th>
        <th style="border: 1px solid grey;padding: 5px;">время старта</th>
        <th style="border: 1px solid grey;padding: 5px;">время прохождения</th>
        <th style="border: 1px solid grey;padding: 5px;">провал</th>
        <th style="border: 1px solid grey;padding: 5px;">полученные балы</th>
        <th style="border: 1px solid grey;padding: 5px;">время старта</th>
        <th style="border: 1px solid grey;padding: 5px;">время прохождения</th>
        <th style="border: 1px solid grey;padding: 5px;">провал</th>
        <th style="border: 1px solid grey;padding: 5px;">полученные балы</th>
        <th style="border: 1px solid grey;padding: 5px;">время старта</th>
        <th style="border: 1px solid grey;padding: 5px;">время прохождения</th>
        <th style="border: 1px solid grey;padding: 5px;">провал</th>
        <th style="border: 1px solid grey;padding: 5px;">полученные балы</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td style="border: 1px solid grey;padding: 5px;">lorem</td>
        <td style="border: 1px solid grey;padding: 5px;">lorem</td>
        <td style="border: 1px solid grey;padding: 5px;">lorem</td>
        <td style="border: 1px solid grey;padding: 5px;">lorem</td>
        <td style="border: 1px solid grey;padding: 5px;">lorem</td>
        <td style="border: 1px solid grey;padding: 5px;">lorem</td>
        <td style="border: 1px solid grey;padding: 5px;">lorem</td>
        <td style="border: 1px solid grey;padding: 5px;">lorem</td>
        <td style="border: 1px solid grey;padding: 5px;">lorem</td>
        <td style="border: 1px solid grey;padding: 5px;">lorem</td>
        <td style="border: 1px solid grey;padding: 5px;">lorem</td>
        <td style="border: 1px solid grey;padding: 5px;">lorem</td>
        <td style="border: 1px solid grey;padding: 5px;">lorem</td>
        <td style="border: 1px solid grey;padding: 5px;">lorem</td>
        <td style="border: 1px solid grey;padding: 5px;">lorem</td>
        <td style="border: 1px solid grey;padding: 5px;">lorem</td>
        <td style="border: 1px solid grey;padding: 5px;">lorem</td>
        <td style="border: 1px solid grey;padding: 5px;">lorem</td>
        <td style="border: 1px solid grey;padding: 5px;">lorem</td>
        <td style="border: 1px solid grey;padding: 5px;">lorem</td>
        <td style="border: 1px solid grey;padding: 5px;">lorem</td>
        <td style="border: 1px solid grey;padding: 5px;">lorem</td>
        <td style="border: 1px solid grey;padding: 5px;">lorem</td>
        <td style="border: 1px solid grey;padding: 5px;">lorem</td>
        <td style="border: 1px solid grey;padding: 5px;">lorem</td>
        <td style="border: 1px solid grey;padding: 5px;">lorem</td>
    </tr>
    </tbody>
</table>
<!--<table>
    <thead>
    <tr>
        <th rowspan="2">id</th>
        <th rowspan="2" valign="center">ФИО</th>
        <th rowspan="2">специализация</th>
        <th rowspan="2">город</th>
        <th rowspan="2">место работы</th>
        <th rowspan="2">номер телефона</th>
        <th rowspan="2">всего баллов</th>
        <th rowspan="2">всего баллов. (статьи)</th>
        <th rowspan="2">всего баллов (тесты)</th>
        <th style="background-color: #E9EE4E" rowspan="2">переведено баллов</th>
        <th style="background-color: #E9EE4E" rowspan="2">тип обмена</th>
        <th colspan="4">тест 1 .</th>
        <th colspan="4">тест 2 .</th>
    </tr>
    </thead>
    <tbody>
    @foreach($users as $user)
        <tr>
            <td>{{ $user->id }}</td>
            <td style="white-space: nowrap;">{{ ($user->basic_information && isset($user->basic_information['name']))?$user->basic_information['name']:$user->name }} {{ ($user->basic_information && isset($user->basic_information['surname']))?$user->basic_information['surname']:'' }}</td>
            <td>{{ ($user->specialized_information && isset($user->specialized_information['specification']))?$user->specialized_information['specification']:'' }}</td>
            <td>{{ ($user->city_info)?$user->city_info['name']:'' }}</td>
            <td>{{ ($user->specialized_information && isset($user->specialized_information['workplace']))?$user->specialized_information['workplace']:'' }}</td>
            <td>{{ $user->phone }}</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
            <td style="background-color: #E9EE4E">0</td>
            <td style="background-color: #E9EE4E">0</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    @endforeach
    </tbody>
</table>-->
