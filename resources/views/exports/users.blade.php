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
    @foreach($results as $value)
        <tr>
            <td style="border: 1px solid grey;">{{ ($value->user['basic_information'] && isset($value->user['basic_information']['name']))?$value->user['basic_information']['name']:$value->user['name'] }} {{ ($value->user['basic_information'] && isset($value->user['basic_information']['surname']))?$value->user['basic_information']['surname']:'' }}</td>
            <td style="border: 1px solid grey;">{{ ($value->user['specialized_information'] && isset($value->user['specialized_information']['specification']))?$value->user['specialized_information']['specification']:'' }}</td>
            <td style="border: 1px solid grey;">{{ ($value->user['city_info'])?$value->user['city_info']['name']:'' }}</td>
            <td style="border: 1px solid grey;">{{ ($value->user['specialized_information'] && isset($value->user['specialized_information']['workplace']))?$value->user['specialized_information']['workplace']:'' }}</td>
            <td style="border: 1px solid grey;">{{ $value->user['phone'] }}</td>
            <td style="border: 1px solid grey;"></td>
            <td style="border: 1px solid grey;"></td>
            <td style="border: 1px solid grey;"></td>
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
