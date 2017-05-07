@extends('cms::front.template.main')
@section('cms::content')
	<section class="post">
        <!-- Blog Post -->

        <!-- Title -->
        <h1>{{$page->title}}</h1>

        <hr>

        <!-- Post Content -->
        {!!nl2br($page->content)!!}	
	</section>
@endsection
