@extends('admin.template.main')
@section('content')
    <!-- Default box -->
    @include('messages')
    <script type="text/javascript" src="{{URL::asset('fine/jquery.fine-uploader.js')}}"></script>

    <link href="{{URL::asset('fine/fine-uploader-new.css')}}" rel="stylesheet">
    <style type="text/css">

        a,a:visited {
            color: #4183C4;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        pre,code {
            font-size: 12px;
        }

        pre {
            width: 100%;
            overflow: auto;
        }

        small {
            font-size: 90%;
        }

        small code {
            font-size: 11px;
        }

        .placeholder {
            outline: 1px dashed #4183C4;
        }

        .mjs-nestedSortable-error {
            background: #fbe3e4;
            border-color: transparent;
        }

        #tree {
            width: 550px;
            margin: 0;
        }

        ol {
            max-width: 450px;
            padding-left: 25px;
        }

        ol.sortable,ol.sortable ol {
            list-style-type: none;
        }

        .sortable li div {
            border: 1px solid #d4d4d4;
            -webkit-border-radius: 3px;
            -moz-border-radius: 3px;
            border-radius: 3px;
            cursor: move;
            border-color: #D4D4D4 #D4D4D4 #BCBCBC;
            margin: 0;
            padding: 3px;
        }
        .sortable li div:hover {
            background: #F2F2F2;
        }

        li.mjs-nestedSortable-collapsed.mjs-nestedSortable-hovering div {
            border-color: #999;
        }

        .disclose, .expandEditor {
            cursor: pointer;
            width: 20px;
            display: none;
        }

        .sortable li.mjs-nestedSortable-collapsed > ol {
            display: none;
        }

        .sortable li.mjs-nestedSortable-branch > div > .disclose {
            display: inline-block;
        }

        .sortable span.ui-icon {
            display: inline-block;
            margin: 0;
            padding: 0;
        }

        .editButton, .deleteButton {
            float: right;
            padding-right: 5px;
        }
        .deleteButton {
            color: #C40000!important;
        }
        .menuDiv {
            background: #EBEBEB;
        }

        .menuEdit {
            background: #FFF;
        }

        .itemTitle {
            vertical-align: middle;
            cursor: pointer;
        }

        .deleteMenu {
            float: right;
            cursor: pointer;
        }

        h1 {
            font-size: 2em;
            margin-bottom: 0;
        }

        h2 {
            font-size: 1.2em;
            font-weight: 400;
            font-style: italic;
            margin-top: .2em;
            margin-bottom: 1.5em;
        }

        h3 {
            font-size: 1em;
            margin: 1em 0 .3em;
        }

        p,ol,ul,pre,form {
            margin-top: 0;
            margin-bottom: 1em;
        }

        dl {
            margin: 0;
        }

        dd {
            margin: 0;
            padding: 0 0 0 1.5em;
        }

        code {
            background: #e5e5e5;
        }

        input {
            vertical-align: text-bottom;
        }

        .notice {
            color: #c33;
        }
    </style>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css" />
    <script src="//code.jquery.com/ui/1.10.4/jquery-ui.min.js"></script>
    <script type="text/javascript" src="{{URL::asset('js/nestedSortable.js')}}"></script>
    <script type="text/javascript" src="{{URL::asset('js/cycle.js')}}"></script>
    <script>
        $(document).ready(function(){
            var ns = $('ol.sortable').nestedSortable({
                forcePlaceholderSize: true,
                handle: 'div',
                helper:	'clone',
                items: 'li',
                opacity: .6,
                placeholder: 'placeholder',
                revert: 250,
                tabSize: 25,
                tolerance: 'pointer',
                toleranceElement: '> div',
                maxLevels: 3,
                isTree: true,
                expandOnHover: 700,
                startCollapsed: false,
                change: function(){
                    console.log("change");

                    $('#orderIndicator').html('<i class="fa fa-bell-o" style="color: orange;" title="Er zijn aanpassingen gebeurt sinds de laatste keer dat er opgeslagen is."></i>');
                }
            });


            $('.editButton').on('click', function() {
                var catData = $(this).data('catobj');

                var modal = $('#editModal');

                $('#mainimgvalues').val("");

                $('#fine-uploader-validation').fineUploader({
                    template: 'qq-template-validation',
                    request: {
                        endpoint: '/admin/upload'
                    },
                    thumbnails: {
                        placeholders: {
                            waitingPath: '/source/placeholders/waiting-generic.png',
                            notAvailablePath: '/source/placeholders/not_available-generic.png'
                        }
                    },
                    validation: {
                        allowedExtensions: ['jpeg', 'jpg', 'png'], // staat ook serverside ingesteld
                        itemLimit: 4,
                        sizeLimit: 1024000 // 1mb
                    },
                    scaling: {
                        hideScaled: true,

                        sizes: [
                            {name: "xsmall", maxSize: 180},
                            {name: "small", maxSize: 400},
                            {name: "medium", maxSize: 800}
                        ]
                    },
                    deleteFile: {
                        enabled: true,
                        forceConfirm: false,
                        endpoint: '/admin/uploadDelete'
                    },
                    callbacks:{
                        onAllComplete: function(ider){
                            var uploads = $('#fine-uploader-validation').fineUploader("getUploads", {status: qq.status.UPLOAD_SUCCESSFUL});
                            console.log(uploads);
                            $('#mainimgvalues').val(JSON.stringify(uploads));
                        },
                        onDeleteComplete: function(ider){
                            if((tellerke % 4) !== 0){
                                var teller = (ider - 1);
                                var array = $('#fine-uploader-validation').fineUploader("getUploads", {id: teller});
                                console.log(array);
                                $('#fine-uploader-validation').fineUploader("deleteFile", array.id);
                                tellerke = tellerke + 1;
                            }else{
                                tellerke = 1;
                            }
                            var uploads = $('#fine-uploader-validation').fineUploader("getUploads", {status: qq.status.UPLOAD_SUCCESSFUL});
                            $('#mainimgvalues').val(JSON.stringify(uploads));
                            console.log(uploads);
                        }
                    }
                });

                modal.find('.editInputID').val(catData.id);
                modal.find('.editInputNL').val(catData.naam_nl);
                modal.find('.editInputEN').val(catData.naam_en);
                modal.find('.editInputFR').val(catData.naam_fr);
                modal.find('.editInputDE').val(catData.naam_de);

                modal.modal("show");
            });


            $('#updateCategoryButton').on('click', function(){
                var editID = $('.editInputID').val();
                var editNL = $('.editInputNL').val();
                var editEN = $('.editInputEN').val();
                var editFR = $('.editInputFR').val();
                var editDE = $('.editInputDE').val();


                var imgValues = $('#mainimgvalues').val();


                console.log(editID);
                console.log("true");


                $.ajax({
                    type: "GET",
                    url: "/update/category/ajax",
                    data: {"naam_nl": editNL, "naam_en": editEN, "naam_fr": editFR, "naam_de": editDE, "id": editID,"imgValues": imgValues},
                    cache: false,
                    success: function(data){
                       window.location.href = "/admin/category";
                    }
                });
            });



            $('#toHierarchy').click(function(e){
                hiered = $('ol.sortable').nestedSortable('toHierarchy', {startDepthCount: 0});
                hiered = dump(hiered);
                console.log(hiered);
                (typeof($('#toHierarchyOutput')[0].textContent) != 'undefined') ?
                        $('#toHierarchyOutput')[0].textContent = hiered : $('#toHierarchyOutput')[0].innerText = hiered;
            });



            $('#toArray').click(function(e){
                arraied = $('ol.sortable').nestedSortable('toArray', {startDepthCount: 0});
                arraied = dump(arraied);
                (typeof($('#toArrayOutput')[0].textContent) != 'undefined') ?
                        $('#toArrayOutput')[0].textContent = arraied : $('#toArrayOutput')[0].innerText = arraied;
            });

            function censor(censor) {
                var i = 0;

                return function(key, value) {
                    if(i !== 0 && typeof(censor) === 'object' && typeof(value) == 'object' && censor == value)
                        return '[Circular]';

                    if(i >= 29) // seems to be a harded maximum of 30 serialized objects?
                        return '[Unknown]';

                    ++i; // so we know we aren't using the original object anymore

                    return value;
                }
            }

            $('#saveOrderButton').on('click', function(){
                $('#orderIndicator').html('<i class="fa fa-refresh fa-spin fa-fw"></i>');

                console.log("test");

                var h = $('ol.sortable').nestedSortable('toHierarchy', {startDepthCount: 0});

                var hstring = stringify(h, null, 2);

                    $.ajax({
                        type: "POST",
                        url: "/admin/category/order",
                        data: {'valuesdrag': hstring},
                        cache: false,
                        success: function(data){
                            if(data == "1") {
                                $('#orderIndicator').html('<i class="fa fa-check" style="color: #B7B7B7;" title="De volgorde is opgeslagen."></i>');
                            } else {
                                $('#orderIndicator').html('<i class="fa fa-exclamation-triangle" style="color: #C40000;" title="De volgorde is niet opgeslagen (' + data + ')."></i>');
                                console.log(data);
                            }
                        }
                    });
            });
        });

        function stringify(obj, replacer, spaces, cycleReplacer) {
            return JSON.stringify(obj, serializer(replacer, cycleReplacer), spaces)
        }

        function serializer(replacer, cycleReplacer) {
            var stack = [], keys = []

            if (cycleReplacer == null) cycleReplacer = function(key, value) {
                if (stack[0] === value) return "[Circular ~]"
                return "[Circular ~." + keys.slice(0, stack.indexOf(value)).join(".") + "]"
            }

            return function(key, value) {
                if (stack.length > 0) {
                    var thisPos = stack.indexOf(this)
                    ~thisPos ? stack.splice(thisPos + 1) : stack.push(this)
                    ~thisPos ? keys.splice(thisPos, Infinity, key) : keys.push(key)
                    if (~stack.indexOf(value)) value = cycleReplacer.call(this, key, value)
                }
                else stack.push(value)

                return replacer == null ? value : replacer.call(this, key, value)
            }
        }
    </script>


    <script type="text/template" id="qq-template-validation">
        <div class="qq-uploader-selector qq-uploader" qq-drop-area-text="Drop files here">
            <div class="qq-total-progress-bar-container-selector qq-total-progress-bar-container">
                <div role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" class="qq-total-progress-bar-selector qq-progress-bar qq-total-progress-bar"></div>
            </div>
            <div class="qq-upload-drop-area-selector qq-upload-drop-area" qq-hide-dropzone>
                <span class="qq-upload-drop-area-text-selector"></span>
            </div>
            <div class="qq-upload-button-selector qq-upload-button">
                <div>Selecteer afbeelding</div>
            </div>
            <span class="qq-drop-processing-selector qq-drop-processing">
                <span>Processing dropped files...</span>
                <span class="qq-drop-processing-spinner-selector qq-drop-processing-spinner"></span>
            </span>
            <ul class="qq-upload-list-selector qq-upload-list" aria-live="polite" aria-relevant="additions removals">
                <li>
                    <div class="qq-progress-bar-container-selector">
                        <div role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" class="qq-progress-bar-selector qq-progress-bar"></div>
                    </div>
                    <span class="qq-upload-spinner-selector qq-upload-spinner"></span>
                    <img class="qq-thumbnail-selector" qq-max-size="100" qq-server-scale>
                    <span class="qq-upload-file-selector qq-upload-file"></span>
                    <span class="qq-upload-size-selector qq-upload-size"></span>
                    <button type="button" class="qq-btn qq-upload-cancel-selector qq-upload-cancel">Cancel</button>
                    <button type="button" class="qq-btn qq-upload-retry-selector qq-upload-retry">Retry</button>
                    <button type="button" class="qq-btn qq-upload-delete-selector qq-upload-delete">Delete</button>
                    <span role="status" class="qq-upload-status-text-selector qq-upload-status-text"></span>
                </li>
            </ul>

            <dialog class="qq-alert-dialog-selector">
                <div class="qq-dialog-message-selector"></div>
                <div class="qq-dialog-buttons">
                    <button type="button" class="qq-cancel-button-selector">Close</button>
                </div>
            </dialog>

            <dialog class="qq-confirm-dialog-selector">
                <div class="qq-dialog-message-selector"></div>
                <div class="qq-dialog-buttons">
                    <button type="button" class="qq-cancel-button-selector">No</button>
                    <button type="button" class="qq-ok-button-selector">Yes</button>
                </div>
            </dialog>

            <dialog class="qq-prompt-dialog-selector">
                <div class="qq-dialog-message-selector"></div>
                <input type="text">
                <div class="qq-dialog-buttons">
                    <button type="button" class="qq-cancel-button-selector">Cancel</button>
                    <button type="button" class="qq-ok-button-selector">Ok</button>
                </div>
            </dialog>
        </div>
    </script>

    <style>
        #fine-uploader-s3 .preview-link {
            display: block;
            height: 100%;
            width: 100%;
        }
    </style>


    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Categorieën</h3>
            <hr/>
            <p style="margin-top:10px;">
                <a id="newCoatingButton" class="btn btn-primary"><i class="fa fa-plus"></i> Nieuwe categorie</a>
                <a id="saveOrderButton" class="btn btn-success"><i class="fa fa-save"></i> Volgorde opslaan</a>
                <span id="orderIndicator"></span>
            </p>
        </div><!-- /.box-header -->
        <div class="box-body no-padding">
            <ol class="sortable">
                <?php
                function getcat($category){
                    echo '<li id="item_' . $category->id . '">';
                    echo '<div>' . $category->naam_nl;
                    echo '<a href="#" class="editButton" data-catobj="' . htmlentities(json_encode($category)) . '"><i class="fa fa-pencil"></i></a>';
                    echo '<a href="#" class="deleteButton"><i class="fa fa-remove"></i></a>';
                    echo'</div>';
                    if($category->subCategories != null){
                        echo '<ol>';
                        foreach($category->subCategories as $sub){
                            getcat($sub);
                        }
                        echo '</ol>';
                    }
                    echo '</li>';
                }
                foreach($categories as $category){
                    getcat($category);
                }
                ?>

            </ol>
        </div><!-- /.box-body -->
    @include('admin.template.pagination', ['pages' => $categories])
    <!-- Modal -->
        <div id="myModal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Modal Header</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <input type="text" class="form-control coatingInputNL" value="" placeholder="Naam (Nederlands)">
                            </div>
                        </div>
                        <div class="row spacersmallest"></div>
                        <div class="row">
                            <div class="col-md-12">
                                <input type="text" class="form-control coatingInputFR" value="" placeholder="Naam (Frans)">
                            </div>
                        </div>
                        <div class="row spacersmallest"></div>
                        <div class="row">
                            <div class="col-md-12">
                                <input type="text" class="form-control coatingInputDE" value="" placeholder="Naam (Duits)">
                            </div>
                        </div>
                        <div class="row spacersmallest"></div>
                        <div class="row">
                            <div class="col-md-12">
                                <input type="text" class="form-control coatingInputEN" value="" placeholder="Naam (Engels)">
                            </div>
                        </div>
                        <div class="row spacersmallest"></div>
                        <div class="row">
                            <div class="col-md-12">
                                <select id="modalHoofdcategory" class="hoofdcategory">
                                    <option class="colorItem" value="0">Geen hoofdcategory</option>
                                    @foreach($categories as $hoofdcategory)
                                        <option class="colorItem" value="{{$hoofdcategory->id}}">{{$hoofdcategory->naam_nl}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="row">
                            <div class="col-md-2 col-md-push-6">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
                            <div class="col-md-3 col-md-push-6">
                                <a id="saveColorButton" class="btn btn-sml btn-success">Categorie toevoegen</a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div id="editModal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Categorie aanpassen</h4>
                    </div>
                    <input type="hidden" class="form-control editInputID" value="" placeholder="Naam (Nederlands)">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <input type="text" class="form-control editInputNL" value="" placeholder="Naam (Nederlands)">
                            </div>
                        </div>
                        <div class="row spacersmallest"></div>
                        <div class="row">
                            <div class="col-md-12">
                                <input type="text" class="form-control editInputFR" value="" placeholder="Naam (Frans)">
                            </div>
                        </div>
                        <div class="row spacersmallest"></div>
                        <div class="row">
                            <div class="col-md-12">
                                <input type="text" class="form-control editInputDE" value="" placeholder="Naam (Duits)">
                            </div>
                        </div>
                        <div class="row spacersmallest"></div>
                        <div class="row">
                            <div class="col-md-12">
                                <input type="text" class="form-control editInputEN" value="" placeholder="Naam (Engels)">
                            </div>
                        </div>
                        <div class="row spacersmallest"></div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="afbeelding">Afbeelding(en)</label>
                                    <div id="fine-uploader-validation"></div>
                                    <div class="row">
                                        <div class="col-md-4">

                                        </div>
                                    </div>
                                </div>

                                <input type="hidden" id="mainimgvalues" name="mainimages" value="">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="row">
                            <div class="col-md-2 col-md-push-6">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
                            <div class="col-md-3 col-md-push-6">
                                <a id="updateCategoryButton" class="btn btn-sml btn-success">Categorie aanpassen</a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div><!-- /.box -->
    <script>




        $(document).on('click', '.update', function() {
            var url = "/updateCategory";
            var categoryId = $(this).data("paramId");
            var naamNL = $(this).parent().parent().find('.naamnl').children().first().val();
            var naamFR = $(this).parent().parent().find('.naamfr').children().first().val();
            var naamDE = $(this).parent().parent().find('.naamde').children().first().val();
            var naamEN = $(this).parent().parent().find('.naamen').children().first().val();

            var imgArrayMain = $('#mainimgvalues').val();

            console.log(imgArrayMain);

            var hoofdcategory = $(this).parent().prev().children().first().val();

            console.log(hoofdcategory);



            $.ajax({
                type: "GET",
                url: url,
                data: {"categoryId": categoryId,"naamNL": naamNL,"naamFR": naamFR,"naamDE": naamDE,"naamEN": naamEN,"imgArrayMain": imgArrayMain, "hoofdcategory": hoofdcategory},
                cache: false,
                success: function(data){
                    window.location.href = "/admin/category";
                }
            });
            return false;
        });

        $('#newCoatingButton').click(function(){
            $('#myModal').modal("show");
        });


        $('#saveColorButton').click(function(){
            var coatingnamenl = $('.coatingInputNL').val();
            var coatingnamefr = $('.coatingInputFR').val();
            var coatingnamede = $('.coatingInputDE').val();
            var coatingnameen = $('.coatingInputEN').val();

            var hoofdcategory = $('#modalHoofdcategory').val();

            var url = "/newCategory";
            $.ajax({
                type: "GET",
                url: url,
                data: {"coatingnamenl": coatingnamenl, "coatingnamefr": coatingnamefr, "coatingnamede": coatingnamede, "coatingnameen": coatingnameen,"hoofdcategory": hoofdcategory},
                cache: false,
                success: function(data){
                    window.location.href = "/admin/category";
                }
            });
        });

        $(document).on('click', '.edit', function() {
            console.log("kleir");
            $(this).parent().parent().children(".formInput").each(function(){
                $(this).children().first().prop("disabled",false);
                console.log("beir");
            });
            $(this).next().next().css("display", "inline");
        });

        $(document).on('click', '.delete', function() {

            var url = "/checkDeleteCategory";
            var categoryId = $(this).data("categoryId");

            $.ajax({
                type: "GET",
                url: url,
                data: {"categoryId": categoryId},
                cache: false,
                success: function(data){
                    if(data == "true"){
                        window.location.href = "/admin/category";
                    }else{
                        toastr["error"]("Kan de category niet verwijderen, want deze hangt nog vast aan producten! Verwijder eerst de producten indien u de category echt wilt verwijderen.")

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

    </script>
    <script type="text/javascript" src="{{URL::asset('js/toastr.js')}}"></script>
    <link href="{{URL::asset('css/toastr.css')}}" rel="stylesheet">
    <script>
        $(document).ready(function(){

            $('.sortable').nestedSortable({
                handle: 'div',
                items: 'li',
                toleranceElement: '> div'
            });

        });
    </script>
@endsection
