<td class="vertical-align-middle">
    <select class="form-control drdRelationType width-100">
        <option value="1t1">{{__('app.one_to_one')}}</option>
        <option value="1tm">{{__('app.one_to_many')}}</option>
        <option value="mt1">{{__('app.many_to_one')}}</option>
        <option value="mtm">{{__('app.many_to_many')}}</option>
    </select>

    <input type="text"  class="form-control foreignTable txtForeignTable display-none"
           placeholder="Custom Table Name"/>
</td>
<td class="vertical-align-middle">
    <input type="text" required class="form-control txtForeignModel"/>
</td>
<td class="vertical-align-middle">
    <input type="text" class="form-control txtForeignKey width-100"/>
</td>
<td class="vertical-align-middle">
    <input type="text" class="form-control txtLocalKey"/>
</td>
<td class="text-center vertical-align-middle">
    <i onclick="removeItem(this)" class="remove fa fa-trash-o field-delete-btn"></i>
</td>
