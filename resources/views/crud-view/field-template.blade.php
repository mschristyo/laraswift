<td class="vertical-align-middle">
    <input type="text" required class="form-control txtFieldName width-100"/>
    <input type="text" class="form-control foreignTable txtForeignTable display-none"
           placeholder="Foreign table,Primary key"/>
</td>
<td class="vertical-align-middle">
    <select class="form-control txtdbType width-100">
        <option value="increments">{{__('app.increments')}}</option>
        <option value="integer">{{__('app.integer')}}</option>
        <option value="smallInteger">{{__('app.smallInteger')}}</option>
        <option value="longText">{{__('app.longText')}}</option>
        <option value="bigInteger">{{__('app.bigInteger')}}</option>
        <option value="double">{{__('app.double')}}</option>
        <option value="float">{{__('app.float')}}</option>
        <option value="decimal">{{__('app.decimal')}}</option>
        <option value="boolean">{{__('app.boolean')}}</option>
        <option value="string" selected>{{__('app.string')}}</option>
        <option value="char">{{__('app.char')}}</option>
        <option value="text">{{__('app.text')}}</option>
        <option value="mediumText">{{__('app.mediumText')}}</option>
        <option value="enum">{{__('app.enum')}}</option>
        <option value="binary">{{__('app.binary')}}</option>
        <option value="dateTime">{{__('app.dateTime')}}</option>
        <option value="date">{{__('app.date')}}</option>
        <option value="timestamp">{{__('app.timestamp')}}</option>
    </select>

    <input type="text" class="form-control dbValue txtDbValue display-none" placeholder=""/>
</td>
<td class="vertical-align-middle">
    <input type="text" class="form-control txtValidation"/>
</td>
<td class="vertical-align-middle">
    <select class="form-control drdHtmlType width-100">
        <option value="text">{{__('app.text')}}</option>
        <option value="email">{{__('app.email')}}</option>
        <option value="number">{{__('app.number')}}</option>
        <option value="date">{{__('app.date')}}</option>
        <option value="file">{{__('app.file')}}</option>
        <option value="password">{{__('app.password')}}</option>
        <option value="select">{{__('app.select')}}</option>
        <option value="radio">{{__('app.radio')}}</option>
        <option value="checkbox">{{__('app.checkbox')}}</option>
        <option value="textarea">{{__('app.textarea')}}</option>
        <option value="toggle-switch">{{__('app.toggle')}}</option>
    </select>
    <input type="text" class="form-control htmlValue txtHtmlValue display-none"
           placeholder=""/>
</td>
<td class="vertical-align-middle">
    <div class="checkbox text-center">
        <label class="pl-0">
            <input type="checkbox" class="flat-red chkPrimary ml-0"/>
        </label>
    </div>
</td>
<td class="vertical-align-middle">
    <div class="checkbox text-center">
        <label class="pl-0">
            <input type="checkbox" class="flat-red chkForeign ml-0"/>
        </label>
    </div>
</td>
<td class="vertical-align-middle">
    <div class="checkbox text-center">
        <label class="pl-0">
            <input type="checkbox" class="flat-red chkSearchable ml-0" checked/>
        </label>
    </div>
</td>
<td class="vertical-align-middle">
    <div class="checkbox text-center">
        <label class="pl-0">
            <input type="checkbox" class="flat-red chkFillable ml-0" checked/>
        </label>
    </div>
</td>
<td class="vertical-align-middle">
    <div class="checkbox text-center">
        <label class="pl-0">
            <input type="checkbox" class="flat-red chkInForm  ml-0" checked/>
        </label>
    </div>
</td>
<td class="vertical-align-middle">
    <div class="checkbox text-center">
        <label class="pl-0">
            <input type="checkbox" class="flat-red chkInIndex ml-0" checked/>
        </label>
    </div>
</td>
<td class="text-center vertical-align-middle">
    <i onclick="removeItem(this)" class="remove fa fa-trash-o field-delete-btn"></i>
</td>
