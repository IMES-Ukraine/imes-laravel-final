
<form action="/cards" method="POST">

<table class="table">
    <tbody>

        <tr>
            <td class="text-right">name</td>
            <td><input v-model="item.name" class="form-control" type="text" /></td>
        </tr>

        <tr>
            <td class="text-right">cost</td>
            <td><input v-model="item.cost" class="form-control" type="text" /></td>
        </tr>

        <tr>
            <td class="text-right">short_description</td>
            <td><input v-model="item.short_description" class="form-control" type="text" /></td>
        </tr>

        <tr>
            <td class="text-right">description</td>
            <td><textarea v-model="item.description" class="form-control"></textarea></td>
        </tr>

        <tr>
            <td class="text-right">category_id</td>
            <td><select v-model="item.category_id" class="form-control">
    <option v-for="option in options" :key="option" :value="option" v-text="option"></option>
</select></td>
        </tr>

        <tr>
            <td class="text-right">is_active</td>
            <td><label>
    <input v-model="item.is_active" :value="0" class="form-control" type="radio" /> 0 </label>
<label>
    <input v-model="item.is_active" :value="1" class="form-control" type="radio" /> 1 </label></td>
        </tr>

    </tbody>
</table>

</form>
