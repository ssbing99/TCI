
@if(count($teachers)>0)
<section class="bg-f0f1f5">
    <div class="container">
        <div class="row clearfix">
            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <h3>Our Instructors
                    <a class="btn btn-trans float-right" href="{{route('teachers.index')}}">View All <i class="fa fa-long-arrow-right"></i></a>
                </h3>
            </div>
            @foreach($teachers as $item)
                <?php
                $teacherProfile = $item->teacherProfile?:'';
                ?>
            <div class="col-12 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                <div class="faculty">
                    <a href="{{route('teachers.show',['id'=>$item->id])}}"><img alt="" style="max-width: 145px;" src="{{$item->picture}}"></a>
                    <div class="faculty-name">
                        <a href="{{route('teachers.show',['id'=>$item->id])}}">{{$item->full_name}}</a>
                        <span>{{$teacherProfile->title}}</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
    @endif