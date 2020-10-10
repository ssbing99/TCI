@if($item->subs)

@else
    <a class="dropdown-item" id="menu-{{$item->id}}" href="{{$item->link}}">{{trans('custom-menu.'.$menu_name.'.'.str_slug($item->label))}}</a>
@endif
