@extends('admin.template.main')
@section('content')
	<!-- Default box -->
	@include('messages')
	<div class="box">
		<div class="box-header">
			<h3 class="box-title">Kleuren</h3>
			<hr/>
			<p style="margin-top:10px;"><a id="newColorButton" class="btn btn-primary">Nieuwe kleur</a></p>
		</div><!-- /.box-header -->
		<div class="box-body no-padding">
			@if(!$colors->isEmpty())
				<table class="table">
					<tr>
						<th class="col-md-1">RAL</th>
						<th class="col-md-1">Hex</th>
						<th class="col-md-2">Naam NL</th>
						<th class="col-md-2">Naam FR</th>
						<th class="col-md-2">Naam DE</th>
						<th class="col-md-2">Naam EN</th>
					</tr>
					@foreach($colors as $color)
						<tr>
							<td class="ral">{{$color->ral}}</td>
							<td class="hex">{{$color->hex}}</td>
							<td class="naamnl">{{$color->naam_nl}}</td>
							<td class="naamfr">{{$color->naam_fr}}</td>
							<td class="naamde">{{$color->naam_de}}</td>
							<td class="naamen">{{$color->naam_en}}</td>
							<td>
								<input type="hidden" name="_method" value="delete" />
								<a class="btn btn-sml btn-success edit" data-color-id="{{$color->id}}"><i class="fa fa-pencil"></i> Edit</a>
								<a class="btn btn-sml btn-danger delete" id="button" data-color-id="{{$color->id}}"><i class="fa fa-times"></i> Delete</a>
							</td>
						</tr>
					@endforeach
				</table>
			@else
				<div class="col-md-12"><p class="alert alert-warning">No colors to show</p></div>
			@endif
		</div><!-- /.box-body -->
		@include('admin.template.pagination', ['pages' => $colors])
		<div id="myModal" class="modal fade" role="dialog">
			<div class="modal-dialog">

				<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Kleur toevoegen</h4>
					</div>
					<div class="modal-body">
						<div class="row">
							<div class="col-md-12">
								<table id="colorTable" style="border-spacing: 10px 0; border-collapse: separate;">
									<tbody>
									<tr>
										<td bgcolor="#d7c794"><a href="javascript:colorchange('D7C794','1000','Green beige','214-199-148')" style="color:#000000;">1000</a></td>
										<td bgcolor="#e05e1f"><a href="javascript:colorchange('E05E1F','2000','Yellow orange','224-094-031')" style="color:#ffffff;">2000</a></td>
										<td bgcolor="#ab1f1c"><a href="javascript:colorchange('AB1F1C','3000','Flame red','171-031-028')" style="color:#ffffff;">3000</a></td>
										<td bgcolor="#824080"><a href="javascript:colorchange('824080','4001','Red lilac','130-064-128')" style="color:#ffffff;">4001</a></td>
										<td bgcolor="#17336b"><a href="javascript:colorchange('17336B','5000','Violet blue','023-051-107')" style="color:#ffffff;">5000</a> </td>
										<td bgcolor="#337854"><a href="javascript:colorchange('337854','6000','Patina green','051-120-084')" style="color:#ffffff;">6000</a></td>
										<td bgcolor="#738591"><a href="javascript:colorchange('738591','7000','Squirrel grey','115-133-148')" style="color:#ffffff;">7000</a></td>
										<td bgcolor="#7d5c38"><a href="javascript:colorchange('7D5C38','8000','Green brown','125-092-056')" style="color:#ffffff;">8000</a></td>
										<td bgcolor="#fffcf0"><a href="javascript:colorchange('FFFCF0','9001','Cream','255-252-240')" style="color:#000000;">9001</a></td></tr>
									<tr>
										<td bgcolor="#d9ba8c"><a href="javascript:colorchange('D9BA8C','1001','Beige','217-186-140')" style="color:#000000;">1001</a></td>
										<td bgcolor="#ba2e21"><a href="javascript:colorchange('BA2E21','2001','Red orange','186-046-033')" style="color:#ffffff;">2001</a></td>
										<td bgcolor="#a3171a"><a href="javascript:colorchange('A3171A','3001','Signal red','163-023-026')" style="color:#ffffff;">3001</a></td>
										<td bgcolor="#8f2640"><a href="javascript:colorchange('8F2640','4002','Red violet','143-038-064')" style="color:#ffffff;">4002</a></td>
										<td bgcolor="#0a3354"><a href="javascript:colorchange('0A3354','5001','Green blue','010-051-084')" style="color:#ffffff;">5001</a> </td>
										<td bgcolor="#266629"><a href="javascript:colorchange('266629','6001','Emerald green','038-102-041')" style="color:#ffffff;">6001</a></td>
										<td bgcolor="#8794a6"><a href="javascript:colorchange('8794A6','7001','Silver grey','135-148-166')" style="color:#ffffff;">7001</a></td>
										<td bgcolor="#91522e"><a href="javascript:colorchange('91522E','8001','Ochre brown','145-082-046')" style="color:#ffffff;">8001</a></td>
										<td bgcolor="#f0ede6"><a href="javascript:colorchange('F0EDE6','9002','Grey white','240-237-230')" style="color:#000000;">9002</a></td></tr>
									<tr>
										<td bgcolor="#d6b075"><a href="javascript:colorchange('D6B075','1002','Sand yellow','214-176-117')" style="color:#000000;">1002</a></td>
										<td bgcolor="#cc241c"><a href="javascript:colorchange('CC241C','2002','Vermilion','204-036-028')" style="color:#ffffff;">2002</a></td>
										<td bgcolor="#a31a1a"><a href="javascript:colorchange('A31A1A','3002','Carmine red','163-026-026')" style="color:#ffffff;">3002</a></td>
										<td bgcolor="#c9388c"><a href="javascript:colorchange('C9388C','4003','Heather violet','201-056-140')" style="color:#ffffff;">4003</a></td>
										<td bgcolor="#000f75"><a href="javascript:colorchange('000F75','5002','Ultramarine blue','000-015-117')" style="color:#ffffff;">5002</a> </td>
										<td bgcolor="#265721"><a href="javascript:colorchange('265721','6002','Leaf green','038-087-033')" style="color:#ffffff;">6002</a></td>
										<td bgcolor="#7a7561"><a href="javascript:colorchange('7A7561','7002','Olive grey','122-117-097')" style="color:#ffffff;">7002</a></td>
										<td bgcolor="#6e3b30"><a href="javascript:colorchange('6E3B30','8002','Signal brown','110-059-048')" style="color:#ffffff;">8002</a></td>
										<td bgcolor="#ffffff"><a href="javascript:colorchange('FFFFFF','9003','Signal white','255-255-255')" style="color:#000000;">9003</a></td></tr>
									<tr>
										<td bgcolor="#fca329"><a href="javascript:colorchange('FCA329','1003','Signal yellow','252-163-041')" style="color:#000000;">1003</a></td>
										<td bgcolor="#ff6336"><a href="javascript:colorchange('FF6336','2003','Pastel orange','255-099-054')" style="color:#ffffff;">2003</a></td>
										<td bgcolor="#8a1214"><a href="javascript:colorchange('8A1214','3003','Ruby red','138-018-020')" style="color:#ffffff;">3003</a></td>
										<td bgcolor="#5c082b"><a href="javascript:colorchange('5C082B','4004','Claret violet','092-008-043')" style="color:#ffffff;">4004</a></td>
										<td bgcolor="#001745"><a href="javascript:colorchange('001745','5003','Saphire blue','000-023-069')" style="color:#ffffff;">5003</a> </td>
										<td bgcolor="#3d452e"><a href="javascript:colorchange('3D452E','6003','Olive green','061-069-046')" style="color:#ffffff;">6003</a></td>
										<td bgcolor="#707061"><a href="javascript:colorchange('707061','7003','Moss grey','112-112-097')" style="color:#ffffff;">7003</a></td>
										<td bgcolor="#733b24"><a href="javascript:colorchange('733B24','8003','Clay brown','115-059-036')" style="color:#ffffff;">8003</a></td>
										<td bgcolor="#1c1c21"><a href="javascript:colorchange('1C1C21','9004','Signal black','028-028-033')" style="color:#ffffff;">9004</a></td></tr>
									<tr>
										<td bgcolor="#e39624"><a href="javascript:colorchange('E39624','1004','Golden yellow','227-150-036')" style="color:#000000;">1004</a></td>
										<td bgcolor="#f23b1c"><a href="javascript:colorchange('F23B1C','2004','Pure orange','242-059-028')" style="color:#ffffff;">2004</a></td>
										<td bgcolor="#690f14"><a href="javascript:colorchange('690F14','3004','Purple red','105-015-020')" style="color:#ffffff;">3004</a></td>
										<td bgcolor="#633d9c"><a href="javascript:colorchange('633D9C','4005','Blue lilac','099-061-156')" style="color:#ffffff;">4005</a></td>
										<td bgcolor="#030d1f"><a href="javascript:colorchange('030D1F','5004','Black blue','003-013-031')" style="color:#ffffff;">5004</a> </td>
										<td bgcolor="#0d3b2e"><a href="javascript:colorchange('0D3B2E','6004','Blue green','013-059-046')" style="color:#ffffff;">6004</a></td>
										<td bgcolor="#9c9ca6"><a href="javascript:colorchange('9C9CA6','7004','Signal grey','156-156-166')" style="color:#ffffff;">7004</a></td>
										<td bgcolor="#85382b"><a href="javascript:colorchange('85382B','8004','Copper brown','133-056-043')" style="color:#ffffff;">8004</a></td>
										<td bgcolor="#03050a"><a href="javascript:colorchange('03050A','9005','Jet black','003-005-010')" style="color:#ffffff;">9005</a></td></tr>
									<tr>
										<td bgcolor="#c98721"><a href="javascript:colorchange('C98721','1005','Honey yellow','201-135-033')" style="color:#000000;">1005</a></td>
										<td bgcolor="#fc1c14"><a href="javascript:colorchange('FC1C14','2005','Luminous orange','252-028-020')" style="color:#ffffff;">2005</a></td>
										<td bgcolor="#4f121a"><a href="javascript:colorchange('4F121A','3005','Wine red','079-018-026')" style="color:#ffffff;">3005</a></td>
										<td bgcolor="#910f66"><a href="javascript:colorchange('910F66','4006','Traffic purple','145-015-102')" style="color:#ffffff;">4006</a></td>
										<td bgcolor="#002e7a"><a href="javascript:colorchange('002E7A','5005','Signal blue','000-046-122')" style="color:#ffffff;">5005</a> </td>
										<td bgcolor="#0a381f"><a href="javascript:colorchange('0A381F','6005','Moss green','010-056-031')" style="color:#ffffff;">6005</a></td>
										<td bgcolor="#616969"><a href="javascript:colorchange('616969','7005','Mouse grey','097-105-105')" style="color:#ffffff;">7005</a></td>
										<td bgcolor="#5e331f"><a href="javascript:colorchange('5E331F','8007','Fawn brown','094-051-031')" style="color:#ffffff;">8007</a></td>
										<td bgcolor="#a6abb5"><a href="javascript:colorchange('A6ABB5','9006','White aluminium','166-171-181')" style="color:#000000;">9006</a></td></tr>
									<tr>
										<td bgcolor="#e0821f"><a href="javascript:colorchange('E0821F','1006','Maize yellow','224-130-031')" style="color:#000000;">1006</a></td>
										<td bgcolor="#ff7521"><a href="javascript:colorchange('FF7521','2007','Luminous bright orange','255-117-033')" style="color:#ffffff;">2007</a></td>
										<td bgcolor="#2e121a"><a href="javascript:colorchange('2E121A','3007','Black red','046-018-026')" style="color:#ffffff;">3007</a></td>
										<td bgcolor="#380a2e"><a href="javascript:colorchange('380A2E','4007','Purple violet','056-010-046')" style="color:#ffffff;">4007</a></td>
										<td bgcolor="#264f87"><a href="javascript:colorchange('264F87','5007','Brillant blue','038-079-135')" style="color:#ffffff;">5007</a> </td>
										<td bgcolor="#292b24"><a href="javascript:colorchange('292B24','6006','Grey olive','041-043-036')" style="color:#ffffff;">6006</a></td>
										<td bgcolor="#6b6157"><a href="javascript:colorchange('6B6157','7006','Beige grey','107-097-087')" style="color:#ffffff;">7006</a></td>
										<td bgcolor="#633d24"><a href="javascript:colorchange('633D24','8008','Olive brown','099-061-036')" style="color:#ffffff;">8008</a></td>
										<td bgcolor="#7d7a78"><a href="javascript:colorchange('7D7A78','9007','Grey aluminium','125-122-120')" style="color:#000000;">9007</a></td></tr>
									<tr>
										<td bgcolor="#e37a1f"><a href="javascript:colorchange('E37A1F','1007','Daffodil yellow','227-122-031')" style="color:#000000;">1007</a></td>
										<td bgcolor="#fa4f29"><a href="javascript:colorchange('FA4F29','2008','Bright red orange','250-079-041')" style="color:#ffffff;">2008</a></td>
										<td bgcolor="#5e2121"><a href="javascript:colorchange('5E2121','3009','Oxide red','094-033-033')" style="color:#ffffff;">3009</a></td>
										<td bgcolor="#7d1f7a"><a href="javascript:colorchange('7D1F7A','4008','Signal violet','125-031-122')" style="color:#ffffff;">4008</a></td>
										<td bgcolor="#1a2938"><a href="javascript:colorchange('1A2938','5008','Grey blue','026-041-056')" style="color:#ffffff;">5008</a> </td>
										<td bgcolor="#1c2617"><a href="javascript:colorchange('1C2617','6007','Bottle green','028-038-023')" style="color:#ffffff;">6007</a></td>
										<td bgcolor="#695438"><a href="javascript:colorchange('695438','7008','Khaki grey','105-084-056')" style="color:#ffffff;">7008</a></td>
										<td bgcolor="#47261c"><a href="javascript:colorchange('47261C','8011','Nut brown','071-038-028')" style="color:#ffffff;">8011</a></td>
										<td bgcolor="#faffff"><a href="javascript:colorchange('FAFFFF','9010','Pure white','250-255-255')" style="color:#000000;">9010</a></td></tr>
									<tr>
										<td bgcolor="#ad7a4f"><a href="javascript:colorchange('AD7A4F','1011','Brown beige','173-122-079')" style="color:#000000;">1011</a></td>
										<td bgcolor="#eb3b1c"><a href="javascript:colorchange('EB3B1C','2009','Traffic orange','235-059-028')" style="color:#ffffff;">2009</a></td>
										<td bgcolor="#781417"><a href="javascript:colorchange('781417','3011','Brown red','120-020-023')" style="color:#ffffff;">3011</a></td>
										<td bgcolor="#9e7394"><a href="javascript:colorchange('9E7394','4009','Pastel violet','158-115-148')" style="color:#ffffff;">4009</a></td>
										<td bgcolor="#174570"><a href="javascript:colorchange('174570','5009','Azure blue','023-069-112')" style="color:#ffffff;">5009</a> </td>
										<td bgcolor="#21211a"><a href="javascript:colorchange('21211a','6008','Brown green','033-033-026')" style="color:#ffffff;">6008</a></td>
										<td bgcolor="#4d524a"><a href="javascript:colorchange('4D524A','7009','Green grey','077-082-074')" style="color:#ffffff;">7009</a></td>
										<td bgcolor="#541f1f"><a href="javascript:colorchange('541F1F','8012','Red brown','084-031-031')" style="color:#ffffff;">8012</a></td>
										<td bgcolor="#0d121a"><a href="javascript:colorchange('0D121A','9011','Graphite black','013-018-026')" style="color:#ffffff;">9011</a></td></tr>
									<tr>
										<td bgcolor="#e3b838"><a href="javascript:colorchange('E3B838','1012','Lemon yellow','227-184-056')" style="color:#000000;">1012</a></td>
										<td bgcolor="#d44529"><a href="javascript:colorchange('D44529','2010','Signal orange','212-069-041')" style="color:#ffffff;">2010</a></td>
										<td bgcolor="#cc8273"><a href="javascript:colorchange('CC8273','3012','Beige red','204-130-115')" style="color:#ffffff;">3012</a></td>
										<td bgcolor="#bf1773"><a href="javascript:colorchange('BF1773','4010','Telemagenta','191-023-115')" style="color:#ffffff;">4010</a></td>
										<td bgcolor="#002b70"><a href="javascript:colorchange('002B70','5010','Gentian blue','000-043-112')" style="color:#ffffff;">5010</a> </td>
										<td bgcolor="#17291c"><a href="javascript:colorchange('17291C','6009','Fir green','023-041-028')" style="color:#ffffff;">6009</a></td>
										<td bgcolor="#4a4f4a"><a href="javascript:colorchange('4A4F4A','7010','Tarpaulin grey','074-079-074')" style="color:#ffffff;">7010</a></td>
										<td bgcolor="#38261c"><a href="javascript:colorchange('38261C','8014','Sepia brown','056-038-028')" style="color:#ffffff;">8014</a></td>
										<td bgcolor="#fcffff"><a href="javascript:colorchange('FCFFFF','9016','Traffic white','252-255-255')" style="color:#000000;">9016</a></td></tr>
									<tr>
										<td bgcolor="#fff5e3"><a href="javascript:colorchange('FFF5E3','1013','Oyster white','255-245-227')" style="color:#000000;">1013</a></td>
										<td bgcolor="#ed5c29"><a href="javascript:colorchange('ED5C29','2011','Deep orange','237-092-041')" style="color:#ffffff;">2011</a></td>
										<td bgcolor="#961f1c"><a href="javascript:colorchange('961F1C','3013','Tomato red','150-031-028')" style="color:#ffffff;">3013</a></td>
										<td></td>
										<td bgcolor="#03142e"><a href="javascript:colorchange('03142E','5011','Steel blue','003-020-046')" style="color:#ffffff;">5011</a> </td>
										<td bgcolor="#366926"><a href="javascript:colorchange('366926','6010','Grass green','054-105-038')" style="color:#ffffff;">6010</a></td>
										<td bgcolor="#404a54"><a href="javascript:colorchange('404A54','7011','Iron grey','064-074-084')" style="color:#ffffff;">7011</a></td>
										<td bgcolor="#4d1f1c"><a href="javascript:colorchange('4D1F1C','8015','Chestnut brown','077-031-028')" style="color:#ffffff;">8015</a></td>
										<td bgcolor="#14171c"><a href="javascript:colorchange('14171C','9017','Traffic black','020-023-028')" style="color:#ffffff;">9017</a></td></tr>
									<tr>
										<td bgcolor="#f0d6ab"><a href="javascript:colorchange('F0D6AB','1014','Ivory','240-214-171')" style="color:#000000;">1014</a> </td>
										<td bgcolor="#de5247"><a href="javascript:colorchange('DE5247','2012','Salmon orange','222-082-071')" style="color:#ffffff;">2012</a></td>
										<td bgcolor="#d96675"><a href="javascript:colorchange('D96675','3014','Antique pink','217-102-117')" style="color:#ffffff;">3014</a> </td>
										<td></td>
										<td bgcolor="#2973b8"><a href="javascript:colorchange('2973B8','5012','Light blue','041-115-184')" style="color:#ffffff;">5012</a> </td>
										<td bgcolor="#5e7d4f"><a href="javascript:colorchange('5E7D4F','6011','Reseda green','094-125-079')" style="color:#ffffff;">6011</a></td>
										<td bgcolor="#4a5459"><a href="javascript:colorchange('4A5459','7012','Basalt grey','074-084-089')" style="color:#ffffff;">7012</a></td>
										<td bgcolor="#3d1f1c"><a href="javascript:colorchange('3D1F1C','8016','Mahogany brown','061-031-028')" style="color:#ffffff;">8016</a></td>
										<td bgcolor="#dbe3de"><a href="javascript:colorchange('DBE3DE','9018','Papyrus white','219-227-222')" style="color:#000000;">9018</a></td></tr>
									<tr>
										<td bgcolor="#fcebcc"><a href="javascript:colorchange('FCEBCC','1015','Light ivory','252-235-204')" style="color:#000000;">1015</a> </td>
										<td></td>
										<td bgcolor="#e89cb5"><a href="javascript:colorchange('E89CB5','3015','Light pink','232-156-181')" style="color:#ffffff;">3015</a></td>
										<td></td>
										<td bgcolor="#001245"><a href="javascript:colorchange('001245','5013','Cobalt blue','000-018-069')" style="color:#ffffff;">5013</a> </td>
										<td bgcolor="#1f2e2b"><a href="javascript:colorchange('1F2E2B','6012','Black green','031-046-043')" style="color:#ffffff;">6012</a></td>
										<td bgcolor="#474238"><a href="javascript:colorchange('474238','7013','Brown grey','071-066-056')" style="color:#ffffff;">7013</a></td>
										<td bgcolor="#2e1c1c"><a href="javascript:colorchange('2E1C1C','8017','Chocolate brown','046-028-028')" style="color:#ffffff;">8017</a></td>
										<td></td></tr>
									<tr>
										<td bgcolor="#fff542"><a href="javascript:colorchange('FFF542','1016','Sulfur yellow','255-245-066')" style="color:#000000;">1016</a> </td>
										<td></td>
										<td bgcolor="#a62426"><a href="javascript:colorchange('A62426','3016','Coral red','166-036-038')" style="color:#ffffff;">3016</a></td>
										<td></td>
										<td bgcolor="#4d6999"><a href="javascript:colorchange('4D6999','5014','Pigeon blue','077-105-153')" style="color:#ffffff;">5014</a> </td>
										<td bgcolor="#75734f"><a href="javascript:colorchange('75734F','6013','Reed green','117-115-079')" style="color:#ffffff;">6013</a></td>
										<td bgcolor="#3d4252"><a href="javascript:colorchange('3D4252','7015','Slate grey','061-066-082')" style="color:#ffffff;">7015</a></td>
										<td bgcolor="#2b2629"><a href="javascript:colorchange('2B2629','8019','Grey brown','043-038-041')" style="color:#ffffff;">8019</a></td>
										<td></td></tr>
									<tr>
										<td bgcolor="#ffab59"><a href="javascript:colorchange('FFAB59','1017','Saffron yellow','255-171-089')" style="color:#000000;">1017</a></td>
										<td></td>
										<td bgcolor="#d13654"><a href="javascript:colorchange('D13654','3017','Rose','209-054-084')" style="color:#ffffff;">3017</a></td>
										<td></td>
										<td bgcolor="#1761ab"><a href="javascript:colorchange('1761AB','5015','Sky blue','023-097-171')" style="color:#ffffff;">5015</a> </td>
										<td bgcolor="#333026"><a href="javascript:colorchange('333026','6014','Yellow olive','051-048-038')" style="color:#ffffff;">6014</a></td>
										<td bgcolor="#262e38"><a href="javascript:colorchange('262E38','7016','Anthracite grey','038-046-056')" style="color:#ffffff;">7016</a></td>
										<td bgcolor="#0d080d"><a href="javascript:colorchange('0D080D','8022','Black brown','013-008-013')" style="color:#ffffff;">8022</a></td>
										<td></td></tr>
									<tr>
										<td bgcolor="#ffd64d"><a href="javascript:colorchange('FFD64D','1018','Zinc yellow','255-214-077')" style="color:#000000;">1018</a> </td>
										<td></td>
										<td bgcolor="#cf2942"><a href="javascript:colorchange('CF2942','3018','Strawberry red','207-041-066')" style="color:#ffffff;">3018</a></td>
										<td></td>
										<td bgcolor="#003b80"><a href="javascript:colorchange('003B80','5017','Traffic blue','000-059-128')" style="color:#ffffff;">5017</a> </td>
										<td bgcolor="#292b26"><a href="javascript:colorchange('292B26','6015','Black olive','041-043-038')" style="color:#ffffff;">6015</a></td>
										<td bgcolor="#1a2129"><a href="javascript:colorchange('1A2129','7021','Black grey','026-033-041')" style="color:#ffffff;">7021</a></td>
										<td bgcolor="#9c4529"><a href="javascript:colorchange('9C4529','8023','Orange brown','156-069-041')" style="color:#ffffff;">8023</a></td>
										<td></td></tr>
									<tr>
										<td bgcolor="#a38c7a"><a href="javascript:colorchange('A38C7A','1019','Grey beige','163-140-122')" style="color:#000000;">1019</a></td>
										<td></td>
										<td bgcolor="#c71712"><a href="javascript:colorchange('C71712','3020','Traffic red','199-023-018')" style="color:#ffffff;">3020</a></td>
										<td></td>
										<td bgcolor="#389482"><a href="javascript:colorchange('389482','5018','Turquoise blue','056-148-130')" style="color:#ffffff;">5018</a> </td>
										<td bgcolor="#0f7033"><a href="javascript:colorchange('0F7033','6016','Turquoise green','015-112-051')" style="color:#ffffff;">6016</a></td>
										<td bgcolor="#3d3d3b"><a href="javascript:colorchange('3D3D3B','7022','Umbra grey','061-061-059')" style="color:#ffffff;">7022</a></td>
										<td bgcolor="#6e4030"><a href="javascript:colorchange('6E4030','8024','Beige brown','110-064-048')" style="color:#ffffff;">8024</a></td>
										<td></td></tr>
									<tr>
										<td bgcolor="#9c8f61"><a href="javascript:colorchange('9C8F61','1020','Olive yellow','156-143-097')" style="color:#000000;">1020</a></td>
										<td></td>
										<td bgcolor="#d9594f"><a href="javascript:colorchange('D9594F','3022','Salmon pink','217-089-079')" style="color:#ffffff;">3022</a></td>
										<td></td>
										<td bgcolor="#0a4278"><a href="javascript:colorchange('0A4278','5019','Capri blue','010-066-120')" style="color:#ffffff;">5019</a> </td>
										<td bgcolor="#408236"><a href="javascript:colorchange('408236','6017','May green','064-130-054')" style="color:#ffffff;">6017</a></td>
										<td bgcolor="#7a7d75"><a href="javascript:colorchange('7A7D75','7023','Concrete grey','122-125-117')" style="color:#ffffff;">7023</a></td>
										<td bgcolor="#664a3d"><a href="javascript:colorchange('664A3D','8025','Pale brown','102-074-061')" style="color:#ffffff;">8025</a></td>
										<td></td></tr>
									<tr>
										<td bgcolor="#fcbd1f"><a href="javascript:colorchange('FCBD1F','1021','Rape yellow','252-189-031')" style="color:#000000;">1021</a></td>
										<td></td>
										<td bgcolor="#fc0a1c"><a href="javascript:colorchange('FC0A1C','3024','Luminous red','252-010-028')" style="color:#ffffff;">3024</a></td>
										<td></td>
										<td bgcolor="#053333"><a href="javascript:colorchange('053333','5020','Ocean blue','005-051-051')" style="color:#ffffff;">5020</a> </td>
										<td bgcolor="#4fa833"><a href="javascript:colorchange('4FA833','6018','Yellow green','079-168-051')" style="color:#ffffff;">6018</a></td>
										<td bgcolor="#303845"><a href="javascript:colorchange('303845','7024','Graphite grey','048-056-069')" style="color:#ffffff;">7024</a></td>
										<td bgcolor="#402e21"><a href="javascript:colorchange('402E21','8028','Terra brown','064-046-033')" style="color:#ffffff;">8028</a></td>
										<td></td></tr>
									<tr>
										<td bgcolor="#fcb821"><a href="javascript:colorchange('FCB821','1023','Traffic yellow','252-184-033')" style="color:#000000;">1023</a></td>
										<td></td>
										<td bgcolor="#fc1414"><a href="javascript:colorchange('FC1414','3026','Luminous bright red','252-020-020')" style="color:#ffffff;">3026</a></td>
										<td></td>
										<td bgcolor="#1a7a63"><a href="javascript:colorchange('1A7A63','5021','Water blue','026-122-099')" style="color:#ffffff;">5021</a></td>
										<td bgcolor="#bfe3ba"><a href="javascript:colorchange('BFE3BA','6019','Pastel green','191-227-186')" style="color:#000000;">6019</a> </td>
										<td bgcolor="#263338"><a href="javascript:colorchange('263338','7026','Granite grey','038-051-056')" style="color:#ffffff;">7026</a></td>
										<td></td>
										<td></td></tr>
									<tr>
										<td bgcolor="#b58c4f"><a href="javascript:colorchange('B58C4F','1024','Ochre yellow','181-140-079')" style="color:#000000;">1024</a></td>
										<td></td>
										<td bgcolor="#b51233"><a href="javascript:colorchange('B51233','3027','Raspberry red','181-018-051')" style="color:#ffffff;">3027</a></td>
										<td></td>
										<td bgcolor="#00084f"><a href="javascript:colorchange('00084F','5022','Night blue','000-008-079')" style="color:#ffffff;">5022</a></td>
										<td bgcolor="#263829"><a href="javascript:colorchange('263829','6020','Chrome green','038-056-041')" style="color:#ffffff;">6020</a></td>
										<td bgcolor="#918f87"><a href="javascript:colorchange('918F87','7030','Stone grey','145-143-135')" style="color:#ffffff;">7030</a></td>
										<td></td>
										<td></td></tr>
									<tr>
										<td bgcolor="#ffff0a"><a href="javascript:colorchange('FFFF0A','1026','Luminous yellow','255-255-010')" style="color:#000000;">1026</a></td>
										<td></td>
										<td bgcolor="#a61c2e"><a href="javascript:colorchange('A61C2E','3031','Orient red','166-028-046')" style="color:#ffffff;">3031</a></td>
										<td></td>
										<td bgcolor="#2e528f"><a href="javascript:colorchange('2E528F','5023','Distant blue','046-082-143')" style="color:#ffffff;">5023</a></td>
										<td bgcolor="#85a67a"><a href="javascript:colorchange('85A67A','6021','Pale green','133-166-122')" style="color:#ffffff;">6021</a></td>
										<td bgcolor="#4d5c6b"><a href="javascript:colorchange('4D5C6B','7031','Blue grey','077-092-107')" style="color:#ffffff;">7031</a></td>
										<td></td>
										<td></td></tr>
									<tr>
										<td bgcolor="#997521"><a href="javascript:colorchange('997521','1027','Curry','153-117-033')" style="color:#000000;">1027</a></td>
										<td></td>
										<td></td>
										<td></td>
										<td bgcolor="#578cb5"><a href="javascript:colorchange('578CB5','5024','Pastel blue','087-140-181')" style="color:#ffffff;">5024</a></td>
										<td bgcolor="#2b261c"><a href="javascript:colorchange('2B261C','6022','Olive drab','043-038-028')" style="color:#ffffff;">6022</a></td>
										<td bgcolor="#bdbaab"><a href="javascript:colorchange('BDBAAB','7032','Pebble grey','189-186-171')" style="color:#000000;">7032</a></td>
										<td></td>
										<td></td></tr>
									<tr>
										<td bgcolor="#ff8c1a"><a href="javascript:colorchange('FF8C1A','1028','Melon yellow','255-140-026')" style="color:#000000;">1028</a></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td bgcolor="#249140"><a href="javascript:colorchange('249140','6024','Traffic green','036-145-064')" style="color:#ffffff;">6024</a></td>
										<td bgcolor="#7a8275"><a href="javascript:colorchange('7A8275','7033','Cement grey','122-130-117')" style="color:#ffffff;">7033</a></td>
										<td></td>
										<td></td></tr>
									<tr>
										<td bgcolor="#e3a329"><a href="javascript:colorchange('E3A329','1032','Broom yellow','227-163-041')" style="color:#000000;">1032</a></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td bgcolor="#4a6e33"><a href="javascript:colorchange('4A6E33','6025','Fern green','074-110-051')" style="color:#ffffff;">6025</a></td>
										<td bgcolor="#8f8770"><a href="javascript:colorchange('8F8770','7034','Yellow grey','143-135-112')" style="color:#ffffff;">7034</a> </td>
										<td></td>
										<td></td></tr>
									<tr>
										<td bgcolor="#ff9436"><a href="javascript:colorchange('FF9436','1033','Dahlia yellow','255-148-054')" style="color:#000000;">1033</a></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td bgcolor="#0a5c33"><a href="javascript:colorchange('0A5C33','6026','Opal green','010-092-051')" style="color:#ffffff;">6026</a> </td>
										<td bgcolor="#d4d9db"><a href="javascript:colorchange('D4D9DB','7035','Light grey','212-217-219')" style="color:#000000;">7035</a></td>
										<td></td>
										<td></td></tr>
									<tr>
										<td bgcolor="#f7995c"><a href="javascript:colorchange('F7995C','1034','Pastel yellow','247-153-092')" style="color:#000000;">1034</a></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td bgcolor="#7dccbd"><a href="javascript:colorchange('7DCCBD','6027','Light green','125-204-189')" style="color:#000000;">6027</a></td>
										<td bgcolor="#9e969c"><a href="javascript:colorchange('9E969C','7036','Platinum grey','158-150-156')" style="color:#ffffff;">7036</a></td>
										<td></td>
										<td></td></tr>
									<tr>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td bgcolor="#264a33"><a href="javascript:colorchange('264A33','6028','Pine green','038-074-051')" style="color:#ffffff;">6028</a></td>
										<td bgcolor="#7a7d80"><a href="javascript:colorchange('7A7D80','7037','Dusty grey','122-125-128')" style="color:#ffffff;">7037</a> </td>
										<td></td>
										<td></td></tr>
									<tr>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td bgcolor="#127826"><a href="javascript:colorchange('127826','6029','Mint green','018-120-038')" style="color:#ffffff;">6029</a></td>
										<td bgcolor="#babdba"><a href="javascript:colorchange('BABDBA','7038','Agate grey','186-189-186')" style="color:#000000;">7038</a></td>
										<td></td>
										<td></td></tr>
									<tr>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td bgcolor="#298a40"><a href="javascript:colorchange('298A40','6032','Signal green','041-138-064')" style="color:#ffffff;">6032</a></td>
										<td bgcolor="#615e59"><a href="javascript:colorchange('615E59','7039','Quartz grey','097-094-089')" style="color:#ffffff;">7039</a></td>
										<td></td>
										<td></td></tr>
									<tr>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td bgcolor="#428c78"><a href="javascript:colorchange('428C78','6033','Mint turquoise','066-140-120')" style="color:#ffffff;">6033</a></td>
										<td bgcolor="#9ea3b0"><a href="javascript:colorchange('9EA3B0','7040','Window grey','158-163-176')" style="color:#ffffff;">7040</a></td>
										<td></td>
										<td></td></tr>
									<tr>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td bgcolor="#7dbdb5"><a href="javascript:colorchange('7DBDB5','6034','Pastel turquoise','125-189-181')" style="color:#000000;">6034</a></td>
										<td bgcolor="#8f9699"><a href="javascript:colorchange('8F9699','7042','Traffic grey A','143-150-153')" style="color:#ffffff;">7042</a></td>
										<td></td>
										<td></td></tr>
									<tr>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td bgcolor="#404545"><a href="javascript:colorchange('404545','7043','Traffic grey B','064-069-069')" style="color:#ffffff;">7043</a></td>
										<td></td>
										<td></td></tr>
									<tr>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td bgcolor="#c2bfb8"><a href="javascript:colorchange('C2BFB8','7044','Silk grey','194-191-184')" style="color:#000000;">7044</a></td>
										<td></td>
										<td></td></tr>
									<tr>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td bgcolor="#8f949e"><a href="javascript:colorchange('8F949E','7045','Telegrey 1','143-148-158')" style="color:#ffffff;">7045</a></td>
										<td></td>
										<td></td></tr>
									<tr>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td bgcolor="#78828c"><a href="javascript:colorchange('78828C','7046','Telegrey 2','120-130-140')" style="color:#ffffff;">7046</a></td>
										<td></td>
										<td></td></tr>
									<tr>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td bgcolor="#d9d6db"><a href="javascript:colorchange('D9D6DB','7047','Telegrey 4','217-214-219')" style="color:#000000;">7047</a></td>
										<td></td>
										<td></td></tr>
									</tbody>
								</table>
							</div>
						</div>
						<div class="row spacersmallest"></div>
						<div class="row">
							<div class="col-md-2">
								<p><b>RAL Kleur:</b></p>
							</div>
							<div class="col-md-6">
								<table>
									<tr id="selectedColorAdd">

									</tr>
								</table>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<input id="newColorNamenl" name="newColorNamenl" type="text" value="" class="form-control float-left colornameclass" placeholder="Kleur (Nederlands)" />
							</div>
							<div class="col-md-6">
								<input id="newColorNamefr" name="newColorNamefr" value="" type="text" class="form-control float-left colornameclass" placeholder="Kleur (Frans)" />
							</div>
						</div>
						<div class="row spacersmallest"></div>
						<div class="row">
							<div class="col-md-6">
								<input id="newColorNamede" name="newColorNamede" value=""  type="text" class="form-control float-left colornameclass" placeholder="Kleur (Duits)" />
							</div>
							<div class="col-md-6">
								<input id="newColorNameen" name="newColorNameen" value="" type="text" class="form-control float-left colornameclass" placeholder="Kleur (Engels)" />
							</div>
						</div>
						<div class="row spacersmallest"></div>

						<input type="hidden" id="newcolorHex"/>
						<input type="hidden" id="newcolorRal"/>
						<input type="hidden" id="selectedColorId"/>

						<script>
							$row = "";
							$("#colorTable td").click(function () {
								$row = $(this);
							});
							function colorchange(hex,ral,name,rgb){
								$rowclone = $row.clone();
								$("#newcolorHex").val("#" + hex);
								$("#newcolorRal").val(ral);
								$("#selectedColorAdd").html($rowclone);
							}

						</script>
					</div>
					<div class="modal-footer">
						<div class="row">
							<div class="col-md-2 col-md-push-7">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							</div>
							<div class="col-md-3 col-md-push-7">
								<a id="saveColorButton" class="btn btn-sml btn-success">Kleur bijwerken</a>
							</div>
						</div>

					</div>
				</div>

			</div>
		</div>
	</div><!-- /.box -->
	<script>


		var colors = <?php echo $colors;?>;
		$('#saveColorButton').click(function() {
			if($('#newcolorHex').val() !== "" && $('#newcolorNamenl').val() !== "" && $('#selectedColorId').val() !== ""){
				var colorhex = $('#newcolorHex').val();
				var colorral = $('#newcolorRal').val();
				var colorId = $('#selectedColorId').val();
				var colornamenl = $('#newColorNamenl').val();
				var colornamefr = $('#newColorNamefr').val();
				var colornamede = $('#newColorNamede').val();
				var colornameen = $('#newColorNameen').val();
				console.log(colornamenl);
				console.log(colorhex);
				var url = "/updateColor";
				$.ajax({
					type: "GET",
					url: url,
					data: {"colornamenl": colornamenl, "colornamefr": colornamefr, "colornamede": colornamede, "colornameen": colornameen,"colorhex": colorhex, "colorral": colorral,"colorId":colorId},
					cache: false,
					success: function(data){
						window.location.href = "/admin/editColor";
					}
				});
			}else{
				var colorhex = $('#newcolorHex').val();
				var colorral = $('#newcolorRal').val();
				var colornamenl = $('#newColorNamenl').val();
				var colornamefr = $('#newColorNamefr').val();
				var colornamede = $('#newColorNamede').val();
				var colornameen = $('#newColorNameen').val();
				console.log(colornamenl);
				console.log(colorhex);
				var url = "/newColor";
				$.ajax({
					type: "GET",
					url: url,
					data: {"colornamenl": colornamenl, "colornamefr": colornamefr, "colornamede": colornamede, "colornameen": colornameen,"colorhex": colorhex, "colorral": colorral},
					cache: false,
					success: function(data){
						window.location.href = "/admin/editColor";
					}
				});
			}
		});

		$('#newColorButton').click(function() {
			$('#selectedColorId').val("");
			$('#saveColorButton').html("Kleur toevoegen");
			$('#myModal').modal('show');
		});

		function update(id){
				var url = "/updateText";
				var text = $("#" + id).val();

				$.ajax({
					type: "GET",
					url: url,
					data: {"pageTextId": id,"pageText": text},
					cache: false,
					success: function(data){
						console.log(data);
						$("#button" + id).css("display", "none");
						$('#' + id).prop('disabled', true);
					}
				});
				return false;
			};


		$(document).on('click', '.delete', function() {
			var url = "/checkDeleteColor";
			var url2 = "/deleteColor";
			var colorId = $(this).data("colorId");
			if(colorId == "0"){
				toastr["error"]("Kan de kleur niet verwijderen, want deze moet verplicht aanwezig zijn in het systeem.")

				toastr.options = {
					"closeButton": false,
					"debug": false,
					"newestOnTop": false,
					"progressBar": false,
					"positionClass": "toast-top-right",
					"preventDuplicates": false,
					"onclick": null,
					"showDuration": "600",
					"hideDuration": "1000",
					"timeOut": "5000",
					"extendedTimeOut": "1000",
					"showEasing": "swing",
					"hideEasing": "linear",
					"showMethod": "fadeIn",
					"hideMethod": "fadeOut"
				}
			}
			$.ajax({
				type: "GET",
				url: url,
				data: {"colorId": colorId},
				cache: false,
				success: function(data){
					if(data == "true"){
						window.location.href = "/admin/editColor";
					}else{
						toastr["error"]("Kan de kleur niet verwijderen, want deze hangt nog vast aan producten! Verwijder eerst de producten indien u de kleur echt wilt verwijderen.")

						toastr.options = {
							"closeButton": false,
							"debug": false,
							"newestOnTop": false,
							"progressBar": false,
							"positionClass": "toast-top-right",
							"preventDuplicates": false,
							"onclick": null,
							"showDuration": "600",
							"hideDuration": "1000",
							"timeOut": "5000",
							"extendedTimeOut": "1000",
							"showEasing": "swing",
							"hideEasing": "linear",
							"showMethod": "fadeIn",
							"hideMethod": "fadeOut"
						}
					}
				}
			});
		});

		$(document).on('click', '.edit', function() {
			var colorId = $(this).data("colorId");
			for(var c in colors){
				if(colors[c].id == colorId){
					var selectedColor = colors[c];
				}
			}

			$('#newcolorHex').val(selectedColor.hex);
			$('#newcolorRal').val(selectedColor.ral);
			$('#newColorNamenl').val(selectedColor.naam_nl);
			$('#newColorNamefr').val(selectedColor.naam_fr);
			$('#newColorNamede').val(selectedColor.naam_de);
			$('#newColorNameen').val(selectedColor.naam_en);
			$('#selectedColorId').val(selectedColor.id);
			$('#selectedColorAdd').html('<td bgcolor="' + selectedColor.hex + '"><a style="color:#fff !important;">' + selectedColor.ral + '</a></td>');
			$('#saveColorButton').html("Kleur bijwerken");
			$('#myModal').modal('show');
		});

	</script>
	<script type="text/javascript" src="{{URL::asset('js/toastr.js')}}"></script>
	<link href="{{URL::asset('css/toastr.css')}}" rel="stylesheet">
@endsection
