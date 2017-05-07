<!-- sidebar menu: : style can be found in sidebar.less -->
<ul class="sidebar-menu">
	<li class="header">MAIN NAVIGATION</li>
	<li @if(!request()->segment(2)) class="active" @endif><a href="{{url('admin')}}"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
	<li class="treeview @if(request()->segment(2) == 'user') active @endif">
	  <a href="#">
	    <i class="fa fa-users"></i> <span>Users</span> <i class="fa fa-angle-left pull-right"></i>
	  </a>
	  <ul class="treeview-menu">
	    <li><a href="{{route('admin.user.index')}}"><i class="fa fa-circle-o text-success"></i> List users</a></li>
	    <li><a href="{{route('admin.user.create')}}"><i class="fa fa-plus text-danger"></i> Add a user</a></li>
	  </ul>
	</li>
	<li class="treeview @if(request()->segment(2) == 'page') active @endif">
	  <a href="#">
	    <i class="fa fa-file-text-o"></i> <span>Pagina's</span> <i class="fa fa-angle-left pull-right"></i>
	  </a>
	  <ul class="treeview-menu">
	    <li><a href="{{route('admin.page.index')}}"><i class="fa fa-circle-o text-success"></i> Pagina's weergeven</a></li>
	  </ul>
	</li>
	<li class="treeview @if(request()->segment(2) == 'page') active @endif">
		<a href="#">
			<i class="fa fa-file-text-o"></i> <span>Producten</span> <i class="fa fa-angle-left pull-right"></i>
		</a>
		<!-- <ul class="treeview-menu">
            <li><a href="/admin/showAllPriceLists"><i class="fa fa-circle-o text-success"></i> Prijslijsten weergeven</a></li>
			<li><a href="/admin/showAllProducts"><i class="fa fa-circle-o text-success"></i> Producten weergeven</a></li>
			<li><a href="{{route('admin.product.create')}}"><i class="fa fa-plus text-danger"></i> Product toevoegen</a></li>
			<li><a href="/admin/category"><i class="fa fa-circle-o text-success"></i> Product categorieën</a></li>
			<li><a href="/admin/editColor"><i class="fa fa-circle-o text-success"></i> Kleuren</a></li>
			<li><a href="/admin/editCoating"><i class="fa fa-circle-o text-success"></i> Coatings</a></li>
		</ul> -->
		<ul class="treeview-menu">
            <li><a href="{{ url('admin/showAllPriceLists') }}"><i class="fa fa-circle-o text-success"></i> Prijslijsten weergeven</a></li>
            <li><a href="{{route('admin.showAllPriceLists.create')}}"><i class="fa fa-plus text-danger"></i> Create Price List</a></li>
			<li><a href="{{ url('admin/showAllProducts') }}"><i class="fa fa-circle-o text-success"></i> Producten weergeven</a></li>
			<li><a href="{{route('admin.product.create')}}"><i class="fa fa-plus text-danger"></i> Product toevoegen</a></li>
			<li><a href="{{ url('admin/category') }}"><i class="fa fa-circle-o text-success"></i> Product categorieën</a></li>
			<li><a href="{{ url('admin/editColor') }}"><i class="fa fa-circle-o text-success"></i> Kleuren</a></li>
			<li><a href="{{ url('admin/editCoating') }}"><i class="fa fa-circle-o text-success"></i> Coatings</a></li>
		</ul>
	</li>
	<li class="treeview @if(request()->segment(2) == 'page') active @endif">
		<a href="#">
			<i class="fa fa-file-text-o"></i> <span>Website configuratie</span> <i class="fa fa-angle-left pull-right"></i>
		</a>
		<ul class="treeview-menu">
			<li><a href="/admin/configuration"><i class="fa fa-circle-o text-success"></i> Configuratie</a></li>
		</ul>
	</li>
</ul>