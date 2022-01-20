<table style="border-collapse: collapse;border: 1px solid grey;">
    <thead>
    <tr>
        <th align="center" rowspan="1" style="width:200px;background-color: #f5f5f5;border: 1px solid grey;">ФИО</th>
        <th align="center" rowspan="1" style="width:150px;background-color: #f5f5f5;border: 1px solid grey;">специализация</th>
        <th align="center" rowspan="1" style="width:100px;background-color: #f5f5f5;border: 1px solid grey;">город</th>
        <th align="center" rowspan="1" style="width:150px;background-color: #f5f5f5;border: 1px solid grey;">место работы</th>
        <th align="center" rowspan="1" style="width:150px;background-color: #f5f5f5;border: 1px solid grey;">номер телефона</th>
        <th align="center" rowspan="1" style="width:150px;background-color: #f5f5f5;border: 1px solid grey;">всего баллов</th>
        <th align="center" rowspan="1" style="width:150px;background-color: #f5f5f5;border: 1px solid grey;">всего баллов. (статьи)</th>
        <th align="center" rowspan="1" style="width:150px;background-color: #f5f5f5;border: 1px solid grey;">всего баллов (тесты)</th>
        <th align="center" rowspan="1" style="width:150px;background-color: #f5f5f5;border: 1px solid grey;">переведено баллов</th>
        <th align="center" rowspan="1" style="width:100px;background-color: #f5f5f5;border: 1px solid grey;">тип обмена</th>
        <!--<th align="center" colspan="4" style="background-color: #f5f5f5;border: 1px solid grey;">тест 1.</th>
        <th align="center" colspan="4" style="background-color: #f5f5f5;border: 1px solid grey;">тест 1.</th>
        <th align="center" colspan="4" style="background-color: #f5f5f5;border: 1px solid grey;">тест 1.</th>
        <th align="center" colspan="4" style="background-color: #f5f5f5;border: 1px solid grey;">статья 1.</th>-->
    </tr>
    <!--<tr>
        <th align="center" style="width:150px;background-color: #f5f5f5;border: 1px solid grey;">время старта</th>
        <th align="center" style="width:150px;background-color: #f5f5f5;border: 1px solid grey;">время прохождения</th>
        <th align="center" style="width:150px;background-color: #f5f5f5;border: 1px solid grey;">провал</th>
        <th align="center" style="width:150px;background-color: #f5f5f5;border: 1px solid grey;">полученные балы</th>
        <th align="center" style="width:150px;background-color: #f5f5f5;border: 1px solid grey;">время старта</th>
        <th align="center" style="width:150px;background-color: #f5f5f5;border: 1px solid grey;">время прохождения</th>
        <th align="center" style="width:150px;background-color: #f5f5f5;border: 1px solid grey;">провал</th>
        <th align="center" style="width:150px;background-color: #f5f5f5;border: 1px solid grey;">полученные балы</th>
        <th align="center" style="width:150px;background-color: #f5f5f5;border: 1px solid grey;">время старта</th>
        <th align="center" style="width:150px;background-color: #f5f5f5;border: 1px solid grey;">время прохождения</th>
        <th align="center" style="width:150px;background-color: #f5f5f5;border: 1px solid grey;">провал</th>
        <th align="center" style="width:150px;background-color: #f5f5f5;border: 1px solid grey;">полученные балы</th>
        <th align="center" style="width:150px;background-color: #f5f5f5;border: 1px solid grey;">время старта</th>
        <th align="center" style="width:150px;background-color: #f5f5f5;border: 1px solid grey;">время прохождения</th>
        <th align="center" style="width:150px;background-color: #f5f5f5;border: 1px solid grey;">провал</th>
        <th align="center" style="width:150px;background-color: #f5f5f5;border: 1px solid grey;">полученные балы</th>
    </tr>-->
    </thead>
    <tbody>
    @foreach($results as $user_id => $data)
        <tr>
            <td style="border: 1px solid grey;">{{ $data['user']->basic_information['name'] }}</td>
            <td style="border: 1px solid grey;">{{$data['user']->specialized_information['specification'] }}</td>
            <td style="border: 1px solid grey;">{{ $data['user']->specialized_information['city'] }}</td>
            <td style="border: 1px solid grey;">{{ $data['user']->specialized_information['workplace']}}</td>
            <td style="border: 1px solid grey;">{{ $data['user']->phone }}</td>
            <td style="border: 1px solid grey;">{{ $data['bonus-total']}}</td>
            <td style="border: 1px solid grey;">{{ $data['bonus-article'] }}</td>
            <td style="border: 1px solid grey;">{{ $data['bonus-test'] }}</td>
            <td style="border: 1px solid grey;background-color: #E9EE4E"></td>
            <td style="border: 1px solid grey;background-color: #E9EE4E"></td>
            <!--<td style="border: 1px solid grey;"></td>
            <td style="border: 1px solid grey;"></td>
            <td style="border: 1px solid grey;"></td>
            <td style="border: 1px solid grey;"></td>
            <td style="border: 1px solid grey;"></td>
            <td style="border: 1px solid grey;"></td>
            <td style="border: 1px solid grey;"></td>
            <td style="border: 1px solid grey;"></td>
            <td style="border: 1px solid grey;"></td>
            <td style="border: 1px solid grey;"></td>
            <td style="border: 1px solid grey;"></td>
            <td style="border: 1px solid grey;"></td>
            <td style="border: 1px solid grey;"></td>
            <td style="border: 1px solid grey;"></td>
            <td style="border: 1px solid grey;"></td>
            <td style="border: 1px solid grey;"></td>-->
        </tr>
    @endforeach
    </tbody>
</table>
