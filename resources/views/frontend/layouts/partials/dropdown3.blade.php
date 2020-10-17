@if($item->subs)

@else
    <a class="dropdown-item" id="menu-{{$item->id}}" href="{{asset($item->link)}}">{{trans('custom-menu.'.$menu_name.'.'.str_slug($item->label))}}</a>
@endif
