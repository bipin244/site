@extends('front.template.main')
@section('content')
	<section class="post">
        <!-- Blog Post -->

        <!-- Title -->
        <h1>{{$page->title}}</h1>


        <!-- Post Content -->
        {!!nl2br($page->content)!!}
	</section>
@endsection
