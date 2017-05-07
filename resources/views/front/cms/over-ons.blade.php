@extends('front.template.main')
@section('content')
	<section class="post">
        <!-- Blog Post -->

        <!-- Title -->
        <h1>{{$page->getTitle()}}</h1>


        <!-- Post Content -->
        {!!nl2br($page->veld("introtekst"))!!}
	</section>
@endsection
